@extends('layouts.app')
@section('title', 'Add Client')
@section('page-title', 'Add New Client')
@section('page-subtitle', 'Create a client account with admin login credentials')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.clients.store') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Business Info --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Business Information
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Business Name <span class="text-red-400">*</span></label>
                <input type="text" name="business_name" value="{{ old('business_name') }}" required
                       placeholder="e.g. Saeed Traders Ltd"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('business_name') border-red-500 @enderror">
                @error('business_name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">NTN</label>
                <input type="text" name="ntn" value="{{ old('ntn') }}" placeholder="1234567-8"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">STRN</label>
                <input type="text" name="strn" value="{{ old('strn') }}" placeholder="Sales Tax Reg. No."
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Province / Authority <span class="text-red-400">*</span></label>
                <select name="province" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('province') border-red-500 @enderror">
                    <option value="">— Select —</option>
                    <option value="FBR"  {{ old('province') === 'FBR'  ? 'selected' : '' }}>Federal (FBR)</option>
                    <option value="SRB"  {{ old('province') === 'SRB'  ? 'selected' : '' }}>Sindh (SRB)</option>
                    <option value="PRA"  {{ old('province') === 'PRA'  ? 'selected' : '' }}>Punjab (PRA)</option>
                    <option value="KPRA" {{ old('province') === 'KPRA' ? 'selected' : '' }}>KPK (KPRA)</option>
                    <option value="BRA"  {{ old('province') === 'BRA'  ? 'selected' : '' }}>Balochistan (BRA)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Default GST Rate (%) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="number" name="default_gst_rate" value="{{ old('default_gst_rate', 18) }}" required
                           step="0.01" min="0" max="100"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 pr-10 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <span class="absolute right-4 top-3 text-gray-400 text-sm">%</span>
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Karachi"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="021-XXXXXXX"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Invoice Prefix <span class="text-red-400">*</span></label>
                <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', 'INV') }}" required
                       maxlength="10" placeholder="INV"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono uppercase focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none">{{ old('address') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Admin Login --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-blue-400 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Client Admin Login Credentials
        </h3>
        <p class="text-xs text-gray-500 mb-5">These credentials will be used by the client to log in to the portal.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Admin Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="admin_name" value="{{ old('admin_name') }}" required placeholder="e.g. Muhammad Saeed"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('admin_name') border-red-500 @enderror">
                @error('admin_name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Admin Email <span class="text-red-400">*</span></label>
                <input type="email" name="admin_email" value="{{ old('admin_email') }}" required placeholder="admin@clientbusiness.com"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('admin_email') border-red-500 @enderror">
                @error('admin_email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Password <span class="text-red-400">*</span></label>
                <input type="password" name="admin_password" required placeholder="Min 8 characters"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('admin_password') border-red-500 @enderror">
                @error('admin_password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Confirm Password <span class="text-red-400">*</span></label>
                <input type="password" name="admin_password_confirmation" required placeholder="Repeat password"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Client Account
        </button>
        <a href="{{ route('admin.clients.index') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>

    </form>
</div>
@endsection
