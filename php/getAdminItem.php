<?php

function getAPI($status, $message, $item = null){
  echo json_encode(['status' => $status, 'message'=>$message, 'item' => $item]);
  exit;
}

require "../php/db_connection.php";

// Prepare and execute the query
$stmt = $conn->prepare("SELECT id, name, description, category_id, price, image_url FROM products");

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $item = [];
    
    while ($row = $result->fetch_assoc()) {
        $item[] = $row;  // Add each row to the $item array
    }

    getAPI(true, "Good", $item);  // Return the data as JSON
} else {
    getAPI(false, "Error executing query");  // Error if query fails
}

$stmt->close();
$conn->close();

?>
