let id;

// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", () => {

  // Listen for login messages
  window.addEventListener("message", (event) => {
    if (event.data.type === "USER_LOGIN") {
      handleUserLogin(event.data.username);
    }
  });

  //view by category
  const category = document.getElementById("navigateToCategory");
  category.addEventListener("change", (event) => {
    const selectedCategory = event.target.value; // Get the selected category value
    window.location.href = `../page/displayItem.php?category=${selectedCategory}`;
  });



  // Fetch and display random items
  fetch("../php/homepage.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((items) => {
      id = items.id; // Not clear where `id` is needed globally
      renderItems(items);
    })
    .catch((error) => console.error("Error fetching items:", error));
});

// Handle user login state update
function handleUserLogin(username) {
  const welcomeMessage = document.getElementById("welcomeMessage");
  const loginButton = document.getElementById("login");
  const registerButton = document.getElementById("Signup");
  const logoutButton = document.getElementById("logout");

  welcomeMessage.textContent = `Namaste, ${username}`;
  loginButton.style.display = "none";
  registerButton.style.display = "none";
  logoutButton.style.display = "block";
}

// Render fetched items into the container
function renderItems(items) {
  const container = document.getElementById("body"); // Ensure `body` exists in the HTML

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
      <button class="add-to-btn">Add to Cart</button>
    `;

    container.appendChild(itemDiv);
  });

  // Add click event listeners to each product frame
  const productFrames = document.getElementsByClassName("product-frame");
  for (let i = 0; i < items.length; i++) {
    productFrames[i].addEventListener("click", () => {
      const targetPage = "../page/ProductDetails.php";
      window.location.href = `${targetPage}?id=${encodeURIComponent(items[i].id)}`;
    });
  }
}
// Search for items
function searchItems() {
  const query = document.getElementById("search-input").value.trim();
  if (query) {
    window.location.href = `../page/displayItem.php?query=${query}`;
  } else {
    alert("Please enter a search term!");
  }
}
