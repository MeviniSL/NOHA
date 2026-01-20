let count = 0;

function increase() {
  count++;
  document.getElementById("count").value = count;
}

function decrease() {
  if (count > 0) {
    count--;
    document.getElementById("count").value = count;
  }
}

function selectVehicle(element) {
  // Remove active class from all vehicles
  const vehicles = document.querySelectorAll('.vehicle');
  vehicles.forEach(v => v.classList.remove('active'));
  
  // Add active class to clicked vehicle
  element.classList.add('active');
}

function scrollVehicles(direction) {
  const container = document.getElementById('vehiclesList');
  const scrollAmount = 250;
  container.scrollLeft += direction * scrollAmount;
}

function cancelForm() {
  if (confirm('Are you sure you want to cancel?')) {
    document.getElementById('availabilityForm').reset();
    count = 0;
    document.getElementById('count').value = count;
    
    // Remove active class from all vehicles
    const vehicles = document.querySelectorAll('.vehicle');
    vehicles.forEach(v => v.classList.remove('active'));
  }
}

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('availabilityForm');
  
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Get form values
      const pickupLocation = form.pickup_location.value;
      const returnLocation = form.return_location.value;
      const pickupDate = form.pickup_date.value;
      const returnDate = form.return_date.value;
      const vehicleCount = document.getElementById('count').value;
      const selectedVehicle = document.querySelector('.vehicle.active');
      
      // Basic validation
      if (!pickupLocation || !returnLocation) {
        alert('Please enter pickup and return locations');
        return;
      }
      
      if (!pickupDate || !returnDate) {
        alert('Please select pickup and return dates');
        return;
      }
      
      if (vehicleCount == 0) {
        alert('Please select at least one vehicle');
        return;
      }
      
      if (!selectedVehicle) {
        alert('Please select a vehicle type');
        return;
      }
      
      // Form is valid, proceed to next step
      console.log('Form submitted successfully');
      // window.location.href = 'step2.php';
    });
  }
});
