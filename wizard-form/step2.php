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

// Validate step1 data exists
if (!isset($_SESSION['step1'])) {
  header('Location: index.php');
  exit;
}

require 'config/api.php';
$booking = $_SESSION['step1'];

// ALWAYS use fallback services - API is not responding
$services = [
        'license_fee' => 40,
        'airport_transfer' => 50
];

$licensePrice = $services['license_fee'] ?? 40;
$airportPrice = $services['airport_transfer'] ?? 50;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wizard – NOHA Service Details</title>
  
  <link rel="stylesheet" href="assests/css/step2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- STEP BAR -->
<div class="step-bar">
  <div class="step-item completed">
    <div class="step-circle">1</div>
    <div class="step-line"></div>
    <span class="step-label">Check Availability</span>
  </div>
  <div class="step-item active">
    <div class="step-circle">2</div>
    <div class="step-line"></div>
    <span class="step-label">NOHA Service Details</span>
  </div>
  <div class="step-item">
    <div class="step-circle">3</div>
    <div class="step-line last"></div>
    <span class="step-label">Rental Details</span>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-container">
  <div class="card">
    <!-- USER BOOKING SUMMARY -->
    <?php
    // Get vehicle info from mock API
    $vehicles = callAPI('/vehicles');
    $vehicle = null;
    foreach ($vehicles as $v) {
      if ((int)$v['id'] === (int)$booking['vehicle_id']) {
        $vehicle = $v;
        break;
      }
    }

    // Use session and step2 data for dynamic rental details
    $step2 = $_SESSION['step2'] ?? [
      'licenses' => 1,
      'passengers' => 1,
      'airport_dropoff' => 0,
      'airport_pickup' => 0,
      'train_transfer' => ''
    ];

    // Custom price list for rides/services
    $priceList = [
      'vehicle_daily' => [
        1 => 15, 2 => 22.5, 3 => 12.5, 4 => 10, 5 => 35, 6 => 30, 7 => 32, 8 => 18
      ],
      'license_fee' => 40,
      'airport_transfer' => 50,
      'train_transfer' => [
        'kandy_ella' => 25,
        'ella_kandy' => 25,
        'nanuoya_ella' => 20,
        'ella_nanuoya' => 20,
        'ella_hatton' => 18,
        'hatton_ella' => 18,
      ]
    ];

    $pickup = strtotime($booking['pickup_date']);
    $return = strtotime($booking['return_date']);
    $days = max(1, ceil(($return - $pickup) / 86400));
    $vehicleCount = (int)$booking['vehicle_count'];
    $vehiclePrice = isset($priceList['vehicle_daily'][$vehicle['id']]) ? $priceList['vehicle_daily'][$vehicle['id']] : 0;
    $vehicleTotal = $vehiclePrice * $days * $vehicleCount;
    $licenseTotal = $step2['licenses'] * $priceList['license_fee'];
    $airportTotal = ($step2['airport_dropoff'] || $step2['airport_pickup']) ? $step2['passengers'] * $priceList['airport_transfer'] : 0;
    $trainTransfer = $step2['train_transfer'];
    $trainTotal = $trainTransfer && isset($priceList['train_transfer'][$trainTransfer]) ? $priceList['train_transfer'][$trainTransfer] : 0;
    $grandTotal = $vehicleTotal + $licenseTotal + $airportTotal + $trainTotal;
    ?>
    <div class="content-wrapper">
      <!-- LEFT COLUMN: Form Details -->
      <div class="form-section">
        <h2>NOHA Service Details</h2>

        <form id="nohaServiceForm">
        <!-- License Information -->
        <div class="info-box">
          <p>A Sri Lankan driving license can be arranged for you within 1 business day at a cost of <strong>USD 40.</strong></p>
          <p>Alternatively, if you have an IDP with a "Type B" endorsement, you can obtain the local license yourself by visiting the AAC in Colombo, which takes about 4 hours and costs <strong>USD 30.</strong></p>
        </div>

        <!-- Driving Licenses Counter -->
        <div class="form-field">
          <label>No of Local Driving Licenses needs fro NOHA</label>
          <div class="inline-counter">
            <button type="button" onclick="decreaseLicense()" class="counter-btn">−</button>
            <input type="text" id="licenseCount" value="0" readonly class="counter-input">
            <button type="button" onclick="increaseLicense()" class="counter-btn">+</button>
          </div>
        </div>

        <!-- Airport Transfer Information -->
        <div class="info-box">
          <p>If you want to start your journey from your pickup location on the day of arrival, NOHA offers an Airport Transfer service. We also provide drop-off service from your selected location back to the airport.</p>
        </div>

        <!-- Airport Passengers Counter -->
        <div class="form-field">
          <label>No of Passengers for the Airport Transfer</label>
          <div class="inline-counter-with-checkboxes">
            <div class="counter-section">
              <button type="button" onclick="decreasePassenger()" class="counter-btn">−</button>
              <input type="text" id="passengerCount" value="0" readonly class="counter-input">
              <button type="button" onclick="increasePassenger()" class="counter-btn">+</button>
            </div>
            <div class="checkbox-section">
              <label class="checkbox-label">
                <input type="checkbox" name="airport_dropoff" value="benthota">
                Airport Drop-off Service To Benthota
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="airport_pickup" value="ahangama">
                Airport Pick-up Service To Ahangama
              </label>
            </div>
          </div>
        </div>

        <!-- Train Transfer Services -->
        <div class="train-services">
          <h3>Train transfer services SUSUKI ALTO K10</h3>
          <div class="radio-grid">
            <div class="radio-column">
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="kandy_ella">
                Kandy to Ella
              </label>
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="ella_kandy">
                Ella to Kandy
              </label>
            </div>
            <div class="radio-column">
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="nanuoya_ella">
                NanuOya(Nuwara Eliya) to Ella
              </label>
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="ella_nanuoya">
                Ella to NanuOya(Nuwara Eliya)
              </label>
            </div>
            <div class="radio-column">
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="ella_hatton">
                Ella to Hatton
              </label>
              <label class="radio-label">
                <input type="radio" name="train_transfer" value="hatton_ella">
                Hatton to Ella
              </label>
            </div>
          </div>
        </div>

      </form>
      </div>

      <!-- RIGHT COLUMN: Rental Details Summary -->
      <div class="summary-section">
        <div class="rental-details-card">
          <h3>Rental Details</h3>

        <!-- Vehicle Info (Dynamic) -->
        <div class="vehicle-summary">
          <div class="vehicle-image">
            <?php if ($vehicle): ?>
              <img src="<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['name']) ?>">
            <?php endif; ?>
          </div>
          <div class="vehicle-info">
            <h4><?= $vehicle ? htmlspecialchars($vehicle['name']) : 'Unknown' ?> × <?= $vehicleCount ?></h4>
            <p class="duration">Rental Duration: <?= $days ?> day<?= $days > 1 ? 's' : '' ?></p>
            <p class="price">Vehicle: USD <?= number_format($vehicleTotal, 2) ?></p>
          </div>
        </div>

        <!-- Price Breakdown -->
        <div class="details-section">
          <h4>Price Breakdown</h4>
          <ul style="list-style:none;padding:0;font-size:15px;">
            <li>Vehicle Rental: <span style="float:right;">USD <?= number_format($vehicleTotal, 2) ?></span></li>
            <li>Local License Fee: <span style="float:right;">USD <?= number_format($licenseTotal, 2) ?></span></li>
            <li>Airport Transfer: <span style="float:right;">USD <?= number_format($airportTotal, 2) ?></span></li>
            <?php if ($trainTotal > 0): ?>
              <li>Train Transfer: <span style="float:right;">USD <?= number_format($trainTotal, 2) ?></span></li>
            <?php endif; ?>
            <li style="font-weight:bold;border-top:1px solid #eee;margin-top:8px;padding-top:8px;">Total: <span style="float:right;">USD <?= number_format($grandTotal, 2) ?></span></li>
          </ul>
        </div>

        <!-- Pick up Details (Dynamic) -->
        <div class="details-section">
          <h4>Pick up Details</h4>
          <div class="detail-row">
            <div class="detail-item location">
              <i class="fas fa-plane"></i>
              <span><?= htmlspecialchars($booking['pickup_location']) ?></span>
            </div>
            <div class="detail-item datetime">
              <i class="fas fa-calendar"></i>
              <span><?= htmlspecialchars($booking['pickup_date']) ?></span>
            </div>
          </div>
        </div>

        <!-- Return Details (Dynamic) -->
        <div class="details-section">
          <h4>Return Details</h4>
          <div class="detail-row">
            <div class="detail-item location">
              <i class="fas fa-plane"></i>
              <span><?= htmlspecialchars($booking['return_location']) ?></span>
            </div>
            <div class="detail-item datetime">
              <i class="fas fa-calendar"></i>
              <span><?= htmlspecialchars($booking['return_date']) ?></span>
            </div>
          </div>
        </div>

        </div>
      </div>

    </div>
    <div class="action-buttons">
    <button type="button" class="btn-back" onclick="goBack()">Back</button>
    <button type="button" class="btn-next" onclick="goNext()">Next</button>
    <button type="button" class="btn-cancel" onclick="cancelForm()">Cancel</button>
  </div>
  </div>

  <!-- Action Buttons -->
  
</div>

<script src="assests/js/step2.js"></script>
</body>
</html>
