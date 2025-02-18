<?php
session_start();

$userid = $_SESSION['id'];

require "../php/db_connection.php";

$stmt_check = $conn->prepare("SELECT id from orders where user_id = ? AND payment_status = 'Pending' ");
$stmt_check->bind_param("i", $userid);
$stmt_check->execute();
$result = $stmt_check->get_result();

if($result->num_rows > 0){
  //delete order items
  $stmt_delete_items = $conn->prepare("DELETE FROM orders WHERE user_id = ? AND payment_status = 'Pending' ");
  $stmt_delete_items->bind_param("i", $userid);
  $stmt_delete_items->execute();
}

session_unset();
session_destroy();
header("location:../page/index.php");
?>