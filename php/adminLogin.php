<?php
session_start();  // Ensure session is started

function adminLoginAPI($status, $message)
{
  header("Content-Type: application/json");
  echo json_encode(['success' => $status, 'message' => $message]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  adminLoginAPI(false, 'Invalid request method');
}

$username = $_POST['usernameAdmin'] ?? null;
$password = $_POST['passwordAdmin'] ?? null;

// $username = "niteshrestha00@gmail.com";
// $password = '12345';

if (empty($username) || empty($password)) {
  adminLoginAPI(false, 'Empty username or password');
}

require "../php/db_connection.php";

// Sanitize username to prevent injection
$username = htmlspecialchars($username);  // Basic sanitization
$email = $username;
// Prepare the query
$stmt = $conn->prepare("SELECT id, name, username, password FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);  // Change "i" to "s" for string
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 0) {
  adminLoginAPI(false, "Invalid username or password");
}

$row = $result->fetch_assoc();

// Use password_verify() to compare the hashed password
// if (!password_verify($password, $row['password'])) {
//   adminLoginAPI(false, "Invalid username or password");
// }
if ($password !== $row['password']) {
  adminLoginAPI(false, "Invalid username or password");
}

// Generate OTP and expiry time
$otp = random_int(100000, 999999);
$otp_expiry = date("Y-m-d H:i:s", strtotime('+2 minutes'));

// Prepare and execute SQL query for inserting user data
$stmt = $conn->prepare("UPDATE admins SET otp =? , otp_expiry = ? WHERE id = ?");
if (!$stmt) {
  adminLoginAPI(false, '(generate)Statement preparation failed: ' . $conn->error);
}

$stmt->bind_param("ssi", $otp, $otp_expiry, $row['id']);

if (!$stmt->execute()) {
  adminLoginAPI(false, '(generate)Database query failed: ' . $stmt->error);
}
$_SESSION['id'] = $row['id'];

require "../php/sendMail.php";


