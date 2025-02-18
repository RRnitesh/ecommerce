<?php
session_start();

function ViewMyCartAPI($status, $message, $ProductDetail = null) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message, 'result' => $ProductDetail]);
    exit;
}

if (!isset($_SESSION['id'])) {
    ViewMyCartAPI(false, "First login to system");
}

$userId = $_SESSION['id'];
// $userId = 71;
require "../php/db_connection.php";

// Fetch cart items
$cartItemQuery = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
$cartItemQuery->bind_param("i", $userId);
$cartItemQuery->execute();
$itemResult = $cartItemQuery->get_result();

if ($itemResult->num_rows == 0) {
    ViewMyCartAPI(false, "No items in cart");
}

// Prepare product IDs and quantities
$cartItems = [];
while ($row = $itemResult->fetch_assoc()) {
    $cartItems[] = $row;
}

$productIds = array_column($cartItems, 'product_id');

// Fetch product details in a single query
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$query = "SELECT id, name, price, description, image_url FROM products WHERE id IN ($placeholders)";
$prodDetailsQuery = $conn->prepare($query);

$types = str_repeat('i', count($productIds));
$prodDetailsQuery->bind_param($types, ...$productIds);
$prodDetailsQuery->execute();
$productResult = $prodDetailsQuery->get_result();

$productDetails = [];
while ($row = $productResult->fetch_assoc()) {
    $productDetails[$row['id']] = $row;
}

// Combine cart and product data
$finalCart = [];
foreach ($cartItems as $item) {
    $prodId = $item['product_id'];
    if (isset($productDetails[$prodId])) {
        $finalCart[] = array_merge($productDetails[$prodId], ['quantity' => $item['quantity']]);
    } else {
        ViewMyCartAPI(false, "Product ID {$prodId} not found in products table");
    }
}
error_log(json_encode($finalCart));
ViewMyCartAPI(true, "Success", $finalCart);
?>
