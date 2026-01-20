<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wizard – Rental Details</title>
  <link rel="stylesheet" href="assests/css/wizard.css">
  <link rel="stylesheet" href="assests/css/step3.css">
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

<!-- MAIN CONTENT -->
<div class="main-container">
  <div class="card">

    <!-- PRICING SECTION -->
    <div class="pricing-section">
      
      <!-- Vehicle Rental Fee -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Vehicle Rental Fee</div>
          <div class="fee-col">Price (USD)</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">SUSUKI ALTO K10 - 1</div>
          <div class="fee-col">29 x 2 x 1</div>
          <div class="fee-col">58.00</div>
        </div>
      </div>

      <!-- Location Fee -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Location Fee</div>
          <div class="fee-col">Price (USD)</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Pick up Fee (Katunayaka Airport)</div>
          <div class="fee-col">45 x 1</div>
          <div class="fee-col">45.00</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Return up Fee (Katunayaka Airport)</div>
          <div class="fee-col">45 x 1</div>
          <div class="fee-col">45.00</div>
        </div>
      </div>

      <!-- Service Fee -->
      <div class="fee-table">
        <div class="fee-header">
          <div class="fee-col">Service Fee</div>
          <div class="fee-col">Price (USD)</div>
          <div class="fee-col">Total (USD)</div>
        </div>
        <div class="fee-row">
          <div class="fee-col">Insurance For SUSUKI ALTO K10 - 1</div>
          <div class="fee-col">0.00 x 1</div>
          <div class="fee-col">0.00</div>
        </div>
      </div>

    </div>

    <!-- COUPON AND PAYMENT SECTION -->
    <div class="coupon-payment-section">
      <div class="coupon-box">
        <div class="coupon-left">
          <label for="couponCode">COUPEN CODE</label>
          <input type="text" id="couponCode" placeholder="" class="coupon-input">
          <button type="button" class="btn-apply" onclick="applyCoupon()">Apply</button>
        </div>
        <div class="coupon-right">
          <div class="cost-row">
            <span>Discount (USD)</span>
            <span id="discountValue">0.00</span>
          </div>
          <div class="cost-row">
            <span>Sub Total (USD)</span>
            <span id="subtotalValue">148.00</span>
          </div>
          <div class="cost-row">
            <span>Security Deposit (USD)</span>
            <span id="depositValue">300.00</span>
          </div>
        </div>
      </div>
      <div class="payment-box">
        <button type="button" class="btn-payment" disabled>
          Pay Now / At Pick Up (USD) <span id="totalValue">448.00</span>
        </button>
      </div>
    </div>

    <!-- CUSTOMER DETAILS SECTION -->
    <div class="customer-details-section">
      <h3>CUSTOMER DETAILS</h3>
      <form id="customerForm">
        <div class="details-grid">
          <div class="form-field">
            <label>Full Name</label>
            <input type="text" name="fullName" placeholder="" required>
          </div>
          <div class="form-field">
            <label>Country</label>
            <input type="text" name="country" placeholder="" required>
          </div>
          <div class="form-field">
            <label>Whatsapp No.</label>
            <input type="text" name="whatsapp" placeholder="" required>
          </div>
          <div class="form-field">
            <label>Email</label>
            <input type="email" name="email" placeholder="" required>
          </div>
        </div>
        <div class="form-field full-width">
          <label>Comments</label>
          <textarea name="comments" placeholder="" rows="6"></textarea>
        </div>
      </form>
    </div>

  </div>

  <!-- ACTION BUTTONS -->
  <div class="action-buttons">
    <button type="button" class="btn-back" onclick="goBack()">Back</button>
    <button type="button" class="btn-book" onclick="submitBooking()">Book</button>
    <button type="button" class="btn-cancel" onclick="cancelBooking()">Cancel</button>
  </div>

</div>

<script src="assests/js/step3.js"></script>
</body>
</html>
