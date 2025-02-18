<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View cart</title>
    <link rel="stylesheet" href="../css/viewcart.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <script src="../js/Viewcart.js"></script>
  </head>
  <body>
    <h2 id="error"></h2>

    <div class="cart-container">
      <div class="cart-item">
        <div class="cart-header">
          <label><input type="checkbox" id="select-all" />SELECT ALL</label>
        </div>

        <div id="cart-item-container"></div>
      </div>

      <div class="cart-summary">
        <h3>Order summery</h3>
        <label for="location">Select a Location:</label>
        <select id="location">
          <option value=""  >Select a location</option>
          <option value="100">Bhaktapur, Sallagari</option>
          <option value="150">Kathmandu, Durbarmarga</option>
          <option value="350">Jhapa, Damak</option>
          <option value="400">Dang, -</option>
        </select>
        <p>
          Subtotal (<span id="item-count"></span> items): Rs.
          <span id="subtotal">0</span>
        </p>
        <p>Shipping Fee: Rs <span id="shipping">0</span></p>
        <p>
          <strong>Total: Rs <span id="total">0</span></strong>
        </p>
        <button id="checkout-button">PROCEED TO CHECKOUT</button>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>

