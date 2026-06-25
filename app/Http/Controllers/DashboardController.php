<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();

        // Super Admin has no client — redirect to admin panel
        if ($user->isSuperAdmin()) {
            $totalClients  = \App\Models\Client::count();
            $totalInvoices = \App\Models\Invoice::count();
            $totalGst      = \App\Models\Invoice::where('status', 'final')->sum('gst_amount');
            $recentInvoices = \App\Models\Invoice::with(['customer','client'])->latest()->take(8)->get();
            return view('dashboard', compact('totalClients', 'totalInvoices', 'totalGst', 'recentInvoices'));
        }

        $client = $user->client;

        $totalInvoices   = Invoice::where('client_id', $client->id)->count();
        $draftInvoices   = Invoice::where('client_id', $client->id)->where('status', 'draft')->count();
        $finalInvoices   = Invoice::where('client_id', $client->id)->where('status', 'final')->count();
        $totalGst        = Invoice::where('client_id', $client->id)->where('status', 'final')->sum('gst_amount');
        $totalRevenue    = Invoice::where('client_id', $client->id)->where('status', 'final')->sum('total');
        $totalCustomers  = Customer::where('client_id', $client->id)->count();
        $totalProducts   = Product::where('client_id', $client->id)->count();

        // Outstanding = total of final invoices minus amount_paid
        $outstandingBalance = Invoice::where('client_id', $client->id)
            ->where('status', 'final')
            ->where('payment_status', '!=', 'paid')
            ->selectRaw('SUM(total - amount_paid) as balance')
            ->value('balance') ?? 0;

        $unpaidCount = Invoice::where('client_id', $client->id)
            ->where('status', 'final')
            ->where('payment_status', 'unpaid')
            ->count();

        $recentInvoices  = Invoice::where('client_id', $client->id)
            ->with('customer')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'client', 'totalInvoices', 'draftInvoices', 'finalInvoices',
            'totalGst', 'totalRevenue', 'totalCustomers', 'totalProducts',
            'recentInvoices', 'outstandingBalance', 'unpaidCount'
        ));
    }
}
