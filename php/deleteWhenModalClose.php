<?php
session_start();

function APIResponse($status, $message)
{
  header('Content-Type: application/json');
  echo json_encode(['success' => $status, 'message' => $message]);
  exit;
}

try {
  require "../php/db_connection.php";

  // Get the email from the session
  if (empty($_SESSION['email'])) {
    APIResponse(false, "No email found in session.");
  }

  $email = $_SESSION['email'];
  //error_log("email from delete page" . $_SESSION['email'], 3, "C:/xampp/php/logs/custom_log_file.log");

  // Delete user from the database
  $sql = "DELETE FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    APIResponse(false, "Failed to prepare statement: " . $conn->error);
  }

  $stmt->bind_param("s", $email);
  if ($stmt->execute() && $stmt->affected_rows > 0) {
    //once email is retrievd from the session we can delete every session
    //that are name, email, and password.
    error_log("delete page Session Data:(before deletion) " . print_r($_SESSION, true));
    
    session_unset();
    session_destroy();

    error_log("delete page Session Data:(after deletion) " . print_r($_SESSION, true));
    
    APIResponse(true, "User data deleted successfully.");
  } else {
    APIResponse(false, "No matching user data to delete.");
  }
} catch (Exception $e) {
  APIResponse(false, "Error: " . $e->getMessage());
}
