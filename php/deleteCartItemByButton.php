<?php
session_start();
function deleteItemAPI($status, $message){
  header("Content-Type: application/json");
  echo json_encode(['status' => $status, 'message' => $message]);
  exit;
}

if($_SERVER["REQUEST_METHOD"] !== "POST"){
  deleteItemAPI(false, "invalid request method");
}
if(!isset($_SESSION['id'])){
  deleteItemAPI(false, "session over ");
}

$input = json_decode(file_get_contents('php://input'), true);

$productId = $input['productId'];
$user_id = $_SESSION['id'];

// error_log("prodId: ". $productId. "userID: ".$user_id);
require "../php/db_connection.php";

$stmt_delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
$stmt_delete->bind_param("ii",$user_id,$productId);
if (!($stmt_delete->execute())) {
  deleteItemAPI(false, "item couldnot be deleted please try again");
}

deleteItemAPI(true, "good");

?>