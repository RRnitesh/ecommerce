<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Navigation Bar</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/idc.css">
  <script src="../js/index.js"></script>
  <script src="../js/toggleRegistration&OTP.js"></script>
  <script src="../js/otpverificationMessage.js"></script>
  <script src="../js/DisplayName.js"></script>
  <link rel="stylesheet" href="../css/frontpageCSS.css">
  <link rel="stylesheet" href="../css/productframe.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="../js/loginpage.js"></script>
  <script src="../js/adminLogin.js"></script>
</head>

<body>
  <!-- navigation section -->
  <?php include "../page/navBar.php"  ?>

  <!-- login modal -->
  <?php include "../page/loginModal.php" ?>

  <!-- registration modal -->
  <?php include "../page/registrationModal.php" ?>

  <!-- admin registration -->
  <?php include "../page/loginAdmin.php" ?> 

  <!-- otp modal -->
  <?php include "../page/otpModal.php" ?>

  <!-- otp modal admin -->
  <?php include "../page/otpAdim.php" ?>
  <span id="welcomeMessage" class="welcome-message">Welcome to the site!</span>


  <!-- <div id="random-items-container" class="item-grid"></div> -->
  <div id="body"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>