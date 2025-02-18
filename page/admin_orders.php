<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Orders</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="../js/admin_orders.js"></script>
  <script src="../js/addNewProduct.js"></script>
</head>

<body>
  <div class="container mt-4">
    <h2 class="mb-4">Admin Orders</h2>

    <!-- button to trigger the modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
      Add Product
    </button>

    <a href="../page/adminDisply.html"><button type="button" class="btn btn-primary">List all item </button></a>

    <!-- Search by Order ID -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search Order ID...">

    <!-- Sort by Payment Status -->
    <select id="sortSelect" class="form-control mb-3">
      <option value="">Sort by Payment Status</option>
      <option value="Completed">Paid</option>
      <option value="Pending">Pending</option>
      <option value="Failed">Failed</option>
    </select>

    <table class="table table-bordered" id="ordersTable">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Total Amount</th>
          <th>Payment Status</th>
          <th>Shipping Address</th>
        </tr>
      </thead>
      <tbody id="ordersBody">
        <!-- Data will be inserted here dynamically -->
      </tbody>
    </table>
  </div>

  <!-- Product Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="productForm" method="POST"  action="../php/ADDitem.php"  enctype="multipart/form-data">
            <div class="mb-3">
              <label for="productName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="productName" name="name" required>
            </div>
            <div class="mb-3">
              <label for="productDesc" class="form-label">Description</label>
              <textarea class="form-control" id="productDesc" name="description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="productCategory" class="form-label">Category</label>
              <select class="form-select" id="productCategory" name="category" required>
                <option value="">View category</option>
                <option value="1">TV</option>
                <option value="2">Home Appliance</option>
                <option value="3">Laptops</option>
                <option value="4">Mobile Phone</option>
                <option value="5">Kitchen</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="productPrice" class="form-label">Price</label>
              <input type="number" class="form-control" id="productPrice" name="price" required>
            </div>
            <div class="mb-3">
              <label for="productImage" class="form-label">Product Image</label>
              <input type="file" class="form-control" id="productImage" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Save Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>