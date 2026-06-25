<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a202c; background: #fff; }

    .page { padding: 30px 35px; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .logo-area { }
    .business-name { font-size: 20px; font-weight: 700; color: #065f46; margin-bottom: 3px; }
    .business-sub  { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
    .invoice-meta  { text-align: right; }
    .invoice-title { font-size: 26px; font-weight: 800; color: #059669; }
    .invoice-no    { font-size: 11px; color: #374151; font-weight: 600; margin-top: 4px; }
    .invoice-date  { font-size: 10px; color: #6b7280; margin-top: 2px; }

    /* Divider */
    .divider { height: 2px; background: linear-gradient(to right, #059669, #34d399, transparent); margin: 16px 0; border: none; }

    /* Parties */
    .parties { display: flex; gap: 20px; margin-bottom: 20px; }
    .party { flex: 1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
    .party-label { font-size: 8px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .party-name  { font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .party-row   { font-size: 9.5px; color: #4b5563; margin-top: 2px; }
    .party-row span { font-weight: 600; color: #374151; }

    /* Items Table */
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead tr { background: #059669; }
    thead th { color: #fff; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; }
    thead th.right { text-align: right; }
    thead th.center { text-align: center; }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody tr:nth-child(odd)  { background: #ffffff; }
    tbody td { padding: 8px 10px; font-size: 10px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    tbody td.right  { text-align: right; font-family: monospace; }
    tbody td.center { text-align: center; }
    .sr-col  { width: 4%; }
    .desc-col{ width: 32%; }
    .hs-col  { width: 10%; }
    .qty-col { width: 8%; }
    .unit-col{ width: 8%; }
    .price-col{ width: 12%; }
    .gst-col  { width: 8%; }
    .gstamt-col{ width: 10%; }
    .total-col { width: 12%; font-weight: 600; color: #111827; }

    /* Totals */
    .totals-section { display: flex; justify-content: flex-end; margin-bottom: 20px; }
    .totals-box { width: 260px; }
    .totals-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
    .totals-row .label { color: #6b7280; }
    .totals-row .val   { font-family: monospace; color: #374151; font-weight: 600; }
    .totals-grand { display: flex; justify-content: space-between; padding: 8px 10px; background: #059669; border-radius: 6px; margin-top: 6px; }
    .totals-grand .label { color: #d1fae5; font-size: 11px; font-weight: 600; }
    .totals-grand .val   { color: #ffffff; font-size: 13px; font-weight: 700; font-family: monospace; }

    /* QR & IRN Placeholder */
    .fbr-section { display: flex; gap: 16px; align-items: center; background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; }
    .qr-box { width: 70px; height: 70px; background: #fff; border: 2px dashed #34d399; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
    .qr-box span { font-size: 8px; color: #6b7280; text-align: center; }
    .fbr-info { flex: 1; }
    .fbr-info .title { font-size: 9px; font-weight: 700; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px; }
    .fbr-info .irn-val { font-size: 11px; font-weight: 600; color: #374151; margin-top: 4px; font-family: monospace; }
    .fbr-info .pending { font-size: 9px; color: #6b7280; font-style: italic; margin-top: 2px; }

    /* Notes */
    .notes { background: #fffbeb; border-left: 3px solid #f59e0b; padding: 8px 12px; border-radius: 4px; margin-bottom: 16px; }
    .notes-title { font-size: 9px; font-weight: 700; color: #92400e; text-transform: uppercase; margin-bottom: 3px; }
    .notes-text  { font-size: 10px; color: #78350f; }

    /* Footer */
    .footer { text-align: center; border-top: 1px solid #e5e7eb; padding-top: 10px; margin-top: 10px; }
    .footer p { font-size: 8.5px; color: #9ca3af; }
    .footer .status-badge { display: inline-block; padding: 2px 8px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 20px; color: #059669; font-size: 8px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
    .footer .status-draft { background: #fef3c7; border-color: #fde68a; color: #92400e; }
</style>
</head>
<body>
<div class="page">

    {{-- ─── Header ──────────────────────────────────────────────────────── --}}
    <div class="header">
        <div class="logo-area">
            @if($invoice->client->logo)
                <img src="{{ storage_path('app/public/' . $invoice->client->logo) }}" height="50" alt="Logo">
            @endif
            <div class="business-name">{{ $invoice->client->business_name }}</div>
            <div class="business-sub">NTN: {{ $invoice->client->ntn ?? 'N/A' }} &nbsp;|&nbsp; STRN: {{ $invoice->client->strn ?? 'N/A' }}</div>
            <div class="business-sub">{{ $invoice->client->address }}</div>
            <div class="business-sub">{{ $invoice->client->province }} | {{ $invoice->client->phone }}</div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-no"># {{ $invoice->invoice_no }}</div>
            <div class="invoice-date">Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
            @if($invoice->due_date)
            <div class="invoice-date">Due: {{ $invoice->due_date->format('d M Y') }}</div>
            @endif
        </div>
    </div>

    <hr class="divider">

    {{-- ─── Parties ─────────────────────────────────────────────────────── --}}
    <div class="parties">
        <div class="party">
            <div class="party-label">Bill From (Seller)</div>
            <div class="party-name">{{ $invoice->client->business_name }}</div>
            <div class="party-row">NTN: <span>{{ $invoice->client->ntn ?? 'N/A' }}</span></div>
            <div class="party-row">STRN: <span>{{ $invoice->client->strn ?? 'N/A' }}</span></div>
            <div class="party-row">Province: <span>{{ $invoice->client->province }}</span></div>
        </div>
        <div class="party">
            <div class="party-label">Bill To (Buyer)</div>
            <div class="party-name">{{ $invoice->customer->name }}</div>
            <div class="party-row">NTN: <span>{{ $invoice->customer->ntn ?? 'N/A' }}</span></div>
            <div class="party-row">CNIC: <span>{{ $invoice->customer->cnic ?? 'N/A' }}</span></div>
            <div class="party-row">Province: <span>{{ $invoice->customer->province ?? 'N/A' }}</span></div>
            <div class="party-row">Address: <span>{{ $invoice->customer->address ?? 'N/A' }}</span></div>
        </div>
    </div>

    {{-- ─── Items Table ─────────────────────────────────────────────────── --}}
    <table>
        <thead>
            <tr>
                <th class="sr-col center">#</th>
                <th class="desc-col">Description</th>
                <th class="hs-col center">HS Code</th>
                <th class="qty-col center">Qty</th>
                <th class="unit-col center">Unit</th>
                <th class="price-col right">Unit Price</th>
                <th class="gst-col center">GST%</th>
                <th class="gstamt-col right">GST Amt</th>
                <th class="total-col right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td class="center">{{ $item->hs_code ?? '—' }}</td>
                <td class="center">{{ number_format($item->qty, 2) }}</td>
                <td class="center">{{ $item->unit }}</td>
                <td class="right">Rs {{ number_format($item->unit_price, 2) }}</td>
                <td class="center">{{ $item->gst_rate }}%</td>
                <td class="right">Rs {{ number_format($item->gst_amount, 2) }}</td>
                <td class="right total-col">Rs {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ─── Totals ──────────────────────────────────────────────────────── --}}
    <div class="totals-section">
        <div class="totals-box">
            <div class="totals-row">
                <span class="label">Subtotal</span>
                <span class="val">Rs {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="label">GST Amount</span>
                <span class="val">Rs {{ number_format($invoice->gst_amount, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
            <div class="totals-row">
                <span class="label">Discount</span>
                <span class="val">- Rs {{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            <div class="totals-grand">
                <span class="label">Grand Total (PKR)</span>
                <span class="val">Rs {{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- ─── FBR / IRN Section ───────────────────────────────────────────── --}}
    <div class="fbr-section">
        <div class="qr-box">
            @if($invoice->fbr_qr)
                {{-- QR will be injected in Phase 2 --}}
                <span>QR Code</span>
            @else
                <span>FBR QR<br>Phase 2</span>
            @endif
        </div>
        <div class="fbr-info">
            <div class="title">FBR Invoice Reference (IRN)</div>
            <div class="irn-val">{{ $invoice->fbr_irn ?? 'Pending FBR Submission' }}</div>
            <div class="pending">
                @if($invoice->fbr_irn) Verified by FBR Pakistan
                @else This invoice has not been submitted to FBR yet. Phase 2 integration coming soon.
                @endif
            </div>
        </div>
        <div style="text-align:right;">
            <div class="title">Province</div>
            <div style="font-size:14px; font-weight:700; color:#059669; margin-top:4px;">{{ $invoice->client->province }}</div>
        </div>
    </div>

    {{-- ─── Notes ───────────────────────────────────────────────────────── --}}
    @if($invoice->notes)
    <div class="notes">
        <div class="notes-title">Notes / Remarks</div>
        <div class="notes-text">{{ $invoice->notes }}</div>
    </div>
    @endif

    {{-- ─── Footer ──────────────────────────────────────────────────────── --}}
    <div class="footer">
        <div class="status-badge {{ $invoice->status === 'draft' ? 'status-draft' : '' }}">
            {{ strtoupper($invoice->status) }} INVOICE
        </div>
        <p>This is a computer-generated invoice. Generated on {{ now()->format('d M Y, h:i A') }}</p>
        <p>{{ $invoice->client->business_name }} &nbsp;|&nbsp; NTN: {{ $invoice->client->ntn ?? 'N/A' }} &nbsp;|&nbsp; {{ $invoice->client->province }}</p>
    </div>

</div>
</body>
</html>
