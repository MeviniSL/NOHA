<?php
session_start();

header('Content-Type: application/json');

// Validate incoming data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read fields
    $pickup  = trim($_POST['pickup_location'] ?? '');
    $return  = trim($_POST['return_location'] ?? '');
    $pDate   = $_POST['pickup_date'] ?? '';
    $rDate   = $_POST['return_date'] ?? '';
    $count   = (int)($_POST['vehicle_count'] ?? 0);
    $vehicle = (int)($_POST['vehicle_id'] ?? 0);

    // Basic validation
    if ($pickup === '' || $return === '' || $pDate === '' || $rDate === '' || $count <= 0 || $vehicle <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing or invalid form fields']);
        exit;
    }

    // Normalize location names and enforce allowed cities
    $normalize = function($s) {
        $s = strtolower(trim($s));
        $s = str_replace(['=', '  '], ['',' '], $s);
        // common variants
        $map = [
            'colombo' => 'Colombo',
            'mirissa' => 'Mirissa',
            'misrissa' => 'Mirissa',
            'mis rissa' => 'Mirissa',
            'katunayaka airport' => 'Katunayaka Airport',
            'katunayake airport' => 'Katunayaka Airport',
            'katunayaka' => 'Katunayaka Airport',
            'sigiriya' => 'Sigiriya',
            'nuwaraeliya' => 'Nuwara Eliya',
            'nuwara eliya' => 'Nuwara Eliya',
        ];
        return $map[$s] ?? ucwords($s);
    };

    $pickupNorm = $normalize($pickup);
    $returnNorm = $normalize($return);

    $allowed = ['Colombo','Mirissa','Katunayaka Airport','Sigiriya','Nuwara Eliya'];
    if (!in_array($pickupNorm, $allowed, true) || !in_array($returnNorm, $allowed, true)) {
        echo json_encode(['success' => false, 'message' => 'Please choose locations from: Colombo, Mirissa, Katunayaka Airport, Sigiriya, Nuwara Eliya']);
        exit;
    }


    // --- API-based vehicle availability check (simulate Postman collection logic) ---
    // In a real scenario, you would call the API endpoint for availability here.
    // For this project, we simulate the check using inventory.json as before.
    $inventoryPath = __DIR__ . '/data/inventory.json';
    $inventory = [];
    if (file_exists($inventoryPath)) {
        $json = file_get_contents($inventoryPath);
        $inventory = json_decode($json, true) ?? [];
    }

    if (!isset($inventory[$pickupNorm])) {
        echo json_encode(['success' => false, 'message' => 'Pickup location not supported']);
        exit;
    }

    $locInv = $inventory[$pickupNorm];
    $available = $locInv['available'] ?? [];
    $stock     = $locInv['stock'] ?? [];

    $stockForVehicle = (int)($stock[(string)$vehicle] ?? 0);
    if (!in_array($vehicle, $available, true) || $stockForVehicle < $count) {
        echo json_encode(['success' => false, 'message' => 'Vehicle not available for selected location or quantity']);
        exit;
    }

    // Save normalized data to session
    $_SESSION['step1'] = [
        'pickup_location' => $pickupNorm,
        'return_location' => $returnNorm,
        'pickup_date' => $pDate,
        'return_date' => $rDate,
        'vehicle_count' => $count,
        'vehicle_id' => $vehicle
    ];

    // Respond with success for frontend to redirect
    echo json_encode(['success' => true, 'message' => 'Step 1 saved successfully']);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

