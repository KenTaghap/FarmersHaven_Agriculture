<?php
require 'vendor/autoload.php'; // Load Composer's autoloader
error_reporting(E_ERROR | E_PARSE);

// MongoDB connection configuration
$mongoURI = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";
$dbName = "Agriculture";
$collectionName = "Products";
$collectionCart = "Cart";

// Create a MongoDB client
$mongoClient = new MongoDB\Client($mongoURI);

// Select database and collection
$database = $mongoClient->$dbName;
$collection = $database->$collectionName;
$Cartcollection = $database->$collectionCart;

// Check if a search term (username) is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Find documents based on the search term (username)
$filter = [];



if (!empty($searchTerm)) {
    $filter = ['name' => $searchTerm];
}

// Find documents matching the filter
$cursor = $collection->find($filter);

// Fetch data and store in an array for HTML rendering
$productData = [];
foreach ($cursor as $document) {
    // Assuming 'Data' field contains the binary image data
    $imageData = $document->Data; // Change 'Data' to your actual field name
    $base64Image = base64_encode($imageData); // Convert binary data to base64

    $productData[] = [
        'Username' => $document->Username,
        'name' => $document->name,
        'price' => $document->price,
        'quan' => $document->quan,
        'image' => $base64Image, // Add base64 encoded image data to the array
    ];
}







