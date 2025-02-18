<?php
session_start();
require "../php/db_connection.php";

function DeleteOrderAPI($status, $message, $httpCode = 200) {
    header("Content-Type: application/json");
    http_response_code($httpCode);
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if (!isset($_SESSION["id"])) {
    DeleteOrderAPI(false, "Session expired", 400);
}

$user_id = $_SESSION['id'];

// Get the order ID associated with the user's cart
$stmt_GetOrderId = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND payment_status = 'pending' LIMIT 1");
$stmt_GetOrderId->bind_param("i", $user_id);

if (!$stmt_GetOrderId->execute()) {
    DeleteOrderAPI(false, "Failed to execute query", 500);
}

$result = $stmt_GetOrderId->get_result();
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $order_id = $row['id'];
} else {
    DeleteOrderAPI(false, "No active order found", 404);
}

$stmt_GetOrderId->close();

// Ensure the connection is established before proceeding
if (!$conn) {
    DeleteOrderAPI(false, "Database connection failed", 500);
}

// Delete the order from the 'orders' table
$stmt_delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
if (!$stmt_delete) {
    DeleteOrderAPI(false, "Failed to prepare delete statement", 500);
}

$stmt_delete->bind_param("i", $order_id);
if (!$stmt_delete->execute()) {
    DeleteOrderAPI(false, "Unable to delete order", 500);
}

$stmt_delete->close();
$conn->close();

DeleteOrderAPI(true, "Successfully deleted the order");
?>
