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
require 'config/api.php';

/* =========================
   STEP VALIDATION
========================= */
if (!isset($_SESSION['step1'], $_SESSION['step2'])) {
    // Debug: Output session state for troubleshooting
    file_put_contents(__DIR__ . '/debug_step3.log', print_r([
      'session_id' => session_id(),
      '_SESSION' => $_SESSION,
      'redirected' => true
    ], true));
    header('Location: index.php');
    exit;
}

/* =========================
   FETCH SESSION DATA
========================= */
$step1 = $_SESSION['step1'];
$step2 = $_SESSION['step2'];

/* =========================
   PRICING API CALL
========================= */
$pricing = callAPI('/calculate-price', 'POST', [
    'booking'  => $step1,
    'services' => $step2
]);

/* =========================
   SAFETY FALLBACK
========================= */
$pricing = $pricing ?? [
    'vehicle_name'    => 'Vehicle',
    'vehicle_total'   => 0,
    'pickup_fee'      => 0,
    'return_fee'      => 0,
    'service_total'   => 0,
    'discount'        => 0,
    'sub_total'       => 0,
    'deposit'         => 0,
    'grand_total'     => 0
];

// Store latest pricing in session for subsequent operations (e.g., coupons)
$_SESSION['pricing'] = $pricing;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wizard – Rental Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  
  <link rel="stylesheet" href="assests/css/step3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- ================= STEP BAR ================= -->
<div class="step-bar">
  <div class="step-item completed">
    <div class="step-circle">1</div>
    <div class="step-line"></div>
    <span class="step-label">Check Availability</span>
  </div>
  <div class="step-item completed">
    <div class="step-circle">2</div>
    <div class="step-line"></div>
    <span class="step-label">NOHA Service Details</span>
  </div>
  <div class="step-item active">
    <div class="step-circle">3</div>
    <div class="step-line last"></div>
    <span class="step-label">Rental Details</span>
  </div>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-container">
  <div class="card">

    <!-- ================= PRICING SECTION ================= -->
    <div class="pricing-section">

      <!-- VEHICLE -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Vehicle Rental Fee</div>
          <div class="fee-col">Price</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col"><?= htmlspecialchars($pricing['vehicle_name']) ?></div>
          <div class="fee-col">Calculated</div>
          <div class="fee-col"><?= number_format($pricing['vehicle_total'], 2) ?></div>
        </div>
      </div>

      <!-- LOCATION -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Location Fee</div>
          <div class="fee-col">Price</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Pickup Fee</div>
          <div class="fee-col">API</div>
          <div class="fee-col"><?= number_format($pricing['pickup_fee'], 2) ?></div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Return Fee</div>
          <div class="fee-col">API</div>
          <div class="fee-col"><?= number_format($pricing['return_fee'], 2) ?></div>
        </div>
      </div>

      <!-- SERVICES -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Service Fee</div>
          <div class="fee-col">Price</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Additional Services</div>
          <div class="fee-col">API</div>
          <div class="fee-col"><?= number_format($pricing['service_total'], 2) ?></div>
        </div>
      </div>

    </div>

    <!-- ================= COUPON & TOTAL ================= -->
    <div class="coupon-payment-section">
      <div class="coupon-box">
        <div class="coupon-left">
          <label>COUPON CODE</label>
          <input type="text" id="couponCode" class="coupon-input">
          <button type="button" class="btn-apply" onclick="applyCoupon()">Apply</button>
        </div>

        <div class="coupon-right">
          <div class="cost-row">
            <span>Discount (USD)</span>
            <span id="discountValue"><?= number_format($pricing['discount'], 2) ?></span>
          </div>
          <div class="cost-row">
            <span>Sub Total (USD)</span>
            <span id="subtotalValue"><?= number_format($pricing['sub_total'], 2) ?></span>
          </div>
          <div class="cost-row">
            <span>Security Deposit (USD)</span>
            <span id="depositValue"><?= number_format($pricing['deposit'], 2) ?></span>
          </div>
        </div>
      </div>

      <div class="payment-box">
        <button type="button" class="btn-payment" disabled>
          Pay Now / At Pick Up (USD)
          <span id="totalValue"><?= number_format($pricing['grand_total'], 2) ?></span>
        </button>
      </div>
    </div>

    <!-- ================= CUSTOMER DETAILS ================= -->
    <div class="customer-details-section">
      <h3>CUSTOMER DETAILS</h3>
      <div class="step-summary" style="margin-bottom:18px;">
        <strong>NOHA Service Details:</strong><br>
        <span>Licenses: <?= (int)$step2['licenses'] ?></span> |
        <span>Passengers: <?= (int)$step2['passengers'] ?></span> |
        <span>Airport Drop-off: <?= $step2['airport_dropoff'] ? 'Yes' : 'No' ?></span> |
        <span>Airport Pick-up: <?= $step2['airport_pickup'] ? 'Yes' : 'No' ?></span>
        <?php if (!empty($step2['train_transfer'])): ?>
          | <span>Train Transfer: <?= htmlspecialchars($step2['train_transfer']) ?></span>
        <?php endif; ?>
      </div>
    <div class="customer-details">
  <div class="input-row">
    <div class="input-group"><input type="text" placeholder="Full Name"></div>
    <div class="input-group"><input type="text" placeholder="Country"></div>
    <div class="input-group">
  <input type="text" class="whatsapp-input" placeholder="whatspp +94771234567" name="whatsapp">
</div>
    <div class="input-group"><input type="email" placeholder="Email"></div>
  </div>
  <div class="input-group">
    <textarea placeholder="Comments"></textarea>
  </div>
</div>

  </div>

  <!-- ================= ACTION BUTTONS ================= -->
  <div class="action-buttons">
    <button class="btn-back">Back</button>
    <button class="btn-book" onclick="submitBooking()">Book</button>
    <button class="btn-cancel" onclick="cancelBooking()">Cancel</button>
  </div>
</div>

<script src="assests/js/step3.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var bookBtn = document.querySelector('.btn-book');
  if (bookBtn) {
    bookBtn.addEventListener('click', function(e) {
      e.preventDefault();
      // Get customer details fields
      var fullName = document.querySelector('input[name="full_name"]');
      var country = document.querySelector('input[name="country"]');
      var whatsapp = document.querySelector('input[name="whatsapp"]');
      var email = document.querySelector('input[name="email"]');
      // Check if all required fields are filled
      if (
        fullName && fullName.value.trim() !== '' &&
        country && country.value.trim() !== '' &&
        whatsapp && whatsapp.value.trim() !== '' &&
        email && email.value.trim() !== ''
      ) {
        alert('Your booking is done!');
      } else {
        alert('Please fill the customer form');
      }
    });
  }
});
</script>
</body>
</html>
