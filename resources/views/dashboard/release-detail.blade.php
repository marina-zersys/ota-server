@extends('layouts.dashboard')
@section('title', 'Release #' . $release->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard.app-detail', $release->app_name) }}" class="text-sm text-gray-500 hover:underline">&larr; {{ $release->app_name }}</a>
    <h2 class="text-2xl font-bold text-gray-900 mt-1">Release #{{ $release->id }}</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-2xl">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Release Details</h3>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                {{ $release->platform === 'ios' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                {{ $release->platform }}
            </span>
            @php
                $envColors = ['prod' => 'bg-red-100 text-red-800', 'staging' => 'bg-yellow-100 text-yellow-800', 'dev' => 'bg-gray-100 text-gray-800'];
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $envColors[$release->environment] ?? 'bg-gray-100 text-gray-800' }}">
                {{ $release->environment }}
            </span>
        </div>
    </div>

    <dl class="divide-y divide-gray-100">
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">App Name</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->app_name }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Platform</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->platform }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">App Version</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->app_version }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Bundle Version</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->bundle_version }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Environment</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->environment }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Bundle File</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->bundle_file_name }}</dd>
        </div>
        <div class="px-6 py-3">
            <dt class="text-sm text-gray-500 mb-1">SHA-256 Hash</dt>
            <dd class="text-sm font-mono text-gray-900 bg-gray-50 px-3 py-2 rounded break-all">{{ $release->bundle_hash }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Bundle URL</dt>
            <dd class="text-sm">
                <a href="{{ $release->bundle_url }}" class="text-indigo-600 hover:underline font-medium" target="_blank">Download Bundle</a>
            </dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Created</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->created_at->format('M j, Y \a\t H:i:s') }}</dd>
        </div>
        <div class="px-6 py-3 flex justify-between">
            <dt class="text-sm text-gray-500">Updated</dt>
            <dd class="text-sm font-medium text-gray-900">{{ $release->updated_at->format('M j, Y \a\t H:i:s') }}</dd>
        </div>
    </dl>

    <div class="px-6 py-4 border-t border-gray-200">
        <form method="POST" action="{{ route('dashboard.release-destroy', $release) }}" onsubmit="return confirm('Delete this release? This will also remove the bundle file. This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                Delete Release
            </button>
        </form>
    </div>
</div>
@endsection
