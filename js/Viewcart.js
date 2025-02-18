document.addEventListener("DOMContentLoaded", function () {
  const cartitemcontainer = document.getElementById("cart-item-container");
  const subtotalElement = document.getElementById("subtotal");
  const itemCountElement = document.getElementById("item-count");
  const totalamountElement = document.getElementById("total");
  const locationElement = document.getElementById("location");
  const shippingCostElement = document.getElementById("shipping");
  const selectallcheckbox = document.getElementById("select-all");
  // const welcomeElement = document.getElementById("welcome");
  let shipping_cost = 0;
  let shippingAddress;

  // let isProcessing = false;

  document.getElementById("checkout-button").addEventListener("click", function () {
    const locationValue = locationElement.value;
    const itemCount = parseInt(itemCountElement.textContent);
    const shippingCost = parseFloat(shippingCostElement.textContent);
  
    if (!locationValue) {
      alert("Please select a location.");
      return;
    }
  
    if (itemCount <= 0) {
      alert("Your cart is empty. Add items to proceed.");
      return;
    }
  
    if (isNaN(shippingCost) || shippingCost <= 0) {
      alert("Shipping address is not set properly.");
      return;
    }
  
    // Collect selected cart items
    const cartItemsPhp = [];
    const selectedItems = document.querySelectorAll(".item-checkbox:checked");
  
    selectedItems.forEach((checkbox) => {
      const itemElement = checkbox.closest(".cart-item");
      const productId = itemElement.getAttribute("data-product-id");
      const quantity = itemElement.querySelector(".quantity").value;
      cartItemsPhp.push({ product_id: productId, quantity: quantity });
    });
  
    if (cartItemsPhp.length === 0) {
      alert("No items selected for checkout.");
      return;
    }
  
    // isProcessing = true; // Set the flag
    // this.disabled = true; // Disable the button
    // this.innerHTML = "Processing....";
    const totalAmount = totalamountElement.textContent;
  
    // Send data to PHP
    fetch("../php/process_checkout.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        totalAmount: totalAmount,
        cartItems: cartItemsPhp,
        shippingAddress: shippingAddress,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          alert("Order placed successfully!");
          window.location.href = "../page/Confimation_page.php";
        } else {
          alert("Failed to place the order. Please try again.");
          // this.disabled = false;
          // this.innerHTML = "PROCEED TO CHECKOUT";
          // isProcessing = false; // Reset the flag
        }
      })
      .catch((error) => {
        console.error("Error occurred during checkout:", error);
        // this.disabled = false;
        // this.innerHTML = "PROCEED TO CHECKOUT";
        // isProcessing = false; // Reset the flag
      });
  });
  
  
  const calculateSubTotal = () => {
    let subtotal = 0;
    let itemcount = 0;

    document.querySelectorAll(".item-checkbox").forEach((checkbox) => {
      if (checkbox.checked) {
        const itemElement = checkbox.closest(".cart-item");
        const price = parseFloat(
          itemElement
            .querySelector(".price")
            .textContent.replace("Rs.", "")
            .trim()
        );
        const quantity = parseInt(itemElement.querySelector(".quantity").value);

        subtotal += price * quantity;
        itemcount += quantity;
      }
    });

    const firstselect = locationElement.querySelector("option[value='']");
    if (itemcount === 0) {
      firstselect.selected = true;
      locationElement.disabled = true;
      shippingCostElement.textContent = "0.00";
      shipping_cost = 0;
    } else {
      locationElement.disabled = false;
      firstselect.selected = false;
    }

    subtotalElement.textContent = subtotal.toFixed(2);
    totalamountElement.textContent = (subtotal + shipping_cost).toFixed(2);
    itemCountElement.textContent = itemcount;
  };

  locationElement.addEventListener("change", (event) => {
    const shippingOption = event.target.options[event.target.selectedIndex];
    shippingAddress = shippingOption.textContent.trim();
    shipping_cost = parseFloat(event.target.value);
    shippingCostElement.textContent = shipping_cost.toFixed(2);
    calculateSubTotal();
  });

  selectallcheckbox.addEventListener("change", (e) => {
    const checkAllItem = document.querySelectorAll(".item-checkbox");
    checkAllItem.forEach((checkbox) => {
      checkbox.checked = e.target.checked;
    });
    calculateSubTotal();
  });



  fetch("../php/ViewCart.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status && data.result.length > 0) {
        data.result.forEach((item) => {
          const itemElement = document.createElement("div");
          itemElement.classList.add("cart-item");

          // Add a data attribute for the product ID
          itemElement.setAttribute("data-product-id", item.id);

          itemElement.innerHTML = `
            <input type="checkbox" class="item-checkbox">
            <img src="http://localhost/E-commerce/${item.image_url}" alt="${item.name}">
            <div class="item-details">
              <h3>${item.name}</h3>
              <p>${item.description}</p>
              <span class="price">Rs. ${item.price}</span>
              
              <button class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
            <div class="quantity-change">
              <button class="decrease">-</button>
              <input type="number" class="quantity" value="${item.quantity}" min="1">
              <button class="increase">+</button>
            </div>
          `;

          cartitemcontainer.appendChild(itemElement);
     
          itemElement.querySelector(".btn-danger").addEventListener("click", e => {
            const product_Id = itemElement.getAttribute("data-product-id");

            fetch("../php/deleteCartItemByButton.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({productId: product_Id}),
            })
              .then((response) => response.json())
              .then((data) => {
                if(data.status){
                  alert("item deleted successfully");
                  itemElement.remove();
                  calculateSubTotal();
                }else{
                  alert("failed to delete item");
                  return;
                }
              })
              .catch((error) => {
                console.log("error from deleting item ", error);
              })
          })
          
          itemElement
            .querySelector(".decrease")
            .addEventListener("click", (event) => {
              const quantity = event.target.nextElementSibling;
              quantity.value = Math.max(parseInt(quantity.value, 10) - 1, 1);
              calculateSubTotal();
            });

          itemElement
            .querySelector(".increase")
            .addEventListener("click", (event) => {
              const quantity = event.target.previousElementSibling;
              quantity.value = parseInt(quantity.value, 10) + 1;
              calculateSubTotal();
            });

          itemElement.querySelector(".quantity").addEventListener("input", calculateSubTotal);

          itemElement.querySelector(".item-checkbox").addEventListener("change", calculateSubTotal);
        });

        calculateSubTotal();
      } else {
        cartitemcontainer.innerHTML = "<p>No items in cart.</p>";
        locationElement.disabled = true;
        const message = document.getElementById("error");
        message.textContent = "Welcome to system. ";
        subtotalElement.textContent = "0.00";
        totalamountElement.textContent = "0.00";
        itemCountElement.textContent = "0";
      }
    })
    .catch((error) => {
      console.error("Error: ", error);
    });
});





