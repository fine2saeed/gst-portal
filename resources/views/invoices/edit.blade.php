@extends('layouts.app')
@section('title', 'Edit Invoice ' . $invoice->invoice_no)
@section('page-title', 'Edit Invoice')
@section('page-subtitle', 'Invoice No: ' . $invoice->invoice_no)

@section('content')
<form id="invoice-form" action="{{ route('invoices.update', $invoice) }}" method="POST">
@csrf
@method('PUT')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ─── LEFT COLUMN (2/3) ─────────────────────────────────────────────── --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Invoice Header Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-gray-300 mb-4 uppercase tracking-wide">Invoice Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Invoice Number</label>
                    <input type="text" value="{{ $invoice->invoice_no }}" readonly
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-emerald-400 font-mono text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Invoice Date <span class="text-red-400">*</span></label>
                    <input type="date" name="invoice_date" id="invoice_date"
                           value="{{ $invoice->invoice_date->format('Y-m-d') }}" required
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Due Date</label>
                    <input type="date" name="due_date" id="due_date"
                           value="{{ $invoice->due_date?->format('Y-m-d') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30">
                </div>
            </div>
        </div>

        {{-- Seller Info Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-gray-300 mb-4 uppercase tracking-wide">Seller (Your Business)</h3>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-500">Business Name</p>
                    <p class="text-sm text-white font-medium">{{ $clients->business_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">NTN</p>
                    <p class="text-sm text-white font-mono">{{ $clients->ntn ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">STRN</p>
                    <p class="text-sm text-white font-mono">{{ $clients->strn ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Province/Authority</p>
                    <p class="text-sm text-emerald-400 font-medium">{{ $clients->province }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">Address</p>
                    <p class="text-sm text-gray-300">{{ $clients->address ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Buyer Info Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wide">Buyer Information</h3>
                <a href="{{ route('customers.create') }}" target="_blank" class="text-xs text-emerald-400 hover:text-emerald-300">+ Add New Customer</a>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Select Customer <span class="text-red-400">*</span></label>
                <select name="customer_id" id="customer_id" required onchange="fillBuyerInfo(this)"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30">
                    <option value="">— Select a customer —</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                            data-ntn="{{ $customer->ntn }}"
                            data-address="{{ $customer->address }}"
                            data-province="{{ $customer->province }}"
                            {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div id="buyer-details" class="mt-4 grid grid-cols-3 gap-3 {{ $invoice->customer ? '' : 'hidden' }}">
                <div>
                    <p class="text-xs text-gray-500">NTN</p>
                    <p id="buyer-ntn" class="text-sm text-white font-mono">{{ $invoice->customer->ntn ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Province</p>
                    <p id="buyer-province" class="text-sm text-white">{{ $invoice->customer->province ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Address</p>
                    <p id="buyer-address" class="text-sm text-gray-300">{{ $invoice->customer->address ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Invoice Items Table --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wide">Invoice Items</h3>
                <button type="button" onclick="addRow()"
                        class="flex items-center gap-2 text-xs bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </button>
            </div>

            {{-- Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-2 mb-2 px-1">
                <div class="col-span-4 text-xs text-gray-500 font-medium uppercase">Description</div>
                <div class="col-span-1 text-xs text-gray-500 font-medium uppercase text-center">HS Code</div>
                <div class="col-span-1 text-xs text-gray-500 font-medium uppercase text-center">Qty</div>
                <div class="col-span-2 text-xs text-gray-500 font-medium uppercase text-center">Unit Price</div>
                <div class="col-span-1 text-xs text-gray-500 font-medium uppercase text-center">GST%</div>
                <div class="col-span-2 text-xs text-gray-500 font-medium uppercase text-right">Total</div>
                <div class="col-span-1"></div>
            </div>

            {{-- Items Container --}}
            <div id="items-container" class="space-y-2">
                {{-- Rows added by JS --}}
            </div>

            {{-- Products JSON for JS --}}
            @php
                $productsJson = $products->map(function($p) {
                    return ['id'=>$p->id,'name'=>$p->name,'price'=>$p->price,'gst_rate'=>$p->gst_rate,'hs_code'=>$p->hs_code,'unit'=>$p->unit];
                });
                $existingItemsJson = $invoice->items->map(function($i) {
                    return [
                        'id'          => $i->id,
                        'product_id'  => $i->product_id,
                        'description' => $i->description,
                        'hs_code'     => $i->hs_code,
                        'qty'         => $i->qty,
                        'unit_price'  => $i->unit_price,
                        'gst_rate'    => $i->gst_rate,
                        'unit'        => $i->unit,
                    ];
                });
            @endphp
            <script id="products-data" type="application/json">
                @json($productsJson)
            </script>

            {{-- Existing items JSON for pre-fill --}}
            <script id="existing-items" type="application/json">
                @json($existingItemsJson)
            </script>

        </div>

        {{-- Notes --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <label class="block text-xs text-gray-400 mb-1.5">Notes / Remarks (optional)</label>
            <textarea name="notes" rows="3" placeholder="Any additional notes for this invoice..."
                      class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 resize-none">{{ $invoice->notes }}</textarea>
        </div>

    </div>

    {{-- ─── RIGHT COLUMN (1/3) ─────────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Totals Card --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sticky top-6">
            <h3 class="text-sm font-semibold text-gray-300 mb-5 uppercase tracking-wide">Summary</h3>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-white font-medium font-mono" id="summary-subtotal">Rs {{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">GST Amount</span>
                    <span class="text-white font-medium font-mono" id="summary-gst">Rs {{ number_format($invoice->gst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm items-center">
                    <span class="text-gray-400">Discount</span>
                    <div class="flex items-center gap-1">
                        <span class="text-gray-400">Rs</span>
                        <input type="number" name="discount" id="discount" value="{{ $invoice->discount }}" min="0" step="0.01"
                               onchange="recalculate()"
                               class="w-24 bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-white text-sm font-mono text-right focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-3 flex justify-between items-baseline">
                    <span class="text-gray-200 font-semibold">Grand Total</span>
                    <span class="text-emerald-400 text-xl font-bold font-mono" id="summary-total">Rs {{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <button type="submit" name="action" value="finalize"
                        class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Finalize Invoice
                </button>
                <button type="submit" name="action" value="draft"
                        class="w-full bg-gray-800 hover:bg-gray-700 text-gray-200 font-medium py-3 rounded-xl transition-colors text-sm border border-gray-700">
                    Save as Draft
                </button>
                <a href="{{ route('invoices.show', $invoice) }}"
                   class="block text-center text-gray-500 hover:text-gray-300 text-sm py-2 transition-colors">
                    Cancel
                </a>
            </div>

            {{-- GST Info --}}
            <div class="mt-5 p-3 bg-gray-800/50 rounded-xl border border-gray-700/50">
                <p class="text-xs text-gray-500 font-medium mb-1">GST Authority</p>
                <p class="text-sm text-emerald-400 font-medium">{{ $clients->province }}</p>
                <p class="text-xs text-gray-500 mt-1">Default rate: {{ $clients->default_gst_rate }}%</p>
            </div>
        </div>

    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const products = JSON.parse(document.getElementById('products-data').textContent);
const existingItems = JSON.parse(document.getElementById('existing-items').textContent);
let rowIndex = 0;

function addRow(item = null) {
    const container = document.getElementById('items-container');
    const idx = rowIndex++;
    const p = item || {};

    const row = document.createElement('div');
    row.className = 'item-row grid grid-cols-12 gap-2 items-start bg-gray-800/40 border border-gray-700/50 rounded-xl p-3';
    row.dataset.index = idx;

    row.innerHTML = `
        <div class="col-span-4">
            <select onchange="selectProduct(this, ${idx})"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs mb-1 focus:outline-none focus:border-emerald-500">
                <option value="">— Select product —</option>
                ${products.map(prod => `<option value="${prod.id}" ${p.product_id == prod.id ? 'selected' : ''} data-price="${prod.price}" data-gst="${prod.gst_rate}" data-hs="${prod.hs_code||''}" data-unit="${prod.unit}">${prod.name}</option>`).join('')}
            </select>
            <input type="text" name="items[${idx}][description]" placeholder="Description *" required value="${escHtml(p.description||'')}"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs focus:outline-none focus:border-emerald-500">
            <input type="hidden" name="items[${idx}][product_id]" class="product-id-field" value="${p.product_id||''}">
        </div>
        <div class="col-span-1">
            <input type="text" name="items[${idx}][hs_code]" placeholder="HS Code" value="${escHtml(p.hs_code||'')}"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs text-center focus:outline-none focus:border-emerald-500">
        </div>
        <div class="col-span-1">
            <input type="number" name="items[${idx}][qty]" value="${p.qty||1}" min="0.001" step="0.001" required
                   oninput="recalcRow(this, ${idx})"
                   class="qty-field w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs text-center focus:outline-none focus:border-emerald-500">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${idx}][unit_price]" value="${p.unit_price||0}" min="0" step="0.01" required
                   oninput="recalcRow(this, ${idx})"
                   class="price-field w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs text-center focus:outline-none focus:border-emerald-500">
        </div>
        <div class="col-span-1">
            <input type="number" name="items[${idx}][gst_rate]" value="${p.gst_rate||0}" min="0" max="100" step="0.01" required
                   oninput="recalcRow(this, ${idx})"
                   class="gst-field w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs text-center focus:outline-none focus:border-emerald-500">
        </div>
        <div class="col-span-2 flex items-center justify-end">
            <span class="row-total text-white font-mono text-sm font-medium">Rs 0.00</span>
        </div>
        <div class="col-span-1 flex items-center justify-center">
            <button type="button" onclick="removeRow(this)"
                    class="text-gray-600 hover:text-red-400 transition-colors p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `;

    container.appendChild(row);
    recalcRow(row.querySelector('.qty-field'), idx);
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function selectProduct(select, idx) {
    const opt = select.selectedOptions[0];
    if (!opt.value) return;
    const row = document.querySelector(`[data-index="${idx}"]`);
    row.querySelector('[name$="[unit_price]"]').value = opt.dataset.price;
    row.querySelector('[name$="[gst_rate]"]').value   = opt.dataset.gst;
    row.querySelector('[name$="[hs_code]"]').value    = opt.dataset.hs;
    row.querySelector('[name$="[description]"]').value = opt.text;
    row.querySelector('.product-id-field').value       = opt.value;
    recalcRow(row.querySelector('.qty-field'), idx);
}

function recalcRow(el, idx) {
    const row   = document.querySelector(`[data-index="${idx}"]`);
    if (!row) return;
    const qty   = parseFloat(row.querySelector('.qty-field').value) || 0;
    const price = parseFloat(row.querySelector('.price-field').value) || 0;
    const gst   = parseFloat(row.querySelector('.gst-field').value) || 0;
    const line  = qty * price;
    const gstAmt= line * gst / 100;
    const total = line + gstAmt;
    row.querySelector('.row-total').textContent = 'Rs ' + total.toLocaleString('en-PK', {minimumFractionDigits:2});
    recalculate();
}

function recalculate() {
    let subtotal = 0, gstTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('.qty-field').value) || 0;
        const price = parseFloat(row.querySelector('.price-field').value) || 0;
        const gst   = parseFloat(row.querySelector('.gst-field').value) || 0;
        const line  = qty * price;
        subtotal += line;
        gstTotal += line * gst / 100;
    });
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total    = subtotal + gstTotal - discount;
    document.getElementById('summary-subtotal').textContent = 'Rs ' + subtotal.toLocaleString('en-PK', {minimumFractionDigits:2});
    document.getElementById('summary-gst').textContent      = 'Rs ' + gstTotal.toLocaleString('en-PK', {minimumFractionDigits:2});
    document.getElementById('summary-total').textContent    = 'Rs ' + total.toLocaleString('en-PK', {minimumFractionDigits:2});
}

function removeRow(btn) {
    const row = btn.closest('.item-row');
    if (document.querySelectorAll('.item-row').length === 1) {
        alert('Invoice must have at least one item.');
        return;
    }
    row.remove();
    recalculate();
}

function fillBuyerInfo(select) {
    const opt = select.selectedOptions[0];
    if (!opt.value) { document.getElementById('buyer-details').classList.add('hidden'); return; }
    document.getElementById('buyer-ntn').textContent     = opt.dataset.ntn || '—';
    document.getElementById('buyer-province').textContent= opt.dataset.province || '—';
    document.getElementById('buyer-address').textContent = opt.dataset.address || '—';
    document.getElementById('buyer-details').classList.remove('hidden');
}

// Load existing items on page load
existingItems.forEach(item => addRow(item));
</script>
@endpush
