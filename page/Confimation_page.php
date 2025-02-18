<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmation Page</title>
  <script src="../js/confirmation.js"></script>
  <link rel="stylesheet" href="../css/viewcart.css">
</head>
<body>
  <h1>These are the items you have selected:</h1>
  <button id="backToViewCart">Go Back to View Cart</button>

  <div class="cart-container">
    <div id="cart-item-container"></div>
    <div id="totalAmount"></div>
    <button class="pay-esewa">Pay Using eSewa</button>
  </div>

  <!-- Hidden form for eSewa -->
  <form action="../php/esewa.php" method="POST" style="display: none;">
    <input id="total_amount" name="total_amount" type="hidden">
    <input id="transaction_uuid" name="transaction_uuid" type="hidden" value="<?php echo uniqid(); ?>">
  </form>
</body>
</html>
