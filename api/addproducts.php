<?php
require 'vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

use MongoDB\BSON\Binary;
use MongoDB\Client;

// Initialize variables
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // MongoDB Atlas connection settings
    $mongoURI = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";
    $dbName = "Agriculture"; // Replace with your database name
    $collectionName = "Products"; // Replace with your collection name

    // Create the "uploads" directory if it doesn't exist
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory recursively
    }

    // Retrieve user data from the form
    $Username = $_POST["Username"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quan = $_POST["quan"];

    // Check if the username already exists in the database
    $client = new Client($mongoURI);
    $db = $client->$dbName;
    $collection = $db->$collectionName;
   

  
        // Upload profile picture
        $targetFile = $targetDir . basename($_FILES["Data"]["name"]);

        if ($_FILES["Data"]["error"] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["Data"]["tmp_name"];
            $imageData = file_get_contents($tmp_name);
            $mimeType = mime_content_type($tmp_name);

            // Check if the uploaded file is an image
            if (strpos($mimeType, 'image') !== false) {
                // Convert image data to binary
                $binaryData = new MongoDB\BSON\Binary($imageData, MongoDB\BSON\Binary::TYPE_GENERIC);

                try {
                    // Insert user data with the profile picture as binary data
                    $insertResult = $collection->insertOne([
                        "Username" => $Username,
                        "name" => $name,
						"Data" => $binaryData,
                        "price" => $price,
                        "quan" => $quan,
                        "Barcode" => "none",
                        // Include other fields here...
                    ]);

                    if ($insertResult->getInsertedCount() > 0) {
                        $message = "Products Registration successful!";
                    } else {
                        // Registration failed, set an error message
                        $message = "products Registration failed. Please try again.";
                    }

                    // Remove the uploaded file since it's already stored as binary data
                    unlink($targetFile);
                } catch (Exception $e) {
                    // Catch any exceptions and log the error message
                    $message = "MongoDB Error: " . $e->getMessage();
                }
            } else {
                $message = "Please upload an image file.";
            }
        } else {
            $message = "Error uploading file: " . $_FILES["Data"]["error"];
        }
    }

?>







<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Form-v8 by Colorlib</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="../farmers/addCrops/css/sourcesanspro-font.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="../farmers/addCrops/css/style.css"/>
</head>
<body class="form-v8">
	<div class="page-content">
		<div class="form-v8-content">
			<div class="form-left">


			</div>
			<div class="form-right">
				<div class="tab">
					<div class="tab-inner">
						&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<button><a href="addcropsindex.php" class="register">Back </a></button>
						
						<center>
						<h4 >Add New Products</h4>
						
					<br><br>
					<!-- Display the success or error message -->
				<?php if (!empty($message)): ?>
        <p style="color:yellowgreen;"><?php echo $message; ?></p>
    <?php endif; ?>
	</center>
					</div>
				</div>
<div class="form-detail">
				<div class="form-row">
							<label class="form-row-inner" > 
								
							</label>
						</div>
						</div>
				<form class="form-detail" action="addproducts.php" method="POST" enctype="multipart/form-data">
				
					<div class="tabcontent" id="sign-up" >
						
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="Username" id="Username" class="input-text" readonly>
								<script>
									// Retrieve the name from localStorage
									var name = localStorage.getItem("Username");
							
									// Display the name on page2.html
									if (name) {
										document.getElementById("Username").value = name;
									}
								</script>
							</label>
						</div>
<br><br>

					

						<div class="form-row">
							<label class="form-row-inner">
							<input placeholder="Name of Products" id="name" type="text" name="name" class="input-text" required>
							</label>
						</div>

						<div class="form-row">
						<input type="text" placeholder="Image name"  id="Filename"  name="Filename" readonly required>
						<input type="file" id="Data" accept="image/*" name="Data">
							</label>
						</div>
						<br>
						<br>

						<div class="form-row" id="product-info">
							<label class="form-row-inner">
								Price
								<input placeholder="Input Price" type="number" id="price" name="price" value="0" min="0" class="input-text" required >
							</label>
						</div>
						
						<div class="form-row">
							<label class="form-row-inner">
								Quantity
								<input placeholder="Input Quantity" type="number" value="0" min="0" name="quan" id="quan" class="input-text" required>
							</label>
						</div>
					</div>
					<br><br>
						<div class="form-row-last">
							<input type="submit" name="register" class="register" value="Create Products">
						</div>
					</div>
			
				</form>
				

			</div>
		</div>
	</div>
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
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>