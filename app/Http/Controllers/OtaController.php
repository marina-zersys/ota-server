<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Models\ReleaseEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\UniqueConstraintViolationException;

class OtaController extends Controller
{
    /**
     * POST /api/upload
     *
     * Receives a raw zip file and stores it on disk.
     * Query params: platform, appVersion, bundleFileName
     * Returns: { bundleUrl }
     */
    public function upload(Request $request)
    {
        $platform = $request->query('platform');
        $appVersion = $request->query('appVersion');
        $bundleFileName = $request->query('bundleFileName');

        if (!$platform || !$appVersion || !$bundleFileName) {
            return response()->json([
                'error' => 'Missing required query parameters: platform, appVersion, bundleFileName',
            ], 400);
        }

        $path = "bundles/{$platform}/{$appVersion}/{$bundleFileName}";
        $contents = $request->getContent();

        Storage::disk('public')->put($path, $contents);

        $bundleUrl = url("storage/{$path}");

        return response()->json(['bundleUrl' => $bundleUrl]);
    }

    /**
     * POST /api/releases
     *
     * Registers a new OTA release in the database.
     * Body JSON: appName, platform, appVersion, bundleVersion, bundleUrl,
     *            bundleHash, bundleFileName, environment (optional)
     * Returns: { id, createdAt }
     */
    public function createRelease(Request $request)
    {
        $required = ['appName', 'platform', 'appVersion', 'bundleVersion', 'bundleUrl', 'bundleHash', 'bundleFileName'];
        $missing = [];

        foreach ($required as $field) {
            if (!$request->input($field)) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            return response()->json([
                'error' => 'Missing required fields: ' . implode(', ', $missing),
            ], 400);
        }

        $environment = $request->input('environment', 'prod');

        // Unset is_current on existing releases for the same tuple
        Release::where('app_name', $request->input('appName'))
            ->where('platform', $request->input('platform'))
            ->where('app_version', $request->input('appVersion'))
            ->where('environment', $environment)
            ->where('is_current', true)
            ->update(['is_current' => false]);

        try {
            $release = Release::create([
                'app_name' => $request->input('appName'),
                'platform' => $request->input('platform'),
                'app_version' => $request->input('appVersion'),
                'bundle_version' => $request->input('bundleVersion'),
                'bundle_url' => $request->input('bundleUrl'),
                'bundle_hash' => $request->input('bundleHash'),
                'bundle_file_name' => $request->input('bundleFileName'),
                'environment' => $environment,
                'is_enabled' => true,
                'rollout_percentage' => 100,
                'is_current' => true,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'error' => 'A release with this appName, platform, appVersion, bundleVersion, and environment already exists.',
            ], 409);
        }

        return response()->json([
            'id' => $release->id,
            'createdAt' => $release->created_at->toIso8601String(),
        ], 201);
    }

    /**
     * GET /api/check-update
     *
     * Checks if a newer OTA bundle is available.
     * Query params: appName, platform, appVersion, env (optional), deviceId (optional)
     * Returns: { updateAvailable, ... }
     */
    public function checkUpdate(Request $request)
    {
        $appName = $request->query('appName');
        $platform = $request->query('platform');
        $appVersion = $request->query('appVersion');
        $env = $request->query('env', 'prod');
        $deviceId = $request->query('deviceId');

        if (!$appName || !$platform || !$appVersion) {
            return response()->json([
                'error' => 'Missing required query parameters: appName, platform, appVersion',
            ], 400);
        }

        $release = Release::where('app_name', $appName)
            ->where('platform', $platform)
            ->where('app_version', $appVersion)
            ->where('environment', $env)
            ->where('is_enabled', true)
            ->where('is_current', true)
            ->first();

        if (!$release) {
            return response()->json(['updateAvailable' => false]);
        }

        // Log check event
        ReleaseEvent::create([
            'release_id' => $release->id,
            'event_type' => 'check',
            'device_id' => $deviceId,
            'ip_address' => $request->ip(),
        ]);

        // Apply rollout percentage
        if ($release->rollout_percentage < 100) {
            if ($deviceId) {
                $hash = crc32($deviceId . $release->id);
                $bucket = abs($hash) % 100;
            } else {
                $bucket = random_int(0, 99);
            }

            if ($bucket >= $release->rollout_percentage) {
                return response()->json(['updateAvailable' => false]);
            }
        }

        return response()->json([
            'updateAvailable' => true,
            'bundleVersion' => $release->bundle_version,
            'bundleUrl' => $release->bundle_url,
            'hash' => $release->bundle_hash,
            'bundleFileName' => $release->bundle_file_name,
            'platform' => $release->platform,
            'appVersion' => $release->app_version,
            'createdAt' => $release->created_at->toIso8601String(),
        ]);
    }

    /**
     * POST /api/events
     *
     * Logs a client event (download, install).
     * Body JSON: releaseId, eventType, deviceId (optional)
     */
    public function logEvent(Request $request)
    {
        $releaseId = $request->input('releaseId');
        $eventType = $request->input('eventType');

        if (!$releaseId || !$eventType) {
            return response()->json([
                'error' => 'Missing required fields: releaseId, eventType',
            ], 400);
        }

        if (!in_array($eventType, ['download', 'install'])) {
            return response()->json([
                'error' => 'eventType must be one of: download, install',
            ], 400);
        }

        $release = Release::find($releaseId);
        if (!$release) {
            return response()->json(['error' => 'Release not found.'], 404);
        }

        ReleaseEvent::create([
            'release_id' => $releaseId,
            'event_type' => $eventType,
            'device_id' => $request->input('deviceId'),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['success' => true], 201);
    }
}
