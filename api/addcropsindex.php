<?php
require 'vendor/autoload.php';
error_reporting(E_ERROR | E_PARSE);
use MongoDB\Client;

// Replace with your MongoDB Atlas connection string
$connectionString = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";

$Username = "";
$name = "";
$price = "";
$quantity = "";
$Barcode = "";
$userImage = null;
$message = "";

try {
    $client = new Client($connectionString);
    $collection = $client->Agriculture->Products; // Replace with your database and collection names

    if (isset($_POST['code'])) {
        $Barcode = $_POST['Barcode'];

        $filter = ['Barcode' => $Barcode];
        $userInfo = $collection->findOne($filter);

        if ($userInfo) {
            $name = $userInfo['name'];
            $price = $userInfo['price'];
            $quantity = $userInfo['quan'];
            $userImage = $userInfo['Data']; // Assuming 'Data' is the field for the image in your MongoDB collection
        } else {
            $message = "Product not found.";
        }
    }

    if (isset($_POST['update'])) {
        $Username = $_POST['Username'];
        $updatedQuantity = $_POST['Quantity'];
		



        try {
            $filter = ['Username' => $Username];
            $updateQuantity = [
                '$set' => ['quan' => $updatedQuantity] // Assuming 'quan' is the field for quantity in your MongoDB collection
            ];

            $updateResult = $collection->updateOne($filter, $updateQuantity);

            if ($updateResult->getModifiedCount() > 0) {
                $message = "Quantity updated successfully!";
            } else {
                $message = "Failed to update quantity.";
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $message = "Error updating quantity.";
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    $message = "Error retrieving user information";
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
						&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<button><a href="../farmers/index.html" class="register">Back </a></button>
						&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<button><a href="addproducts.php" class="register">Add New Products </a></button>
						<center>
						<h4 >Add Crops</h4>
						
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
							
						</div>
						</div>
				<form class="form-detail" action="addcropsindex.php" method="POST">
					<center>
					<img src="data:image/png;base64,<?= base64_encode($userImage) ?>" alt="form" width="300px" height="300px">
				<style>
	/* Style the product image */
	#product-image {
            width: 350px;
            height: 350px;
			
        }
</style>
	</center>
<br><br>
					<div class="tabcontent" id="sign-up" >
						
						<div class="form-row">
							<label class="form-row-inner">
								<input type="text" name="Username" id="Username" class="input-text" required readonly>
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
								<input min="0" placeholder="Input Barcode" type="text" name="Barcode" id="Barcode"  class="input-text">
							</label>
						</div>
						

						<div class="form-row">
							<label class="form-row-inner">
								<input min="0" placeholder="Quantity" value="<?= $quantity ?>"  type="number" name="Quantity" id="Quantity" class="input-text" >
							</label>
						</div>
						


						<div class="form-row" id="product-info">
							<label class="form-row-inner">
								<input placeholder="Crops Name" type="text" value="<?= $name ?>" id="Name" name="Name" class="input-text" readonly >
							</label>
						</div>
						
						<div class="form-row">
							<label class="form-row-inner">
								<input placeholder="Price" type="number" value="<?= $price ?>" name="Price" id="Price" class="input-text" readonly >
							
							</label>
						</div>
					</div>
					<br><br>
						<div class="form-row-last">
						<input type="submit" style="display: none;" name="code" class="register" value="Display">
						&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" name="update" class="register" value="Submit">
						</div>
					</div>
			
				</form>
				

			</div>
		</div>
	</div>
	<script>
  
	</script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
