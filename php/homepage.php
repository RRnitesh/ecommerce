<?php
require "../php/db_connection.php";
// Fetch 10 random items
$sql = "SELECT id, name, description, price, image_url FROM products ORDER BY RAND() LIMIT 10";
$result = $conn->query($sql);
// Store items in an array
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
// Close connection
$conn->close();

// Pass items to frontend
echo json_encode($items);

?>