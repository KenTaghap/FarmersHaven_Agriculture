<?php
require 'vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

use MongoDB\Client;

// Replace with your MongoDB Atlas connection string
$connectionString = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";

try {
    $client = new Client($connectionString);
    $collection = $client->Agriculture->Farmers; // Replace with your database and collection names

    // Retrieve user information by name
    $Username = $_POST['Username'];

    $filter = ['name' => $Username];
    $userInfo = $collection->findOne($filter);

    if ($userInfo) {
        $palay = $userInfo['Palay'];
        $palayPrice = $userInfo['PalayPrice'];
		$tubo = $userInfo['Tubo'];
        $tuboPrice = $userInfo['TuboPrice'];
		$carrots = $userInfo['Carrots'];
		$carrotsPice = $userInfo['CarrotsPice'];
 // Assuming 'image' is the field where the binary image data is stored
        // Add other fields as needed

	
    } else {
        $palay = "";
        $palayPrice = "";
        $tubo = "";
		$tuboPrice = "";
        $carrots = "";
		$carrotsPice = "";


    }
} catch (MongoDB\Driver\Exception\Exception $e) {
	$palay = "";
        $palayPrice = "";
        $tubo = "";
		$tuboPrice = "";
        $carrots = "";
		$carrotsPice = "";


}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Farmers Monitor</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<style>
        body {
  background-image: url('../farmers/Monitor/images/manipulation-wallpaper-preview.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;
}
        /* Basic CSS styling for the product list */
        ul.product-list {
            list-style-type: none;
            padding: 0;
        }

        li.product-item {
            border: 2px solid white;
            margin-bottom: 10px ;
            padding: 10px ;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-image {
            max-width: 100px; /* Set the maximum width for the product image */
            margin-right: 10px; /* Add some space between the image and text */
        }

        .product-details {
            flex: 1; /* Allow the product details to expand and fill the remaining space */
        }

        .product-name {
            font-weight: bold;
        }

        .product-price {
            color: yellowgreen;
        }

        .product-quantity {
            width: 50px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
	<h1 style="color: wheat;">Product List</h1>
    <h4 style="color:white;">This page is your Crops,&nbsp; &nbsp;<input type="text" name="Username" id="Username" class="input-text" readonly>.</h4>
				<script>
					// Retrieve the name from localStorage
					var name = localStorage.getItem("Username");
			
					// Display the name on page2.html
					if (name) {
						document.getElementById("Username").value = name;
					}
				</script>

    <ul class="product-list">
        <li class="product-item">
            <img class="product-image" src="../farmers/Monitor/images/palay.jpg" alt="Product 1 Image">
            <div class="product-details">
                <span class="product-name" style="color: wheat;">Palay</span>
				&nbsp; &nbsp;&nbsp; &nbsp;
            <span class="product-quantity" style="color: wheat;" ><?= $palay ?> </span>
				 &nbsp; &nbsp;&nbsp; &nbsp;
                 <span class="product-price"><?= $palayPrice ?></span>
            </div>
        </li>
        <li class="product-item">
            <img class="product-image" src="../farmers/Monitor/images/tubo.jpg" alt="Product 2 Image">
            <div class="product-details">
                <span class="product-name" style="color: wheat;">Tubo</span>
				&nbsp; &nbsp;&nbsp; &nbsp;
                <span class="product-quantity" style="color: wheat;" ><?= $tubo ?> </span>
				&nbsp; &nbsp;&nbsp; &nbsp;
                <span class="product-price"><?= $tuboPrice ?></span>
            </div>
        </li>
        <li class="product-item">
            <img class="product-image" src="../farmers/Monitor/images/carrots.jpg" alt="Product 3 Image">
            <div class="product-details">
                <span class="product-name" style="color: wheat;">Carrots</span>
				&nbsp; &nbsp;&nbsp; &nbsp;
                <span class="product-quantity" style="color: wheat;" ><?= $carrots ?> </span>
				&nbsp; &nbsp;&nbsp; &nbsp;
                <span class="product-price"><?= $carrotsPice ?></span>
            </div>
        </li>
        <!-- Add more product items as needed -->
    </ul>
	<button><a href="../farmers/index.html" class="register">Back </a></button>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>