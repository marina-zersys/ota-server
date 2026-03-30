@extends('layouts.dashboard')
@section('title', $appName)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('dashboard.apps') }}" class="text-sm text-gray-500 hover:underline">&larr; All Apps</a>
        <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $appName }}</h2>
    </div>
</div>

{{-- Filter bar --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Platform</label>
        <select name="platform" class="rounded-lg border-gray-300 text-sm px-3 py-2 bg-gray-50 border focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All</option>
            @foreach($platforms as $p)
                <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Environment</label>
        <select name="environment" class="rounded-lg border-gray-300 text-sm px-3 py-2 bg-gray-50 border focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All</option>
            @foreach($environments as $e)
                <option value="{{ $e }}" {{ request('environment') === $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">App Version</label>
        <select name="app_version" class="rounded-lg border-gray-300 text-sm px-3 py-2 bg-gray-50 border focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All</option>
            @foreach($appVersions as $v)
                <option value="{{ $v }}" {{ request('app_version') === $v ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">Filter</button>
    <a href="{{ route('dashboard.app-detail', $appName) }}" class="px-4 py-2 text-gray-600 text-sm font-medium hover:underline">Reset</a>
</form>

{{-- Releases table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Platform</th>
                    <th class="px-6 py-3 text-left">App Version</th>
                    <th class="px-6 py-3 text-left">Bundle Version</th>
                    <th class="px-6 py-3 text-left">Environment</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($releases as $release)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $release->platform === 'ios' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $release->platform }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $release->app_version }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $release->bundle_version }}</td>
                    <td class="px-6 py-3">
                        @php
                            $envColors = ['prod' => 'bg-red-100 text-red-800', 'staging' => 'bg-yellow-100 text-yellow-800', 'dev' => 'bg-gray-100 text-gray-800'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $envColors[$release->environment] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $release->environment }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $release->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-6 py-3 text-right space-x-3">
                        <a href="{{ route('dashboard.release-detail', $release) }}" class="text-indigo-600 hover:underline text-xs font-medium">View</a>
                        <form method="POST" action="{{ route('dashboard.release-destroy', $release) }}" class="inline" onsubmit="return confirm('Delete this release? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No releases match your filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($releases->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $releases->links() }}
    </div>
    @endif
</div>
@endsection
