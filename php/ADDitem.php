<?php

function ADDAPI($status, $message){
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ADDAPI(false, "Error: Invalid request method");
}

require "../php/db_connection.php";

// $name = "test";
// $description = "testtest";
// $categoryNUM = 4 ;
// $price = 300;
// $stock_quantity = 0;
$name = htmlspecialchars($_POST["name"]);
$description = htmlspecialchars($_POST["description"]);
$categoryNUM = intval($_POST["category"]);  
$price = floatval($_POST["price"]);
$stock_quantity = 0;
$category = "";

// Assign category based on category number
switch ($categoryNUM) {
    case 1:
        $category = "tv";
        break;
    case 2:
        $category = "home_appliances";
        break;
    case 3:
        $category = "laptops";
        break;
    case 4:
        $category = "mobile";
        break;
    case 5:
        $category = "kitchen_appliances";
        break;
    default:
        ADDAPI(false, "Invalid category");
}

$target_dir = "C:/xampp/htdocs/E-commerce/ProductImage/$category/";

$imageName = basename($_FILES["image"]["name"]);
$targetFile = $target_dir . $imageName; 

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    $address = "ProductImage/$category/$imageName";
    $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, description, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sidsis", $name, $categoryNUM, $price, $description, $stock_quantity, $address);

    if ($stmt->execute()) {
        ADDAPI(true, "Product added successfully");
    } else {
        ADDAPI(false, "Error: " . $stmt->error);
    }

    $stmt->close();
} else {
    ADDAPI(false, "Error uploading file");
}

$conn->close();
?>
