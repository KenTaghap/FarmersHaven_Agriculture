<?php
require 'vendor/autoload.php';
error_reporting(E_ERROR | E_PARSE);
ob_start();

// Connect to MongoDB Atlas
$mongoClient = new MongoDB\Client("mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority");

// Select the database and collection
$database = $mongoClient->Agriculture;
$collection = $database->Vendors;

$errorMsg = ""; // Initialize an error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];

    // Query for the user
    $query = ["Username" => $Username, "Password" => $Password];
    $user = $collection->findOne($query);

    if ($user) {
        // Successful login, set session variables or redirect to a protected area
        header("Location: ../vendors/index.html");
        exit();
    } else {
        // Invalid login, display an error message
        $errorMsg = "Invalid username or password";
    }
}
    ob_end_flush();
?>


<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
    <link rel="stylesheet" href="../css/loginstyle.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
      <img src="../images/bsr-fba-hero.jpg" alt="">
        <div class="text">
          <span class="text-1">WELCOME <br> Vendors</span>
          <span class="text-2">Let's get started!</span>
        </div>
      </div>
    </div>
    <div class="forms">
        <div class="form-content">
          <div class="login-form">
            <div class="title">Login</div>

          <form action="VendorsLogphp.php" method="POST">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="Username" id="Username"  placeholder="Enter your username" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="Password" id="Password" placeholder="Enter your password" required>
              </div>
              <br><label style="color: red;"><?php echo $errorMsg; ?></label><br><br>

              <div class="button input-box">
                <input type="submit" value="Login">
              </div>
              <div class="text sign-up-text">Don't have an account? <a href="../VendorsReg.html">Sigup now</a></div>
              <div class="text sign-up-text">Won't Register? <a href="../index.html">Back</a></div>
            </div>
        </form>

     
      </div>
        
    </div>
    </div>
  </div>
</body>
</html>
