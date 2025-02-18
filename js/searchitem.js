document.addEventListener("DOMContentLoaded", function () {
  // Fetch items from the API

  const urlParam = new URLSearchParams(window.location.search);
  const query = urlParam.get("query");
  const category = urlParam.get("category");

    // Construct the API URL based on query or category
    let apiUrl = "../php/fetchitem.php";
    if (query) {
      apiUrl += `?query=${encodeURIComponent(query)}`;
    } else if (category) {
      apiUrl += `?category=${encodeURIComponent(category)}`;
    }

  fetch(apiUrl)
    .then((response) => response.json())
    .then((data) => {
      if (data.status && data.items.length > 0) {
        renderItems(data.items);
      } else {
        alert("No items found in the database.");
      }
    })
    .catch((error) => {
      console.error("Error from fetchitem.php:", error);
    });

  // Render fetched items into the container
  function renderItems(items) {
    const container = document.getElementById("body"); // Ensure the container exists

    items.forEach((item) => {
      const itemDiv = document.createElement("div");
      const discount = 30;
      const discountedPrice = item.price - item.price * (discount / 100);

      itemDiv.className = "product-frame";
      itemDiv.innerHTML = `
        <img src="http://localhost/E-commerce/${item.image_url}" alt="${item.name}" class="product-image">
        <div class="product-title">${item.name}</div>
        <div>
          <span class="price">Rs ${discountedPrice}</span>
          <span class="original-price">Rs.${item.price}</span>
          <span class="discount">30% OFF</span>
        </div>
        <button class="add-to-btn" data-id="${item.id}">Add To Cart</button>
      `;

      container.appendChild(itemDiv);
    });

    // Add click event listeners to the buttons
    const buttons = document.getElementsByClassName("add-to-btn");
    for (let i = 0; i < buttons.length; i++) {
      buttons[i].addEventListener("click", function () {
        const itemId = this.getAttribute("data-id"); // Retrieve the item's id
        const targetPage = "../page/ProductDetails.php";
        // Redirect to the details page with the item id in the query string
        window.location.href = `${targetPage}?id=${encodeURIComponent(itemId)}`;
      });
    }
  }
});
