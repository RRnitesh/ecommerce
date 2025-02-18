document.addEventListener("DOMContentLoaded", function() {
  const ordersBody = document.getElementById("ordersBody");
  const searchInput = document.getElementById("searchInput");
  const sortSelect = document.getElementById("sortSelect");

  let orders = []; // Store fetched orders

  fetch("../php/getAdminItem.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status) {
        orders = data.item;
        displayOrders(orders);
      } else {
        console.log("No items found");
      }
    })
    .catch((error) => {
      console.log(error);
    });


    function displayOrders(filteredOrders) {
      ordersBody.innerHTML = ""; // Clear table
    
      if (filteredOrders.length === 0) {
        ordersBody.innerHTML = `<tr><td colspan="6" class="text-center">No products found</td></tr>`;
        return;
      }
    
      filteredOrders.forEach((order) => {
        const row = `<tr>
          <td>${order.id}</td>
          <td>${order.name}</td>
          <td>${order.description}</td>
          <td>${order.price}</td> <!-- Assuming you want to display the price here -->
          <td>${order.category_id}</td>
          <td><img src="http://localhost/E-commerce/${order.image_url}" alt="${order.name}" class="product-image"></td>
        </tr>`;
        ordersBody.innerHTML += row;
      });
    
      // Search functionality
      searchInput.addEventListener("input", function () {
        const searchValue = searchInput.value.trim().toLowerCase();
        const filteredOrders = orders.filter((order) =>
          order.name.toString().toLowerCase().includes(searchValue)
        );
        displayOrders(filteredOrders);
      });
    
      // Sort functionality
      sortSelect.addEventListener("change", function () {
        const selectedCategory = sortSelect.value;
        let sortedOrders = [...orders];
    
        if (selectedCategory) {
          sortedOrders = sortedOrders.filter(
            (order) => order.category_id == selectedCategory
          );
        }
    
        displayOrders(sortedOrders);
      });
    }
    
});

