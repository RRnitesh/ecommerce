document.addEventListener("DOMContentLoaded", function () {
  // OTP Submit Button Click Listener
  const otpSubmitButton = document.getElementById("otpSubmitButton");

  if (otpSubmitButton) {
    otpSubmitButton.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent form submission
      otpVerificationFetch();
    });
  }

  // Close Button Click Listener for Modal
  const closeButtons = document.querySelectorAll(".closeModalButton");

  closeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Trigger fetch to delete user data when modal is closed
      fetch("../php/deleteWhenModalClose.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({}), // Send any necessary data (e.g., session info)
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Registration canceled, user data deleted.");
          } else {
            alert("Failed to delete user data.");
          }
        })
        .catch((error) =>
          console.error("Error during modal close fetch:", error)
        );
    });
  });
});

function otpVerificationFetch() {
  const otpInput = document.getElementById("otpGivenbyUser");
  const otpErrorMessage = document.getElementById("otpErrorMessage");
  const otpSubmitButton = document.getElementById("otpSubmitButton");

  // Hide error message initially
  otpErrorMessage.style.display = "none";
  otpErrorMessage.innerText = "";

  const otp = otpInput.value;

  if (!otp) {
    otpErrorMessage.style.display = "block";
    otpErrorMessage.innerText = "Please enter the OTP.";
    return;
  }

  // Disable button during processing
  otpSubmitButton.disabled = true;
  otpSubmitButton.innerText = "Verifying...";

  fetch("../php/OTPverification.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ otp: otp }),
  })
    .then((response) => response.json())
    .then((data) => {
      otpSubmitButton.disabled = false;
      otpSubmitButton.innerText = "Submit";

      if (data.success) {
        const otpModalEl = document.getElementById("otpModal");
        const otpModal = bootstrap.Modal.getInstance(otpModalEl);

        // Hide OTP modal on success
        otpModal.hide();

        // Toggle visibility of elements
        document.querySelectorAll("#login, #Signup, #adminlogin, #admin_orders")
        .forEach(el => el.style.display = "none");
              document.getElementById("logout").style.display = "block";
              document.getElementById("view-cart").style.display = "block";
        // Display welcome message
        const welcomeMessage = document.getElementById("welcomeMessage");
        if (welcomeMessage) {
          welcomeMessage.innerHTML = data.username
            ? `Namaste!!! ${data.username}`
            : `Namaste!!!`;
        }

        // Reset registration form
        const registrationForm = document.getElementById("registrationForm");
        registrationForm.reset();

        // Re-enable the submit button
        const submitButton = registrationForm.querySelector(
          'button[type="submit"]'
        );
        submitButton.disabled = false;
        submitButton.innerText = "Submit";
      } else {
        // Handle errors
        otpInput.value = "";
        otpInput.focus();
        otpErrorMessage.style.display = "block";
        otpErrorMessage.innerText = data.message;
      }
    })
    .catch((error) => {
      otpSubmitButton.disabled = false;
      otpSubmitButton.innerText = "Submit";
      console.error("Error during OTP verification:", error);
      otpErrorMessage.style.display = "block";
      otpErrorMessage.innerText = `Unexpected error occurred: ${error.message}`;
    });
}
