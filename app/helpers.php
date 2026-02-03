<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('storage_url')) {
    /**
     * Return a publicly accessible URL for a storage path, using the default disk.
     * Falls back to asset('storage/...') when using local disk.
     *
     * @param  string|null  $path
     * @return string|null
     */
    function storage_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $disk = config('filesystems.default');

        // Non-s3 disks use local storage asset path
        if ($disk !== 's3') {
            return asset('storage/' . $path);
        }

        // For S3 disks, prefer constructing endpoint/bucket/path when available so
        // previews include the bucket name (works for path-style endpoints).
        $diskConfig = config('filesystems.disks.s3', []);
        $bucket = $diskConfig['bucket'] ?? env('AWS_BUCKET') ?? null;

        // Attempt to use the adapter-provided URL first
        try {
            $adapterUrl = Storage::disk('s3')->url($path);
        } catch (\Throwable $e) {
            $adapterUrl = null;
        }

        // If the adapter URL already contains the bucket, return it unchanged
        if ($bucket && $adapterUrl && strpos($adapterUrl, '/'.trim($bucket, '/').'/') !== false) {
            return $adapterUrl;
        }

        // Use endpoint / url from config or env
        $endpoint = $diskConfig['endpoint'] ?? $diskConfig['url'] ?? env('AWS_URL') ?? null;

        if ($endpoint && $bucket) {
            return rtrim($endpoint, '/') . '/' . trim($bucket, '/') . '/' . ltrim($path, '/');
        }

        // Fallback to whatever the adapter returns (may or may not include the bucket)
        if ($adapterUrl) {
            return $adapterUrl;
        }

        // Last resort: build using bucket if present
        if ($endpoint && $bucket) {
            return rtrim($endpoint, '/') . '/' . trim($bucket, '/') . '/' . ltrim($path, '/');
        }

        return Storage::disk('s3')->url($path);
    }
}
