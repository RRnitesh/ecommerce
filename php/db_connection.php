<?php
// Database connection script
function getDatabaseConnection() {
    $servername = getenv('DB_SERVER') ?: 'localhost';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $database = getenv('DB_NAME') ?: 'ecommerce_shop';

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        die("Internal server error. Please try again later."); // Avoid exposing sensitive info
    }

    return $conn;
}
// Set timezone to Nepal (Asia/Kathmandu)
date_default_timezone_set('Asia/Kathmandu');

$conn = getDatabaseConnection();

// Check the connection and output JSON response
// if ($conn->connect_error) {
//     echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
//     exit();
// } else {
//     echo json_encode(['success' => true, 'message' => 'Database connected successfully']);
// }
?>
