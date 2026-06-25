@extends('layouts.app')
@section('title', 'Edit Client')
@section('page-title', 'Edit Client')
@section('page-subtitle', $client->business_name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.clients.update', $client) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5">Business Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Business Name <span class="text-red-400">*</span></label>
                <input type="text" name="business_name" value="{{ old('business_name', $client->business_name) }}" required
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">NTN</label>
                <input type="text" name="ntn" value="{{ old('ntn', $client->ntn) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">STRN</label>
                <input type="text" name="strn" value="{{ old('strn', $client->strn) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Province / Authority</label>
                <select name="province" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <option value="FBR"  {{ old('province', $client->province) === 'FBR'  ? 'selected' : '' }}>Federal (FBR)</option>
                    <option value="SRB"  {{ old('province', $client->province) === 'SRB'  ? 'selected' : '' }}>Sindh (SRB)</option>
                    <option value="PRA"  {{ old('province', $client->province) === 'PRA'  ? 'selected' : '' }}>Punjab (PRA)</option>
                    <option value="KPRA" {{ old('province', $client->province) === 'KPRA' ? 'selected' : '' }}>KPK (KPRA)</option>
                    <option value="BRA"  {{ old('province', $client->province) === 'BRA'  ? 'selected' : '' }}>Balochistan (BRA)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Default GST Rate (%)</label>
                <div class="relative">
                    <input type="number" name="default_gst_rate" value="{{ old('default_gst_rate', $client->default_gst_rate) }}"
                           step="0.01" min="0" max="100"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 pr-10 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <span class="absolute right-4 top-3 text-gray-400 text-sm">%</span>
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">City</label>
                <input type="text" name="city" value="{{ old('city', $client->city) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Invoice Prefix</label>
                <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $client->invoice_prefix) }}"
                       maxlength="10" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono uppercase focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Status</label>
                <select name="is_active" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <option value="1" {{ $client->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$client->is_active ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none">{{ old('address', $client->address) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
        <a href="{{ route('admin.clients.index') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>
    </form>
</div>
@endsection
