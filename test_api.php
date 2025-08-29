<?php

// Test script untuk API user selfies
echo "Testing API Endpoints...\n";

// Function untuk test API
function testApi($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    if ($method === 'POST' && $data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response
    ];
}

// Test 1: Get all user selfies
echo "\n=== Test 1: GET /api/user-selfies ===\n";
$result1 = testApi('http://127.0.0.1:8000/api/user-selfies');
echo "HTTP Code: " . $result1['http_code'] . "\n";
echo "Response: " . $result1['response'] . "\n";

// Test 2: Get specific user selfie (menggunakan ID 1)
echo "\n=== Test 2: GET /api/user-selfie/1 ===\n";
$result2 = testApi('http://127.0.0.1:8000/api/user-selfie/1');
echo "HTTP Code: " . $result2['http_code'] . "\n";
echo "Response: " . $result2['response'] . "\n";

// Test 3: Get user selfie dengan ID yang tidak ada
echo "\n=== Test 3: GET /api/user-selfie/999 (ID tidak ada) ===\n";
$result3 = testApi('http://127.0.0.1:8000/api/user-selfie/999');
echo "HTTP Code: " . $result3['http_code'] . "\n";
echo "Response: " . $result3['response'] . "\n";

echo "\n=== Testing Complete ===\n";
