document.addEventListener("DOMContentLoaded", function () {
  const cartItemContainer = document.getElementById("cart-item-container");
  const totalAmountElement = document.getElementById("totalAmount");

  // Fetch order details
  fetch("../php/confirmationPHP.php")
    .then((response) => response.json())
    .then((data) => {
      console.log("API Response:", data);
      if (data.status && data.items.length > 0 && data.totalAmount) {
        // Populate total amount
        document.getElementById("total_amount").value = data.totalAmount;
        totalAmountElement.textContent = `Total Amount: NPR ${data.totalAmount}`;

        // Populate cart items
        data.items.forEach((item) => {
          const itemElement = document.createElement("div");
          itemElement.classList.add("cart-item");
          itemElement.innerHTML = `
            <img src="http://localhost/E-commerce/${item.image_url}" alt="${item.name}">
            <div class="item-details">
              <h3>${item.name}</h3>
              <p>${item.description}</p>
              <p>Quantity: ${item.quantity}</p>
            </div>
          `;
          cartItemContainer.appendChild(itemElement);
        });
      } else {
        alert("Sorry, no items found.");
      }
    })
    .catch((error) => {
      console.log("Error fetching confirmation data:", error);
    });

  // Event listener for "Pay Using eSewa" button
  document.querySelector(".pay-esewa").addEventListener("click", function () {
    const totalAmount = document.getElementById("total_amount").value;
    console.log("Total Amount:", totalAmount);

    const esewaForm = document.querySelector("form[action='../php/esewa.php']");
    if (esewaForm && totalAmount) {
      esewaForm.submit(); // Submit the form
    } else {
      console.error("Form or total amount is missing");
      alert("Something went wrong. Please try again.");
    }
  });

  // Event listener for "Go Back to View Cart"
  document.getElementById("backToViewCart").addEventListener("click", function () {
    fetch("../php/BackToViewCart.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          alert("Order is cancelled.");
          window.location.href = "../page/ViewMyCart.php";
        } else {
          alert("Sorry, something went wrong.");
        }
      })
      .catch((error) => {
        console.log("Error from deleting data:", error);
      });
  });
});
