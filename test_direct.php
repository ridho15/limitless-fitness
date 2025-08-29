<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing API Methods...\n\n";

// Test getAllUserSelfies
$controller = new App\Http\Controllers\HelperController();

echo "=== Test 1: getAllUserSelfies ===\n";
try {
    $response = $controller->getAllUserSelfies();
    $data = $response->getData();
    echo "Status: " . ($data->status ? "Success" : "Failed") . "\n";
    echo "User Count: " . count($data->data) . "\n";
    
    if (count($data->data) > 0) {
        echo "Sample users:\n";
        foreach (array_slice($data->data, 0, 3) as $user) {
            echo "- ID: {$user->user_id}, Name: {$user->user_name}, Has Selfie: " . 
                 ($user->selfie ? "Yes" : "No") . ", Has img_base64: " . 
                 ($user->img_base64 ? "Yes" : "No") . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test 2: getUserSelfie(1) ===\n";
try {
    $response = $controller->getUserSelfie(1);
    $data = $response->getData();
    echo "Status: " . ($data->status ? "Success" : "Failed") . "\n";
    
    if ($data->status) {
        echo "User: {$data->data->user_name} (ID: {$data->data->user_id})\n";
        echo "Has selfie: " . ($data->data->selfie ? "Yes" : "No") . "\n";
        echo "Has img_base64: " . ($data->data->img_base64 ? "Yes" : "No") . "\n";
    } else {
        echo "Message: {$data->message}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test 3: getUserSelfie(999) (non-existent user) ===\n";
try {
    $response = $controller->getUserSelfie(999);
    $data = $response->getData();
    echo "Status: " . ($data->status ? "Success" : "Failed") . "\n";
    echo "Message: {$data->message}\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
