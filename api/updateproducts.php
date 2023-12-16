<?php
// Include the MongoDB PHP driver
require 'vendor/autoload.php';


use MongoDB\Client;

// MongoDB Atlas connection string
$connectionString = "mongodb+srv://kenUser:KenPassword@atlascluster.qrj9egp.mongodb.net/Agriculture";

try {
    // Create a MongoDB client instance
    $client = new Client($connectionString);

    // Select the database and collection
    $database = $client->Agriculture; // Replace with your database name
    $collection = $database->Vendors; // Replace with your collection name

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get user input from the form
        $name = $_POST['Username'];


    $palay = $_POST['palay'];
    $tubo = $_POST['tubo'];
    $carrots = $_POST['carrots'];

    


        // Define the filter to identify the document to update based on the username
        $filter = ['Username' => $name];

        // Define the update operation based on the user input
        $update = [
            '$set' => [
				'carrotscart' => $carrots,
                'palaycart' => $palay,
                'tubocart' => $tubo,
                
                
            ],
        ];

        // Update data in the collection
        $result = $collection->updateOne($filter, $update);

        if ($result->getModifiedCount() > 0) {
            echo "Products Added to the cart!";
            
            
        } else {
            echo "Products not added";
        }
    } else {
        echo "Error";
        
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>

<div id="center_button"><button onclick="location.href='../vendors/buyCrops/chocolate.html'">back</button></div>
