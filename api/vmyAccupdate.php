<?php
// Include the MongoDB PHP driver
require '../vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

use MongoDB\Client;

// MongoDB Atlas connection string
$connectionString = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";

try {
    // Create a MongoDB client instance
    $client = new Client($connectionString);

    // Select the database and collection
    $database = $client->Agriculture; // Replace with your database name
    $collection = $database->Vendors; // Replace with your collection name

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get user input from the form
        $name = $_POST['info'];
        $Fullname = $_POST['Fullname'];
    $Address = $_POST['Address'];
    $Password = $_POST['Password'];
    $Gender = $_POST['Sex'];
    $Birthday = $_POST['Birthday'];
    $ContactNum = $_POST['ContactNum'];
    $Email = $_POST['Email'];
    


        // Define the filter to identify the document to update based on the username
        $filter = ['Username' => $name];

        // Define the update operation based on the user input
        $update = [
            '$set' => [
                'Fullname' => $Fullname,
                'Address' => $Address,
                'Password' => $Password,
                'Gender' => $Gender,
                'Birthday' => $Birthday,
                'ContactNum' => $ContactNum,
                'Email' => $Email,
            ],
        ];

        // Update data in the collection
        $result = $collection->updateOne($filter, $update);

        if ($result->getModifiedCount() > 0) {
            $errorMsg = "Document updated successfully.";
            $errorMsg = $POST['new'];
            header("Location: index.php");
            
            
        } else {
            $errorMsg = "Document not updated.";
            $errorMsg = $POST['new'];
            header("Location: index.php");
        }
    } else {
        $errorMsg = "Please submit the form to update user information.";
        $errorMsg = $POST['new'];
        header("Location: index.php");
        
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
