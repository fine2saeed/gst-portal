@extends('layouts.app')
@section('title', 'GST Reports')
@section('page-title', 'GST Reports')
@section('page-subtitle', $client->business_name . ' · ' . $year . ' Annual Report')

@section('header-actions')
<form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-3">
    <label class="text-xs text-gray-400">Year:</label>
    <select name="year" onchange="this.form.submit()"
            class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
        @foreach($availableYears as $y)
        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
</form>
@endsection

@section('content')

{{-- ─── Yearly Summary Cards ─────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-emerald-500/30 transition-colors">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Revenue</p>
        <p class="text-2xl font-bold text-white">Rs {{ number_format($yearlyTotals->total ?? 0, 0) }}</p>
        <p class="text-xs text-emerald-400 mt-1">{{ $yearlyTotals->count ?? 0 }} finalized invoices</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-amber-500/30 transition-colors">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">GST Collected</p>
        <p class="text-2xl font-bold text-white">Rs {{ number_format($yearlyTotals->gst ?? 0, 0) }}</p>
        <p class="text-xs text-amber-400 mt-1">{{ $client->province }} — Payable</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-blue-500/30 transition-colors">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Net Subtotal</p>
        <p class="text-2xl font-bold text-white">Rs {{ number_format($yearlyTotals->subtotal ?? 0, 0) }}</p>
        <p class="text-xs text-gray-500 mt-1">Before GST</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-purple-500/30 transition-colors">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Effective GST Rate</p>
        @php
            $effRate = ($yearlyTotals->subtotal ?? 0) > 0
                ? round(($yearlyTotals->gst / $yearlyTotals->subtotal) * 100, 2)
                : 0;
        @endphp
        <p class="text-2xl font-bold text-white">{{ $effRate }}%</p>
        <p class="text-xs text-gray-500 mt-1">Avg across all invoices</p>
    </div>
</div>

{{-- ─── Monthly Breakdown Table ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800">
            <h3 class="text-sm font-semibold text-white">Monthly Breakdown — {{ $year }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800/60">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoices</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">GST</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/40">
                    @php
                        $monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                        $maxTotal = collect($months)->max('total') ?: 1;
                    @endphp
                    @foreach($months as $m => $data)
                    <tr class="{{ $data->count > 0 ? 'hover:bg-gray-800/20' : 'opacity-40' }} transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-300 font-medium w-8">{{ $monthNames[$m-1] }}</span>
                                @if($data->count > 0)
                                <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden min-w-16">
                                    <div class="h-full bg-emerald-500 rounded-full"
                                         style="width: {{ round(($data->total / $maxTotal) * 100) }}%"></div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-center text-gray-400">{{ $data->count ?: '—' }}</td>
                        <td class="px-4 py-3.5 text-right font-mono text-gray-300 text-xs">
                            {{ $data->count > 0 ? 'Rs ' . number_format($data->subtotal, 0) : '—' }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-mono text-amber-400 text-xs">
                            {{ $data->count > 0 ? 'Rs ' . number_format($data->gst, 0) : '—' }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-mono font-semibold text-white text-sm">
                            {{ $data->count > 0 ? 'Rs ' . number_format($data->total, 0) : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-700 bg-gray-800/30">
                        <td class="px-6 py-3.5 text-xs font-bold text-gray-300 uppercase tracking-wider">Total {{ $year }}</td>
                        <td class="px-4 py-3.5 text-center font-bold text-white">{{ $yearlyTotals->count ?? 0 }}</td>
                        <td class="px-4 py-3.5 text-right font-mono font-bold text-gray-200 text-xs">Rs {{ number_format($yearlyTotals->subtotal ?? 0, 0) }}</td>
                        <td class="px-4 py-3.5 text-right font-mono font-bold text-amber-400 text-xs">Rs {{ number_format($yearlyTotals->gst ?? 0, 0) }}</td>
                        <td class="px-6 py-3.5 text-right font-mono font-bold text-emerald-400">Rs {{ number_format($yearlyTotals->total ?? 0, 0) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ─── Right Panel: Status + Top Customers ─────────────────────────── --}}
    <div class="space-y-5">

        {{-- Invoice Status Breakdown --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Invoice Status — {{ $year }}</h3>
            <div class="space-y-3">
                @foreach($statusBreakdown as $row)
                @php
                    $color = match($row->status) {
                        'final'     => 'emerald',
                        'draft'     => 'amber',
                        'cancelled' => 'red',
                        default     => 'gray',
                    };
                    $label = match($row->status) {
                        'final'     => 'Finalized',
                        'draft'     => 'Draft',
                        'cancelled' => 'Cancelled',
                        default     => ucfirst($row->status),
                    };
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $color }}-400"></span>
                        <span class="text-sm text-gray-300">{{ $label }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-semibold text-white">{{ $row->count }}</span>
                        <span class="text-xs text-gray-500 ml-1">· Rs {{ number_format($row->total, 0) }}</span>
                    </div>
                </div>
                @endforeach
                @if($statusBreakdown->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No invoices in {{ $year }}</p>
                @endif
            </div>
        </div>

        {{-- Top Customers --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Top Customers — {{ $year }}</h3>
            <div class="space-y-3">
                @forelse($topCustomers as $i => $cust)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-center shrink-0">
                        <span class="text-xs font-bold text-gray-400">{{ $i + 1 }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-200 font-medium truncate">{{ $cust->name }}</p>
                        <p class="text-xs text-gray-500">{{ $cust->invoice_count }} invoices · Rs {{ number_format($cust->total_gst, 0) }} GST</p>
                    </div>
                    <span class="text-sm font-mono font-semibold text-emerald-400 shrink-0">Rs {{ number_format($cust->total_revenue, 0) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No data for {{ $year }}</p>
                @endforelse
            </div>
        </div>

        {{-- GST Authority Info --}}
        <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-amber-400 mb-3">GST Filing Info</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-400">Authority</span>
                    <span class="text-white font-semibold">{{ $client->province }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Default Rate</span>
                    <span class="text-white font-semibold">{{ $client->default_gst_rate }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">NTN</span>
                    <span class="text-white font-mono">{{ $client->ntn ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">STRN</span>
                    <span class="text-white font-mono">{{ $client->strn ?? 'Not set' }}</span>
                </div>
                <div class="mt-3 pt-3 border-t border-amber-500/20">
                    <p class="text-amber-400/70">FBR e-Filing integration coming in Phase 2.</p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ─── Bar Chart (SVG-based, no library needed) ──────────────────────────── --}}
<div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
    <h3 class="text-sm font-semibold text-white mb-6">Revenue vs GST — {{ $year }}</h3>
    @php
        $chartMonths = ['J','F','M','A','M','J','J','A','S','O','N','D'];
        $maxVal = max(collect($months)->max('total'), 1);
        $chartHeight = 140;
    @endphp
    <div class="flex items-end gap-1.5 h-44 relative">
        {{-- Y-axis grid lines --}}
        @foreach([0, 25, 50, 75, 100] as $pct)
        <div class="absolute w-full border-t border-gray-800/50 text-right"
             style="bottom: {{ $chartHeight * $pct / 100 }}px; left: 0; right: 0;">
            <span class="text-[10px] text-gray-600 absolute -top-3 -left-1 leading-none">
                @if($pct > 0)Rs {{ number_format($maxVal * $pct / 100 / 1000, 0) }}k @endif
            </span>
        </div>
        @endforeach

        <div class="flex items-end gap-1.5 w-full ml-6">
            @foreach($months as $m => $data)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex gap-0.5 items-end" style="height: {{ $chartHeight }}px;">
                    @php
                        $totalH = $data->total > 0 ? max(2, round(($data->total / $maxVal) * $chartHeight)) : 0;
                        $gstH   = $data->gst > 0   ? max(2, round(($data->gst / $maxVal) * $chartHeight)) : 0;
                    @endphp
                    <div class="flex-1 bg-emerald-500/70 hover:bg-emerald-400 rounded-t transition-all duration-300"
                         style="height: {{ $totalH }}px;"
                         title="Revenue: Rs {{ number_format($data->total, 0) }}"></div>
                    <div class="flex-1 bg-amber-400/70 hover:bg-amber-300 rounded-t transition-all duration-300"
                         style="height: {{ $gstH }}px;"
                         title="GST: Rs {{ number_format($data->gst, 0) }}"></div>
                </div>
                <span class="text-[10px] text-gray-600 font-medium">{{ $chartMonths[$m-1] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="flex items-center gap-5 mt-4 ml-6 text-xs text-gray-400">
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-500/70 rounded-sm"></span> Revenue</div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 bg-amber-400/70 rounded-sm"></span> GST</div>
    </div>
</div>

@endsection
