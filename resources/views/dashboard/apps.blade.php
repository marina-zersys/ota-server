@extends('layouts.dashboard')
@section('title', 'Apps')

@section('content')
<h2 class="text-2xl font-bold text-white mb-6">Apps</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($apps as $appName => $platformGroups)
    <a href="{{ route('dashboard.app-detail', $appName) }}"
       class="block backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6 hover:bg-white/20 hover:-translate-y-1 transition-all duration-200">
        <h3 class="text-lg font-semibold text-white mb-3">{{ $appName }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($platformGroups as $group)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $group->platform === 'ios' ? 'bg-blue-500/30 text-blue-200 border border-blue-400/30' : 'bg-green-500/30 text-green-200 border border-green-400/30' }}">
                    {{ $group->platform }} &middot; {{ $group->release_count }} {{ Str::plural('release', $group->release_count) }}
                </span>
            @endforeach
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">
        No apps found. Upload your first release via the API.
    </div>
    @endforelse
</div>
@endsection
