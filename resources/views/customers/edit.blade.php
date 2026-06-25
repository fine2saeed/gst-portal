@extends('layouts.app')
@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')
@section('page-subtitle', $customer->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5">Customer Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">NTN</label>
                <input type="text" name="ntn" value="{{ old('ntn', $customer->ntn) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">CNIC</label>
                <input type="text" name="cnic" value="{{ old('cnic', $customer->cnic) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">STRN</label>
                <input type="text" name="strn" value="{{ old('strn', $customer->strn) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Province</label>
                <select name="province" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <option value="">— Select —</option>
                    @foreach($provinces as $code => $label)
                    <option value="{{ $code }}" {{ old('province', $customer->province) === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">City</label>
                <input type="text" name="city" value="{{ old('city', $customer->city) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
        <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>
    </form>
</div>
@endsection
