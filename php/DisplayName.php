<?php
session_start();

// Check if session variables are set
if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    // Default response for logged-in users
    $response = [
        'success' => true,
        'user_name' => $_SESSION['name'],
        'user_id' => $_SESSION['id'],
        'is_admin' => false // Default to false
    ];

    // Check if the user is an admin
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        $response['is_admin'] = true;
    }

    echo json_encode($response);
} else {
    // Response for non-logged-in users
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
}

exit;
?>