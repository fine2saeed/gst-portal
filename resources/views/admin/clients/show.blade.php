@extends('layouts.app')
@section('title', $client->business_name)
@section('page-title', $client->business_name)
@section('page-subtitle', 'Client Account Details')

@section('header-actions')
<a href="{{ route('admin.clients.edit', $client) }}"
   class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    Edit Client
</a>
@endsection

@section('content')
<div class="max-w-4xl space-y-5">

    {{-- Summary Card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <div class="flex items-start gap-5">
            @if($client->logo)
                <img src="{{ asset('storage/' . $client->logo) }}" class="w-16 h-16 rounded-2xl object-cover shrink-0" alt="">
            @else
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                    <span class="text-emerald-400 font-bold text-xl">{{ strtoupper(substr($client->business_name, 0, 2)) }}</span>
                </div>
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-bold text-white">{{ $client->business_name }}</h2>
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">{{ $client->province }}</span>
                    @if($client->is_active)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>Active
                        </span>
                    @else
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-700 text-gray-400">Suspended</span>
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                    <div><p class="text-xs text-gray-500">NTN</p><p class="text-sm font-mono text-gray-200 mt-0.5">{{ $client->ntn ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-500">STRN</p><p class="text-sm font-mono text-gray-200 mt-0.5">{{ $client->strn ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-500">GST Rate</p><p class="text-sm font-bold text-amber-400 mt-0.5">{{ $client->default_gst_rate }}%</p></div>
                    <div><p class="text-xs text-gray-500">Invoice Prefix</p><p class="text-sm font-mono text-gray-200 mt-0.5">{{ $client->invoice_prefix }}</p></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Metrics --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 text-center">
            <p class="text-3xl font-bold text-white">{{ $client->invoices()->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Invoices</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 text-center">
            <p class="text-3xl font-bold text-white">{{ $client->customers()->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Customers</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 text-center">
            <p class="text-3xl font-bold text-emerald-400">Rs {{ number_format($client->invoices()->where('status','final')->sum('total'), 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
        </div>
    </div>

    {{-- Recent Invoices --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800">
            <h3 class="text-sm font-semibold text-white">Recent Invoices</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-6 py-3 text-xs text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-6 py-3 text-xs text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="text-center px-6 py-3 text-xs text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/40">
                @forelse($client->invoices()->with('customer')->latest()->take(10)->get() as $invoice)
                <tr class="hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-3.5 font-mono text-emerald-400 text-sm">{{ $invoice->invoice_no }}</td>
                    <td class="px-6 py-3.5 text-gray-200">{{ $invoice->customer->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td class="px-6 py-3.5 text-right font-mono text-white font-semibold">Rs {{ number_format($invoice->total, 0) }}</td>
                    <td class="px-6 py-3.5 text-center">
                        @if($invoice->status === 'final')
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Final</span>
                        @elseif($invoice->status === 'draft')
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-amber-500/10 text-amber-400 border border-amber-500/20">Draft</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400 border border-red-500/20">Cancelled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">No invoices yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
