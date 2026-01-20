<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wizard – NOHA Service Details</title>
  <link rel="stylesheet" href="assests/css/wizard.css">
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
            <input type="text" id="licenseCount" value="1" readonly class="counter-input">
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
              <input type="text" id="passengerCount" value="1" readonly class="counter-input">
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

        <!-- Vehicle Info -->
        <div class="vehicle-summary">
          <div class="vehicle-image">
            <img src="assests/images/vehicles/car.png" alt="Suzuki Alto K10">
          </div>
          <div class="vehicle-info">
            <h4>SUSUKI ALTO K10 - 1</h4>
            <p class="duration">2 DAYS</p>
            <p class="price">USD 58.00</p>
          </div>
        </div>

        <!-- Pick up Details -->
        <div class="details-section">
          <h4>Pick up Details</h4>
          <div class="detail-row">
            <div class="detail-item location">
              <i class="fas fa-plane"></i>
              <span>Katunayaka Airport</span>
            </div>
            <div class="detail-item datetime">
              <i class="fas fa-calendar"></i>
              <span>2026-01-13</span>
              <span class="time">10.45 PM</span>
            </div>
            <div class="price-badge">USD 45.50</div>
          </div>
        </div>

        <!-- Return Details -->
        <div class="details-section">
          <h4>Return Details</h4>
          <div class="detail-row">
            <div class="detail-item location">
              <i class="fas fa-plane"></i>
              <span>Katunayaka Airport</span>
            </div>
            <div class="detail-item datetime">
              <i class="fas fa-calendar"></i>
              <span>2026-01-13</span>
              <span class="time">10.45 PM</span>
            </div>
            <div class="price-badge">USD 45.50</div>
          </div>
        </div>

        </div>
      </div>

    </div>
  </div>

  <!-- Action Buttons -->
  <div class="action-buttons">
    <button type="button" class="btn-back" onclick="goBack()">Back</button>
    <button type="button" class="btn-next" onclick="goNext()">Next</button>
    <button type="button" class="btn-cancel" onclick="cancelForm()">Cancel</button>
  </div>
</div>

<script src="assests/js/step2.js"></script>
</body>
</html>
