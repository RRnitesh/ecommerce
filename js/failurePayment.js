document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const userid = urlParams.get("userid");
  const orderid = urlParams.get("orderid");
  
  // // Check if the user has visited the failed payment page before
  // if (sessionStorage.getItem("paymentFailed")) {
  //   // Redirect the user to the index page
  //   window.location.href = "../page/index.php";
  //   return; // Stop further script execution
  // }

  // Store the flag in sessionStorage to track the visit
  // sessionStorage.setItem("paymentFailed", "true");

  if (userid && orderid) {
    // Fetch request to update the payment status
    fetch("../php/failPaymentUpdate.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ userid, orderid }),
    })
      .then((response) => response.json())
      .then((data) => {
        const messageSpan = document.getElementById("paymentStatusMessage");

        if (data.status) {
          messageSpan.textContent = data.message; // Display success message
          messageSpan.style.color = "green";
        } else {
          messageSpan.textContent = data.message; // Display error message
          messageSpan.style.color = "red";
        }
      })
      .catch((error) => {
        console.log("Error from failPaymentUpdate:", error);
      });
  } else {
    console.log("User ID not found");
  }
});
