<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function clientId(): int
    {
        return Auth::user()->client_id;
    }

    public function index(Request $request)
    {
        $clientId = $this->clientId();
        $year = $request->get('year', date('Y'));

        // Monthly revenue & GST breakdown (final invoices only)
        $monthlyData = Invoice::where('client_id', $clientId)
            ->where('status', 'final')
            ->whereYear('invoice_date', $year)
            ->selectRaw('MONTH(invoice_date) as month, COUNT(*) as count, SUM(subtotal) as subtotal, SUM(gst_amount) as gst, SUM(total) as total')
            ->groupByRaw('MONTH(invoice_date)')
            ->orderByRaw('MONTH(invoice_date)')
            ->get()
            ->keyBy('month');

        // Build full 12-month array
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = $monthlyData->get($m) ?? (object)[
                'month'    => $m,
                'count'    => 0,
                'subtotal' => 0,
                'gst'      => 0,
                'total'    => 0,
            ];
        }

        // Status breakdown
        $statusBreakdown = Invoice::where('client_id', $clientId)
            ->whereYear('invoice_date', $year)
            ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Top 5 customers by revenue (final invoices)
        $topCustomers = Customer::where('customers.client_id', $clientId)
            ->join('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->where('invoices.status', 'final')
            ->whereYear('invoices.invoice_date', $year)
            ->selectRaw('customers.name, COUNT(invoices.id) as invoice_count, SUM(invoices.total) as total_revenue, SUM(invoices.gst_amount) as total_gst')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Yearly totals
        $yearlyTotals = Invoice::where('client_id', $clientId)
            ->where('status', 'final')
            ->whereYear('invoice_date', $year)
            ->selectRaw('SUM(subtotal) as subtotal, SUM(gst_amount) as gst, SUM(total) as total, COUNT(*) as count')
            ->first();

        // Available years
        $availableYears = Invoice::where('client_id', $clientId)
            ->selectRaw('YEAR(invoice_date) as year')
            ->groupByRaw('YEAR(invoice_date)')
            ->orderByDesc('year')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        $client = Auth::user()->client;

        return view('reports.index', compact(
            'months', 'statusBreakdown', 'topCustomers',
            'yearlyTotals', 'year', 'availableYears', 'client'
        ));
    }
}
