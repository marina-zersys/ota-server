@extends('layouts.dashboard')
@section('title', 'Overview')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>

{{-- Stat cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm font-medium text-gray-500">Total Apps</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalApps }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm font-medium text-gray-500">Total Releases</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalReleases }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm font-medium text-gray-500">Platforms</p>
        <div class="mt-2 flex flex-wrap gap-2">
            @forelse($platforms as $platform => $count)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $platform === 'ios' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $platform }} &middot; {{ $count }}
                </span>
            @empty
                <span class="text-gray-400 text-sm">No releases yet</span>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent releases --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Releases</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">App</th>
                    <th class="px-6 py-3 text-left">Platform</th>
                    <th class="px-6 py-3 text-left">App Version</th>
                    <th class="px-6 py-3 text-left">Bundle Version</th>
                    <th class="px-6 py-3 text-left">Environment</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentReleases as $release)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-900">
                        <a href="{{ route('dashboard.app-detail', $release->app_name) }}" class="hover:underline">{{ $release->app_name }}</a>
                    </td>
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
                    <td class="px-6 py-3">
                        <a href="{{ route('dashboard.release-detail', $release) }}" class="text-indigo-600 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">No releases yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
