<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoicePaymentController extends Controller
{
    private function clientId(): int
    {
        return Auth::user()->client_id;
    }

    public function store(Request $request, Invoice $invoice)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if($invoice->isCancelled(), 422, 'Cannot record payment for a cancelled invoice.');

        $request->validate([
            'amount'       => 'required|numeric|min:0.01|max:' . $invoice->balance(),
            'payment_date' => 'required|date',
            'method'       => 'required|in:cash,bank_transfer,cheque,online,other',
            'reference_no' => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $payment = InvoicePayment::create([
                'invoice_id'   => $invoice->id,
                'amount'       => $request->amount,
                'payment_date' => $request->payment_date,
                'method'       => $request->method,
                'reference_no' => $request->reference_no,
                'notes'        => $request->notes,
                'created_by'   => Auth::id(),
            ]);

            $newAmountPaid = (float) $invoice->amount_paid + (float) $payment->amount;
            $invoice->update(['amount_paid' => $newAmountPaid]);
            $invoice->recalculatePaymentStatus();

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'client_id'   => $this->clientId(),
                'action'      => 'recorded_payment',
                'model_type'  => Invoice::class,
                'model_id'    => $invoice->id,
                'description' => "Payment of Rs " . number_format($payment->amount, 2) . " recorded for Invoice #{$invoice->invoice_no}",
                'ip_address'  => request()->ip(),
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice, InvoicePayment $payment)
    {
        abort_if($invoice->client_id !== $this->clientId(), 403);
        abort_if($payment->invoice_id !== $invoice->id, 404);

        DB::transaction(function () use ($invoice, $payment) {
            $amount = (float) $payment->amount;
            $payment->delete();

            $newAmountPaid = max(0, (float) $invoice->amount_paid - $amount);
            $invoice->update(['amount_paid' => $newAmountPaid]);
            $invoice->recalculatePaymentStatus();
        });

        return back()->with('success', 'Payment record removed.');
    }
}
