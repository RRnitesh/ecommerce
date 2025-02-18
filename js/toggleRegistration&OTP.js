document.addEventListener('DOMContentLoaded', function () {
  const registrationForm = document.getElementById('registrationForm');

  if (registrationForm) {
    registrationForm.addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent the form from submitting normally

      const submitButton = registrationForm.querySelector('button[type="submit"]');
      submitButton.disabled = true; // Disable the button
      submitButton.innerText = 'Processing...'; // Optional: Change the button text

      const formData = new FormData(this);

      fetch('../php/generateOTP&Send.php', {
        method: 'POST',
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Hide the registration modal
            const registrationModalEl = document.getElementById('registrationModal');
            if (registrationModalEl) {
              const registrationModal = bootstrap.Modal.getInstance(registrationModalEl);
              if (registrationModal) {
                registrationModal.hide();
              }
            }

            // Show the OTP modal
            const otpModalEl = document.getElementById('otpModal');
            if (otpModalEl) {
              const otpModal = new bootstrap.Modal(otpModalEl, { backdrop: 'static', keyboard: false });
              otpModal.show();
            }
          } else {
            // Show the error message from the backend
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert(`toggleRegistration An unexpected error occurred: ${error.message}`);
        });
    });
  } 
  else {
    console.error("Registration form element not found.");
  }
});
