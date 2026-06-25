@extends('layouts.app')
@section('title', 'Manage Clients')
@section('page-title', 'Client Accounts')
@section('page-subtitle', 'Super Admin — manage all registered businesses')

@section('header-actions')
<a href="{{ route('admin.clients.create') }}"
   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Client
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($clients as $client)
    <div class="bg-gray-900 border border-gray-800 hover:border-emerald-500/30 rounded-2xl p-5 transition-all duration-200 group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                @if($client->logo)
                    <img src="{{ asset('storage/' . $client->logo) }}" class="w-10 h-10 rounded-xl object-cover" alt="">
                @else
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                        <span class="text-emerald-400 font-bold text-sm">{{ strtoupper(substr($client->business_name, 0, 2)) }}</span>
                    </div>
                @endif
                <div>
                    <h3 class="font-semibold text-white text-sm">{{ $client->business_name }}</h3>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                        {{ $client->province }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-1">
                @if($client->is_active)
                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                @else
                    <span class="w-2 h-2 bg-gray-600 rounded-full"></span>
                @endif
            </div>
        </div>

        <div class="space-y-1.5 mb-4">
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">NTN</span>
                <span class="text-gray-300 font-mono">{{ $client->ntn ?? '—' }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">STRN</span>
                <span class="text-gray-300 font-mono">{{ $client->strn ?? '—' }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">GST Rate</span>
                <span class="text-amber-400 font-semibold">{{ $client->default_gst_rate }}%</span>
            </div>
        </div>

        <div class="flex items-center justify-between border-t border-gray-800 pt-4">
            <div class="flex gap-4 text-center">
                <div>
                    <p class="text-lg font-bold text-white">{{ $client->invoices_count }}</p>
                    <p class="text-xs text-gray-500">Invoices</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-white">{{ $client->customers_count }}</p>
                    <p class="text-xs text-gray-500">Customers</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}"
                   class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg border border-gray-700 transition-colors">
                    Edit
                </a>
                <a href="{{ route('admin.clients.show', $client) }}"
                   class="text-xs bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-lg border border-emerald-500/20 transition-colors">
                    View
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center">
        <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        <p class="text-gray-400 font-medium">No clients registered yet</p>
        <a href="{{ route('admin.clients.create') }}" class="mt-3 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Add your first client →</a>
    </div>
    @endforelse
</div>

@if($clients->hasPages())
<div class="mt-6">{{ $clients->links() }}</div>
@endif
@endsection
