<?php
// Remove session_start() - it will be called in each PHP file that needs it

// Toggle: use remote API vs local mock
define('USE_MOCK_API', true);

define('API_BASE_URL', 'https://api.nohatuktuk.com');
define('API_TOKEN', 'YOUR_API_TOKEN_HERE'); // Replace with your actual API token

function callAPI($endpoint, $method = 'GET', $data = null) {
    if (USE_MOCK_API) {
        return mockAPI($endpoint, $method, $data);
    }

    $ch = curl_init(API_BASE_URL . $endpoint);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For development
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . API_TOKEN,
        'Content-Type: application/json'
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log errors for debugging
    if ($error || $httpCode >= 400) {
        error_log("API Error ($method $endpoint): HTTP $httpCode - $error - Response: $response");
    }

    return json_decode($response, true);
}

function mockAPI($endpoint, $method = 'GET', $data = null) {
    // Static data for mock
    $vehicles = [
        ['id' => 1, 'name' => 'TUKTUK (200cc)', 'engine' => 'Bajaj', 'image' => 'assests/images/vehicles/tuktuk.jpg'],
        ['id' => 2, 'name' => 'MOTORBIKE (150cc)', 'engine' => 'Yamaha FZ', 'image' => 'assests/images/vehicles/motorbike.jpg'],
        ['id' => 3, 'name' => 'SCOOTER (125cc)', 'engine' => 'TVS Ntorq / Yamaha Ray ZR', 'image' => 'assests/images/vehicles/scooter125.jpg'],
        ['id' => 4, 'name' => 'SCOOTER (110cc)', 'engine' => 'Honda Dio', 'image' => 'assests/images/vehicles/scooter110.jpg'],
        ['id' => 5, 'name' => 'SUZUKI WAGON R', 'engine' => '', 'image' => 'assests/images/vehicles/waganr.jpg'],
        ['id' => 6, 'name' => 'TOYOTA AQUA', 'engine' => '', 'image' => 'assests/images/vehicles/aqua.jpg'],
        ['id' => 7, 'name' => 'TOYOTA PRIUS', 'engine' => '', 'image' => 'assests/images/vehicles/prius.jpg'],
        ['id' => 8, 'name' => 'SUZUKI ALTO K10', 'engine' => '', 'image' => 'assests/images/vehicles/alto.jpg'],
    ];

    $vehicleRates = [
        1 => 15.0,
        2 => 22.5,
        3 => 12.5,
        4 => 10.0,
        5 => 35.0,
        6 => 30.0,
        7 => 32.0,
        8 => 18.0,
    ];

    switch ($endpoint) {
        case '/vehicles':
            return $vehicles;

        case '/locations':
            // Could be objects or simple strings; project now uses plain inputs
            return ['Colombo', 'Kandy', 'Benthota', 'Ahangama', 'Nuwara Eliya'];

        case '/noha/services':
            return [
                'license_fee' => 40.0,
                'airport_transfer' => 25.0, // per passenger, per direction
            ];

        case '/calculate-price':
            if ($method !== 'POST') return null;
            $booking  = $data['booking']  ?? [];
            $services = $data['services'] ?? [];
            $coupon   = isset($data['coupon']) ? trim($data['coupon']) : '';

            $vehicleId     = (int)($booking['vehicle_id'] ?? 0);
            $vehicleCount  = (int)($booking['vehicle_count'] ?? 0);
            $days          = 2; // fixed as per UI
            $rate          = $vehicleRates[$vehicleId] ?? 0.0;
            $vehicleTotal  = round($rate * $vehicleCount * $days, 2);

            $pickupFee = !empty($booking['pickup_location']) ? 5.0 : 0.0;
            $returnFee = !empty($booking['return_location']) ? 5.0 : 0.0;

            $licenses   = (int)($services['licenses'] ?? 0);
            $passengers = (int)($services['passengers'] ?? 0);
            $airportPickup  = (int)($services['airport_pickup'] ?? 0);
            $airportDropoff = (int)($services['airport_dropoff'] ?? 0);
            $trainTransfer  = trim($services['train_transfer'] ?? '') !== '' ? 15.0 : 0.0;

            $serviceTotal = 0.0;
            $serviceTotal += $licenses * 40.0;
            $serviceTotal += ($airportPickup ? $passengers * 25.0 : 0.0);
            $serviceTotal += ($airportDropoff ? $passengers * 25.0 : 0.0);
            $serviceTotal += $trainTransfer;

            $subTotal = round($vehicleTotal + $pickupFee + $returnFee + $serviceTotal, 2);

            $deposit  = round(max(50.0, $subTotal * 0.1), 2);

            $discount = 0.0;
            if ($coupon !== '') {
                if (strcasecmp($coupon, 'NOHA10') === 0) {
                    $discount = round($subTotal * 0.10, 2);
                } elseif (strcasecmp($coupon, 'NOHA50') === 0) {
                    $discount = 50.0;
                }
            }

            $grandTotal = round($subTotal - $discount + $deposit, 2);

            $vehicleName = 'Vehicle';
            foreach ($vehicles as $v) {
                if ((int)$v['id'] === $vehicleId) { $vehicleName = $v['name']; break; }
            }

            return [
                'vehicle_name'   => $vehicleName,
                'vehicle_total'  => $vehicleTotal,
                'pickup_fee'     => $pickupFee,
                'return_fee'     => $returnFee,
                'service_total'  => $serviceTotal,
                'discount'       => $discount,
                'sub_total'      => $subTotal,
                'deposit'        => $deposit,
                'grand_total'    => $grandTotal,
            ];

        case '/bookings':
            if ($method !== 'POST') return null;
            // Simulate booking creation
            return [
                'reference' => 'NOHA-' . strtoupper(substr(md5(uniqid('', true)), 0, 8)),
                'message'   => 'Mock booking created',
            ];

        default:
            return null;
    }
}
