<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — GST Invoice Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-950 text-white antialiased min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-md">

    {{-- Logo Card --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-emerald-500 rounded-2xl shadow-xl shadow-emerald-500/30 mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">GST Invoice Portal</h1>
        <p class="text-sm text-gray-400 mt-1">FBR · SRB · PRA · KPRA · BRA Compliant</p>
    </div>

    {{-- Login Card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-semibold text-white mb-6">Sign in to your account</h2>

        {{-- Session Status --}}
        @if(session('status'))
        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-sm text-emerald-400">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="admin@yourportal.com"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-colors
                              @error('email') border-red-500 @enderror">
                @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="text-xs font-medium text-gray-400">Password</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors">
                        Forgot password?
                    </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-colors
                              @error('password') border-red-500 @enderror">
                @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-emerald-500 focus:ring-emerald-500/30">
                <label for="remember_me" class="text-sm text-gray-400 cursor-pointer">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl text-sm transition-all duration-150 shadow-lg shadow-emerald-500/20">
                Sign In
            </button>

        </form>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-600 mt-6">
        GST Invoice Portal v1.0 · All rights reserved
    </p>
</div>

</body>
</html>
