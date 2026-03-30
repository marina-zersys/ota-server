@extends('layouts.dashboard')
@section('title', 'Release #' . $release->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard.app-detail', $release->app_name) }}" class="text-sm text-gray-400 hover:text-gray-300 transition">&larr; {{ $release->app_name }}</a>
    <h2 class="text-2xl font-bold text-white mt-1">Release #{{ $release->id }}</h2>
</div>

<div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl max-w-2xl">
    <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-white">Release Details</h3>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                {{ $release->platform === 'ios' ? 'bg-blue-500/30 text-blue-200' : 'bg-green-500/30 text-green-200' }}">
                {{ $release->platform }}
            </span>
            @php
                $envColors = ['prod' => 'bg-red-500/30 text-red-200', 'staging' => 'bg-yellow-500/30 text-yellow-200', 'dev' => 'bg-gray-500/30 text-gray-300'];
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $envColors[$release->environment] ?? 'bg-gray-500/30 text-gray-300' }}">
                {{ $release->environment }}
            </span>
        </div>
    </div>

    <dl class="divide-y divide-white/5">
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">App Name</dt>
            <dd class="text-sm font-medium text-white">{{ $release->app_name }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Platform</dt>
            <dd class="text-sm font-medium text-white">{{ $release->platform }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">App Version</dt>
            <dd class="text-sm font-medium text-white">{{ $release->app_version }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Bundle Version</dt>
            <dd class="text-sm font-medium text-white">{{ $release->bundle_version }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Environment</dt>
            <dd class="text-sm font-medium text-white">{{ $release->environment }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Bundle File</dt>
            <dd class="text-sm font-medium text-white">{{ $release->bundle_file_name }}</dd>
        </div>
        <div class="px-6 py-3">
            <dt class="text-sm text-gray-400 mb-1">SHA-256 Hash</dt>
            <dd class="text-sm font-mono text-gray-300 bg-black/20 border border-white/10 px-3 py-2 rounded-lg break-all">{{ $release->bundle_hash }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Bundle URL</dt>
            <dd class="text-sm">
                <a href="{{ $release->bundle_url }}" class="text-purple-300 hover:text-purple-200 font-medium transition" target="_blank">Download Bundle</a>
            </dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Created</dt>
            <dd class="text-sm font-medium text-white">{{ $release->created_at->format('M j, Y \a\t H:i:s') }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-400">Updated</dt>
            <dd class="text-sm font-medium text-white">{{ $release->updated_at->format('M j, Y \a\t H:i:s') }}</dd>
        </div>
    </dl>

    <div class="px-6 py-4 border-t border-white/10">
        <button type="button"
                @click="$dispatch('open-confirm', {
                    title: 'Delete Release',
                    message: 'Delete this release? This will also remove the bundle file. This cannot be undone.',
                    actionUrl: '{{ route('dashboard.release-destroy', $release) }}',
                    method: 'DELETE'
                })"
                class="px-4 py-2 bg-red-600/80 border border-red-500/30 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition">
            Delete Release
        </button>
    </div>
</div>
@endsection
