@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Manage your buyer / client list')

@section('header-actions')
<a href="{{ route('customers.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Customer
</a>
@endsection

@section('content')

<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">NTN / CNIC</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Province</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/60">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-800/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                                <span class="text-blue-400 text-xs font-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $customer->name }}</p>
                                @if($customer->address)<p class="text-xs text-gray-400 truncate max-w-xs">{{ $customer->address }}</p>@endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->ntn)
                            <span class="font-mono text-xs text-gray-300 bg-gray-800 px-2 py-1 rounded-lg">NTN: {{ $customer->ntn }}</span>
                        @elseif($customer->cnic)
                            <span class="font-mono text-xs text-gray-300 bg-gray-800 px-2 py-1 rounded-lg">CNIC: {{ $customer->cnic }}</span>
                        @else
                            <span class="text-gray-600 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->province)
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                            {{ $customer->province }}
                        </span>
                        @else
                        <span class="text-gray-600 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->phone)<p class="text-sm text-gray-300">📞 {{ $customer->phone }}</p>@endif
                        @if($customer->email)<p class="text-xs text-gray-400">{{ $customer->email }}</p>@endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg border border-gray-700 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $customer->name }}?')">
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
                    <td colspan="5" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-gray-500 text-sm">No customers yet</p>
                        <a href="{{ route('customers.create') }}" class="mt-2 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Add your first customer →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-800">{{ $customers->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
