<?php
require 'vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

// Connect to MongoDB Atlas
$client = new MongoDB\Client("mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority");
$collection = $client->Agriculture->Farmers;

// Get user input from the form
$Username = $_POST['Username'];
$Product = $_POST['product-title'];
$Quantity = (int)$_POST['Quantity']; // Ensure it's an integer
$Price = (int)$_POST['Price']; // Ensure it's an integer

// Define an associative array for product field names
$productFields = [
    "Palay" => ["Quantity" => "Palay", "Price" => "PalayPrice"],
    "Tubo" => ["Quantity" => "Tubo", "Price" => "TuboPrice"],
    "Carrots" => ["Quantity" => "Carrots", "Price" => "CarrotsPice"]
];

// Check if the selected product is valid
if (isset($productFields[$Product])) {
    $fieldQuantity = $productFields[$Product]["Quantity"];
    $fieldPrice = $productFields[$Product]["Price"];

    // Filter documents by the "username" field
    $filter = ['Username' => $Username];

    // Update the quantity and price fields
    $update = [
        '$inc' => [
            $fieldQuantity => $Quantity,
            $fieldPrice => $Price
        ]
    ];

    $result = $collection->updateOne($filter, $update);

    if ($result->getModifiedCount() > 0) {
		$message = "Crops inserted successfully!";
        
    } else {
		$message = "Crops Failed to Insert!";
        
    }
} else {
	$message = "Type the product!";
}

// No need to explicitly close the connection
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
						&nbsp; &nbsp;&nbsp; &nbsp;<button><a href="../farmers/index.html" class="register">Back </a></button>
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
							<label class="form-row-inner" > 
								<input placeholder="Barcode" id="product-name" oninput="showProduct()" type="text" name="product-name" class="input-text" required>
							</label>
						</div>
						</div>
				<form class="form-detail" action="addcropsindex.php" method="POST">
					<center>
				<img src="../farmers/addCrops/images/farmers heaven.jpg" alt="form" id="product-image">
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
								<input min="0" placeholder="Quantity" oninput="calculateTotal()" type="number" name="Quantity" id="Quantity" value="0" class="input-text" required>
							</label>
						</div>
						


						<div class="form-row" id="product-info">
							<label class="form-row-inner">
								<input placeholder="Crops Name" type="text" id="product-title" name="product-title" class="input-text" readonly >
							</label>
						</div>
						
						<div class="form-row">
							<label class="form-row-inner">
								<input placeholder="Price" type="number" name="Price" id="Price" class="input-text" readonly required>
								<p style="color: yellowgreen;">Price:<input type="text" name="Php" id="Php" readonly></p>
							</label>
						</div>
					</div>
					<br><br>
						<div class="form-row-last">
							<input type="submit" name="register" class="register" value="Submit">
						</div>
					</div>
			
				</form>
				

			</div>
		</div>
	</div>
	<script>
   // Get references to the itemName, quantity, and total elements
   var itemNameInput = document.getElementById("product-title");
        var quantityInput = document.getElementById("Quantity");
        var totalInput = document.getElementById("Price");

        // Prices for the items
        var itemPrices = {
            "Palay": 100,
            "Tubo": 200,
            "Carrots": 300
        };

        // Function to calculate and update the total
        function calculateTotal() {
            // Get the entered item name and quantity
            var itemName = itemNameInput.value.trim(); // Remove leading/trailing spaces
            var quantity = parseFloat(quantityInput.value);

            // Check if the entered item name exists in the itemPrices object
            if (itemPrices[itemName] !== undefined && !isNaN(quantity)) {
                // Get the price for the entered item name
                var price = itemPrices[itemName];

                // Calculate the total
                var total = quantity * price;

                // Display the total in the totalInput textbox
                totalInput.value = total; // Display total with 2 decimal places
            } else {
                // If either the item name is not valid or quantity is not a valid number, clear the total input
                totalInput.value = "";
            }
        }

        // Add event listeners to the itemName and quantity inputs
        itemNameInput.addEventListener("input", calculateTotal);
        quantityInput.addEventListener("input", calculateTotal);





 		// Function to show the product image and name
 			function showProduct() {
            var input = document.getElementById('product-name').value.toLowerCase();
            
            var productInfo = document.getElementById('product-info');
            var productImage = document.getElementById('product-image');
            var productTitle = document.getElementById('product-title');
			var phpvalue = document.getElementById('Php');
            
            // Define product data with image paths relative to the "images" folder
            var products = {
                '11111115': {
                    'image': '../farmers/addCrops/images/palay.jpg',
                    'name': 'Palay',
					'value': '100 php'
                },
                '22222220': {
                    'image': '../farmers/addCrops/images/tubo.jpg',
                    'name': 'Tubo',
					'value': '200 php'
                },
                '33333335': {
                    'image': '../farmers/addCrops/images/carrots.jpg',
                    'name': 'Carrots',
					'value': '300 php'
                },
				'': {
                    'image': '../farmers/addCrops/images/farmers heaven.jpg',
                    'name': '',
					'value': ''
                }
            };
            
            // Check if the input matches a product
            if (input in products) {
                productImage.src = products[input].image;
                productImage.alt = products[input].name;
                // Set the product title as the value of the input field
                productTitle.value = products[input].name;
				phpvalue.value = products[input].value;
                productInfo.style.display = 'block';
            } else {
                // If no match, hide the product display and clear the input field
                productInfo.style.display = 'none';
                productTitle.value = '';
            }
        }

		function openCity(evt, cityName) {
		    var i, tabcontent, tablinks;
		    tabcontent = document.getElementsByClassName("tabcontent");
		    for (i = 0; i < tabcontent.length; i++) {
		        tabcontent[i].style.display = "none";
		    }
		    tablinks = document.getElementsByClassName("tablinks");
		    for (i = 0; i < tablinks.length; i++) {
		        tablinks[i].className = tablinks[i].className.replace(" active", "");
		    }
		    document.getElementById(cityName).style.display = "block";
		    evt.currentTarget.className += " active";
		}

		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	</script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
