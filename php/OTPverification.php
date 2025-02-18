<?php
session_start();

// Function to send JSON responses
function returnSuccessMessageAPI($status, $message, $name = null, $id = null)
{
  header('Content-Type: application/json');
  echo json_encode(['success' => $status, 'message' => $message, 'username' => $name, 'id' => $id]);
  exit();
}

// Checking if session email is available
if (!isset($_SESSION['email'])) {
  //error_log("Session email is not set.", 3, "C:/xampp/php/logs/custom_log_file.log");
  returnSuccessMessageAPI(false, "Session expired. Please log in again.");
}

$user_email = $_SESSION['email'];

// Log the email for OTP verification
//error_log("rechieved email from session " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");

require "../php/db_connection.php";

// Retrieve OTP and expiry time from the database
$sql = "SELECT otp, otp_expiry FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  //error_log("Database error in preparing statement: " . $conn->error, 3, "C:/xampp/php/logs/custom_log_file.log");
  returnSuccessMessageAPI(false, "(verification)Database error: " . $conn->error);
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  //error_log("No user found for email: " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");
  returnSuccessMessageAPI(false, '(verification)Invalid request. User not found.');
}

$row = $result->fetch_assoc();
$stored_otp = $row['otp'];
$stored_otp_expiry = $row['otp_expiry'];

// Log OTP and expiry details
//error_log("Stored OTP: " . $stored_otp . " | OTP Expiry: " . $stored_otp_expiry, 3, "C:/xampp/php/logs/custom_log_file.log");

// Check if OTP has expired
if (time() > strtotime($stored_otp_expiry)) {
  //error_log("OTP has expired for email: " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");

  $sql_delete = "DELETE FROM users WHERE email = ?";
  $stmt_delete = $conn->prepare($sql_delete);
  if (!$stmt_delete) {
    //error_log("Error preparing delete statement: " . $conn->error, 3, "C:/xampp/php/logs/custom_log_file.log");
    returnSuccessMessageAPI(false, "(verification)Database error: " . $conn->error);
  }

  $stmt_delete->bind_param("s", $user_email);
  if (!$stmt_delete->execute() || $stmt_delete->affected_rows === 0) {
    //error_log("Failed to delete expired OTP data for email: " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");
    returnSuccessMessageAPI(false, '(verification)Failed to delete expired OTP data.');
  }

  //error_log("OTP data deleted for expired session for email: " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");
  returnSuccessMessageAPI(false, '(verification)OTP has expired. Please try again.');
}

// Handle POST request for OTP verification
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  // Read the JSON input from the request
  $input = file_get_contents("php://input");

  // Decode the JSON input
  $data = json_decode($input, true);

  // Extract the OTP value from the decoded JSON
  $user_entered_otp = $data['otp'] ?? null;

  //error_log("verification process User entered OTP: " . ($user_entered_otp), 3, "C:/xampp/php/logs/custom_log_file.log");

  if ($user_entered_otp == $stored_otp) {
    // Prepare the SELECT query to fetch the id
    $stmt_id = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt_id->bind_param("s", $user_email);
    $stmt_id->execute();
    $result = $stmt_id->get_result();

    if ($row = $result->fetch_assoc()) {
      $_SESSION['id'] = $row['id'];
    } else {
      returnSuccessMessageAPI(false, "User ID cannot be extracted");
    }
    $stmt_id->close();

    // $password_hashed = password_hash($_SESSION['password'], PASSWORD_BCRYPT);
    $password_hashed = $_SESSION['password'];
    $name = $_SESSION['name'];
    
    $sql_insertion = "UPDATE users SET username = ?, password = ? , otp = NULL, otp_expiry = NULL WHERE email = ?";
    $stmt_insertion = $conn->prepare($sql_insertion);

    if (!$stmt_insertion) {
      returnSuccessMessageAPI(false, '(verification)Database error: ' . $conn->error);
    }

    $stmt_insertion->bind_param("sss", $name, $password_hashed, $user_email);

    if ($stmt_insertion->execute() && $stmt_insertion->affected_rows > 0) {
      // Log all session data to the PHP error log
      error_log("OTP Verification page Session Data:(before verfication) " . print_r($_SESSION, true));
      unset($_SESSION['password']);
      unset($_SESSION['email']);
      error_log("OTP Verification page Session Data:(after verfication) " . print_r($_SESSION, true));

      returnSuccessMessageAPI(true, '(verification)Database updated successfully.', $_SESSION['name'], $_SESSION['id']);
    } else {
      returnSuccessMessageAPI(false, '(verification)Failed to update the database.');
    }
  } else {
    // OTP mismatch, log the event and return error message
    //error_log("OTP mismatch for email: " . $user_email, 3, "C:/xampp/php/logs/custom_log_file.log");
    returnSuccessMessageAPI(false, '(verification)OTP did not match. Please try again.');
  }
}
