<?php
require "../php/db_connection.php"; // Ensure this path is correct

// Sample product details
$name = "Test Product";
$category_id = 2; // Make sure this ID exists in your database
$price = 299.99;
$description = "This is a test product.";
$stock_quantity = 10;
$image_url = "ProductImage/home_appliances/test_image.jpg"; // Example image path

// Prepare and execute the insert query
$stmt = $conn->prepare("INSERT INTO products (name, category_id, price, description, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sidsis", $name, $category_id, $price, $description, $stock_quantity, $image_url);

if ($stmt->execute()) {
    echo "Product inserted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
