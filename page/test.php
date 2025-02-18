<?php
// Input Data
$total_amount = "100";
$transaction_uuid = "11-201-13";
$product_code = "EPAYTEST";
$signed_field_names = "total_amount,transaction_uuid,product_code";
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";

// Secret Key
$secret = "8gBm/:&EnhH.1/q"; // Replace with your actual test secret key

// Generate Signature
$hashed = hash_hmac('sha256', $message, $secret, true);
$signature = base64_encode($hashed);

echo "Message: $message\n";
echo "Signature: $signature\n";
?>
