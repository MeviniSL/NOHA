<?php
require 'config/api.php';
session_start();

if (!isset($_SESSION['step1']) || !isset($_SESSION['step2'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please start over.'
    ]);
    exit;
}

$data = array_merge(
    $_SESSION['step1'],
    $_SESSION['step2'],
    $_POST
);

// Send booking to API (simulate API call as per Postman collection)
$response = callAPI('/bookings', 'POST', $data);

if (!$response || !isset($response['reference'])) {
    echo json_encode([
        'success' => false,
        'message' => $response['message'] ?? 'API Error: Booking failed. Please check your API token and endpoint.'
    ]);
    exit;
}

// Show booking confirmation popup (handled in frontend JS)
echo json_encode([
    'success' => true,
    'reference' => $response['reference'],
    'message' => 'Booking successful!'
]);

