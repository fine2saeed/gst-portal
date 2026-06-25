@extends('layouts.app')
@section('title', 'Add Customer')
@section('page-title', 'Add New Customer')
@section('page-subtitle', 'Add a buyer to your customer list')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('customers.store') }}" method="POST" class="space-y-5">
    @csrf

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Customer Details
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Customer / Company Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. ABC Company or Ali Raza"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">NTN (for business buyers)</label>
                <input type="text" name="ntn" value="{{ old('ntn') }}" placeholder="1234567-8"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">CNIC (for individual buyers)</label>
                <input type="text" name="cnic" value="{{ old('cnic') }}" placeholder="35202-XXXXXXX-X"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">STRN</label>
                <input type="text" name="strn" value="{{ old('strn') }}" placeholder="Sales Tax Reg. No."
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Province / Authority</label>
                <select name="province"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <option value="">— Select Province —</option>
                    @foreach($provinces as $code => $label)
                    <option value="{{ $code }}" {{ old('province') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Address</label>
                <textarea name="address" rows="2" placeholder="Full postal address..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none">{{ old('address') }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Lahore"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0300-1234567"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Email (for invoice delivery)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="buyer@company.com"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                <p class="text-xs text-gray-500 mt-1">Used to email invoices directly to this customer</p>
            </div>

        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit"
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Customer
        </button>
        <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>

    </form>
</div>
@endsection
