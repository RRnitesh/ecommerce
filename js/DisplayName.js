document.addEventListener("DOMContentLoaded", () => {
  // Fetch session data to display the welcome message
  fetch("../php/DisplayName.php")
    .then((response) => response.json())
    .then((data) => {
      console.log("Session data:", data); // Debug response

      const welcomeMessage = document.getElementById("welcomeMessage");
      const loginButton = document.getElementById("login");
      const registerButton = document.getElementById("Signup");
      const logoutButton = document.getElementById("logout");
      const adminLoginButton = document.getElementById("adminlogin");
      const adminOrdersButton = document.getElementById("admin_orders");
      const viewCartButton = document.getElementById("view-cart");

      if (data.success) {
        // Admin-specific logic
        if (data.is_admin) {
          if (welcomeMessage) {
            welcomeMessage.innerHTML = data.user_name
              ? `HELLO ADMIN !!! ${data.user_name}`
              : `HELLO ADMIN !!!`;
          }

          // Show admin-specific buttons
          if (adminLoginButton) adminLoginButton.style.display = "none";
          if (adminOrdersButton) adminOrdersButton.style.display = "block";

          // Hide user-specific buttons
          if (loginButton) loginButton.style.display = "none";
          if (registerButton) registerButton.style.display = "none";
          if (viewCartButton) viewCartButton.style.display = "none";
        }
        // User-specific logic
        else {
          if (welcomeMessage) {
            welcomeMessage.innerHTML = data.user_name
              ? `Namaste!!! ${data.user_name}`
              : `Namaste!!!`;
          }

          // Hide admin-specific buttons
          if (adminLoginButton) adminLoginButton.style.display = "none";
          if (adminOrdersButton) adminOrdersButton.style.display = "none";

          // Show user-specific buttons
          if (viewCartButton) viewCartButton.style.display = "block";
        }

        // Common logic for both admin and user (logged-in users)
        if (loginButton) loginButton.style.display = "none";
        if (registerButton) registerButton.style.display = "none";
        if (logoutButton) logoutButton.style.display = "block";
      }
      // Non-logged-in users
      // else {
      //   if (welcomeMessage) {
      //     welcomeMessage.innerHTML = ""; // Clear welcome message
      //   }

      //   // Show login and register buttons
      //   if (loginButton) loginButton.style.display = "block";
      //   if (registerButton) registerButton.style.display = "block";

      //   // Hide other buttons
      //   if (logoutButton) logoutButton.style.display = "none";
      //   if (adminLoginButton) adminLoginButton.style.display = "none";
      //   if (adminOrdersButton) adminOrdersButton.style.display = "none";
      //   if (viewCartButton) viewCartButton.style.display = "none";
      // }
    })
    .catch((error) => {
      console.error("Error fetching session data:", error);
    });
});