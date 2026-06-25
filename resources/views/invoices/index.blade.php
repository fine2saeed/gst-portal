@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage all your GST invoices')

@section('header-actions')
<a href="{{ route('invoices.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Invoice
</a>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by invoice # or customer..."
           class="flex-1 min-w-48 bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 placeholder-gray-500">
    <select name="status" class="bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500">
        <option value="">All Status</option>
        <option value="draft"  {{ request('status') === 'draft'  ? 'selected' : '' }}>Draft</option>
        <option value="final"  {{ request('status') === 'final'  ? 'selected' : '' }}>Final</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <button class="bg-emerald-500 hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">Filter</button>
    @if(request()->anyFilled(['search','status']))
    <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-white px-4 py-2.5 rounded-xl text-sm border border-gray-700 hover:border-gray-500 transition-colors">Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Invoice #</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Subtotal</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">GST</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                    <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/60">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-800/30 transition-colors group">
                    <td class="px-6 py-4 font-mono text-emerald-400 font-medium text-sm">{{ $invoice->invoice_no }}</td>
                    <td class="px-6 py-4 text-gray-200 font-medium">{{ $invoice->customer->name }}</td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right text-gray-300 font-mono text-sm">Rs {{ number_format($invoice->subtotal, 0) }}</td>
                    <td class="px-6 py-4 text-right text-amber-400 font-mono text-sm">Rs {{ number_format($invoice->gst_amount, 0) }}</td>
                    <td class="px-6 py-4 text-right text-white font-semibold font-mono">Rs {{ number_format($invoice->total, 0) }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($invoice->status === 'draft')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Draft</span>
                        @elseif($invoice->status === 'final')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Final</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Cancelled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($invoice->status === 'final')
                            @if($invoice->payment_status === 'paid')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/20">Paid</span>
                            @elseif($invoice->payment_status === 'partial')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Partial</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-500/10 text-gray-500 border border-gray-600/20">Unpaid</span>
                            @endif
                        @else
                            <span class="text-gray-700 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('invoices.show', $invoice) }}" title="View"
                               class="text-gray-400 hover:text-emerald-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if($invoice->isDraft())
                            <a href="{{ route('invoices.edit', $invoice) }}" title="Edit"
                               class="text-gray-400 hover:text-blue-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @endif
                            <a href="{{ route('invoices.pdf', $invoice) }}" title="Download PDF"
                               class="text-gray-400 hover:text-purple-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-gray-500 text-sm">No invoices found</p>
                        <a href="{{ route('invoices.create') }}" class="mt-2 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Create your first invoice →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">
        {{ $invoices->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
