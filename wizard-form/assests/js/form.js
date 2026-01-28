let count = 1;
let selectedVehicleId = null;
const allowedLocations = ['Colombo','Mirissa','Katunayaka Airport','Sigiriya','Nuwara Eliya'];

function isMobile() {
  return window.matchMedia('(max-width: 600px)').matches;
}

/* =====================
   SIMPLE LOCATION FILTER (type-ahead hint)
===================== */
function normalizeLocationQuery(s) {
  return s.toLowerCase().replace(/[^a-z\s]+/g, ' ').replace(/\s+/g, ' ').trim();
}

function getLocationMatches(query) {
  const q = normalizeLocationQuery(query);
  if (!q) return [];
  const max = isMobile() ? 1 : 3;
  return allowedLocations.filter(loc => loc.toLowerCase().includes(q)).slice(0, max);
}

function ensureHintElement(input) {
  if (input._hintEl) return input._hintEl;
  const hint = document.createElement('div');
  hint.style.fontSize = isMobile() ? '10px' : '12px';
  hint.style.color = '#666';
  hint.style.marginTop = isMobile() ? '2px' : '4px';
  hint.style.minHeight = isMobile() ? '12px' : '16px';
  hint.style.lineHeight = '1.2';
  hint.style.whiteSpace = 'nowrap';
  hint.style.overflow = 'hidden';
  hint.style.textOverflow = 'ellipsis';
  hint.style.maxWidth = '100%';
  hint.style.userSelect = 'none';
  hint.style.pointerEvents = 'none';
  const group = input.closest('.input-group') || input.parentElement;
  group.appendChild(hint);
  input._hintEl = hint;
  return hint;
}

function updateLocationHint(input) {
  const hint = ensureHintElement(input);
  const val = input.value;
  if (!val) { hint.textContent = ''; return; }
  const matches = getLocationMatches(val);
  if (matches.length) {
    hint.textContent = isMobile() ? ('Try: ' + matches[0]) : ('Matches: ' + matches.join(' | '));
  } else {
    hint.textContent = 'No match';
  }
}

/* =====================
   VEHICLE COUNT
===================== */
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

/* =====================
   VEHICLE SELECTION
===================== */
function selectVehicle(element) {
  const vehicles = document.querySelectorAll('.vehicle');
  vehicles.forEach(v => v.classList.remove('active'));

  element.classList.add('active');

  // Get vehicle ID from data attribute
  const vehicleId = element.getAttribute('data-id');
  selectedVehicleId = vehicleId;
  console.log('Vehicle selected:', vehicleId, element);
}

/* =====================
   VEHICLE SCROLL
===================== */
function scrollVehicles(direction) {
  const container = document.getElementById('vehiclesList');
  container.scrollLeft += direction * 250;
}

/* =====================
   CANCEL FORM
===================== */
function cancelForm() {
  if (confirm('Are you sure you want to cancel?')) {
    document.getElementById('availabilityForm').reset();
    count = 0;
    selectedVehicleId = null;
    document.getElementById('count').value = count;

    document.querySelectorAll('.vehicle').forEach(v =>
      v.classList.remove('active')
    );
  }
}

/* =====================
   FORM SUBMIT
===================== */
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('availabilityForm');
  const pickupInput = form ? form.querySelector('input[name="pickup_location"]') : null;
  const returnInput = form ? form.querySelector('input[name="return_location"]') : null;

  // Initialize vehicle count to 1 for smoother flow
  const countInput = document.getElementById('count');
  if (countInput) {
    countInput.value = count;
  }

  // Auto-select first vehicle for faster navigation
  const firstVehicle = document.querySelector('.vehicle');
  if (firstVehicle) {
    selectVehicle(firstVehicle);
  }

  if (!form) return;

  // Attach live filtering hints
  if (pickupInput) {
    pickupInput.addEventListener('input', () => updateLocationHint(pickupInput));
  }
  if (returnInput) {
    returnInput.addEventListener('input', () => updateLocationHint(returnInput));
  }

  // Adjust hint size dynamically on resize/orientation changes
  window.addEventListener('resize', () => {
    if (pickupInput && pickupInput._hintEl) {
      pickupInput._hintEl.style.fontSize = isMobile() ? '10px' : '12px';
      pickupInput._hintEl.style.marginTop = isMobile() ? '2px' : '4px';
      pickupInput._hintEl.style.minHeight = isMobile() ? '12px' : '16px';
      updateLocationHint(pickupInput);
    }
    if (returnInput && returnInput._hintEl) {
      returnInput._hintEl.style.fontSize = isMobile() ? '10px' : '12px';
      returnInput._hintEl.style.marginTop = isMobile() ? '2px' : '4px';
      returnInput._hintEl.style.minHeight = isMobile() ? '12px' : '16px';
      updateLocationHint(returnInput);
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const pickupLocation = form.pickup_location.value.trim();
    const returnLocation = form.return_location.value.trim();
    const pickupDate = form.pickup_date.value;
    const returnDate = form.return_date.value;
    const vehicleCount = document.getElementById('count').value;

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
    if (!selectedVehicleId) {
      alert('Please click on a vehicle to select it');
      return;
    }

    // Send to PHP
    const formData = new FormData();
    formData.append('pickup_location', pickupLocation);
    formData.append('return_location', returnLocation);
    formData.append('pickup_date', pickupDate);
    formData.append('return_date', returnDate);
    formData.append('vehicle_count', vehicleCount);
    formData.append('vehicle_id', selectedVehicleId);

    fetch('save-step1.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data && data.success) {
        window.location.href = 'step2.php';
      } else {
        alert('Booking is not available to this requirements');
      }
    })
    .catch(() => {
      alert('Booking is not available to this requirements');
    });
  });
});
