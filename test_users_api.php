<?php

// Test script untuk API Users
function testUsersAPI($url) {
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

echo "Testing Users API Endpoints...\n";
echo "==============================\n\n";

// Test 1: Get users with default pagination
echo "1. GET /api/users (default pagination)\n";
$result = testUsersAPI('http://127.0.0.1:8000/api/users');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data && isset($data['status'])) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        if ($data['status']) {
            echo "Message: " . $data['message'] . "\n";
            echo "User Count: " . count($data['data']) . "\n";
            echo "Total Users: " . $data['pagination']['total'] . "\n";
            echo "Current Page: " . $data['pagination']['current_page'] . "\n";
            echo "Last Page: " . $data['pagination']['last_page'] . "\n";
        }
    } else {
        echo "Raw Response: " . substr($result['response'], 0, 200) . "...\n";
    }
}
echo "\n";

// Test 2: Get users with pagination parameters
echo "2. GET /api/users?page=1&per_page=5\n";
$result = testUsersAPI('http://127.0.0.1:8000/api/users?page=1&per_page=5');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data && isset($data['status']) && $data['status']) {
        echo "API Status: Success\n";
        echo "Items per page: " . $data['pagination']['per_page'] . "\n";
        echo "Items returned: " . count($data['data']) . "\n";
        if (count($data['data']) > 0) {
            echo "Sample user: " . $data['data'][0]['name'] . " (" . $data['data'][0]['email'] . ")\n";
        }
    } else {
        echo "API Status: Failed or no valid response\n";
    }
}
echo "\n";

// Test 3: Get user detail
echo "3. GET /api/user/1/detail\n";
$result = testUsersAPI('http://127.0.0.1:8000/api/user/1/detail');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data && isset($data['status'])) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        if ($data['status']) {
            echo "User: " . $data['data']['name'] . " (" . $data['data']['email'] . ")\n";
            echo "Roles: " . implode(', ', array_column($data['data']['roles'], 'name')) . "\n";
            echo "Statistics:\n";
            echo "  - Total Packages: " . $data['data']['statistics']['total_order_paket'] . "\n";
            echo "  - Active Packages: " . $data['data']['statistics']['active_paket'] . "\n";
            echo "  - Total Bookings: " . $data['data']['statistics']['total_bookings'] . "\n";
            echo "  - Attendance Rate: " . $data['data']['statistics']['attendance_rate'] . "%\n";
        } else {
            echo "Message: " . $data['message'] . "\n";
        }
    }
}
echo "\n";

// Test 4: Search users
echo "4. GET /api/users?search=admin\n";
$result = testUsersAPI('http://127.0.0.1:8000/api/users?search=admin');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data && isset($data['status']) && $data['status']) {
        echo "API Status: Success\n";
        echo "Search results: " . count($data['data']) . " users found\n";
        if (count($data['data']) > 0) {
            foreach ($data['data'] as $user) {
                echo "  - " . $user['name'] . " (" . $user['email'] . ")\n";
            }
        }
    }
}
echo "\n";

// Test 5: Get non-existent user detail
echo "5. GET /api/user/9999/detail (non-existent user)\n";
$result = testUsersAPI('http://127.0.0.1:8000/api/user/9999/detail');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['error']) {
    echo "cURL Error: " . $result['error'] . "\n";
} else {
    $data = json_decode($result['response'], true);
    if ($data && isset($data['status'])) {
        echo "API Status: " . ($data['status'] ? 'Success' : 'Failed') . "\n";
        echo "Message: " . $data['message'] . "\n";
    }
}

echo "\n==============================\n";
echo "Testing Complete!\n";
