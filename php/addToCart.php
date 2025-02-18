<?php
session_start();

function AddtoCartAPI($status, $message = null){
  header('Content-Type: application/json');
  echo json_encode(['status'=> $status, 'message' => $message]);
  exit;
}

if( !isset($_SESSION['name']) || !isset($_SESSION['id']) ){
  http_response_code(401); //unauthorized
  AddtoCartAPI(false );
}

$user_id = intval($_SESSION['id']); //session may treat as string
$user_name = $_SESSION['name'];

$prodID = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

if ( !$prodID || $quantity < 1 || !$user_id){
  AddtoCartAPI(false, "invalid product or quantity");
}

require "../php/db_connection.php";

$productQuery = $conn->prepare("SELECT id, stock_quantity, description, name, price FROM products WHERE id = ?");
$productQuery->bind_param("i", $prodID);
$productQuery->execute();
$prodResult = $productQuery->get_result();

if ($prodResult->num_rows === 0){
  AddtoCartAPI(false, "product not found");
}

$product = $prodResult->fetch_assoc();

//data about product
$price = $product['price'];
$stock_quantity = $product['stock_quantity'];
$prodDesc = $product['description'];
$prodName = $product['name'];

if ( $stock_quantity === 0 ){
  AddtoCartAPI(false, "item out of stock");
}else if( $quantity > $stock_quantity){
  AddtoCartAPI(false, "requeset quantity is not avaiable at stock ");
}

// Check if the item already exists in the cart
$cartQuery = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
$cartQuery->bind_param("ii", $user_id, $prodID);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

// function updateStockQuantity($conn, $prodID){
//   $updateStockQuantity = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - 1 WHERE id = ?");
//   $updateStockQuantity->bind_param("i", $prodID);
//   return $updateStockQuantity->execute();
// }
// updateStockQuantity($conn, $prodID)
if ($cartResult->num_rows === 0) {
    // Item not in the cart, insert with quantity = 1
    $insertQuery = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insertQuery->bind_param("iii", $user_id, $prodID, $quantity);
    if ($insertQuery->execute()) {
      AddtoCartAPI(true, "product added to cart");
    } else {
        AddtoCartAPI(false, "Failed to add product to cart");
    }
} else {
    // Item exists, increment the quantity by 1
    $updateQuery = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $updateQuery->bind_param("ii", $user_id, $prodID);
    if ($updateQuery->execute()) {
      AddtoCartAPI(true, "product added to cart");
    } else {
        AddtoCartAPI(false, "Failed to update product quantity in cart");
    }
}
?>