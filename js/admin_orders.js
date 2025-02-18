document.addEventListener("DOMContentLoaded", function () {
  const ordersBody = document.getElementById("ordersBody");
  const searchInput = document.getElementById("searchInput");
  const sortSelect = document.getElementById("sortSelect");

  let orders = []; // Store fetched orders

  // Fetch orders from PHP API
  fetch("../php/fetch_admin_orders.php")
    .then((response) => response.json())
    .then((data) => {
      orders = data; // Store data globally
      displayOrders(orders); // Display all orders initially
    })
    .catch((error) => console.error("Error fetching orders:", error));

  // Function to display orders in the table
  function displayOrders(filteredOrders) {
    ordersBody.innerHTML = ""; // Clear table

    if (filteredOrders.length === 0) {
      ordersBody.innerHTML = `<tr><td colspan="5" class="text-center">No orders found</td></tr>`;
      return;
    }

    filteredOrders.forEach((order) => {
      const row = `<tr>
          <td>${order.id}</td>
          <td>${order.user_id}</td>
          <td>$${order.total_amount}</td>
          <td>${order.payment_status}</td>
          <td>${order.shipping_address}</td>
      </tr>`;
      ordersBody.innerHTML += row;
    });
  }

  // Search functionality
  searchInput.addEventListener("input", function () {
    const searchValue = searchInput.value.trim().toLowerCase();
    const filteredOrders = orders.filter((order) =>
      order.id.toString().toLowerCase().includes(searchValue)
    );
    displayOrders(filteredOrders);
  });

  // Sort functionality
  sortSelect.addEventListener("change", function () {
    const selectedStatus = sortSelect.value;
    let sortedOrders = [...orders];

    if (selectedStatus) {
      sortedOrders = sortedOrders.filter(
        (order) => order.payment_status === selectedStatus
      );
    }

    displayOrders(sortedOrders);
  });
});