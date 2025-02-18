<header class="header">
  <div class="logo-section">
  </div>
  <button id="adminlogin" data-bs-toggle="modal" data-bs-target="#loginModalAdmin"
  >Admin</button>
  <a href="../page/admin_orders.php">
  <button id="admin_orders">Admin Orders</button></a>

  <div class="search-section">
    <input type="text" id="search-input" class="search-bar" placeholder="Search...">

    <select class="categories" id="navigateToCategory">
      <option value="">View category</option>
      <option value="1">TV</option>
      <option value="2">Home Appliance</option>
      <option value="3">Laptops</option>
      <option value="4">Mobile Phone</option>
      <option value="5">Kitchen</option>
    </select>
    <button onclick="searchItems()" class="search-btn">🔍</button>

  </div>

  <div class="info-section">
    <div class="contact">
    </div>
    <div class="icons">
      <button id="login" data-bs-toggle="modal" data-bs-target="#loginModal" data-action="../php/login.php">login</button>
      <button id="Signup" data-bs-toggle="modal" data-bs-target="#registrationModal">Signup</button>
      <button id="logout"><a href="../php/logout.php">logout</a></button>
      <button id="view-cart"><a href="../page/ViewMyCart.php">ViewCart</a></button>
    </div>
  </div>
</header>