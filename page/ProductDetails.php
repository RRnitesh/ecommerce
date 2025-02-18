<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/productdeails.css">
  <?php require "../page/ErrorModal.php"?>
  <?php require "../page/SuccessModal.php" ?>
  <script src="../js/addToCart.js" defer></script> 
  <script src="../js/loginpage.js"></script>
  <?php require "../page/loginModal.php" ?>  
</head>
<body>
  <?php require("../php/GetProductDetails.php") ?>
  <div class="container">
    <!-- Product Image Section -->
    <div class="product-image">
      <img src="<?php echo "http://localhost/E-commerce/$image_url"; ?>" alt="<?php echo ($name); ?>">
    </div>

    <!-- Product Details Section -->
    <div class="product-details">
      <h1 class="product-name"><?php echo ($name); ?></h1>
      <p class="product-price">Rs<?php echo ($price); ?></p>

      
      <p class="product-stock">In Stock</p>
      <!-- yo in stock wala ni database vata tanney item avaible xa ki xiana vnera -->

      <!-- Quantity Section -->
      <!-- <div class="quantity-container">
        <button>-</button>
        <input type="number" value="1" min="1">
        <button>+</button>
      </div> -->

      <button class="add-to-cart" data-product-id = "<?php echo $itemIdFromUrl ?>">Add to Cart</button>

      <!-- Product Description -->
      <div class="product-description">
        <h2>Description</h2>
        <p><?php echo ($description); ?></p>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
