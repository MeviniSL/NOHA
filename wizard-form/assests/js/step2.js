// =====================
// INITIAL COUNTERS
// =====================
let licenseCount = 1;
let passengerCount = 1;

// =====================
// LICENSE COUNTER
// =====================
function increaseLicense() {
  licenseCount++;
  document.getElementById('licenseCount').value = licenseCount;
}

function decreaseLicense() {
  if (licenseCount > 0) {
    licenseCount--;
    document.getElementById('licenseCount').value = licenseCount;
  }
}

// =====================
// PASSENGER COUNTER
// =====================
function increasePassenger() {
  passengerCount++;
  document.getElementById('passengerCount').value = passengerCount;
}

function decreasePassenger() {
  if (passengerCount > 0) {
    passengerCount--;
    document.getElementById('passengerCount').value = passengerCount;
  }
}

// =====================
// NAVIGATION
// =====================
function goBack() {
  window.location.href = 'index.php';
}

function cancelForm() {
  if (confirm('Are you sure you want to cancel? All progress will be lost.')) {
    window.location.href = 'index.php';
  }
}

// =====================
// NEXT STEP (SAVE → STEP 3)
// =====================
function goNext() {
  const form = document.getElementById('nohaServiceForm');

  const airportDropoff = form.querySelector('input[name="airport_dropoff"]').checked;
  const airportPickup  = form.querySelector('input[name="airport_pickup"]').checked;
  const trainTransfer  = form.querySelector('input[name="train_transfer"]:checked');

  // =====================
  // VALIDATION
  // =====================
  if (passengerCount > 0 && !airportDropoff && !airportPickup) {
    const proceed = confirm(
      'You have selected passengers but no airport service.\nDo you want to continue?'
    );
    if (!proceed) return;
  }

  // =====================
  // SEND TO PHP (SESSION)
  // =====================
  const formData = new FormData();
  formData.append('licenses', licenseCount);
  formData.append('passengers', passengerCount);
  formData.append('airport_dropoff', airportDropoff ? 1 : 0);
  formData.append('airport_pickup', airportPickup ? 1 : 0);
  formData.append('train_transfer', trainTransfer ? trainTransfer.value : '');

  fetch('save-step2.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data && data.success) {
        window.location.href = 'step3.php';
      } else {
        alert('Something went wrong. Please try again.');
      }
    })
    .catch(() => {
      alert('Something went wrong. Please try again.');
    });
}
