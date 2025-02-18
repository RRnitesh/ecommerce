document.addEventListener('DOMContentLoaded', function () {
document.querySelectorAll(".add-to-cart").forEach(button => {
  button.addEventListener("click", function () {

    const prodId = this.getAttribute("data-product-id");
    const quantity = 1;

    // Disable the current button and show loading feedback
    this.disabled = true;
    this.textContent = "Adding...";

    fetch("../php/addToCart.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `product_id=${encodeURIComponent(prodId)}&quantity=${encodeURIComponent(quantity)}`
    })
      .then(response => {
        console.log("Response status:", response.status);
        if (response.status === 401) {
          showErrorModal("Please, login to system");
          return null; // Prevent further processing
        }
        return response.json();
      })
      .then(data => {
        if (data) {
          if (data.status) {
            showSuccessModal(data.message);
          } else {
            showErrorModal(data.message);
          }
        }
      })
      .catch(error => {
        console.error("Error:", error);
      })
      .finally(() => {
        // Re-enable the button and reset its text
        this.disabled = false;
        this.textContent = "Add to Cart";
      });
  });
});
});
function showSuccessModal(message){
  document.getElementById("show-success-message").innerText = message;
  const successmodal = new bootstrap.Modal(document.getElementById("successModal"));
  successmodal.show();
}
function showErrorModal(message) {
  document.getElementById("errorModalMessage").innerText = message;
  
  const errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
  errorModal.show();
  
  document.getElementById("errorModal").addEventListener("hidden.bs.modal", function () {
    window.location.href = '../page/index.php';
  });
}
