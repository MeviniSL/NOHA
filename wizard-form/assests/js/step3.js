// Apply coupon function
function applyCoupon() {
  const couponCode = document.getElementById('couponCode').value.trim();
  
  if (!couponCode) {
    alert('Please enter a coupon code');
    return;
  }
  
  // Here you would normally validate the coupon code with your backend
  // For now, we'll just show a message
  alert('Coupon code "' + couponCode + '" applied');
  
  // You can add logic here to update the discount
  // For example:
  // const discount = getDiscountForCoupon(couponCode);
  // updatePrices(discount);
}

// Calculate totals
function calculateTotals() {
  const rentalFee = 58.00;
  const pickupFee = 45.00;
  const returnFee = 45.00;
  const insuranceFee = 0.00;
  const securityDeposit = 300.00;
  
  const subtotal = rentalFee + pickupFee + returnFee + insuranceFee;
  const discount = 0.00; // Would be calculated based on coupon
  const total = subtotal + securityDeposit - discount;
  
  document.getElementById('subtotalValue').textContent = subtotal.toFixed(2);
  document.getElementById('discountValue').textContent = discount.toFixed(2);
  document.getElementById('depositValue').textContent = securityDeposit.toFixed(2);
  document.getElementById('totalValue').textContent = total.toFixed(2);
}

// Navigation functions
function goBack() {
  window.location.href = 'step2.php';
}

function submitBooking() {
  const form = document.getElementById('customerForm');
  const fullName = form.fullName.value.trim();
  const country = form.country.value.trim();
  const whatsapp = form.whatsapp.value.trim();
  const email = form.email.value.trim();
  
  // Validation
  if (!fullName) {
    alert('Please enter your full name');
    return;
  }
  
  if (!country) {
    alert('Please select your country');
    return;
  }
  
  if (!whatsapp) {
    alert('Please enter your WhatsApp number');
    return;
  }
  
  if (!email) {
    alert('Please enter your email address');
    return;
  }
  
  if (!validateEmail(email)) {
    alert('Please enter a valid email address');
    return;
  }
  
  // Prepare booking data
  const bookingData = {
    fullName: fullName,
    country: country,
    whatsapp: whatsapp,
    email: email,
    comments: form.comments.value.trim(),
    rentalFee: 58.00,
    locationFee: 90.00,
    serviceFee: 0.00,
    discount: 0.00,
    subtotal: 148.00,
    securityDeposit: 300.00,
    totalAmount: 448.00,
    bookingDate: new Date().toISOString()
  };
  
  // Store booking data
  sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
  
  // Submit booking (you would send this to your backend)
  console.log('Booking submitted:', bookingData);
  
  // Show success message
  alert('Your booking has been submitted successfully!\n\nBooking Reference: #' + generateBookingReference());
  
  // Clear form and redirect
  // window.location.href = 'confirmation.php';
}

function cancelBooking() {
  if (confirm('Are you sure you want to cancel this booking? All progress will be lost.')) {
    sessionStorage.clear();
    window.location.href = 'index.php';
  }
}

// Email validation
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Generate booking reference
function generateBookingReference() {
  return 'BK' + Date.now() + Math.floor(Math.random() * 1000);
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
  // Initialize totals
  calculateTotals();
  
  // Load previous step data if available
  const step1Data = sessionStorage.getItem('step1Data');
  const step2Data = sessionStorage.getItem('step2Data');
  
  if (step1Data) {
    console.log('Step 1 Data:', JSON.parse(step1Data));
  }
  
  if (step2Data) {
    console.log('Step 2 Data:', JSON.parse(step2Data));
  }
  
  // Auto-save customer details
  const customerForm = document.getElementById('customerForm');
  if (customerForm) {
    customerForm.addEventListener('change', function() {
      const formData = new FormData(customerForm);
      const data = Object.fromEntries(formData);
      sessionStorage.setItem('customerData', JSON.stringify(data));
    });
    
    // Load saved customer data
    const savedCustomerData = sessionStorage.getItem('customerData');
    if (savedCustomerData) {
      const data = JSON.parse(savedCustomerData);
      Object.keys(data).forEach(key => {
        const field = customerForm.querySelector(`[name="${key}"]`);
        if (field) field.value = data[key];
      });
    }
  }
});
