@extends('layouts.dashboard')
@section('title', 'Release #' . $release->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard.app-detail', $release->app_name) }}" class="text-sm text-gray-400 hover:text-gray-300 transition">&larr; {{ $release->app_name }}</a>
    <h2 class="text-2xl font-bold text-white mt-1">Release #{{ $release->id }}</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Release details --}}
    <div class="lg:col-span-2 backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl">
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Release Details</h3>
            <div class="flex items-center gap-2">
                @if($release->is_current)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-lime-500/30 text-lime-200 border border-lime-400/30">
                        &#9733; Current
                    </span>
                @endif
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
            <div class="px-6 py-3 flex justify-between items-center">
                <dt class="text-sm text-gray-400">Status</dt>
                <dd>
                    <form action="{{ route('dashboard.release-toggle', $release) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition hover:opacity-80 {{ $release->is_enabled ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-gray-500/30 text-gray-400 border border-gray-500/30' }}">
                            {{ $release->is_enabled ? 'Enabled — Click to Disable' : 'Disabled — Click to Enable' }}
                        </button>
                    </form>
                </dd>
            </div>
            <div class="px-6 py-3 flex justify-between items-center" x-data="{ editing: false }">
                <dt class="text-sm text-gray-400">Rollout Percentage</dt>
                <dd>
                    <div x-show="!editing">
                        <button @click="editing = true" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-lime-500/20 text-lime-200 border border-lime-400/20 hover:bg-lime-500/30 transition cursor-pointer">
                            {{ $release->rollout_percentage }}% — Click to edit
                        </button>
                    </div>
                    <form x-show="editing" x-cloak action="{{ route('dashboard.release-rollout', $release) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="range" name="rollout_percentage" value="{{ $release->rollout_percentage }}" min="0" max="100" step="1"
                               class="w-32 accent-lime-500"
                               x-data x-on:input="$el.nextElementSibling.textContent = $el.value + '%'">
                        <span class="text-sm text-lime-200 w-10">{{ $release->rollout_percentage }}%</span>
                        <button type="submit" class="px-2 py-1 bg-lime-600/80 text-white text-xs rounded-lg hover:bg-lime-600 transition">Save</button>
                        <button type="button" @click="editing = false" class="px-2 py-1 text-gray-400 text-xs hover:text-white transition">Cancel</button>
                    </form>
                </dd>
            </div>
            @if($release->promotedFrom)
            <div class="px-6 py-3 flex justify-between">
                <dt class="text-sm text-gray-400">Promoted From</dt>
                <dd class="text-sm">
                    <a href="{{ route('dashboard.release-detail', $release->promotedFrom) }}" class="text-lime-300 hover:text-lime-200 font-medium transition">
                        Release #{{ $release->promoted_from_id }} ({{ $release->promotedFrom->environment }})
                    </a>
                </dd>
            </div>
            @endif
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
                    <a href="{{ $release->bundle_url }}" class="text-lime-300 hover:text-lime-200 font-medium transition" target="_blank">Download Bundle</a>
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

        {{-- Action buttons --}}
        <div class="px-6 py-4 border-t border-white/10 flex flex-wrap gap-3">
            @if(!$release->is_current)
            <form action="{{ route('dashboard.release-make-current', $release) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-lime-600/80 border border-lime-500/30 text-white text-sm font-medium rounded-lg hover:bg-lime-600 transition">
                    Make Current
                </button>
            </form>
            @endif

            <button type="button"
                    @click="$dispatch('open-promote', { releaseId: {{ $release->id }}, currentEnv: '{{ $release->environment }}' })"
                    class="px-4 py-2 bg-blue-600/80 border border-blue-500/30 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition">
                Promote
            </button>

            @if($release->is_current)
            <form action="{{ route('dashboard.release-rollback', $release) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-yellow-600/80 border border-yellow-500/30 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition">
                    Rollback
                </button>
            </form>
            @endif

        </div>
    </div>

    {{-- Metrics sidebar --}}
    <div class="space-y-6">
        {{-- Event counts --}}
        <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Metrics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Checks</span>
                    <span class="text-2xl font-bold text-lime-300">{{ $eventCounts['check'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Downloads</span>
                    <span class="text-2xl font-bold text-blue-300">{{ $eventCounts['download'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Installs</span>
                    <span class="text-2xl font-bold text-emerald-300">{{ $eventCounts['install'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Daily chart (last 7 days) --}}
        <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Last 7 Days</h3>
            @php
                $maxVal = 1;
                foreach ($chartData as $day) {
                    $dayMax = max($day['check'], $day['download'], $day['install']);
                    if ($dayMax > $maxVal) $maxVal = $dayMax;
                }
            @endphp
            <div class="space-y-3">
                @foreach($chartData as $date => $counts)
                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span>{{ \Carbon\Carbon::parse($date)->format('M j') }}</span>
                        <span>{{ $counts['check'] + $counts['download'] + $counts['install'] }}</span>
                    </div>
                    <div class="flex gap-1 h-4">
                        @if($counts['check'] > 0)
                        <div class="bg-lime-500/60 rounded-sm" style="width: {{ ($counts['check'] / $maxVal) * 100 }}%" title="Checks: {{ $counts['check'] }}"></div>
                        @endif
                        @if($counts['download'] > 0)
                        <div class="bg-blue-500/60 rounded-sm" style="width: {{ ($counts['download'] / $maxVal) * 100 }}%" title="Downloads: {{ $counts['download'] }}"></div>
                        @endif
                        @if($counts['install'] > 0)
                        <div class="bg-emerald-500/60 rounded-sm" style="width: {{ ($counts['install'] / $maxVal) * 100 }}%" title="Installs: {{ $counts['install'] }}"></div>
                        @endif
                        @if($counts['check'] + $counts['download'] + $counts['install'] === 0)
                        <div class="bg-white/5 rounded-sm w-full"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex gap-4 mt-4 text-xs text-gray-400">
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-lime-500/60 rounded-sm inline-block"></span> Checks</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-500/60 rounded-sm inline-block"></span> Downloads</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-emerald-500/60 rounded-sm inline-block"></span> Installs</span>
            </div>
        </div>
    </div>
</div>

@include('partials.promote-modal')
@endsection
