<?php
require 'vendor/autoload.php';

use MongoDB\BSON\Binary;
use MongoDB\Client;

// Initialize variables
$error_message = "";
$success_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // MongoDB Atlas connection settings
    $mongoURI = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";
    $dbName = "Agriculture"; // Replace with your database name
    $collectionName = "Farmers_Validate";

    // Create the "uploads" directory if it doesn't exist
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory recursively
    }

    // Retrieve user data from the form
    $Fullname = $_POST["Fullname"];
    $Filename = $_POST["Filename"];

	$Address = $_POST["Address"];
	$Username = $_POST["Username"];
    $Password = $_POST["Password"];
    $Gender = $_POST["Gender"];
	$Birthday = $_POST["Birthday"];
	$Number = $_POST["Number"];
    $Email = $_POST["Email"];

    // Connect to MongoDB Atlas
    $client = new Client($mongoURI);

    // Select the database and collection
    $db = $client->$dbName;
    $collection = $db->$collectionName;

    // Upload profile picture
    $targetFile = $targetDir . basename($_FILES["Data"]["name"]);

    if (move_uploaded_file($_FILES["Data"]["tmp_name"], $targetFile)) {
        // Read the uploaded picture as binary
        $binaryData = new Binary(file_get_contents($targetFile), Binary::TYPE_GENERIC);

        // Insert user data with the profile picture as binary data
        $insertResult = $collection->insertOne([
            "Fullname" => $Fullname,
            "ProfilenName" => $Filename,
            "File" => $binaryData,
            "Address" => $Address,
            "Username" => $Username,
            "Password" => $Password,
            "Gender" => $Gender,
            "Birthday" => $Birthday,
            "ContactNum" => $Number,
            "Email" => $Email
        ]);

        if ($insertResult->getInsertedCount() > 0) {
            $success_message = "Registration successful!";

        } else {
            // Registration failed, set an error message
            $error_message = "Registration failed. Please try again.";
        }

        // Remove the uploaded file since it's already stored as binary data
        unlink($targetFile);
    } else {
        // Error uploading profile picture
        $error_message = "Error uploading profile picture.";
    }
}
?>




<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Farmer Register</title>
    <link rel="stylesheet" href="../css/regstyle.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
      .error-label {
          color: red;
      }
      .success-label {
          color: green;
      }
  </style>
  </head>
  <body>
    <div class="container">
      <header>Farmers <br>Signup Form</header>
      <br><label id="error-label" class="error-label"><?php echo $error_message; ?></label><br>
        <label id="success-label" class="success-label"><?php echo $success_message; ?></label><br>


      <div class="progress-bar">
        <div class="step">
          <p>Name</p>
          <div class="bullet">
            <span>1</span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p>Info</p>
          <div class="bullet">
            <span>2</span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p>Birth</p>
          <div class="bullet">
            <span>3</span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
        <div class="step">
          <p>Account</p>
          <div class="bullet">
            <span>4</span>
          </div>
          <div class="check fas fa-check"></div>
        </div>
      </div>
      <div class="form-outer">

        <form action="FarmersRegphp.php" method="POST" enctype="multipart/form-data">
          <div class="page slide-page">
            <div class="title">Basic Info:</div>
            <div class="field">
              <div class="label">Fullname</div>
              <input type="text" name="Fullname" id="Fullname">
            </div>
            <div class="field">
              <div class="label">Profile</div>
              <input type="text" id="Filename"  name="Filename" readonly>
            </div>
            <input type="file" id="Data" accept="image/*" name="Data">
            <div class="field">
              <button class="firstNext next">Next</button>
            </div>
          </div>

          <div class="page">
            <div class="title">Other Information:</div>
            <div class="field">
              <div class="label">Address</div>
              <input type="text" name="Address" id="Address">
            </div>
            <div class="field">
              <div class="label">Email Address</div>
              <input type="text" name="Email" id="Email">
            </div>
            <div class="field">
              <div class="label">Phone Number</div>
              <input type="Number" name="Number" id="Number">
            </div>
            <div class="field btns">
              <button class="prev-1 prev">Previous</button>
              <button class="next-1 next">Next</button>
            </div>
          </div>

          <div class="page">
            <div class="title">Date of Birth:</div>
            <div class="field">
              <div class="label">Date</div>
              <input type="date" name="Birthday" id="Birthday">
            </div>
            <div class="field">
              <div class="label">Gender</div>
              <select name="Gender" id="Gender">
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
              </select>
            </div>
            <div class="field btns">
              <button class="prev-2 prev">Previous</button>
              <button class="next-2 next">Next</button>
            </div>
          </div>

          <div class="page">
            <div class="title">Login Details:</div>
            <div class="field">
              <div class="label">Username</div>
              <input type="text" name="Username" id="Username">
            </div>
            <div class="field">
              <div class="label">Password</div>
              <input type="password" name="Password" id="Password">
            </div>
            <div class="field btns">
              <button class="prev-3 prev">Previous</button>
              <button class="submit">Submit</button>
            </div>
          </div>
        </form>

        <script>
          document.getElementById('Data').addEventListener('change', function() {
              var input = this;
              var filenameTextbox = document.getElementById('Filename');
              
              if (input.files.length > 0) {
                  filenameTextbox.value = input.files[0].name;
              } else {
                  filenameTextbox.value = '';
              }
          });

         
      </script>
        <div class="text sign-up-text">Already have an account? <a href="../FarmersLogin.html">Log-in Now</a></div>
      </div>
      
    </div>
    <script src="../css/script.js"></script>

  </body>
</html>
