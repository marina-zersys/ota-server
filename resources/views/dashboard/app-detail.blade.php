@extends('layouts.dashboard')
@section('title', $appName)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('dashboard.apps') }}" class="text-sm text-gray-400 hover:text-gray-300 transition">&larr; All Apps</a>
        <h2 class="text-2xl font-bold text-white mt-1">{{ $appName }}</h2>
    </div>
</div>

{{-- Filter bar --}}
<form method="GET" class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Platform</label>
        <select name="platform" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-lime-500 focus:border-lime-500 [&>option]:bg-slate-800 [&>option]:text-white">
            <option value="">All</option>
            @foreach($platforms as $p)
                <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Environment</label>
        <select name="environment" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-lime-500 focus:border-lime-500 [&>option]:bg-slate-800 [&>option]:text-white">
            <option value="">All</option>
            @foreach($environments as $e)
                <option value="{{ $e }}" {{ request('environment') === $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">App Version</label>
        <select name="app_version" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-lime-500 focus:border-lime-500 [&>option]:bg-slate-800 [&>option]:text-white">
            <option value="">All</option>
            @foreach($appVersions as $v)
                <option value="{{ $v }}" {{ request('app_version') === $v ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-white/20 border border-white/20 text-white text-sm font-medium rounded-lg hover:bg-white/30 transition">Filter</button>
    <a href="{{ route('dashboard.app-detail', $appName) }}" class="px-4 py-2 text-gray-400 text-sm font-medium hover:text-white transition">Reset</a>
</form>

{{-- Releases table --}}
<div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Platform</th>
                    <th class="px-4 py-3 text-left">App Version</th>
                    <th class="px-4 py-3 text-left">Bundle Version</th>
                    <th class="px-4 py-3 text-left">Environment</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Rollout</th>
                    <th class="px-4 py-3 text-left">Current</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($releases as $release)
                <tr class="hover:bg-white/5 transition" x-data="{ showRollout: false }">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $release->platform === 'ios' ? 'bg-blue-500/30 text-blue-200' : 'bg-green-500/30 text-green-200' }}">
                            {{ $release->platform }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-300">{{ $release->app_version }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $release->bundle_version }}</td>
                    <td class="px-4 py-3">
                        @php
                            $envColors = ['prod' => 'bg-red-500/30 text-red-200', 'staging' => 'bg-yellow-500/30 text-yellow-200', 'dev' => 'bg-gray-500/30 text-gray-300'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $envColors[$release->environment] ?? 'bg-gray-500/30 text-gray-300' }}">
                            {{ $release->environment }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('dashboard.release-toggle', $release) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium transition hover:opacity-80 {{ $release->is_enabled ? 'bg-emerald-500/30 text-emerald-200' : 'bg-gray-500/30 text-gray-400' }}" title="Click to toggle">
                                {{ $release->is_enabled ? 'Enabled' : 'Disabled' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative">
                            <button @click="showRollout = !showRollout" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-lime-500/20 text-lime-200 border border-lime-400/20 hover:bg-lime-500/30 transition cursor-pointer">
                                {{ $release->rollout_percentage }}%
                            </button>
                            <div x-show="showRollout" x-cloak @click.away="showRollout = false" class="absolute z-10 mt-2 left-0 backdrop-blur-2xl bg-slate-800/90 border border-white/20 rounded-xl shadow-2xl p-3 w-48">
                                <form action="{{ route('dashboard.release-rollout', $release) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="rollout_percentage" value="{{ $release->rollout_percentage }}" min="0" max="100" class="w-16 text-sm px-2 py-1 bg-white/10 border border-white/20 text-white rounded-lg focus:ring-lime-500 focus:border-lime-500">
                                    <span class="text-gray-400 text-xs">%</span>
                                    <button type="submit" class="px-2 py-1 bg-lime-600/80 text-white text-xs rounded-lg hover:bg-lime-600 transition">Save</button>
                                </form>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($release->is_current)
                            <span class="text-lime-400 text-lg" title="Current release">&#9733;</span>
                        @else
                            <form action="{{ route('dashboard.release-make-current', $release) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-lime-400 text-lg transition" title="Make current">&#9734;</button>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $release->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('dashboard.release-detail', $release) }}" class="text-lime-300 hover:text-lime-200 text-xs font-medium transition">View</a>
                            <button type="button"
                                    @click="$dispatch('open-promote', { releaseId: {{ $release->id }}, currentEnv: '{{ $release->environment }}' })"
                                    class="text-blue-300 hover:text-blue-200 text-xs font-medium transition">Promote</button>
                            <button type="button"
                                    @click="$dispatch('open-confirm', {
                                        title: 'Delete Release',
                                        message: 'Delete this release? This cannot be undone.',
                                        actionUrl: '{{ route('dashboard.release-destroy', $release) }}',
                                        method: 'DELETE'
                                    })"
                                    class="text-red-400 hover:text-red-300 text-xs font-medium transition">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">No releases match your filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($releases->hasPages())
    <div class="px-6 py-4 border-t border-white/10">
        {{ $releases->links() }}
    </div>
    @endif
</div>

@include('partials.promote-modal')
@endsection
