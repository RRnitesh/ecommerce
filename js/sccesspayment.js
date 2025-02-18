document.addEventListener("DOMContentLoaded", function() {

  // Extract userid from the query string
  const urlParams = new URLSearchParams(window.location.search);
  const userid = urlParams.get("userid");
  const orderid = urlParams.get("orderid");

  if (userid && orderid) {
    // Send the userid to the backend using fetch
  fetch("../php/successPaymentUpdate.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ userid, orderid }),
  })
  .then((response) => response.json())
  .then((data) => {
    const messageSpan = document.getElementById("paymentStatusMessage"); // Replace with the correct ID of your span element

    if (data.status) {
      messageSpan.textContent = data.message; // Display success message
      messageSpan.style.color = "green"; // Optional: style success message (e.g., green color)
    } else {
      messageSpan.textContent = data.message; // Display error message
      messageSpan.style.color = "red"; // Optional: style error message (e.g., red color)
    }
  })
  .catch((error) => {
    console.log("error from success model ", error);
  });
}else{
  console.error("User ID not found in URL or order id not set properly. ");
}
});
