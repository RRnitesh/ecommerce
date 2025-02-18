<?php
session_start();

function SuccessAPI($status, $message){
  header("Content-Type: application/json");
  echo json_encode(['status' => $status, 'message' => $message]);
  exit;
}
// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$userid = $data['userid'] ?? null;
$order_id = $data['orderid'] ?? null;

if (!$userid || !$order_id) {
    SuccessAPI(false,'User ID or Order id is not set.');
}

require "../php/db_connection.php";

// Prepare the query to update the payment status
$stmt_update_payment_status = $conn->prepare("UPDATE orders SET payment_status = 'Completed' WHERE user_id = ? AND id = ? AND payment_status = 'pending' ");
$stmt_update_payment_status->bind_param("ii", $userid, $order_id);

// Execute the query
$stmt_update_payment_status->execute();

// Check if the payment status was updated
if ($stmt_update_payment_status->affected_rows > 0) {
    SuccessAPI(true, "Your payment status has been updated");
} else {
    SuccessAPI(false, "Unable to update the payment status");
}
?>
