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
        <select name="platform" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-purple-500 focus:border-purple-500 [&>option]:bg-slate-800 [&>option]:text-white">
            <option value="">All</option>
            @foreach($platforms as $p)
                <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Environment</label>
        <select name="environment" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-purple-500 focus:border-purple-500 [&>option]:bg-slate-800 [&>option]:text-white">
            <option value="">All</option>
            @foreach($environments as $e)
                <option value="{{ $e }}" {{ request('environment') === $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">App Version</label>
        <select name="app_version" class="rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-purple-500 focus:border-purple-500 [&>option]:bg-slate-800 [&>option]:text-white">
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
                    <th class="px-6 py-3 text-left">Platform</th>
                    <th class="px-6 py-3 text-left">App Version</th>
                    <th class="px-6 py-3 text-left">Bundle Version</th>
                    <th class="px-6 py-3 text-left">Environment</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($releases as $release)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $release->platform === 'ios' ? 'bg-blue-500/30 text-blue-200' : 'bg-green-500/30 text-green-200' }}">
                            {{ $release->platform }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-300">{{ $release->app_version }}</td>
                    <td class="px-6 py-3 text-gray-300">{{ $release->bundle_version }}</td>
                    <td class="px-6 py-3">
                        @php
                            $envColors = ['prod' => 'bg-red-500/30 text-red-200', 'staging' => 'bg-yellow-500/30 text-yellow-200', 'dev' => 'bg-gray-500/30 text-gray-300'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $envColors[$release->environment] ?? 'bg-gray-500/30 text-gray-300' }}">
                            {{ $release->environment }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $release->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-6 py-3 text-right space-x-3">
                        <a href="{{ route('dashboard.release-detail', $release) }}" class="text-purple-300 hover:text-purple-200 text-xs font-medium transition">View</a>
                        <button type="button"
                                @click="$dispatch('open-confirm', {
                                    title: 'Delete Release',
                                    message: 'Delete this release? This cannot be undone.',
                                    actionUrl: '{{ route('dashboard.release-destroy', $release) }}',
                                    method: 'DELETE'
                                })"
                                class="text-red-400 hover:text-red-300 text-xs font-medium transition">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No releases match your filters.</td>
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
@endsection
