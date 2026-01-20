<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wizard – Check Availability</title>
  <link rel="stylesheet" href="assests/css/wizard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- STEP BAR -->
<div class="step-bar">
  <div class="step-item active">
    <div class="step-circle">1</div>
    <div class="step-line"></div>
    <span class="step-label">Check Availability</span>
  </div>
  <div class="step-item">
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

<!-- FORM CARD -->
<div class="card">
  <h2>Check Availability</h2>

  <form id="availabilityForm">
    <div class="grid">
      <div class="input-group">
        <i class="fas fa-search icon"></i>
        <input type="text" placeholder="Pickup location and city" name="pickup_location">
      </div>

      <div class="input-group">
        <i class="fas fa-location-arrow icon"></i>
        <input type="text" placeholder="Return location and city" name="return_location">
      </div>

      <div class="input-group">
        <input type="text" value="2 Days Only" readonly class="readonly-input">
      </div>

      <div class="input-group">
        <i class="fas fa-calendar icon"></i>
        <input type="text" placeholder="Pickup Date" name="pickup_date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
      </div>

      <div class="input-group">
        <i class="fas fa-calendar icon"></i>
        <input type="text" placeholder="Return Date" name="return_date" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
      </div>

      <div class="input-group counter">
        <span class="counter-label">No of Vehicles</span>
        <div class="counter-controls">
          <button type="button" onclick="decrease()" class="counter-btn">−</button>
          <input type="text" id="count" value="0" readonly class="counter-value">
          <button type="button" onclick="increase()" class="counter-btn">+</button>
        </div>
      </div>
    </div>

    <h3>Select Your Vehicle Type</h3>

    <div class="vehicles-container">
      <button type="button" class="nav-btn nav-left" onclick="scrollVehicles(-1)">‹</button>
      
      <div class="vehicles" id="vehiclesList">
        <div class="vehicle" onclick="selectVehicle(this)">
          <img src="assests/images/vehicles/tuktuk.png" alt="TUKTUK">
          <p>TUKTUK (200cc) - Bajaj</p>
        </div>

        <div class="vehicle" onclick="selectVehicle(this)">
          <img src="assests/images/vehicles/bike.png" alt="MOTORBIKE">
          <p>MOTORBIKE (150cc) - Yamaha FZ</p>
        </div>

        <div class="vehicle" onclick="selectVehicle(this)">
          <img src="assests/images/vehicles/scooter.png" alt="SCOOTER">
          <p>SCOOTER (125cc) - TVS Ntorq / Yamaha Ray ZR</p>
        </div>

        <div class="vehicle" onclick="selectVehicle(this)">
          <img src="assests/images/vehicles/scooter2.png" alt="SCOOTER">
          <p>SCOOTER (110cc) - Honda Dio without Insurance</p>
        </div>

        <div class="vehicle" onclick="selectVehicle(this)">
          <img src="assests/images/vehicles/car.png" alt="CAR">
          <p>SUZUKI WAGON R</p>
        </div>
      </div>

      <button type="button" class="nav-btn nav-right" onclick="scrollVehicles(1)">›</button>
    </div>

    <div class="actions">
      <button type="submit" class="btn-search">Search</button>
      <button type="button" class="btn-cancel" onclick="cancelForm()">Cancel</button>
    </div>
  </form>
</div>

<script src="assests/js/form.js"></script>
</body>
</html>
