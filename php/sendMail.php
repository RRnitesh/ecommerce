<?php

// Include the PHPMailer autoload file
require '../vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

try {
    // Server settings
    $mail->isSMTP();                                     // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to Gmail
    $mail->SMTPAuth = true;                              // Enable SMTP authentication
    $mail->Username = 'companyan999@gmail.com';            // Your Gmail address
    $mail->Password = 'ugir jxal hezr wvjv';             // Your Gmail password (consider using App Passwords)
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587;                                   // TCP port for TLS

    $mail->setFrom('companyan999@gmail.com', 'AN company');  // Sender email and name
    $mail->addAddress($email, ''); // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Otp for registration';
    $mail->Body    = 'OTP '.$otp;
    $mail->AltBody = 'ignore this message if you are not i dont know what'; // For non-HTML email clients

    if ($mail->send()) {
      echo json_encode(['success' => true]);
  } else {
      echo json_encode(['success' => false,  'message' => 'failed to send otp']);
      exit();
  }
    
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
  exit();
} 

?>
