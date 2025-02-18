<?php

function AdminOrdersAPI($item){
  header("content-type: application/json");
  echo json_encode($item);
  exit;
}

require "../php/db_connection.php";

$sql = "SELECT id,user_id,total_amount,payment_status,shipping_address FROM orders ";

$result = $conn->query($sql);

$orders = [];

while($row = $result->fetch_assoc()){
  $orders[] = $row;
}

AdminOrdersAPI($orders);
?>