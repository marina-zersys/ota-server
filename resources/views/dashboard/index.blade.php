@extends('layouts.dashboard')
@section('title', 'Overview')

@section('content')
<h2 class="text-2xl font-bold text-white mb-6">Dashboard</h2>

{{-- Stat cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
        <p class="text-sm font-medium text-gray-400">Total Apps</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $totalApps }}</p>
    </div>
    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
        <p class="text-sm font-medium text-gray-400">Total Releases</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $totalReleases }}</p>
    </div>
    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
        <p class="text-sm font-medium text-gray-400">Platforms</p>
        <div class="mt-2 flex flex-wrap gap-2">
            @forelse($platforms as $platform => $count)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $platform === 'ios' ? 'bg-blue-500/30 text-blue-200 border border-blue-400/30' : 'bg-green-500/30 text-green-200 border border-green-400/30' }}">
                    {{ $platform }} &middot; {{ $count }}
                </span>
            @empty
                <span class="text-gray-500 text-sm">No releases yet</span>
            @endforelse
        </div>
    </div>
    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
        <p class="text-sm font-medium text-gray-400">Checks Today</p>
        <p class="text-3xl font-bold text-lime-300 mt-1">{{ $checksToday }}</p>
    </div>
    <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
        <p class="text-sm font-medium text-gray-400">Installs Today</p>
        <p class="text-3xl font-bold text-lime-300 mt-1">{{ $installsToday }}</p>
    </div>
</div>

{{-- Recent releases --}}
<div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl">
    <div class="px-6 py-4 border-b border-white/10">
        <h3 class="text-lg font-semibold text-white">Recent Releases</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">App</th>
                    <th class="px-6 py-3 text-left">Platform</th>
                    <th class="px-6 py-3 text-left">App Version</th>
                    <th class="px-6 py-3 text-left">Bundle Version</th>
                    <th class="px-6 py-3 text-left">Environment</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($recentReleases as $release)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-3 font-medium text-white">
                        <a href="{{ route('dashboard.app-detail', $release->app_name) }}" class="hover:text-lime-300 transition">{{ $release->app_name }}</a>
                    </td>
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
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            @if($release->is_current)
                                <span class="text-lime-400" title="Current">&#9733;</span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $release->is_enabled ? 'bg-emerald-500/30 text-emerald-200' : 'bg-gray-500/30 text-gray-400' }}">
                                {{ $release->is_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $release->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('dashboard.release-detail', $release) }}" class="text-lime-300 hover:text-lime-200 text-xs font-medium transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No releases yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
