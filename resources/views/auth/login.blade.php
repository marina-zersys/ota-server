@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="text-lg font-semibold text-white mb-6">Sign in to your account</h2>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition">
            @error('email')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition">
            @error('password')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded bg-white/5 border-white/10 text-emerald-500 focus:ring-emerald-500/50">
                <span class="ml-2 text-sm text-gray-400">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm text-emerald-400 hover:text-emerald-300 transition">
                Forgot password?
            </a>
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium transition focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
            Sign in
        </button>
    </form>
@endsection
