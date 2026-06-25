@extends('layouts.app')
@section('title', 'Invoice ' . $invoice->invoice_no)
@section('page-title', 'Invoice #' . $invoice->invoice_no)
@section('page-subtitle', $invoice->customer->name . ' · ' . $invoice->invoice_date->format('d M Y'))

@section('header-actions')
<div class="flex gap-3">
    @if($invoice->isDraft())
    <a href="{{ route('invoices.edit', $invoice) }}"
       class="flex items-center gap-2 text-sm bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
    </a>
    @endif
    @if(!$invoice->isCancelled())
    <a href="{{ route('invoices.pdf', $invoice) }}"
       class="flex items-center gap-2 text-sm bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Download PDF
    </a>
    @if($invoice->customer->email)
    <form action="{{ route('invoices.email', $invoice) }}" method="POST">
        @csrf
        <button type="submit"
                class="flex items-center gap-2 text-sm bg-emerald-500 hover:bg-emerald-400 text-white px-4 py-2 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Email Invoice
        </button>
    </form>
    @endif
    @endif
</div>
@endsection

@section('content')
<div class="max-w-4xl space-y-5">

    {{-- Status Bar --}}
    <div class="flex items-center gap-3">
        @if($invoice->status === 'draft')
            <span class="flex items-center gap-2 px-3 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-sm font-semibold">
                <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>Draft
            </span>
            <span class="text-gray-500 text-sm">This invoice is editable. Finalize it to lock.</span>
        @elseif($invoice->status === 'final')
            <span class="flex items-center gap-2 px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-sm font-semibold">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Final
            </span>
            {{-- Payment Status Badge --}}
            @if($invoice->payment_status === 'paid')
                <span class="flex items-center gap-2 px-3 py-1.5 bg-green-500/10 border border-green-500/20 rounded-full text-green-400 text-sm font-semibold">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    Paid
                </span>
            @elseif($invoice->payment_status === 'partial')
                <span class="flex items-center gap-2 px-3 py-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-sm font-semibold">
                    Partial — Rs {{ number_format($invoice->balance(), 2) }} due
                </span>
            @else
                <span class="flex items-center gap-2 px-3 py-1.5 bg-gray-500/10 border border-gray-500/20 rounded-full text-gray-400 text-sm font-semibold">
                    Unpaid
                </span>
            @endif
        @else
            <span class="flex items-center gap-2 px-3 py-1.5 bg-red-500/10 border border-red-500/20 rounded-full text-red-400 text-sm font-semibold">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                Cancelled
            </span>
            @if($invoice->cancellation_reason)
                <span class="text-gray-500 text-sm">Reason: {{ $invoice->cancellation_reason }}</span>
            @endif
        @endif
    </div>

    {{-- Invoice Card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

        {{-- Invoice Header --}}
        <div class="p-6 border-b border-gray-800 flex justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $invoice->client->business_name }}</h2>
                <p class="text-sm text-gray-400 mt-1">NTN: {{ $invoice->client->ntn ?? 'N/A' }} &nbsp;|&nbsp; STRN: {{ $invoice->client->strn ?? 'N/A' }}</p>
                <p class="text-sm text-gray-400">{{ $invoice->client->address }}</p>
                <p class="text-sm text-emerald-400 font-medium mt-1">{{ $invoice->client->province }}</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-extrabold text-emerald-400">INVOICE</p>
                <p class="text-lg font-mono font-bold text-white mt-1">{{ $invoice->invoice_no }}</p>
                <p class="text-sm text-gray-400">Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
                @if($invoice->due_date)
                <p class="text-sm text-gray-400">Due: {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        {{-- Parties --}}
        <div class="grid grid-cols-2 gap-6 p-6 border-b border-gray-800">
            <div class="bg-gray-800/40 rounded-xl p-4">
                <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-3">Bill From</p>
                <p class="font-semibold text-white">{{ $invoice->client->business_name }}</p>
                <p class="text-sm text-gray-400 mt-1">NTN: {{ $invoice->client->ntn ?? 'N/A' }}</p>
                <p class="text-sm text-gray-400">STRN: {{ $invoice->client->strn ?? 'N/A' }}</p>
            </div>
            <div class="bg-gray-800/40 rounded-xl p-4">
                <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">Bill To</p>
                <p class="font-semibold text-white">{{ $invoice->customer->name }}</p>
                <p class="text-sm text-gray-400 mt-1">NTN: {{ $invoice->customer->ntn ?? 'N/A' }}</p>
                <p class="text-sm text-gray-400">Address: {{ $invoice->customer->address ?? 'N/A' }}</p>
                @if($invoice->customer->email)
                <p class="text-sm text-gray-400">{{ $invoice->customer->email }}</p>
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <div class="p-6 border-b border-gray-800">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider w-8">#</th>
                        <th class="text-left pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="text-center pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">HS Code</th>
                        <th class="text-center pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Qty</th>
                        <th class="text-right pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Unit Price</th>
                        <th class="text-center pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">GST%</th>
                        <th class="text-right pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">GST Amt</th>
                        <th class="text-right pb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @foreach($invoice->items as $i => $item)
                    <tr class="hover:bg-gray-800/20 transition-colors">
                        <td class="py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="py-3 text-gray-200 font-medium">
                            {{ $item->description }}
                            @if($item->unit)<span class="text-xs text-gray-500 ml-1">({{ $item->unit }})</span>@endif
                        </td>
                        <td class="py-3 text-center text-gray-400 font-mono text-xs">{{ $item->hs_code ?? '—' }}</td>
                        <td class="py-3 text-center text-gray-300">{{ number_format($item->qty, 2) }}</td>
                        <td class="py-3 text-right font-mono text-gray-300">Rs {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-center text-amber-400 font-mono">{{ $item->gst_rate }}%</td>
                        <td class="py-3 text-right font-mono text-amber-400">Rs {{ number_format($item->gst_amount, 2) }}</td>
                        <td class="py-3 text-right font-mono font-semibold text-white">Rs {{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="p-6 flex justify-end border-b border-gray-800">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-gray-200 font-mono">Rs {{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">GST Amount</span>
                    <span class="text-amber-400 font-mono">Rs {{ number_format($invoice->gst_amount, 2) }}</span>
                </div>
                @if($invoice->discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Discount</span>
                    <span class="text-red-400 font-mono">- Rs {{ number_format($invoice->discount, 2) }}</span>
                </div>
                @endif
                <div class="border-t border-gray-700 pt-3 flex justify-between items-center">
                    <span class="text-white font-semibold">Grand Total</span>
                    <span class="text-emerald-400 text-xl font-bold font-mono">Rs {{ number_format($invoice->total, 2) }}</span>
                </div>
                @if($invoice->isFinal())
                <div class="flex justify-between text-sm pt-1">
                    <span class="text-gray-400">Amount Paid</span>
                    <span class="text-green-400 font-mono">Rs {{ number_format($invoice->amount_paid, 2) }}</span>
                </div>
                @if($invoice->balance() > 0)
                <div class="flex justify-between text-sm font-semibold">
                    <span class="text-gray-300">Balance Due</span>
                    <span class="text-red-400 font-mono">Rs {{ number_format($invoice->balance(), 2) }}</span>
                </div>
                @endif
                @endif
            </div>
        </div>

        @if($invoice->notes)
        <div class="px-6 pb-6 pt-4 border-b border-gray-800">
            <div class="bg-gray-800/40 rounded-xl p-4 border border-gray-700/50">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
                <p class="text-sm text-gray-300">{{ $invoice->notes }}</p>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="px-6 py-4 bg-gray-800/20 border-t border-gray-800 text-xs text-gray-500 flex justify-between">
            <span>Created by {{ $invoice->creator->name }} on {{ $invoice->created_at->format('d M Y, h:i A') }}</span>
            <span>FBR Status: <span class="text-amber-400">{{ $invoice->fbr_status ?? 'Not submitted (Phase 2)' }}</span></span>
        </div>
    </div>

    {{-- ─── Payment Tracking (Final invoices only) ───────────────────────── --}}
    @if($invoice->isFinal())
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Payments
            </h3>
            @if(!$invoice->isPaid())
            <button onclick="document.getElementById('add-payment-form').classList.toggle('hidden')"
                    class="flex items-center gap-1.5 text-xs bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/20 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Record Payment
            </button>
            @endif
        </div>

        {{-- Add Payment Form --}}
        @if(!$invoice->isPaid())
        <div id="add-payment-form" class="hidden px-6 py-5 border-b border-gray-800 bg-gray-800/20">
            <form action="{{ route('invoices.payments.store', $invoice) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Amount (Rs) <span class="text-red-400">*</span></label>
                        <input type="number" name="amount" min="0.01" step="0.01"
                               max="{{ $invoice->balance() }}"
                               value="{{ $invoice->balance() }}"
                               required
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/30">
                        <p class="text-xs text-gray-500 mt-1">Max: Rs {{ number_format($invoice->balance(), 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Date <span class="text-red-400">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/30">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Method <span class="text-red-400">*</span></label>
                        <select name="method" required
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/30">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="online">Online/Mobile</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Reference No.</label>
                        <input type="text" name="reference_no" placeholder="Cheque/TRN/Ref..."
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/30">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                            Save Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- Payments List --}}
        <div class="divide-y divide-gray-800/60">
            @forelse($invoice->payments as $payment)
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-green-500/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">Rs {{ number_format($payment->amount, 2) }}</p>
                        <p class="text-xs text-gray-400">
                            {{ \App\Models\InvoicePayment::methodLabel($payment->method) }}
                            @if($payment->reference_no) · Ref: {{ $payment->reference_no }} @endif
                            · {{ $payment->payment_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <form action="{{ route('invoices.payments.destroy', [$invoice, $payment]) }}" method="POST"
                      onsubmit="return confirm('Remove this payment record?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-600 hover:text-red-400 transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500 text-sm">
                No payments recorded yet.
            </div>
            @endforelse
        </div>
    </div>
    @endif

    {{-- ─── Cancel Invoice Section ──────────────────────────────────────────── --}}
    @if(!$invoice->isCancelled())
    <div class="bg-gray-900 border border-red-500/20 rounded-2xl p-6" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-red-400">Danger Zone</h3>
                <p class="text-xs text-gray-500 mt-0.5">Cancel this invoice permanently. This cannot be undone.</p>
            </div>
            <button @click="open = !open"
                    class="flex items-center gap-2 text-sm bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Cancel Invoice
            </button>
        </div>
        <div x-show="open" x-cloak class="mt-5 pt-5 border-t border-red-500/20">
            <form action="{{ route('invoices.cancel', $invoice) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs text-gray-400 mb-1.5">Cancellation Reason (optional)</label>
                    <textarea name="cancellation_reason" rows="2" placeholder="e.g. Customer requested cancellation, duplicate invoice..."
                              class="w-full bg-gray-800 border border-red-500/30 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-red-500 resize-none"></textarea>
                </div>
                <button type="submit"
                        onclick="return confirm('Are you sure you want to cancel Invoice #{{ $invoice->invoice_no }}? This cannot be undone.')"
                        class="bg-red-600 hover:bg-red-500 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm">
                    Confirm Cancellation
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
