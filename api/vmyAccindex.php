<?php
require 'vendor/autoload.php';

use MongoDB\Client;

// Replace with your MongoDB Atlas connection string
$connectionString = "mongodb://kenUser:KenPassword@ac-kvsfcpt-shard-00-00.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-01.qrj9egp.mongodb.net:27017,ac-kvsfcpt-shard-00-02.qrj9egp.mongodb.net:27017/Agriculture?ssl=true&replicaSet=atlas-4pn5vh-shard-0&authSource=admin&retryWrites=true&w=majority";

$userFullname = "";
$userAddress = "";
$userUsername = "";
$userPassword = "";
$userGender = "";
$userBirthday = "";
$userContactNum = "";
$userEmail = "";
$userImage = null;

try {
    $client = new Client($connectionString);
    $collection = $client->Agriculture->Vendors; // Replace with your database and collection names

    if (isset($_POST['Username'])) {
        $Username = $_POST['Username'];

        $filter = ['Username' => $Username];
        $userInfo = $collection->findOne($filter);

        if ($userInfo) {
            $userFullname = $userInfo['Fullname'];
            $userAddress = $userInfo['Address'];
            $userUsername = $userInfo['Username'];
            $userPassword = $userInfo['Password'];
            $userGender = $userInfo['Gender'];
            $userBirthday = $userInfo['Birthday'];
            $userContactNum = $userInfo['ContactNum'];
            $userEmail = $userInfo['Email'];
            $userImage = $userInfo['File']; // Assuming 'File' is the field where the binary image data is stored
            // Add other fields as needed
        } else {
            $userFullname = "User not found";
            // Set other fields to default or empty values
        }
    } else {
        // Handle case where 'Username' is not set in $_POST
        $userFullname = "Please Click Display Button.";
        // Set other fields to default or empty values
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    // Handle MongoDB exceptions
    $userFullname = "Error retrieving user information";
    // Set other fields to default or empty values
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Farmers Account</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="../farmers/myAcc/css/sourcesanspro-font.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="../farmers/myAcc/css/style.css"/>
</head>
<body class="form-v8">
	
	<div class="page-content">
		<div class="form-v8-content">
			<div class="form-left">
				
				<?php if ($userImage): ?>
        <p><strong>Image:</strong></p>
        <img src="data:image/png;base64,<?= base64_encode($userImage) ?>" alt="form" width="469px" height="750px">
    <?php endif; ?>
			</div>
			<div class="form-right">
				<div class="tab">
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'sign-up')" id="defaultOpen">Account</button>
					</div>
					<div class="tab-inner">
						<button class="tablinks" onclick="openCity(event, 'sign-in')">Modify</button>
					</div>
				</div>
				
				<form class="form-detail" action="myAccindex.php" method="POST">
				<center>
				<h4 style="color:white;">You can Edit your personal Info,&nbsp; &nbsp;
				<input type="text" name="Username" id="Username"  readonly></h4>
				<script>
					// Retrieve the name from localStorage
					var name = localStorage.getItem("Username");
			
					// Display the name on page2.html
					if (name) {
						document.getElementById("Username").value = name;
					}
				</script>
				</center>
				
					<div class="tabcontent" id="sign-up">
					
								<p><strong>Fullname:</strong>&nbsp; &nbsp;  <?= $userFullname ?></p>
								
								<p ><strong>Address:</strong>&nbsp; &nbsp;  <?= $userAddress ?></p>
								<p><strong>Password:</strong>&nbsp; &nbsp;  <?= $userPassword ?></p>
								<p><strong>Gender:</strong>&nbsp; &nbsp;  <?= $userGender ?></p>
    							<p><strong>Birthday:</strong>&nbsp; &nbsp;  <?= $userBirthday ?></p>
								<p><strong>Contact#:</strong>&nbsp; &nbsp;  <?= $userContactNum ?></p>
								<p><strong>Email:</strong>&nbsp; &nbsp;  <?= $userEmail ?></p>


								
								<div class="form-row-last">
					
								<input type="submit" name="register" class="register" value="Display">
						</div>
								</form>
								<br>
								<br>
								<br>
						<div class="form-row-last">
							<button><a href="../farmers/index.html" class="register">Back </a></button>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br> 
								<br>
								<br>
								<br>
								<br>
								
						</div>
					</div>
				
					
				<form class="form-detail" action="myAccupdate.php" method="POST">
					<div class="tabcontent" id="sign-in">
								<input placeholder="Fullname" value="<?= $userFullname ?>" type="text" name="Fullname" id="Fullname" class="input-text" required >
								<input placeholder="Address" value="<?= $userAddress ?>" type="text" name="Address" id="Address" class="input-text" required>
								
								<input placeholder="Username" value="<?= $userUsername ?>" type="text" name="info" id="info" class="input-text" readonly required>
								<input placeholder="Password" value="<?= $userPassword ?>" type="text" name="Password" id="Password" class="input-text" required>
								&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
								&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
								<select id="gen" name="gen" onchange="updateTextBox()">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
								<input placeholder="Gender" value="<?= $userGender ?>" type="text" name="Sex" id="Sex" class="input-text" readonly required>			
		<script>
        function updateTextBox() {
            var selectedGender = document.getElementById("gen").value;
            document.getElementById("Sex").value = selectedGender;
        }
    </script>
								
								
								<input placeholder="Birthday" value="<?= $userBirthday ?>" type="date" name="Birthday" id="Birthday" class="input-text" required>
								<input placeholder="Contact #" value="<?= $userContactNum ?>" type="Number" name="ContactNum" id="ContactNum" class="input-text" required>
								<input placeholder="Email" value="<?= $userEmail ?>" type="text" name="Email" id="Email" class="input-text" required>
								

						<div class="form-row-last">
							<input type="submit" name="register" class="register" value="Edit">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
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
