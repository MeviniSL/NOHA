<?php
if (function_exists('opcache_reset')) { opcache_reset(); }
session_start();
require 'config/api.php';

// ALWAYS use fallback vehicles - API is not responding
$vehicles = [
        ['id' => 1, 'name' => 'TUKTUK (200cc)', 'engine' => 'Bajaj', 'image' => 'assests/images/vehicles/tuktuk.jpg'],
        ['id' => 2, 'name' => 'MOTORBIKE (150cc)', 'engine' => 'Yamaha FZ', 'image' => 'assests/images/vehicles/motorbike.jpg'],
        ['id' => 3, 'name' => 'SCOOTER (125cc)', 'engine' => 'TVS Ntorq / Yamaha Ray ZR', 'image' => 'assests/images/vehicles/scooter125.jpg'],
        ['id' => 4, 'name' => 'SCOOTER (110cc)', 'engine' => 'Honda Dio without Insurance', 'image' => 'assests/images/vehicles/scooter110.jpg'],
        ['id' => 5, 'name' => 'SUZUKI WAGON R', 'engine' => '', 'image' => 'assests/images/vehicles/waganr.jpg'],
        ['id' => 6, 'name' => 'TOYOTA AQUA', 'engine' => '', 'image' => 'assests/images/vehicles/aqua.jpg'],
        ['id' => 7, 'name' => 'TOYOTA PRIUS', 'engine' => '', 'image' => 'assests/images/vehicles/prius.jpg'],
        ['id' => 8, 'name' => 'SUZUKI ALTO K10', 'engine' => '', 'image' => 'assests/images/vehicles/alto.jpg']
];

// ALWAYS use fallback locations
$locations = ['Colombo', 'Kandy', 'Benthota', 'Ahangama', 'Nuwara Eliya'];
?>

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

      <!-- PICKUP LOCATION -->
      <div class="input-group">
        <i class="fas fa-search icon"></i>
        <input list="allowedLocations" name="pickup_location" placeholder="Pickup location and city">
        <datalist id="allowedLocations">
          <option value="Colombo">
          <option value="Mirissa">
          <option value="Katunayaka Airport">
          <option value="Sigiriya">
          <option value="Nuwara Eliya">
        </datalist>
      </div>

      <!-- RETURN LOCATION -->
      <div class="input-group">
        <i class="fas fa-location-arrow icon"></i>
        <input list="allowedLocations" name="return_location" placeholder="Return location and city">
      </div>

      <!-- DAYS -->
      <div class="input-group">
        <input type="text" value="2 Days Only" readonly class="readonly-input">
      </div>

      <!-- PICKUP DATE -->
      <div class="input-group">
        <i class="fas fa-calendar icon"></i>
        <input type="text" placeholder="Pickup Date" name="pickup_date"
               onfocus="this.type='date'"
               onblur="if(!this.value)this.type='text'">
      </div>

      <!-- RETURN DATE -->
      <div class="input-group">
        <i class="fas fa-calendar icon"></i>
        <input type="text" placeholder="Return Date" name="return_date"
               onfocus="this.type='date'"
               onblur="if(!this.value)this.type='text'">
      </div>

      <!-- VEHICLE COUNT -->
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
        <?php if (!empty($vehicles)): ?>
          <?php foreach ($vehicles as $v): ?>
            <div class="vehicle"
                 data-id="<?= (int)$v['id'] ?>"
                 onclick="selectVehicle(this)">
              <img src="<?= htmlspecialchars($v['image']) ?>"
                   alt="<?= htmlspecialchars($v['name']) ?>">
              <p><?= htmlspecialchars($v['name']) ?>
                <?php if (!empty($v['engine'])): ?>
                  (<?= htmlspecialchars($v['engine']) ?>)
                <?php endif; ?>
              </p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="padding:20px;">No vehicles available</p>
        <?php endif; ?>
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
