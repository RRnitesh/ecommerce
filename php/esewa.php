<?php
session_start();

$userId = $_SESSION['id'];
$orderid = $_SESSION['orderId'];

// Validate incoming POST data
if (!isset($_POST['total_amount'])) {
  die("Required parameters are missing.");
}

// Generate required parameters
$transaction_uuid = uniqid();
$total_amount = number_format((float)$_POST['total_amount'], 2, '.', '');
$product_code = "EPAYTEST"; // Test product code as per eSewa documentation
$signed_field_names = "total_amount,transaction_uuid,product_code";

// Generate signature
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";
$secret = "8gBm/:&EnhH.1/q"; // Replace with your eSewa secret key
$hashed = hash_hmac('sha256', $message, $secret, true);
$signature = base64_encode($hashed);

// Debug log for validation
// file_put_contents('esewa_debug.log', print_r([
//     'message' => $message,
//     'signature' => $signature,
//     'POST' => $_POST,
// ], true), FILE_APPEND);

// Redirect to eSewa with required parameters
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Redirecting to eSewa</title>
</head>

<body>

  <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
    <input type="hidden" name="amount" value="<?php echo $total_amount; ?>"> <!-- Total amount -->
    <input type="hidden" name="tax_amount" value="0"> <!-- Tax -->
    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>"> <!-- Total with tax -->
    <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
    <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">
    <input type="hidden" name="product_service_charge" value="0">
    <input type="hidden" name="product_delivery_charge" value="0">
    <input type="hidden" name="success_url" value="https:///fd5a-2407-54c0-1b12-7b8c-14e7-89b1-b356-74c0.ngrok-free.app/E-commerce/page/successPayment.php?userid=<?php echo urlencode($userId); ?>&orderid=<?php echo urlencode($orderid) ?>">
    <input type="hidden" name="failure_url" value="https:///fd5a-2407-54c0-1b12-7b8c-14e7-89b1-b356-74c0.ngrok-free.app/E-commerce/page/failedPayment.php?userid=<?php echo urlencode($userId); ?>&orderid=<?php echo urlencode($orderid) ?>">
    <input type="hidden" name="signed_field_names" value="<?php echo $signed_field_names; ?>">
    <input type="hidden" name="signature" value="<?php echo $signature; ?>">
  </form>

  <script>
    // Automatically submit the form
    document.getElementById("esewaForm").submit();
  </script>
</body>

</html>