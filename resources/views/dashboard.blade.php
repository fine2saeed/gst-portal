@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', isset($client) ? $client->business_name . ' · ' . $client->province : 'Super Admin Overview')

@section('header-actions')
@if(isset($client))
<a href="{{ route('invoices.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/20">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
    New Invoice
</a>
@else
<a href="{{ route('admin.clients.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/20">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
    Add Client
</a>
@endif
@endsection

@section('content')

{{-- ─── Profile Incomplete Warning ─────────────────────────────────────────── --}}
@if(isset($client) && !$client->profile_complete)
<div class="mb-6 flex items-start gap-3 bg-amber-500/10 border border-amber-500/30 rounded-2xl px-5 py-4">
    <svg class="w-5 h-5 text-amber-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <div>
        <p class="text-sm font-semibold text-amber-300">Business profile incomplete</p>
        <p class="text-xs text-amber-400/80 mt-0.5">Fill in your NTN, address, and province to start creating invoices.</p>
    </div>
    <a href="{{ route('profile.edit') }}" class="ml-auto shrink-0 text-xs bg-amber-500 hover:bg-amber-400 text-white font-semibold px-3 py-1.5 rounded-lg transition-colors">
        Complete Now →
    </a>
</div>
@endif

{{-- ─── Stats Cards ─────────────────────────────────────────────────────────── --}}
@if(isset($client))
{{-- Client Admin Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-emerald-500/30 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</span>
            <div class="w-8 h-8 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rs {{ number_format($totalRevenue ?? 0, 0) }}</p>
        <p class="text-xs text-emerald-400 mt-1">From finalized invoices</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-amber-500/30 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">GST Collected</span>
            <div class="w-8 h-8 bg-amber-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rs {{ number_format($totalGst ?? 0, 0) }}</p>
        <p class="text-xs text-amber-400 mt-1">{{ $client->province }} — Payable</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-blue-500/30 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoices</span>
            <div class="w-8 h-8 bg-blue-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $totalInvoices ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">
            <span class="text-emerald-400">{{ $finalInvoices ?? 0 }} final</span>
            · <span class="text-amber-400">{{ $draftInvoices ?? 0 }} draft</span>
        </p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-red-500/30 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Outstanding</span>
            <div class="w-8 h-8 bg-red-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold {{ ($outstandingBalance ?? 0) > 0 ? 'text-red-400' : 'text-white' }}">
            Rs {{ number_format($outstandingBalance ?? 0, 0) }}
        </p>
        <p class="text-xs text-gray-500 mt-1">
            {{ $unpaidCount ?? 0 }} unpaid · {{ $totalCustomers ?? 0 }} customers
        </p>
    </div>
</div>

@else
{{-- Super Admin Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Clients</p>
        <p class="text-3xl font-bold text-white">{{ $totalClients ?? 0 }}</p>
        <p class="text-xs text-emerald-400 mt-1">Registered businesses</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Invoices</p>
        <p class="text-3xl font-bold text-white">{{ $totalInvoices ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Across all clients</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total GST Collected</p>
        <p class="text-3xl font-bold text-white">Rs {{ number_format($totalGst ?? 0, 0) }}</p>
        <p class="text-xs text-amber-400 mt-1">All finalized invoices</p>
    </div>
</div>
@endif

{{-- ─── Recent Invoices Table ──────────────────────────────────────────────── --}}
<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-white">Recent Invoices</h3>
        @if(isset($client))
        <a href="{{ route('invoices.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors">View all →</a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800/60">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice #</th>
                    @if(!isset($client))<th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Business</th>@endif
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/40">
                @forelse($recentInvoices as $invoice)
                <tr class="hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <a href="{{ route('invoices.show', $invoice) }}" class="font-mono text-emerald-400 hover:text-emerald-300 text-sm font-medium transition-colors">
                            {{ $invoice->invoice_no }}
                        </a>
                    </td>
                    @if(!isset($client))
                    <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $invoice->client->business_name ?? '—' }}</td>
                    @endif
                    <td class="px-6 py-3.5 text-gray-200 font-medium">{{ $invoice->customer->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td class="px-6 py-3.5 text-right font-mono font-semibold text-white text-sm">Rs {{ number_format($invoice->total, 0) }}</td>
                    <td class="px-6 py-3.5 text-center">
                        @if($invoice->status === 'draft')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Draft</span>
                        @elseif($invoice->status === 'final')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Final</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Cancelled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ isset($client) ? 5 : 6 }}" class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No invoices yet</p>
                        @if(isset($client))
                        <a href="{{ route('invoices.create') }}" class="mt-2 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Create your first invoice →</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
