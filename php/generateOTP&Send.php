<?php
session_start();

try {
  // Function to return error response
  function ErrorReturn($status, $message)
  {
    header('Content-Type: application/json');
    echo json_encode(['success' => $status, 'message' => $message]);
    exit();
  }

  // Check for valid request method
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ErrorReturn(false, "Error: Invalid request method(generate)");
  }

  // Retrieve POST data
  $email = $_POST['emailRegistration'] ?? null;
  $name = $_POST['fullname'] ?? null;
  $password = $_POST['passwordRegistration'] ?? null;


  //setting the session.
  $_SESSION['email'] = $email;
  $_SESSION['name'] = $name;
  $_SESSION['password'] = $password;

  //check for missing field
  if (!$email || !$name || !$password) {
    ErrorReturn(false, 'Missing required fields(generate)');
  }

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ErrorReturn(false, 'Invalid email address(generate)');
  }

  // Include database connection
  require "../php/db_connection.php";

  // Check database connection
  if ($conn->connect_error) {
    ErrorReturn(false, '(generate)Database connection failed: ' . $conn->connect_error);
  }

  // Check if the email already exists in the database
  $stmt_check = $conn->prepare("SELECT email FROM users WHERE email = ?");
  if (!$stmt_check) {
    ErrorReturn(false, '(generate)Statement preparation failed: ' . $conn->error);
  }

  $stmt_check->bind_param("s", $email);
  $stmt_check->execute();
  $stmt_check->store_result();

  if ($stmt_check->num_rows > 0) {
    ErrorReturn(false, 'Email is already registered(generate)');
  }

  // Generate OTP and expiry time
  $otp = random_int(100000, 999999);
  $otp_expiry = date("Y-m-d H:i:s", strtotime('+2 minutes'));

  // Prepare and execute SQL query for inserting user data
  $stmt = $conn->prepare("INSERT INTO users (email, otp, otp_expiry) VALUES (?, ?, ?)");
  if (!$stmt) {
    ErrorReturn(false, '(generate)Statement preparation failed: ' . $conn->error);
  }

  $stmt->bind_param("sss", $email, $otp, $otp_expiry);

  if (!$stmt->execute()) {
    ErrorReturn(false, '(generate)Database query failed: ' . $stmt->error);
  }

  // error_log("otpGeneratin page Session Data:() " . print_r($_SESSION, true));

  // Log a custom message to a specific log file for debugging
  // error_log("Email session from generateotp page: and name" . $_SESSION['email']. $_SESSION['name'], 3, "C:/xampp/php/logs/custom_log_file.log");
  // error_log("OTP generated: " . $otp, 3, "C:/xampp/php/logs/custom_log_file.log");

  // Include sendMail.php to send OTP
  require "../php/sendMail.php";

  exit();
} catch (Exception $e) {
  // Handle any unexpected errors
  ErrorReturn(false, "(generate)Error: " . $e->getMessage());
}
