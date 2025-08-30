<?php

// Test script untuk API check-paket yang sudah diperbaiki
echo "=== Test Check Paket API ===\n\n";

// URL API
$url = 'http://127.0.0.1:8000/api/check-paket';

// Data untuk testing (hanya id_user)
$data = [
    'id_user' => 1
];

// Setup cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

// Execute request
echo "🔄 Testing POST $url\n";
echo "📤 Request data: " . json_encode($data) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "❌ Error: " . curl_error($ch) . "\n";
} else {
    echo "📥 HTTP Status: $httpCode\n";
    echo "📥 Response: " . $response . "\n\n";
    
    $responseData = json_decode($response, true);
    
    if ($responseData && isset($responseData['status'])) {
        if ($responseData['status']) {
            echo "✅ SUCCESS: " . $responseData['message'] . "\n";
            
            if (isset($responseData['data'])) {
                $data = $responseData['data'];
                echo "👤 User ID: " . $data['user_id'] . "\n";
                echo "📦 Total Active Packages: " . $data['total_active_packages'] . "\n";
                
                if (isset($data['packages']) && is_array($data['packages'])) {
                    echo "📋 Package Details:\n";
                    foreach ($data['packages'] as $i => $package) {
                        echo "  " . ($i + 1) . ". " . $package['paket_name'] . "\n";
                        echo "     - ID: " . $package['paket_id'] . "\n";
                        echo "     - Expires: " . $package['expired_date'] . "\n";
                        echo "     - Days Left: " . $package['remaining_days'] . "\n";
                        echo "     - Status: " . $package['status'] . "\n\n";
                    }
                }
            }
        } else {
            echo "❌ FAILED: " . $responseData['message'] . "\n";
        }
    }
}

curl_close($ch);

// Test dengan user yang tidak ada
echo "\n=== Test dengan User ID yang tidak ada ===\n";

$data2 = [
    'id_user' => 999999 // ID yang tidak ada
];

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url);
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data2));
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

echo "🔄 Testing dengan user ID: " . $data2['id_user'] . "\n";
$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

if (curl_errno($ch2)) {
    echo "❌ Error: " . curl_error($ch2) . "\n";
} else {
    echo "📥 HTTP Status: $httpCode2\n";
    echo "📥 Response: " . $response2 . "\n";
    
    $responseData2 = json_decode($response2, true);
    if ($responseData2 && isset($responseData2['status'])) {
        if ($responseData2['status']) {
            echo "✅ SUCCESS: " . $responseData2['message'] . "\n";
        } else {
            echo "❌ EXPECTED FAILURE: " . $responseData2['message'] . "\n";
        }
    }
}

curl_close($ch2);

echo "\n=== Test Complete ===\n";
