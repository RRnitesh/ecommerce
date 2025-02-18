document.addEventListener("DOMContentLoaded", function () {
  // Hide elements initially
  document.getElementById("logout").style.display = "none";
  document.getElementById("admin_orders").style.display = "none";
  
  const loginModal = new bootstrap.Modal(document.getElementById("loginModalAdmin"));
  const otpModal = new bootstrap.Modal(document.getElementById("otpModalAdmin"));
  const loginForm = document.getElementById("loginFormAdmin");
  const otpForm = document.getElementById("otpFormAdmin");
  const loginErrorMessage = document.getElementById("loginErrorMessageAdmin");
  const otpErrorMessage = document.getElementById("otpErrorMessageAdmin");

  // Handle login form submission
  loginForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const loginButton = loginForm.querySelector('button[type="submit"]');
    
    // Disable login button during processing
    loginButton.disabled = true;
    loginButton.innerHTML = "Processing...";

    const formData = new FormData(loginForm);

    fetch("../php/adminLogin.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        loginModal.hide();
        otpModal.show(); // Show OTP modal after successful credential check
      } else {
        loginErrorMessage.style.display = "block";
        loginErrorMessage.textContent = data.message;
      }
    })
    .catch(error => {
      loginErrorMessage.style.display = "block";
      loginErrorMessage.textContent = "Connection error: " + error.message;
    })
    .finally(() => {
      loginButton.disabled = false;
      loginButton.innerHTML = "Login";
    });
  });

  //Handle OTP form submission
  otpForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const otpSubmitButton = otpForm.querySelector('button[type="submit"]');
    const otpInput = document.getElementById("otpGivenbyUserAdmin");

    // Basic OTP validation
    if (!otpInput.value.trim()) {
      otpErrorMessage.style.display = "block";
      otpErrorMessage.textContent = "Please enter the OTP";
      return;
    }

    // Disable button during processing
    otpSubmitButton.disabled = true;
    otpSubmitButton.innerHTML = "Verifying...";

    fetch("../php/OTPverificationAdmin.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ otp: otpInput.value }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.status) {
        otpModal.hide();
        
        // Update UI for logged-in admin
        document.getElementById("welcomeMessage").textContent = 
          data.user_name ? `Hello ADMIN! ${data.user_name}` : "Welcome Admin!";
        
        // Toggle visibility of elements
        document.querySelectorAll("#login, #Signup, #view-cart, #adminlogin")
          .forEach(el => el.style.display = "none");
        document.getElementById("logout").style.display = "block";
        document.getElementById("admin_orders").style.display = "block";
      } else {
        otpErrorMessage.style.display = "block";
        otpErrorMessage.textContent = data.message;
      }
    })
    .catch(error => {
      otpErrorMessage.style.display = "block";
      otpErrorMessage.textContent = "Verification error: " + error.message;
    })
    .finally(() => {
      otpSubmitButton.disabled = false;
      otpSubmitButton.innerHTML = "Verify OTP";
    });
  });
});