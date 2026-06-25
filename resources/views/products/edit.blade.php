@extends('layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', $product->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-5"
          x-data="{ taxType: '{{ old('tax_type', $product->tax_type) }}' }">
    @csrf @method('PUT')

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-5">Product Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs text-gray-400 mb-1.5">Description</label>
                <textarea name="description" rows="2"
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 resize-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">HS Code</label>
                <input type="text" name="hs_code" value="{{ old('hs_code', $product->hs_code) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Unit</label>
                <select name="unit" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    @foreach(['Unit','Kg','Grams','Liter','Meter','Feet','Ton','Box','Carton','Dozen','Service','Hour','Month','Year'] as $u)
                    <option value="{{ $u }}" {{ old('unit', $product->unit) === $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Unit Price (Rs) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-400 text-sm">Rs</span>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Tax Type <span class="text-red-400">*</span></label>
                <select name="tax_type" x-model="taxType"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <option value="standard" {{ old('tax_type', $product->tax_type) === 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="zero_rated" {{ old('tax_type', $product->tax_type) === 'zero_rated' ? 'selected' : '' }}>Zero Rated</option>
                    <option value="exempt" {{ old('tax_type', $product->tax_type) === 'exempt' ? 'selected' : '' }}>Exempt</option>
                </select>
            </div>

            <div x-show="taxType === 'standard'" x-cloak>
                <label class="block text-xs text-gray-400 mb-1.5">GST Rate (%)</label>
                <div class="relative">
                    <input type="number" name="gst_rate" value="{{ old('gst_rate', $product->gst_rate) }}" min="0" max="100" step="0.01"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 pr-10 text-white text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20">
                    <span class="absolute right-4 top-3 text-gray-400 text-sm font-bold">%</span>
                </div>
            </div>
            <div x-show="taxType !== 'standard'" x-cloak>
                <input type="hidden" name="gst_rate" value="0">
                <div class="bg-gray-800/40 border border-gray-700/40 rounded-xl px-4 py-3">
                    <p class="text-xs text-gray-500">GST Rate: <span class="text-blue-400 font-semibold">0% (Zero Rated / Exempt)</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">Cancel</a>
    </div>
    </form>
</div>
@endsection
