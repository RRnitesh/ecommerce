<?php
// Get the item ID from the URL
$itemIdFromUrl = $_GET['id'];

// Require the database connection
require "../php/db_connection.php";

// Prepare the SQL query
$sql = "SELECT name, description, price, image_url FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);

// Bind the parameter to the query (assuming id is an integer)
$stmt->bind_param('i', $itemIdFromUrl);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if a row was returned
if ($result->num_rows == 1) {
    // Fetch the associated data
    $row = $result->fetch_assoc();
    
    // Extract the details
    $name = $row['name'];
    $description = $row['description'];
    $price = $row['price'];
    $image_url = $row['image_url'];

   
    // echo "Name: $name<br>";
    // echo "Description: $description<br>";
    // echo "Price: $price<br>";
    // echo "<img src='http://localhost/E-commerce/$image_url' alt='$name' style='max-width:200px;'><br>";
} else {
error_log("error in get product details ");
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();
?>
