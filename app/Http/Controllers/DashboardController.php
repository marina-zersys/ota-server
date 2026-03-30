<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Models\ReleaseEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalApps = Release::distinct('app_name')->count('app_name');
        $totalReleases = Release::count();
        $platforms = Release::select('platform')
            ->selectRaw('count(*) as count')
            ->groupBy('platform')
            ->pluck('count', 'platform');
        $recentReleases = Release::latest()->limit(10)->get();

        $checksToday = ReleaseEvent::where('event_type', 'check')
            ->whereDate('created_at', today())
            ->count();
        $installsToday = ReleaseEvent::where('event_type', 'install')
            ->whereDate('created_at', today())
            ->count();

        return view('dashboard.index', compact(
            'totalApps', 'totalReleases', 'platforms', 'recentReleases',
            'checksToday', 'installsToday'
        ));
    }

    public function apps()
    {
        $apps = Release::select('app_name', 'platform')
            ->selectRaw('count(*) as release_count')
            ->groupBy('app_name', 'platform')
            ->orderBy('app_name')
            ->get()
            ->groupBy('app_name');

        return view('dashboard.apps', compact('apps'));
    }

    public function appDetail(Request $request, string $appName)
    {
        $query = Release::where('app_name', $appName);

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->filled('environment')) {
            $query->where('environment', $request->environment);
        }
        if ($request->filled('app_version')) {
            $query->where('app_version', $request->app_version);
        }

        $releases = $query->latest()->paginate(25)->withQueryString();

        $platforms = Release::where('app_name', $appName)->distinct()->pluck('platform');
        $environments = Release::where('app_name', $appName)->distinct()->pluck('environment');
        $appVersions = Release::where('app_name', $appName)->distinct()->pluck('app_version');

        return view('dashboard.app-detail', compact(
            'appName', 'releases', 'platforms', 'environments', 'appVersions'
        ));
    }

    public function releaseDetail(Release $release)
    {
        $release->load('promotedFrom');

        $eventCounts = $release->events()
            ->selectRaw('event_type, count(*) as count')
            ->groupBy('event_type')
            ->pluck('count', 'event_type');

        // Daily events for last 7 days
        $dailyEvents = ReleaseEvent::where('release_id', $release->id)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, event_type, count(*) as count')
            ->groupBy('date', 'event_type')
            ->orderBy('date')
            ->get();

        // Build chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[$date] = ['check' => 0, 'download' => 0, 'install' => 0];
        }
        foreach ($dailyEvents as $event) {
            if (isset($chartData[$event->date])) {
                $chartData[$event->date][$event->event_type] = $event->count;
            }
        }

        return view('dashboard.release-detail', compact('release', 'eventCounts', 'chartData'));
    }

    public function destroyRelease(Release $release)
    {
        $path = 'bundles/' . $release->platform . '/' . $release->app_version . '/' . $release->bundle_file_name;
        Storage::disk('public')->delete($path);

        $release->delete();

        return redirect('/dashboard')->with('success', 'Release deleted successfully.');
    }

    public function toggleEnabled(Release $release)
    {
        $release->update(['is_enabled' => !$release->is_enabled]);

        $status = $release->is_enabled ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Release #{$release->id} {$status}.");
    }

    public function updateRollout(Request $request, Release $release)
    {
        $request->validate([
            'rollout_percentage' => 'required|integer|min:0|max:100',
        ]);

        $release->update(['rollout_percentage' => $request->rollout_percentage]);

        return redirect()->back()->with('success', "Rollout updated to {$release->rollout_percentage}%.");
    }

    public function promote(Request $request, Release $release)
    {
        $request->validate([
            'environment' => 'required|string',
        ]);

        $targetEnv = $request->environment;

        if ($targetEnv === $release->environment) {
            return redirect()->back()->with('error', 'Cannot promote to the same environment.');
        }

        // Unset is_current on existing releases in target env
        Release::where('app_name', $release->app_name)
            ->where('platform', $release->platform)
            ->where('app_version', $release->app_version)
            ->where('environment', $targetEnv)
            ->where('is_current', true)
            ->update(['is_current' => false]);

        // Clone release to target environment
        $newRelease = $release->replicate(['id', 'created_at', 'updated_at']);
        $newRelease->environment = $targetEnv;
        $newRelease->promoted_from_id = $release->id;
        $newRelease->is_current = true;
        $newRelease->is_enabled = true;
        $newRelease->rollout_percentage = 100;
        $newRelease->save();

        return redirect()->back()->with('success', "Release promoted to {$targetEnv}.");
    }

    public function rollback(Release $release)
    {
        $previous = Release::where('app_name', $release->app_name)
            ->where('platform', $release->platform)
            ->where('app_version', $release->app_version)
            ->where('environment', $release->environment)
            ->where('id', '!=', $release->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$previous) {
            return redirect()->back()->with('error', 'No previous release to rollback to.');
        }

        $release->update(['is_current' => false]);
        $previous->update(['is_current' => true]);

        return redirect()->back()->with('success', "Rolled back to Release #{$previous->id}.");
    }

    public function makeCurrent(Release $release)
    {
        // Unset is_current for all releases in the same group
        Release::where('app_name', $release->app_name)
            ->where('platform', $release->platform)
            ->where('app_version', $release->app_version)
            ->where('environment', $release->environment)
            ->where('is_current', true)
            ->update(['is_current' => false]);

        $release->update(['is_current' => true]);

        return redirect()->back()->with('success', "Release #{$release->id} is now current.");
    }
}
