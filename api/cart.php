<?php
require 'vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

use MongoDB\Client;

// Replace with your MongoDB Atlas connection string
$connectionString = "mongodb+srv://kenUser:KenPassword@atlascluster.qrj9egp.mongodb.net/Agriculture";

try {
    $client = new Client($connectionString);
    $collection = $client->Agriculture->Vendors; // Replace with your database and collection names

    if (isset($_POST['Username'])) {
        $Username = $_POST['Username'];
        $filter = ['Username' => $Username];
        $userInfo = $collection->findOne($filter);

        // Continue with the rest of your code
		$carrotscart = $userInfo['carrotscart'];
        $palaycart = $userInfo['palaycart'];
        $tubocart = $userInfo['tubocart'];
        



    } else {
        // Handle the case where "Username" is not set in the POST request
        // You can display an error message or take appropriate action.
        $carrotscart = "";
        $palaycart = "";
        $tubocart = "";


    }

    if (isset($_POST['display'])) {
        // Handle the "Display" button click
        // You can keep your existing display logic here
    } elseif (isset($_POST['update'])) {
        // Handle the "Update" button click
        $Username = $_POST['Username'];
         // Continue with the rest of your code
         $palaycart = $_POST['inputNumber1'];
         $tubocart = $_POST['inputNumber2'];
         $carrotscart = $_POST['inputNumber3'];


         $palayp = $_POST['result1'];
         $tubop= $_POST['result2'];
         $carrotsp = $_POST['result3'];

        // Create an update filter based on the username
        $filter = ['Username' => $Username];

        // Create an update document with the new values
        $updateDocument = [
            '$set' => [
                'Palay' => $palaycart,
                'Tubo' => $tubocart,
                'Carrots' => $carrotscart,
                'PalayPrice' => $palayp,
                'TuboPrice' => $tubop,
                'CarrotsPice' => $carrotsp
            ]
        ];

        // Perform the update in the MongoDB database
        $result = $collection->updateOne($filter, $updateDocument);

        if ($result->getModifiedCount() > 0) {
            // The update was successful
            echo "Thank you for purchasing!";
        } else {
            // The update did not modify any documents (username not found)
            echo "Something error, cannot buy.";
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
        $carrotscart = "";
        $palaycart = "";
        $tubocart = "";
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit-no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Farmershaven</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../vendors/buyCrops/css/bootstrap.css" />
    <!-- slick slider stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet" />
    <!-- slick slider -->

    <link rel="stylesheet" href="../vendors/buyCrops/css/slick-theme.css" />
    <!-- font awesome style -->
    <link href="../vendors/buyCrops/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="../vendors/buyCrops/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="../vendors/buyCrops/css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
    <div class="main_body_content">
        <div class="hero_area">
            <!-- header section strats -->
            <header class="header_section">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="navbar-brand" href="#">
                            Farmers Haven
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>
                        <div class="collapse navbar-collapse " id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                <li class="nav-item ">
                  <a class="nav-link" href="../index.html">Home <span class="sr-only">(current)</span></a>
                </li>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="chocolate.html"> Buy Crops</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Cart</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../../api/myAcc/index.php">My Account</a>
                </li>
				<li class="nav-item">
                  <a class="nav-link" href="../about.html">About Us</a>
                </li>
				<li class="nav-item">
                  <a class="nav-link" href="../VendorsLogin.html">Logout</a>
                </li>
              </ul>
                        </div>
                    </nav>
                </div>
            </header>
            <section class="contact_section layout_padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5 col-lg-4 offset-md-1 offset-lg-2">
                            <div class="form_container">
                                <div class="heading_container">
                                    <h2>
                                        Cart
                                    </h2>
                                </div>
                                <form action="cart.php" method="POST">
                                    <div>
                                        <center>
                                            <h4 style="color:black;">Username<input type="text" name="Username"
                                                id="Username" class="input-text" readonly></h4>
                                                <script>
					// Retrieve the name from localStorage
					var name = localStorage.getItem("Username");
			
					// Display the name on page2.html
					if (name) {
						document.getElementById("Username").value = name;
					}
				</script>
                                            <h4>(please hit enter on your Username or click display to display total products.)<h4><br>
                                        </center>
                                    </div>
                                    <div>
                                        <h4>Palay</h4>
                                        <input type="number" value="<?= $palaycart ?>" placeholder="none" id="inputNumber1"
                                            name="inputNumber1" readonly/>
                                            <input type="number" placeholder="price" id="result1"
                                            name="result1" readonly />
                                    </div>
                                    <div>
                                    <h4>Tubo</h4>
                                        <input type="number" value="<?= $tubocart ?>" placeholder="none" id="inputNumber2"
                                            name="inputNumber2"  readonly/>
                                            <input type="number"  placeholder="price" id="result2"
                                            name="result2" readonly />
                                    </div>
                                    <div>
                                    <h4>Carrots</h4>
                                        <input type="number" value="<?= $carrotscart ?>" placeholder="none" id="inputNumber3" name="inputNumber3" readonly/>
                                        <input type="number" placeholder="price" id="result3" name="result3" readonly />
                                    </div>
                                   
                                   <script>
        function calculateSum(inputId, resultId, multiplier) {
            var inputElement = document.getElementById(inputId);
            var resultElement = document.getElementById(resultId);
            
            var inputValue = parseFloat(inputElement.value);
            
            if (!isNaN(inputValue)) {
                var sum = multiplier * inputValue;
                resultElement.value = sum;
            } else {
                resultElement.value = "";
            }
        }
        
        function addEventListeners(inputId, resultId, multiplier) {
            var inputElement = document.getElementById(inputId);
            
            inputElement.addEventListener("input", function() {
                calculateSum(inputId, resultId, multiplier);
            });
            
            inputElement.addEventListener("blur", function() {
                if (inputElement.value === "") {
                    inputElement.value = "Please enter your username";
                }
            });
            
            inputElement.addEventListener("focus", function() {
                if (inputElement.value === "Please enter your username") {
                    inputElement.value = "";
                }
            });
            
            // Check if the textbox is empty when the page loads
            if (inputElement.value === "") {
                inputElement.value = "Please enter your username";
            }
        }
        
        function initializeTextboxes() {
            addEventListeners("inputNumber1", "result1", 100);
            addEventListeners("inputNumber2", "result2", 200);
            addEventListeners("inputNumber3", "result3", 300);
            
            // Calculate and display results when the page loads
            calculateSum("inputNumber1", "result1", 100);
            calculateSum("inputNumber2", "result2", 200);
            calculateSum("inputNumber3", "result3", 300);
        }
        
        // Call the initialization function when the page loads
        window.addEventListener("load", initializeTextboxes);
    </script>
                                    
                                   
                                    
                                    <div class="d-flex">
                                        <button type="submit" id="displayButton" name="display" class="btn btn-primary" ">Display</button>
										&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                        <button type="submit" id="updateButton" name="update" class="btn btn-primary" onclick="submitForm()">Update</button>
                                        <script>
    function submitForm() {
      if (confirm("Are you sure you want to buy this products?")) {
        var form = document.getElementById("cart");
        var formData = new FormData(form);

        // Get the user's email from the URL query parameter
        var urlParams = new URLSearchParams(window.location.search);
        var userEmail = urlParams.get("Username");

        // Add the user's email to the form data
        formData.append("username", userEmail);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              var response = xhr.responseText;
              alert(response);
            }
          }
        };

        xhr.open("POST", "cart.php", true);
        xhr.send(formData);
      } else {
        alert("cannot buy.");
      }
    }
  </script>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <script src="../vendors/buyCrops/js/jquery-3.4.1.min.js"></script>
        <script src="../vendors/buyCrops/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.js"></script>
        <script src="../vendors/buyCrops/js/custom.js"></script>
    </div>
</body>
</html>
