@extends('layouts.dashboard')
@section('title', 'Apps')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Apps</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($apps as $appName => $platformGroups)
    <a href="{{ route('dashboard.app-detail', $appName) }}"
       class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $appName }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($platformGroups as $group)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $group->platform === 'ios' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $group->platform }} &middot; {{ $group->release_count }} {{ Str::plural('release', $group->release_count) }}
                </span>
            @endforeach
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-12 text-gray-400">
        No apps found. Upload your first release via the API.
    </div>
    @endforelse
</div>
@endsection
