<?php

function FetchItemAPI($status, $message, $items = null) {
    header("Content-Type: application/json");
    echo json_encode(['status' => $status, 'message' => $message, 'items' => $items]);
    exit;
}

require "../php/db_connection.php";

//getting the item field from url
$category = $_GET["category"] ?? '';
$query = $_GET["query"] ?? '';
// $category = 1;
// $category = 1;
// Base SQL query


// $query = 'samsung';
$sql = "SELECT id, name, description, price, image_url FROM products WHERE 1=1";

$params = [];
$types = "";

// Add category condition
if (!empty($category)) {
    $sql .= " AND category_id = ?";
    $params[] = $category;
    $types .= "i";
}

// Add search query condition
if (!empty($query)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%" . $query . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch items into an array
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    FetchItemAPI(true, 'Items retrieved successfully', $items);
} else {
    FetchItemAPI(false, 'No items found');
}

?>
