<?php
require __DIR__ . '/../vendor/autoload.php';
use Aws\S3\S3Client;

// Load .env if not present in CLI environment
if (!getenv('AWS_ACCESS_KEY_ID') || !getenv('AWS_BUCKET')) {
    if (file_exists(__DIR__ . '/../.env')) {
        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => null]);
            if ($k && !getenv($k)) putenv(sprintf('%s=%s', $k, trim($v, "\"'")));
        }
    }
}

$cfg = [
    'version' => 'latest',
    'region' => getenv('AWS_DEFAULT_REGION') ?: 'us-east-1',
    'endpoint' => getenv('AWS_ENDPOINT') ?: null,
    'use_path_style_endpoint' => filter_var(getenv('AWS_USE_PATH_STYLE_ENDPOINT', false), FILTER_VALIDATE_BOOLEAN),
    'credentials' => [
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ],
];

echo "Using config:\n" . var_export($cfg, true) . "\n";
$bucket = getenv('AWS_BUCKET');
if (!$bucket) {
    echo "No AWS_BUCKET env var set\n";
    exit(1);
}

try {
    $s3 = new S3Client($cfg);
    echo "PutObject...\n";
    $res = $s3->putObject(['Bucket' => $bucket, 'Key' => 'integration_check_cli.txt', 'Body' => 'ok']);
    echo "PutObject succeeded, ETag: " . ($res['ETag'] ?? '(no etag)') . "\n";

    echo "HeadObject...\n";
    $s3->headObject(['Bucket' => $bucket, 'Key' => 'integration_check_cli.txt']);
    echo "HeadObject success\n";

    echo "DeleteObject...\n";
    $s3->deleteObject(['Bucket' => $bucket, 'Key' => 'integration_check_cli.txt']);
    echo "Deleted\n";
} catch (\Exception $e) {
    echo 'EXCEPTION: ' . get_class($e) . ': ' . $e->getMessage() . "\n";
    if (method_exists($e, 'getAwsRequestId')) {
        echo 'AWSRequestId: ' . $e->getAwsRequestId() . "\n";
    }
    exit(2);
}

echo "S3 check OK\n";
