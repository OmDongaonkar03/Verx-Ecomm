<?php
	include('config.php');
	
	$userIP = $_SERVER['REMOTE_ADDR'];
	$current_page_url = $_SERVER['REQUEST_URI'];
	$string=exec('getmac');
	$mac=substr($string, 0, 17); 
	
	if(isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
		$latitude = $_COOKIE['latitude'];
		$longitude = $_COOKIE['longitude'];
		
		$check_sql = mysqli_query($conn,"SELECT count FROM `interaction` WHERE `MAC addr` = '$mac' AND `pages visited` = '$current_page_url' LIMIT 1");
		
		if(mysqli_num_rows($check_sql) > 0) {
			$update_sql = mysqli_query($conn,"UPDATE `interaction` SET count = count + 1 WHERE `MAC addr`  = '$mac' AND `pages visited` = '$current_page_url'");
		}else {
			$insert_sql = mysqli_query($conn,"INSERT INTO `interaction`(`MAC addr`, `IP`, `latitude`, `longitude`, `pages visited`, `count`) VALUES ('$mac','$userIP','$latitude','$longitude','$current_page_url',1)");
		}
	}
	
	session_start();
	if($_SERVER['REQUEST_METHOD'] == "POST"){			
		if(isset($_POST['name']) and isset($_POST['contact']) and isset($_POST['email']) and isset($_POST['password'])){
			$userID = (rand(1000,100000));
			$name = $_POST['name'];
			$contact = $_POST['contact'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$latitude = $_COOKIE['latitude'];  
            $longitude = $_COOKIE['longitude']; 
			
			$dp = 'uploads/user.png';
			date_default_timezone_set("Asia/Kolkata");
			$current_time = date("Y/m/d H:i");
			$current_date = date("d/m/Y");
			$checkSQL = mysqli_query($conn, "SELECT * FROM `signup` WHERE `email` = '$email'");
			$rows = mysqli_num_rows($checkSQL);
			if($rows == 0){
				mysqli_query($conn,"INSERT INTO `signup`(`id`,`name`, `contact`, `email`, `password`,`Profile photo`, `Status`,`date`,`latitude`,`longitude`) VALUES ('$userID','$name','$contact','$email','$password','$dp','Pending','$current_date','$latitude','$longitude')");
				
				mysqli_query($conn,"INSERT INTO `notifications`(`title`, `detail`,`timestamp`) VALUES ('New user Access Request','$name has sent a request to access the website','$current_time')");
				
				mysqli_query($conn,"UPDATE `interaction` SET `UserID`='$userID' WHERE `MAC addr`  = '$mac'");
				
				$_SESSION["user_email"] = $email;
				header("Location:pending_registration.php");
				exit;
			}else{
				echo "Email Already In Use";
			}
			
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VerX Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            min-height: 100vh;
            margin: 0;
            padding: 15px;
        }
        .signup-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 5.5%;
        }
        .signup-form {
            padding: 2rem;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        @media (max-width: 991px) {
            .signup-image {
                display: none;
            }
            .signup-form {
                width: 100%;
            }
        }
    </style>
</head>
<body class="">
    <div class="container-fluid">
        <div class="row signup-container">
            <div class="col-lg-6 d-flex align-items-center">
                <img src="images\signup img.jpeg" class="img-fluid rounded shadow" alt="VerX">
            </div>
            <div class="col-lg-6 signup-form">
                <form method="POST" onsubmit="submitForm(event)">
					<!-- Hidden fields for coordinates -->
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
					
                    <h2 class="mb-4 text-center text-uppercase">Sign Up</h2>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="number" class="form-control" id="contact" name="contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailID" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="emailID" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="login-link">
                        <p class="text-muted">Already have an account? <a href="login.php" class="text-primary">Log In</a></p>
                        <p class="text-muted">Are you a admin? <a href="admin/admin login.php" class="text-primary">Log In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<script>
		function resetForm() {
			document.getElementById('name').value = '';
			document.getElementById('contact').value = '';
			document.getElementById('emailID').value = '';
			document.getElementById('password').value = '';
		}
	</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>