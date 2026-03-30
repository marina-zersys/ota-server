<?php

namespace App\Http\Controllers;

use App\Models\Release;
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

        return view('dashboard.index', compact(
            'totalApps', 'totalReleases', 'platforms', 'recentReleases'
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
        return view('dashboard.release-detail', compact('release'));
    }

    public function destroyRelease(Release $release)
    {
        $path = 'bundles/' . $release->platform . '/' . $release->app_version . '/' . $release->bundle_file_name;
        Storage::disk('public')->delete($path);

        $release->delete();

        return redirect('/dashboard')->with('success', 'Release deleted successfully.');
    }
}
