<?php
require 'vendor/autoload.php'; // Load Composer's autoloader

// MongoDB connection configuration
$mongoURI = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";
$dbName = "Agriculture";
$collectionName = "Products";

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
    $filter = ['Username' => $searchTerm];
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

        .product-details {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
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
        <h1>Product List</h1>
        
        <!-- Search form -->
        <form action="" method="GET">
            <label for="search">Search by Username:</label>
            <input type="text" id="search" name="search" placeholder="Enter username" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <input type="submit" value="Search">
        </form>
        
        <ul class="product-list">
            <?php foreach ($productData as $product) : ?>
                <li class="product-item">
                    <div class="product-details">
                        <span>Username:</span>
                        &nbsp;&nbsp;
                        <span class="product-info"><?php echo $product['Username']; ?></span>
                    </div>
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
                        <span>Name:</span>
                        &nbsp;&nbsp;
                        <span class="product-info"><?php echo $product['name']; ?></span>
                    </div>
                    <div class="product-details">
                        <span>Price:</span>
                        &nbsp;&nbsp;
                        <span class="product-info"><?php echo $product['price']; ?></span>
                    </div>
                    <div class="product-details">
                        <span>Quantity:</span>
                        &nbsp;&nbsp;
                        <span class="product-info"><?php echo $product['quan']; ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <button><a href="../farmers/index.html">Back to Homepage</a></button>
    </div>
</body>
</html>
