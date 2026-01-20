// Initialize counters
let licenseCount = 1;
let passengerCount = 1;

// License counter functions
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

// Passenger counter functions
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

// Navigation functions
function goBack() {
  window.location.href = 'index.php';
}

function goNext() {
  // Get form data
  const form = document.getElementById('nohaServiceForm');
  const formData = {
    licenses: licenseCount,
    passengers: passengerCount,
    airportDropoff: form.querySelector('input[name="airport_dropoff"]').checked,
    airportPickup: form.querySelector('input[name="airport_pickup"]').checked,
    trainTransfer: form.querySelector('input[name="train_transfer"]:checked')?.value || null
  };
  
  // Validation
  if (passengerCount > 0) {
    const hasAirportService = formData.airportDropoff || formData.airportPickup;
    if (!hasAirportService) {
      const confirmProceed = confirm('You have selected passengers but no airport service. Do you want to continue?');
      if (!confirmProceed) return;
    }
  }
  
  // Store data in sessionStorage
  sessionStorage.setItem('step2Data', JSON.stringify(formData));
  
  // Navigate to step 3
  window.location.href = 'step3.php';
}

function cancelForm() {
  if (confirm('Are you sure you want to cancel? All progress will be lost.')) {
    // Clear all stored data
    sessionStorage.clear();
    window.location.href = 'index.php';
  }
}

// Load saved data on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check if there's saved data
  const savedData = sessionStorage.getItem('step2Data');
  if (savedData) {
    const data = JSON.parse(savedData);
    
    // Restore counters
    licenseCount = data.licenses || 1;
    passengerCount = data.passengers || 1;
    document.getElementById('licenseCount').value = licenseCount;
    document.getElementById('passengerCount').value = passengerCount;
    
    // Restore checkboxes
    const form = document.getElementById('nohaServiceForm');
    if (data.airportDropoff) {
      form.querySelector('input[name="airport_dropoff"]').checked = true;
    }
    if (data.airportPickup) {
      form.querySelector('input[name="airport_pickup"]').checked = true;
    }
    
    // Restore radio selection
    if (data.trainTransfer) {
      const radio = form.querySelector(`input[name="train_transfer"][value="${data.trainTransfer}"]`);
      if (radio) radio.checked = true;
    }
  }
  
  // Load step 1 data if available
  const step1Data = sessionStorage.getItem('step1Data');
  if (step1Data) {
    // You can use this to populate the rental details summary
    const data = JSON.parse(step1Data);
    // Update the summary card with actual data if needed
  }
});
