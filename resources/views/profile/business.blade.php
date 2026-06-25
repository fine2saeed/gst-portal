@extends('layouts.app')
@section('title', 'Business Profile')
@section('page-title', 'Business Profile')
@section('page-subtitle', 'Your GST business details — required before creating invoices')

@section('content')
<div class="max-w-3xl">

    @if(!$client->profile_complete)
    <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/30 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-amber-300 text-sm">⚠️ Your profile is incomplete. Fill all required fields to start creating invoices.</p>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    {{-- Business Identity --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Business Identity
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Logo --}}
            <div class="sm:col-span-2 flex items-center gap-5">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden flex items-center justify-center shrink-0">
                    @if($client->logo)
                        <img src="{{ asset('storage/' . $client->logo) }}" class="w-full h-full object-cover" alt="Logo">
                    @else
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Business Logo (optional)</label>
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg"
                           class="block text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">PNG/JPG · max 2MB · appears on PDF invoices</p>
                </div>
            </div>

            {{-- Business Name --}}
            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Business Name <span class="text-red-400">*</span></label>
                <input type="text" name="business_name" value="{{ old('business_name', $client->business_name) }}" required
                       placeholder="e.g. Rafey Traders Pvt Ltd"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('business_name') border-red-500 @enderror">
                @error('business_name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            {{-- NTN --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">NTN (National Tax No.) <span class="text-red-400">*</span></label>
                <input type="text" name="ntn" value="{{ old('ntn', $client->ntn) }}" required
                       placeholder="e.g. 1234567-8"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('ntn') border-red-500 @enderror">
                @error('ntn')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            {{-- STRN --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">STRN (Sales Tax Reg. No.)</label>
                <input type="text" name="strn" value="{{ old('strn', $client->strn) }}"
                       placeholder="e.g. 12-00-1234-567-89"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            {{-- Province --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Tax Authority / Province <span class="text-red-400">*</span></label>
                <select name="province" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('province') border-red-500 @enderror">
                    <option value="">— Select Province —</option>
                    @foreach($provinces as $code => $label)
                    <option value="{{ $code }}" {{ old('province', $client->province) === $code ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('province')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            {{-- Default GST Rate --}}
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Default GST Rate (%) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="number" name="default_gst_rate" value="{{ old('default_gst_rate', $client->default_gst_rate ?? 18) }}"
                           step="0.01" min="0" max="100" required
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 pr-10 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <span class="absolute right-4 top-3 text-gray-400 text-sm font-bold">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Standard: FBR=18% · SRB=13% · PRA=16% · KPRA=15% · BRA=15%</p>
            </div>

        </div>
    </div>

    {{-- Contact & Address --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Contact & Address
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Business Address <span class="text-red-400">*</span></label>
                <textarea name="address" rows="2" required placeholder="Street address, area..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none @error('address') border-red-500 @enderror">{{ old('address', $client->address) }}</textarea>
                @error('address')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">City <span class="text-red-400">*</span></label>
                <input type="text" name="city" value="{{ old('city', $client->city) }}" required
                       placeholder="e.g. Karachi"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $client->phone) }}"
                       placeholder="e.g. 021-XXXXXXXX"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Business Email</label>
                <input type="email" name="email" value="{{ old('email', $client->email) }}"
                       placeholder="billing@yourbusiness.com"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Invoice Number Prefix <span class="text-red-400">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $client->invoice_prefix ?? 'INV') }}"
                           maxlength="10" required placeholder="INV"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono uppercase focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                </div>
                <p class="text-xs text-gray-500 mt-1">Preview: <span class="text-emerald-400 font-mono">{{ $client->invoice_prefix ?? 'INV' }}-{{ date('Y') }}-00001</span></p>
            </div>

        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex items-center gap-4">
        <button type="submit"
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Business Profile
        </button>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>

    </form>
</div>
@endsection
