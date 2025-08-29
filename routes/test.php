<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelperController;

// Route khusus untuk testing API
Route::get('/test-api', function () {
    $controller = new HelperController();
    
    echo "=== Testing API Endpoints ===\n\n";
    
    // Test 1: Get all user selfies
    echo "1. Testing GET /api/user-selfies\n";
    try {
        $response = $controller->getAllUserSelfies();
        $data = $response->getData();
        echo "Status: " . ($data->status ? 'Success' : 'Failed') . "\n";
        echo "Data count: " . count($data->data) . "\n";
        echo "Sample data: " . json_encode(collect($data->data)->take(2)) . "\n\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
    
    // Test 2: Get specific user selfie
    echo "2. Testing GET /api/user-selfie/1\n";
    try {
        $response = $controller->getUserSelfie(1);
        $data = $response->getData();
        echo "Status: " . ($data->status ? 'Success' : 'Failed') . "\n";
        if ($data->status) {
            echo "User: " . $data->data->user_name . " (ID: " . $data->data->user_id . ")\n";
            echo "Has selfie: " . ($data->data->selfie ? 'Yes' : 'No') . "\n";
            echo "Has img_base64: " . ($data->data->img_base64 ? 'Yes' : 'No') . "\n";
        } else {
            echo "Message: " . $data->message . "\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
    
    // Test 3: Get user selfie yang tidak ada
    echo "3. Testing GET /api/user-selfie/999 (non-existent user)\n";
    try {
        $response = $controller->getUserSelfie(999);
        $data = $response->getData();
        echo "Status: " . ($data->status ? 'Success' : 'Failed') . "\n";
        echo "Message: " . $data->message . "\n\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
    
    echo "=== Testing Complete ===\n";
    
    return response()->json(['message' => 'Test completed']);
});
