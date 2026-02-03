<?php
/**
 * Quick script to set a permissive CORS policy on the S3/MinIO bucket used in .env (AWS_BUCKET).
 * Usage: php scripts/set_bucket_cors.php
 * Requires AWS PHP SDK (already installed via composer) and environment variables set (AWS_* in .env).
 */

require __DIR__.'/../vendor/autoload.php';

// Load .env when run from CLI so getenv() works
if (file_exists(__DIR__.'/../.env')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();
    } catch (Throwable $e) {
        // fallback: parse .env manually if Dotenv is unavailable or getenv() is empty
        $env = file(__DIR__.'/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($env as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (!str_contains($line, '=')) continue;
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v);
            $v = preg_replace('/(^\"|\"$|^\'|\'$)/', '', $v);
            putenv("{$k}={$v}");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
    }
}

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Try to read both getenv and fallback parsed .env to ensure variables exist when running from CLI
$envVars = [];
if (file_exists(__DIR__.'/../.env')) {
    $lines = file(__DIR__.'/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v, " \t\n\r\0\x0B\"'");
        $envVars[$k] = $v;
    }
}

// CLI options (long): bucket:, endpoint:, region:, access-key:, secret:, path-style, origins:, methods:, dry-run, verbose, help
$options = getopt('', ['bucket:', 'endpoint:', 'region:', 'access-key:', 'secret:', 'path-style', 'origins:', 'methods:', 'dry-run', 'verbose', 'help']);

if (isset($options['help'])) {
    echo "Usage: php scripts/set_bucket_cors.php [--bucket=NAME] [--endpoint=URL] [--region=REGION] [--access-key=ID] [--secret=SECRET] [--path-style] [--origins=origin1,origin2] [--methods=GET,PUT,POST] [--dry-run] [--verbose]\n";
    exit(0);
}

$bucket = $options['bucket'] ?? getenv('AWS_BUCKET') ?: ($envVars['AWS_BUCKET'] ?? 'limitlessfitness');
$endpoint = $options['endpoint'] ?? getenv('AWS_ENDPOINT') ?: ($envVars['AWS_ENDPOINT'] ?? null);
$region = $options['region'] ?? getenv('AWS_DEFAULT_REGION') ?: ($envVars['AWS_DEFAULT_REGION'] ?? 'us-east-1');
$accessKey = $options['access-key'] ?? getenv('AWS_ACCESS_KEY_ID') ?: ($envVars['AWS_ACCESS_KEY_ID'] ?? null);
$secretKey = $options['secret'] ?? getenv('AWS_SECRET_ACCESS_KEY') ?: ($envVars['AWS_SECRET_ACCESS_KEY'] ?? null);
$usePathStyle = isset($options['path-style']);
$dryRun = isset($options['dry-run']);
$verbose = isset($options['verbose']);

$origins = ['http://localhost:8008', 'http://127.0.0.1:8008'];
if (!empty($options['origins'])) {
    $origins = array_map('trim', explode(',', $options['origins']));
}
$methods = ['GET', 'PUT', 'POST', 'HEAD', 'OPTIONS'];
if (!empty($options['methods'])) {
    $methods = array_map('trim', explode(',', strtoupper($options['methods'])));
}

if (!$accessKey || !$secretKey) {
    echo "AWS credentials not found in environment/.env or provided via --access-key/--secret.\n";
    exit(1);
}

$config = [
    'version' => 'latest',
    'region'  => $region,
    'credentials' => [
        'key'    => $accessKey,
        'secret' => $secretKey,
    ],
    // Use signature v4 by default (compatible with MinIO)
    'signature_version' => 'v4',
];

if ($endpoint) {
    $config['endpoint'] = $endpoint;
    if ($usePathStyle) {
        $config['use_path_style_endpoint'] = true;
    }
}

// Allow skipping TLS verification for non-prod self-signed endpoints (only if verbose/insecure needed)
if (isset($options['insecure'])) {
    $config['http'] = ['verify' => false];
}

if ($verbose) {
    echo "Using configuration:\n";
    echo "  Bucket: {$bucket}\n";
    echo "  Endpoint: " . ($endpoint ?: '(default AWS)') . "\n";
    echo "  Region: {$region}\n";
    echo "  Use path style endpoint: " . ($usePathStyle ? 'yes' : 'no') . "\n";
    echo "  Origins: " . implode(', ', $origins) . "\n";
    echo "  Methods: " . implode(', ', $methods) . "\n";
    if ($dryRun) echo "  DRY RUN: no changes will be applied\n";
}

// Enable SDK debug output when verbose: provide logfn callable and enable http debug
if ($verbose) {
    $config['debug'] = [
        'logfn' => function ($message) {
            // write to stderr to separate from normal output
            file_put_contents('php://stderr', $message);
        },
        'http' => true,
        'scrub_auth' => false, // show auth for debugging - be careful
    ];
}

$s3 = new S3Client($config);

$rule = [
    'AllowedOrigins' => $origins,
    'AllowedMethods' => $methods,
    'AllowedHeaders' => ['*'],
    'ExposeHeaders'  => ['ETag', 'x-amz-request-id', 'x-amz-id-2', 'x-amz-meta-*'],
    'MaxAgeSeconds'  => 300,
];

$corsConfig = ['CORSRules' => [$rule]];

if ($dryRun) {
    echo "Dry run - CORS configuration to be applied to bucket '{$bucket}':\n" . json_encode($corsConfig, JSON_PRETTY_PRINT) . "\n";
    exit(0);
}

try {
    $s3->putBucketCors([
        'Bucket' => $bucket,
        'CORSConfiguration' => $corsConfig,
    ]);
    echo "CORS rules successfully applied to bucket '{$bucket}'\n";
} catch (AwsException $e) {
    // Improve error output
    echo "Failed to apply CORS: " . $e->getMessage() . "\n";
    // Try to print response body if available
    try {
        $resp = $e->getResponse();
        if ($resp) {
            $body = (string) $resp->getBody();
            echo "Response body:\n" . $body . "\n";
        }
    } catch (Throwable $ex) {
        if ($verbose) echo "(Unable to retrieve response body: " . $ex->getMessage() . ")\n";
    }
    exit(1);
} catch (Throwable $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
    if ($verbose) echo $e->getTraceAsString() . "\n";
    exit(1);
}

?>