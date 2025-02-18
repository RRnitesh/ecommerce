<?php
session_start();

function VerifyAPI($status, $message, $name = null) {
  header("Content-Type: application/json");
  echo json_encode(['status' => $status, 'message' => $message, 'user_name' => $name]);
  exit;
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] != "POST") {
  VerifyAPI(false, "Invalid request method");
}

// Decode input data
$data = json_decode(file_get_contents("php://input"), true);
if (empty($data['otp'])) {
  VerifyAPI(false, "OTP is required");
}

$submittedOTP = $data['otp'];
// $submittedOTP = 980802;
// Check if session ID is set
if (!isset($_SESSION['id'])) {
  VerifyAPI(false, "Session expired. Please log in again.");
}
$_SESSION['id'] = 1;
require "../php/db_connection.php";

// Fetch OTP and expiry from the database
$stmt = $conn->prepare("SELECT name, id, otp, otp_expiry FROM admins WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  VerifyAPI(false, "Admin not found.");
}

$row = $result->fetch_assoc();
$storedOTP = $row['otp'];
$storedOTPExpiry = $row['otp_expiry'];

// Check if OTP has expired
if (time() > strtotime($storedOTPExpiry)) {
  // Clear expired OTP from the database
  $stmt_delete = $conn->prepare("UPDATE admins SET otp = NULL, otp_expiry = NULL WHERE id = ?");
  $stmt_delete->bind_param("i", $_SESSION['id']);
  $stmt_delete->execute();
  VerifyAPI(false, "OTP has expired. Please try again.");
}

// Verify submitted OTP
if ($submittedOTP == $storedOTP) {
  // Set session variables
  $_SESSION['name'] = $row['name'];
  $_SESSION['id'] = $row['id'];
  $_SESSION['is_admin'] = true;

  // Clear OTP from the database after successful verification
  $stmt_delete = $conn->prepare("UPDATE admins SET otp = NULL, otp_expiry = NULL WHERE id = ?");
  $stmt_delete->bind_param("i", $_SESSION['id']);
  $stmt_delete->execute();

  VerifyAPI(true, "OTP verified successfully.", $_SESSION['name']);
} else {
  VerifyAPI(false, "OTP did not match.");
}
?>