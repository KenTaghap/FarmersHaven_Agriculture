<?php
require 'vendor/autoload.php'; // Load Composer's autoloader
error_reporting(E_ERROR | E_PARSE);
// MongoDB connection configuration
$mongoURI = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";
$dbName = "Agriculture";
$collectionName = "Cart";

// Create a MongoDB client
$mongoClient = new MongoDB\Client($mongoURI);

// Select database and collection
$database = $mongoClient->$dbName;
$collection = $database->$collectionName;

// Check if a search term (username) is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Find documents based on the search term (username)
$filter = [];
if (!empty($searchTerm)) {
    $filter = ['username' => $searchTerm];
}

// Find documents matching the filter
$cursor = $collection->find($filter);

// Initialize total sum
$totalSum = 0;
// Fetch data and store in an array for HTML rendering
$productData = [];
foreach ($cursor as $document) {


    $productData[] = [
        'username' => $document->username,
        'product' => $document->product,
        'price' => $document->price,
        'quantity' => $document->quantity,

    ];
    
    // Calculate total price for each product and add to the total sum
    $totalSum += $document->price * $document->quantity;
}

// If a buy action is triggered (for example, via a POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {


    $error_message = "";
$success_message = "";
    // Check if a search term (username) is provided
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    if (!empty($searchTerm)) {
        // Transfer all products with the provided username to another collection
        $filter = ['username' => $searchTerm];
        $cursor = $collection->find($filter);

        // Initialize an array to store products for transfer
        $productsToTransfer = [];

        foreach ($cursor as $product) {
            $productsToTransfer[] = [
                'username' => $product->username,
                'product' => $product->product,
                'price' => $product->price,
                'quantity' => $product->quantity
                // Add more fields if necessary
            ];
        }



if (!empty($productsToTransfer)) {
    // Transfer products to another collection (e.g., 'PurchasedItems')
    $newCollection = $database->PurchasedItems;

    $insertResult = $newCollection->insertMany($productsToTransfer);

    if ($insertResult->getInsertedCount() === count($productsToTransfer)) {
        // Products successfully transferred

        // Delete all products with the provided username from the cart collection
        $deleteResult = $collection->deleteMany(['username' => $searchTerm]);

        if ($deleteResult->getDeletedCount() === count($productsToTransfer)) {
            $successMessage = "Thank you for Purchasing, The products will send in your Email. please Wait within 24 hours.";
            // You can add further actions or messages here if needed
        } else {
            $errorMessage ="it seems your Products cant be peoceed. please try again.";
            // Handle deletion failure from the cart collection
        }
    } else {
        // Handle insertion failure into the new collection
        $errorMessage = "Failed to transfer products to the PurchasedItems collection.";
    }
} else {
    // No products found to transfer
    $errorMessage = "No products found for the specified username.";
}
} else {
// No username provided
$errorMessage = "No username provided for product transfer.";
}

// Redirect to the cart page or another page after the transfer and deletion process
if (isset($searchTerm)) {
$redirectURL = 'cart.php?search=' . urlencode($searchTerm);
} else {
$redirectURL = 'cart.php';
}

if (isset($successMessage)) {
header('Location: ' . $redirectURL . '&success=' . urlencode($successMessage));
} else {
header('Location: ' . $redirectURL . '&error=' . urlencode($errorMessage));
}
exit;
}









?>






<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farmers Monitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-image: url('../farmers/Monitor/images/manipulation-wallpaper-preview.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
            color: white;
        }

        .container {
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
            max-width: 100px;
            margin-right: 20px;
            margin-bottom: 10px;
        }

      
        .product-details span {
            font-weight: bold;
        }

        button {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Cart Page</h1>
        
        <!-- Search form -->
        <form action="" method="GET">
            <label for="search">Username:</label>
            <input type="text" id="search" name="search" placeholder="None" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <input type="submit" value="Display">
        </form>
        <form action="" method="POST">
        <ul class="product-list" style="display: <?php echo (!empty($searchTerm)) ? 'flex' : 'none'; ?>;">
            <?php foreach ($productData as $product) : ?>
                <li class="product-item">
                    <div class="product-details" style="display:none;">
                        <span>Username:</span>
                        &nbsp;&nbsp;
                        <span class="product-info"><?php echo $product['username']; ?></span>
                    </div>
                   
                    <div class="product-details">
                    <span>Name:</span>
                    <input type="hidden" name="product_name" value="<?php echo $product['product']; ?>">
                    <?php echo $product['product']; ?>
                    </div>
                    <div class="product-details">
                    <span>Price:</span>
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <?php echo $product['price']; ?>
                    </div>
                    <div class="product-details">
                    <span>Quantity:</span>
                    <input type="hidden" name="quantity" value="<?php echo $product['quantity']; ?>">
                    <?php echo $product['quantity']; ?>
                    </div>
 <!-- Calculate total price -->
 <div class="product-details" style="color:green;">
                        <span style="color:green;">Total Price:</span>
                        <?php
                        $totalPrice = $product['price'] * $product['quantity'];
                        echo $totalPrice;
                        ?>
                    </div>


                </li>
            <?php endforeach; ?>
        </ul>
<!-- Add a hidden input field to trigger the buy action -->
<input type="hidden" name="buy" value="1">
    <button type="submit">Buy Now</button>
    </form>
 <!-- Display success or error message if provided in the URL parameters -->
 <?php
    if (isset($_GET['success'])) {
        echo '<p style="color: green;">' . htmlspecialchars($_GET['success']) . '</p>';
    } elseif (isset($_GET['error'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>


        <button><a href="../vendors/index.html">Back to Homepage</a></button>
    </div>
</body>
</html>
