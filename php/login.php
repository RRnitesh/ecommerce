<?php
session_start();

function LoginAPI($status, $message, $name = null, $id = null)
{
  header('Content-Type: application/json');
  echo json_encode(['success' => $status, 'message' => $message, 'user_name' => $name, 'id' => $id]);
  exit;
}

try {
  // Ensure the request method is POST
  if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    LoginAPI(false, "(loginpage)Invalid request method");
  }

  // Validate if both username and password are present in the request
  if (empty($_POST['usernameLogin']) || empty($_POST['passwordLogin'])) {
    LoginAPI(false, "(loginpage)Username or password is missing");
  }

  $email = $_POST['usernameLogin'];
  $password = $_POST['passwordLogin'];

  // Include database connection
  require "../php/db_connection.php";

  // Use prepared statements to fetch user details
  $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    LoginAPI(false, "(loginpage)Failed to prepare statement: " . $conn->error);
  }

  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows === 0) {
    LoginAPI(false, "Invalid username or password");
  }

  $row = $result->fetch_assoc();

  // Verify password (if not hashed, you can use a direct comparison)
  if ($password !== $row['password']) {
    LoginAPI(false, "Invalid username or password");
  }

  // Set session variables
  $_SESSION['name'] = $row['username'];
  $_SESSION['id'] = $row['id'];
  //error_log("login page Session Data:(after verfication) " . print_r($_SESSION, true));

  // Return success response
  LoginAPI(true, "Welcome", $_SESSION['name'], $_SESSION['id']);
  
} catch (Exception $e) {
  // Handle unexpected errors
  LoginAPI(false, "(loginpage)Error: " . $e->getMessage());
}


