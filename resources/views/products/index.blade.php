@extends('layouts.app')
@section('title', 'Products & Services')
@section('page-title', 'Products & Services')
@section('page-subtitle', 'Manage items you sell and their GST rates')

@section('header-actions')
<a href="{{ route('products.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Product
</a>
@endsection

@section('content')
<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Product / Service</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">HS Code</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">GST%</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tax Type</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/60">
                @forelse($products as $product)
                <tr class="hover:bg-gray-800/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $product->name }}</p>
                                @if($product->description)
                                <p class="text-xs text-gray-500 truncate max-w-xs">{{ $product->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-400 text-xs">{{ $product->hs_code ?? '—' }}</td>
                    <td class="px-6 py-4 text-right font-mono font-semibold text-white">
                        Rs {{ number_format($product->price, 2) }}
                        <span class="text-gray-500 text-xs font-normal">/ {{ $product->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($product->tax_type === 'standard')
                        <span class="font-mono text-amber-400 font-bold">{{ $product->gst_rate }}%</span>
                        @else
                        <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($product->tax_type === 'standard')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Standard</span>
                        @elseif($product->tax_type === 'zero_rated')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Zero Rated</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-500/10 text-gray-400 border border-gray-500/20">Exempt</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('products.edit', $product) }}"
                               class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg border border-gray-700 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $product->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-1.5 rounded-lg border border-red-500/20 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-gray-500 text-sm">No products yet</p>
                        <a href="{{ route('products.create') }}" class="mt-2 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Add your first product →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">{{ $products->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
