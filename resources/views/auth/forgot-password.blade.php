@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h2 class="text-lg font-semibold text-white mb-2">Forgot your password?</h2>
    <p class="text-sm text-gray-400 mb-6">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition">
            @error('email')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium transition focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
            Send reset link
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">
                Back to login
            </a>
        </div>
    </form>
@endsection
