<?php
require "../php/db_connection.php";

function ConfirmationAPI($status, $message, $items = null, $totalAmount = null) {
    header("Content-Type: application/json");
    echo json_encode(['status' => $status, 'message' => $message, 'items' => $items, 'totalAmount' => $totalAmount]);
    exit;
}

// Ensure the user is logged in (session check)
session_start();
if (!isset($_SESSION['id'])) {
    ConfirmationAPI(false, "Session expired");
}

$user_id = $_SESSION['id']; // User ID from the session

// Fetch the active order ID for the user from the database
$query = "SELECT id FROM orders WHERE user_id = ? AND payment_status = 'Pending' LIMIT 1";
$stmt = $conn->prepare($query);
if (!$stmt) {
    ConfirmationAPI(false, "Failed to prepare query: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($order_id);
$stmt->fetch();
$stmt->close();
// $order_id = 97;
$_SESSION['orderId'] = $order_id;

if (!$order_id) {
    ConfirmationAPI(false, "No active order found for the user");
}

// Fetch items from the `order_items` table for the current `order_id`
$sql = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    ConfirmationAPI(false, "Failed to prepare query: " . $conn->error);
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Store items in an array
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
} else {
    ConfirmationAPI(false, "No items found for the current order");
}

// Extract product IDs
$productIds = array_column($items, 'product_id');

// Fetch product details for the given product IDs
if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $query = "SELECT id, name, price, description, image_url FROM products WHERE id IN ($placeholders)";
    $prodDetailsQuery = $conn->prepare($query);
    if (!$prodDetailsQuery) {
        ConfirmationAPI(false, "Failed to prepare query: " . $conn->error);
    }

    $types = str_repeat('i', count($productIds));
    $prodDetailsQuery->bind_param($types, ...$productIds);
    $prodDetailsQuery->execute();
    $productResult = $prodDetailsQuery->get_result();

    // Map product details by product ID
    $productDetails = [];
    while ($row = $productResult->fetch_assoc()) {
        $productDetails[$row['id']] = $row;
    }

    $prodDetailsQuery->close();
} else {
    ConfirmationAPI(false, "No product IDs found for the current order");
}

// Combine cart and product data
$finalCart = [];
foreach ($items as $item) {
    $prodId = $item['product_id'];
    if (isset($productDetails[$prodId])) {
        $finalCart[] = array_merge($productDetails[$prodId], ['quantity' => $item['quantity']]);
    } else {
        ConfirmationAPI(false, "Product ID {$prodId} not found in products table");
    }
}

$stmt_TotalAmount = $conn->prepare("SELECT total_amount FROM orders WHERE user_id = ? AND payment_status = 'pending' LIMIT 1");
$stmt_TotalAmount->bind_param("i", $user_id);
$stmt_TotalAmount->execute();
$stmt_TotalAmount->bind_result($totalAmount);
$stmt_TotalAmount->fetch();
$stmt_TotalAmount->close();
// Return the final cart data
ConfirmationAPI(true, "Order retrieved successfully", $finalCart, $totalAmount);
?>
