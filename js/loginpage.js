document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.querySelector("#loginModal form");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const adminLoginButton = document.getElementById("adminlogin");
      const loginButton = loginForm.querySelector('button[type="submit"]');
      const loginErrorMessage = document.getElementById("loginErrorMessage");

      loginButton.disabled = true;
      loginButton.innerHTML = "Processing...";

      const formData = new FormData(loginForm);

      fetch("../php/login.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const loginModalEl = document.getElementById("loginModal");
            if (loginModalEl) {
              const loginModal = bootstrap.Modal.getInstance(loginModalEl);
              if (loginModal) {
                loginModal.hide();
              }
            }

            const welcomeMessage = document.getElementById("welcomeMessage");
            welcomeMessage.innerHTML = data.user_name
              ? `Namaste!!! ${data.user_name}`
              : `Namaste!!!`;

            if (adminLoginButton) adminLoginButton.style.display = "none";

            document.getElementById("login").style.display = "none";
            document.getElementById("Signup").style.display = "none";
            document.getElementById("logout").style.display = "block";

            loginForm.reset();
            loginButton.disabled = false;
            loginButton.innerHTML = "Login";

            if (loginErrorMessage) {
              loginErrorMessage.style.display = "none";
              loginErrorMessage.innerText = "";
            }
          } else {
            if (loginErrorMessage) {
              loginErrorMessage.style.display = "block";
              loginErrorMessage.innerText = data.message;
            }
            loginButton.disabled = false;
            loginButton.innerHTML = "Login";
          }
        })
        .catch((error) => {
          if (loginErrorMessage) {
            loginErrorMessage.style.display = "block";
            loginErrorMessage.innerText = `Unexpected error occurred: ${error.message}`;
          }
          loginForm.reset();
          loginButton.disabled = false;
          loginButton.innerHTML = "Login";
        });
    });
  }
});
