<?php
session_start();
require 'config/api.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  exit;
}

if (!isset($_SESSION['step1'], $_SESSION['step2'])) {
  echo json_encode(['success' => false, 'message' => 'Session expired. Please start over.']);
  exit;
}

$coupon = isset($_POST['coupon']) ? trim($_POST['coupon']) : '';

$pricing = callAPI('/calculate-price', 'POST', [
  'booking'  => $_SESSION['step1'],
  'services' => $_SESSION['step2'],
  'coupon'   => $coupon,
]);

if (!$pricing) {
  echo json_encode(['success' => false, 'message' => 'Failed to calculate price']);
  exit;
}

// Optionally store latest pricing
$_SESSION['pricing'] = $pricing;

echo json_encode([
  'success'     => true,
  'discount'    => (float)$pricing['discount'],
  'sub_total'   => (float)$pricing['sub_total'],
  'deposit'     => (float)$pricing['deposit'],
  'grand_total' => (float)$pricing['grand_total'],
]);
require 'config/api.php';
session_start();

$coupon = $_POST['coupon'] ?? '';

$response = callAPI('/apply-coupon', 'POST', [
  'coupon'  => $coupon,
  'booking' => $_SESSION['step1'],
  'services'=> $_SESSION['step2']
]);

echo json_encode([
  'success'     => $response['valid'] ?? false,
  'discount'    => $response['discount'] ?? 0,
  'sub_total'   => $response['sub_total'] ?? 0,
  'grand_total' => $response['grand_total'] ?? 0,
  'message'     => $response['message'] ?? 'Invalid coupon'
]);
