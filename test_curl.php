<?php

// Test API dengan cURL langsung
function testAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

echo "Testing API Endpoints...\n";
echo "========================\n\n";

// Test 1: Get all user selfies
echo "1. GET /api/user-selfies\n";
$result = testAPI('http://127.0.0.1:8000/api/user-selfies');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        echo "User Count: " . count($data['data']) . "\n";
        if (count($data['data']) > 0) {
            echo "Sample users: ";
            foreach (array_slice($data['data'], 0, 2) as $user) {
                echo "[ID: {$user['user_id']}, Name: {$user['user_name']}] ";
            }
            echo "\n";
        }
    } else {
        echo "Response: " . $result['response'] . "\n";
    }
}

echo "\n";

// Test 2: Get specific user
echo "2. GET /api/user-selfie/1\n";
$result = testAPI('http://127.0.0.1:8000/api/user-selfie/1');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        if ($data['status']) {
            echo "User: {$data['data']['user_name']} (ID: {$data['data']['user_id']})\n";
            echo "Has selfie: " . ($data['data']['selfie'] ? 'Yes' : 'No') . "\n";
            echo "Has img_base64: " . ($data['data']['img_base64'] ? 'Yes' : 'No') . "\n";
        } else {
            echo "Message: " . $data['message'] . "\n";
        }
    } else {
        echo "Response: " . $result['response'] . "\n";
    }
}

echo "\n";

// Test 3: Get non-existent user
echo "3. GET /api/user-selfie/999\n";
$result = testAPI('http://127.0.0.1:8000/api/user-selfie/999');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        echo "Message: " . $data['message'] . "\n";
    } else {
        echo "Response: " . $result['response'] . "\n";
    }
}

echo "\n========================\n";
echo "Testing Complete!\n";