// Handle adding items to the cart for a specific user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($productData as $product) {
        $buttonName = 'add_to_cart_' . $product['name'];

        if (isset($_POST[$buttonName])) {
            $vendor = $_POST['displayName'];
            $productName = $product['name'];
            $productPrice = $product['price'];
            $quantityFieldName = 'quantity_' . $product['name'];
            $productQuantity = isset($_POST[$quantityFieldName]) ? $_POST[$quantityFieldName] : 0;

            // Insert the current product into the Cart collection
            $insertResult = $Cartcollection->insertOne([
                "username" => $vendor,
                "product" => $productName,
                "price" => $productPrice,
                "quantity" => $productQuantity,
                // Include other fields here...
            ]);

            if ($insertResult->getInsertedCount() > 0) {
                echo "Product '$productName' added successfully!";
            } else {
                // Product insertion failed, set an error message
                echo "Product '$productName' not added. Please try again.";
            }

            // Break the loop after identifying the clicked button
            break;
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>Vendors Dashboard</title>
    

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendors/css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="../vendors/css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="../vendors/css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../vendors/css/custom.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
   

    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                   
                    <a class="navbar-brand" href="#"><img src="../vendors/images/logo.png" class="logo" alt="" width="200" height="150" ></a>
                </div>
                <!-- End Header Navigation -->

                <div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                   


                        <ul >
                            <li class="nav-item active"><a class="nav-link" href="../vendors/index.html">Home</a></li>
                            <li class="nav-item active"><a class="nav-link" href="BuyCrops.php">Buy Crops</a></li>
                            <li class="nav-item active"><a class="nav-link" href="cart.php">Cart</a></li>
                            <li class="nav-item active"><a class="nav-link" href="vmyAccindex.php">My Account</a></li>
                            <li class="nav-item active"><a class="nav-link" href="../vendors/about.html">About Us</a></li>
                            <li><a class="nav-link" href="../VendorsLogin.html">Logout</a></li>
                        </ul>
                
                 </div>
                 <button><span class="nav-link" onclick="openNav()">Menu</span></button>

                 <script>
        function openNav() {
          document.getElementById("mySidenav").style.width = "250px";
        }
        
        function closeNav() {
          document.getElementById("mySidenav").style.width = "0";
        }
     </script>
                 <style>
                 *,
*::after,
*::before {
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
}
                 
                 .sidenav {
   height: 100%;
   width: 0;
   position: fixed;
   z-index: 1;
   top: 0;
   left: 0;
   background-color: #111;
   overflow-x: hidden;
   transition: 0.5s;
   padding-top: 60px;
}

.sidenav a {
   padding: 8px 8px 8px 32px;
   text-decoration: none;
   font-size: 20px;
   color: #ffffff;
   display: block;
   transition: 0.3s;
}

.sidenav a:hover {
   color: #f26522;
}

.sidenav .closebtn {
   position: absolute;
   top: 0;
   right: 25px;
   font-size: 36px;
   margin-left: 50px;
}

@media screen and (max-height: 450px) {
   .sidenav {
       padding-top: 15px;
   }
   .sidenav a {
       font-size: 18px;
   }
}

.box {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
        }

        h1, h4 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: none;
            width: 100%;
            max-width: 300px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        ul.product-list {
            list-style-type: none;
            padding: 0;
        }

        li.product-item {
            border: 2px solid white;
            margin-bottom: 20px;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        .product-image {
            max-width: 200px;
            margin-right: 20px;
            margin-bottom: 10px;
        }

        .product-details {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .product-details span {
            font-weight: bold;
        }

        .click {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            margin: 20px auto;
            background-color: #4CAF50;
            color: white;
        }

        button a {
            color: white;
            text-decoration: none;
        }
                 
        .product-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-start; /* Change this based on your alignment preference */
        margin-top: 20px; /* Add margin for spacing */
    }

    .product-item {
        width: 300px; /* Set a fixed width for each product item (adjust as needed) */
        border: 2px solid white;
        margin: 10px; /* Add margin to separate products */
        padding: 10px;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.7); /* Set a background color */
        border-radius: 10px;
    }
                 </style>










                <!-- Collect the nav links, forms, and other content for toggling -->
                
                <!-- /.navbar-collapse -->

              
            </div>
           
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->

    <!-- Start Top Search -->
    <div class="top-search">
        <div class="box">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->

    <!-- Start Slider -->
   
    <!-- End Slider -->

   
        <h1 style="color:black;">Farmers Products</h1>
        
        <!-- Search form -->
        <!-- Search form -->
        <form action="" method="GET">
            <label for="search">Products Name:</label>
            <input type="text" id="search" name="search" placeholder="None" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <input type="submit" value="Display">
        </form>

        <form method="POST">
        <span style="color:black;">Vendor Username:</span>
            <input type="text" name="displayName" id="displayName" >
      
      <script>
      // Retrieve the name from localStorage
      var name = localStorage.getItem("Username");

      // Display the name on page2.html
      if (name) {
          document.getElementById("displayName").value = name;
      }
  </script>
        <!-- Start of product list -->
<ul class="product-list">
    <?php foreach ($productData as $product) : ?>
        <li class="product-item">
           
                <div class="product-details">
                    <div class="product-image">
                        <?php
                        if (isset($product['image']) && !empty($product['image'])) {
                            echo '<img src="data:image/jpeg;base64,' . $product['image'] . '" class="product-image" alt="Product Image">';
                        } else {
                            echo 'Image not available';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="product-details">
                    <span style="color:black;">Products Name:</span>
                    <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                    <?php echo $product['name']; ?>
                </div>
                <div class="product-details">
                    <span style="color:black;">Price:</span>
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <?php echo $product['price']; ?>
                </div>
                <div class="product-details">
                    <span style="color:black;">Stock:</span>
                    <span class="product-info" style="color:black;"></span>
                    <input type="hidden" name="product_stock" value="<?php echo $product['quan']; ?>">
                    <?php echo $product['quan']; ?>
                </div>
                <div class="product-details">
                    <span style="color:black;">Quantity:</span>
                    <input type="number" placeholder="Quantity" value="0" min="0" name="quantity_<?php echo $product['name']; ?>" id="quantity_<?php echo $product['name']; ?>"/>
                </div>
                <div class="product-details">
                    <input type="submit" value="Add To Cart" name="add_to_cart_<?php echo $product['name']; ?>" class="click">
                </div>
                </form>
        </li>
    <?php endforeach; ?>
</ul>


    <!-- Start Footer  -->
    <footer>
        <div class="footer-main">
        
       
       
            <div class="container">
				<hr>
                <div class="row">
                    
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link">
                            
                            <h4>Farmer's Heaven | Group 6</h4>
                            <ul>
                  <li><a href="https://www.facebook.com/jonel.boncalan.3">Jonel Salvosa Boncalan</a></li>
                  <li><a href="https://www.facebook.com/ClintVincent.Taghap.19">Clint Vincent O. Taghap</a></li>
                  <li><a href="https://www.facebook.com/angeloemil.gabriel">Angelo Gabriel</a></li>
                  <li><a href="https://www.facebook.com/HxHnamcho29">Man Adam Intuan</a></li>
                  <li><a href="https://www.facebook.com/mark.nicolas.100483">Mark Nicolas</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer  -->

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2023</p>
    </div>
    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    <!-- ALL JS FILES -->
    <script src="../vendors/js/jquery-3.2.1.min.js"></script>
    <script src="../vendors/js/popper.min.js"></script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="../vendors/js/jquery.superslides.min.js"></script>
    <script src="../vendors/js/bootstrap-select.js"></script>
    <script src="../vendors/js/inewsticker.js"></script>
    <script src="../vendors/js/bootsnav.js."></script>
    <script src="../vendors/js/images-loded.min.js"></script>
    <script src="../vendors/js/isotope.min.js"></script>
    <script src="../vendors/js/owl.carousel.min.js"></script>
    <script src="../vendors/js/baguetteBox.min.js"></script>
    <script src="../vendors/js/form-validator.min.js"></script>
    <script src="../vendors/js/contact-form-script.js"></script>
    <script src="../vendors/js/custom.js"></script>
</body>

</html>
