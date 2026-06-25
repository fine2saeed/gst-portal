<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class InvoiceController extends Controller
{
    private function clientId(): int
    {
        return Auth::user()->client_id;
    }

    public function index(Request $request)
    {
        $query = Invoice::where('client_id', $this->clientId())
            ->with('customer')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_no', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $invoices = $query->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $client = Auth::user()->client;

        if (!$client->profile_complete) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Please complete your business profile before creating invoices.');
        }

        $customers = Customer::where('client_id', $this->clientId())->where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('client_id', $this->clientId())->where('is_active', true)->orderBy('name')->get();
        $invoiceNo = $client->generateInvoiceNumber();

        return view('invoices.create', compact('client', 'customers', 'products', 'invoiceNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'           => 'required|exists:customers,id',
            'invoice_date'          => 'required|date',
            'due_date'              => 'nullable|date|after_or_equal:invoice_date',
            'items'                 => 'required|array|min:1',
            'items.*.description'   => 'required|string',
            'items.*.qty'           => 'required|numeric|min:0.001',
            'items.*.unit_price'    => 'required|numeric|min:0',
            'items.*.gst_rate'      => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $client    = Auth::user()->client;
            $invoiceNo = $client->generateInvoiceNumber();

            $subtotal  = 0;
            $gstTotal  = 0;

            foreach ($request->items as $item) {
                $lineTotal  = $item['qty'] * $item['unit_price'];
                $gstAmount  = ($lineTotal * $item['gst_rate']) / 100;
                $subtotal  += $lineTotal;
                $gstTotal  += $gstAmount;
            }

            $discount = $request->discount ?? 0;
            $total    = $subtotal + $gstTotal - $discount;

            $invoice = Invoice::create([
                'client_id'    => $this->clientId(),
                'customer_id'  => $request->customer_id,
                'invoice_no'   => $invoiceNo,
                'invoice_date' => $request->invoice_date,
                'due_date'     => $request->due_date,
                'status'       => $request->action === 'finalize' ? 'final' : 'draft',
                'subtotal'     => $subtotal,
                'gst_amount'   => $gstTotal,
                'discount'     => $discount,
                'total'        => $total,
                'notes'        => $request->notes,
                'created_by'   => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $lineTotal  = $item['qty'] * $item['unit_price'];
                $gstAmount  = ($lineTotal * $item['gst_rate']) / 100;
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'hs_code'     => $item['hs_code'] ?? null,
                    'qty'         => $item['qty'],
                    'unit'        => $item['unit'] ?? 'Unit',
                    'unit_price'  => $item['unit_price'],
                    'gst_rate'    => $item['gst_rate'],
                    'gst_amount'  => $gstAmount,
                    'total'       => $lineTotal + $gstAmount,
                ]);
            }

            $client->incrementInvoiceCounter();

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'client_id'   => $this->clientId(),
                'action'      => $invoice->status === 'final' ? 'finalized_invoice' : 'created_invoice',
                'model_type'  => Invoice::class,
                'model_id'    => $invoice->id,
                'description' => "Invoice #{$invoiceNo} {$invoice->status}",
                'ip_address'  => request()->ip(),
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        $invoice->load('customer', 'items.product', 'creator', 'client', 'payments.creator');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if($invoice->isFinal(), 403, 'Finalized invoices cannot be edited.');

        $clients   = Auth::user()->client;
        $customers = Customer::where('client_id', $this->clientId())->where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('client_id', $this->clientId())->where('is_active', true)->orderBy('name')->get();
        $invoice->load('items');

        return view('invoices.edit', compact('invoice', 'clients', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if($invoice->isFinal(), 403, 'Finalized invoices cannot be edited.');

        $request->validate([
            'customer_id'           => 'required|exists:customers,id',
            'invoice_date'          => 'required|date',
            'items'                 => 'required|array|min:1',
            'items.*.description'   => 'required|string',
            'items.*.qty'           => 'required|numeric|min:0.001',
            'items.*.unit_price'    => 'required|numeric|min:0',
            'items.*.gst_rate'      => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $subtotal = 0;
            $gstTotal = 0;

            $invoice->items()->delete();

            foreach ($request->items as $item) {
                $lineTotal  = $item['qty'] * $item['unit_price'];
                $gstAmount  = ($lineTotal * $item['gst_rate']) / 100;
                $subtotal  += $lineTotal;
                $gstTotal  += $gstAmount;

                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'hs_code'     => $item['hs_code'] ?? null,
                    'qty'         => $item['qty'],
                    'unit'        => $item['unit'] ?? 'Unit',
                    'unit_price'  => $item['unit_price'],
                    'gst_rate'    => $item['gst_rate'],
                    'gst_amount'  => $gstAmount,
                    'total'       => $lineTotal + $gstAmount,
                ]);
            }

            $discount = $request->discount ?? 0;
            $invoice->update([
                'customer_id'  => $request->customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date'     => $request->due_date,
                'status'       => $request->action === 'finalize' ? 'final' : 'draft',
                'subtotal'     => $subtotal,
                'gst_amount'   => $gstTotal,
                'discount'     => $discount,
                'total'        => $subtotal + $gstTotal - $discount,
                'notes'        => $request->notes,
            ]);

            ActivityLog::create([
                'user_id'    => Auth::id(),
                'client_id'  => $this->clientId(),
                'action'     => 'updated_invoice',
                'model_type' => Invoice::class,
                'model_id'   => $invoice->id,
                'description'=> "Invoice #{$invoice->invoice_no} updated",
                'ip_address' => request()->ip(),
            ]);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        $invoice->load('customer', 'items.product', 'client');
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("Invoice-{$invoice->invoice_no}.pdf");
    }

    public function sendEmail(Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        $invoice->load('customer', 'items.product', 'client');

        if (!$invoice->customer->email) {
            return back()->with('error', 'Customer has no email address.');
        }

        Mail::to($invoice->customer->email)->send(new InvoiceMail($invoice));

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'client_id'  => $this->clientId(),
            'action'     => 'emailed_invoice',
            'model_type' => Invoice::class,
            'model_id'   => $invoice->id,
            'description'=> "Invoice #{$invoice->invoice_no} emailed to {$invoice->customer->email}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Invoice emailed successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if(!$invoice->isDraft(), 422, 'Only draft invoices can be deleted.');

        $invoice->items()->delete();
        $invoice->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'client_id'   => $this->clientId(),
            'action'      => 'deleted_invoice',
            'model_type'  => Invoice::class,
            'model_id'    => $invoice->id,
            'description' => "Draft Invoice #{$invoice->invoice_no} deleted",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('invoices.index')
            ->with('success', 'Draft invoice deleted.');
    }

    public function cancel(Request $request, Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if($invoice->isCancelled(), 422, 'Invoice is already cancelled.');

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $invoice->update([
            'status'               => 'cancelled',
            'cancellation_reason'  => $request->cancellation_reason,
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'client_id'   => $this->clientId(),
            'action'      => 'cancelled_invoice',
            'model_type'  => Invoice::class,
            'model_id'    => $invoice->id,
            'description' => "Invoice #{$invoice->invoice_no} cancelled. Reason: " . ($request->cancellation_reason ?: 'Not provided'),
            'ip_address'  => request()->ip(),
        ]);

        return back()->with('success', 'Invoice #' . $invoice->invoice_no . ' has been cancelled.');
    }
}
