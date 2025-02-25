<?php
    include("function.php");

	$userIP = $_SERVER['REMOTE_ADDR'];
	$current_page_url = $_SERVER['REQUEST_URI'];
	$string=exec('getmac');
	$mac=substr($string, 0, 17);
	
	
	if(isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
		$latitude = $_COOKIE['latitude'];
		$longitude = $_COOKIE['longitude'];
		
		$check_sql = mysqli_query($conn,"SELECT count FROM interaction WHERE `MAC addr` = '$mac' AND `pages visited` = '$current_page_url' LIMIT 1");
		
		if(mysqli_num_rows($check_sql) > 0) {
			$update_sql = mysqli_query($conn,"UPDATE interaction SET count = count + 1 WHERE `MAC addr` = '$mac' AND `pages visited` = '$current_page_url'");
		}else {
			$insert_sql = mysqli_query($conn,"INSERT INTO `interaction`(`MAC addr`, `IP`, `latitude`, `longitude`, `pages visited`, `count`) 
			VALUES ('$mac', '$userIP','$latitude', '$longitude', '$current_page_url', 1)");
		}
	}
	
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if(isset($_POST['email']) and isset($_POST['password'])){
			$userEmail = $_POST['email'];
			$userPassword = $_POST['password'];
			$latitude = $_POST['latitude'];  
			$longitude = $_POST['longitude']; 
			
			$sql = mysqli_query($conn, "SELECT * FROM `signup` WHERE `email` = '$userEmail' AND `password` = '$userPassword'");
		
			if(mysqli_num_rows($sql) > 0){
				$data = mysqli_fetch_assoc($sql);
				$status = $data['Status'];
				
				$update_sql = mysqli_query($conn, "UPDATE signup SET latitude='$latitude', longitude='$longitude' WHERE email='$userEmail'");
				$_SESSION["user_email"] = $userEmail;
				if($status == 'Accepted'){    
					header("Location:index.php");
					exit();
				} else {
					header("Location:pending_registration.php");
					exit();
				}
			} else {
				echo "<script>alert('Invalid Email or Password')</script>";
			}
		}
	}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log-In VerX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <style>
    body{
        background-color: hsl(0, 0%, 96%)
    }
    section{
        margin-top:5%;
    }
  </style>
  <body>
        <section>
            <div class="px-4 py-5 px-md-5 text-center text-lg-start">
                <div class="container">
                    <div class="row gx-lg-5 align-items-center">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h1><span class="text-primary" style="font-size:85px">VerX</span></h1>
                            <p style="color: hsl(217, 10%, 50.8%)">
                                We're more than a clothing brand—we're a movement challenging traditional fashion paradigms.Our mission is simple: create clothing that empowers individuals, respects the planet, and pushes the boundaries of design and sustainability.
                            </p>
                        </div>
                
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <div class="card">
                                <div class="card-body py-5 px-md-5">
                                    <form method="POST">    
                                        <!-- Hidden fields for coordinates -->
                                        <input type="hidden" name="latitude" id="lat">
                                        <input type="hidden" name="longitude" id="lng">
                                        
                                        <!-- Email input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="email">Email address</label>
                                            <input type="email" id="email" name="email" class="form-control" required/>
                                        </div>
                    
                                        <!-- Password input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" id="password" name="password" class="form-control" required/>
                                        </div>
                    
                                        <!-- Submit button -->
                                        <button type="button" onclick="getLocation()" class="btn btn-primary btn-block mb-4 d-flex justify-content-center align-items-center">
                                            Log-In
                                        </button>
                                        
                                        <div class="mb-1 d-flex justify-content-center align-items-center p-1 rounded">
                                            Don't have a account?  <a href="signup.php">Sign-Up</a>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center p-2 rounded">
                                            Are you a admin? <a href="admin/admin login.php">Log-In</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition, showError);
			} else {
				alert("Geolocation is not supported by this browser.");
			}
		}
	
		function showPosition(position) {
			document.getElementById("lat").value = position.coords.latitude;
			document.getElementById("lng").value = position.coords.longitude;
			document.forms[0].submit();
		}
	
		function showError(error) {
			switch(error.code) {
				case error.PERMISSION_DENIED:
					alert("Please allow location access to login");
					break;
				case error.POSITION_UNAVAILABLE:
					alert("Location information is unavailable.");
					break;
				case error.TIMEOUT:
					alert("The request to get user location timed out.");
					break;
				case error.UNKNOWN_ERROR:
					alert("An unknown error occurred.");
					break;
			}
		}
    </script>
  </body>
</html>