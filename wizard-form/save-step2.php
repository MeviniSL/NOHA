<?php
// Force session cookie parameters to be consistent and accessible
session_set_cookie_params([
	'lifetime' => 0,
	'path' => '/',
	'domain' => '',
	'secure' => false,
	'httponly' => false,
	'samesite' => 'Lax',
]);
session_start();
// Normalize and store all relevant step2 data
$step2 = [
	'licenses' => isset($_POST['licenses']) ? (int)$_POST['licenses'] : 1,
	'passengers' => isset($_POST['passengers']) ? (int)$_POST['passengers'] : 1,
	'airport_dropoff' => !empty($_POST['airport_dropoff']) ? 1 : 0,
	'airport_pickup' => !empty($_POST['airport_pickup']) ? 1 : 0,
	'train_transfer' => isset($_POST['train_transfer']) ? trim($_POST['train_transfer']) : '',
];
$_SESSION['step2'] = $step2;
session_write_close(); // Ensure session is saved before redirect
// Respond with success for JS fetch
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;
