/* =========================
   APPLY COUPON (BACKEND)
========================= */
function applyCoupon() {
  const couponCode = document.getElementById('couponCode').value.trim();

  if (!couponCode) {
    alert('Please enter a coupon code');
    return;
  }

  const formData = new FormData();
  formData.append('coupon', couponCode);

  fetch('apply-coupon.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || 'Invalid coupon');
        return;
      }

      // Update UI with backend values
      document.getElementById('discountValue').textContent = data.discount.toFixed(2);
      document.getElementById('subtotalValue').textContent = data.sub_total.toFixed(2);
      document.getElementById('totalValue').textContent = data.grand_total.toFixed(2);

      alert('Coupon applied successfully!');
    })
    .catch(() => {
      alert('Failed to apply coupon. Please try again.');
    });
}

/* =========================
   NAVIGATION
========================= */
function goBack() {
  window.location.href = 'step2.php';
}

function cancelBooking() {
  if (confirm('Are you sure you want to cancel this booking?')) {
    window.location.href = 'index.php';
  }
}

/* =========================
   SUBMIT BOOKING
========================= */
function submitBooking() {
  const form = document.getElementById('customerForm');

  const fullName = form.fullName.value.trim();
  const country  = form.country.value.trim();
  const whatsapp = form.whatsapp.value.trim();
  const email    = form.email.value.trim();

  // ================= VALIDATION =================
  if (!fullName || !country || !whatsapp || !email) {
    alert('Please fill in all required fields');
    return;
  }

  if (!validateEmail(email)) {
    alert('Please enter a valid email address');
    return;
  }

  // ================= SEND TO BACKEND =================
  const formData = new FormData(form);

  fetch('submit-booking.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert('Booking Failed!\n\n' + (data.message || 'Unknown error occurred'));
        return;
      }

      alert(
        'Booking successful!\n\n' +
        'Booking Reference: ' + data.reference
      );

      // Optional redirect
      // window.location.href = 'confirmation.php';
    })
    .catch((error) => {
      console.error('Error:', error);
      alert('Something went wrong. Please try again.\n\nError: ' + error.message);
    });
}

/* =========================
   EMAIL VALIDATION
========================= */
function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
