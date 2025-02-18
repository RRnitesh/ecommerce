<?php
session_start();

function ProcessCheckOutAPI($status, $message, $order_id = null) {
    header("Content-Type: application/json");
    echo json_encode(['status' => $status, 'message' => $message, 'order_id' => $order_id]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    ProcessCheckOutAPI(false, "Invalid request method");
}

if (!isset($_SESSION['id'])) {
    ProcessCheckOutAPI(false, "User must be logged in");
}

$user_id = $_SESSION['id'];

$input = json_decode(file_get_contents('php://input'), true);

$totalAmount = $input['totalAmount'];
$cartItems = $input['cartItems'];
$shippingAddress = $input['shippingAddress'];
$payment_status = "Pending";

require "../php/db_connection.php";

// Start transaction
$conn->begin_transaction();

try {
    // Check if there's already a pending order for the user
    $stmt_check = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND payment_status = ?");
    if (!$stmt_check) {
        throw new Exception("Failed to prepare order check statement");
    }

    $stmt_check->bind_param("is", $user_id, $payment_status);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Pending order exists, update the order items
        $order = $result_check->fetch_assoc();
        $order_id = $order['id'];
        // $_SESSION['orderId'] = $order_id;

        // Clear existing items for the pending order
        $stmt_clear_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        if (!$stmt_clear_items) {
            throw new Exception("Failed to prepare order items clear statement");
        }

        $stmt_clear_items->bind_param("i", $order_id);
        if (!$stmt_clear_items->execute()) {
            throw new Exception("Failed to clear existing order items");
        }

        // Insert new items into order_items table
        $stmt_insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        if (!$stmt_insert_item) {
            throw new Exception("Failed to prepare order item insert statement");
        }

        foreach ($cartItems as $item) {
            $prodId = $item['product_id'];
            $quantity = $item['quantity'];

            $stmt_insert_item->bind_param("iii", $order_id, $prodId, $quantity);
            if (!$stmt_insert_item->execute()) {
                throw new Exception("Failed to insert product: $prodId");
            }
        }

        // Update total amount and shipping address in the orders table
        $stmt_update_order = $conn->prepare("UPDATE orders SET total_amount = ?, shipping_address = ? WHERE id = ?");
        if (!$stmt_update_order) {
            throw new Exception("Failed to prepare order update statement");
        }

        $stmt_update_order->bind_param("isi", $totalAmount, $shippingAddress, $order_id);
        if (!$stmt_update_order->execute()) {
            throw new Exception("Failed to update order details");
        }

        $conn->commit();
        ProcessCheckOutAPI(true, "Order updated successfully", $order_id);

    } else {
        // No pending order, proceed with normal insertion logic
        $stmt_insert = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status, shipping_address) VALUES (?, ?, ?, ?)");
        if (!$stmt_insert) {
            throw new Exception("Failed to prepare order insert statement");
        }

        $stmt_insert->bind_param("iiss", $user_id, $totalAmount, $payment_status, $shippingAddress);
        if (!$stmt_insert->execute()) {
            throw new Exception("Failed to insert order");
        }

        $order_id = $conn->insert_id;

        // Insert into order_items table
        $stmt_insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        if (!$stmt_insert_item) {
            throw new Exception("Failed to prepare order item insert statement");
        }

        foreach ($cartItems as $item) {
            $prodId = $item['product_id'];
            $quantity = $item['quantity'];

            $stmt_insert_item->bind_param("iii", $order_id, $prodId, $quantity);
            if (!$stmt_insert_item->execute()) {
                throw new Exception("Failed to insert product: $prodId");
            }
        }

        $conn->commit();
        ProcessCheckOutAPI(true, "Order placed successfully", $order_id);
    }

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    ProcessCheckOutAPI(false, $e->getMessage());
}
?>
