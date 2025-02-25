<?php
	include("function.php");
	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		
		$sql= mysqli_query($conn,"INSERT INTO `connect request`(`userName`, `userEmail`, `userMSG`) VALUES ('$name','$email','$message')");
		
		if(!$sql){
			echo "<script>alert('error=')</script>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
		*{
			margin:0;
			padding:0;
		}
		body {
            background-color:white;
        }
        .contact-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .contact-form .form-control {
            border-radius: 25px;
            padding-left: 50px;
        }
        .contact-form .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: rgba(13, 110, 253, 1);
        }
        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .form-control-wrapper {
            position: relative;
        }
        .btn-custom {
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
        }
        .btn-custom:hover {
            background-color: #333333;
            color: white;
        }
    </style>
</head>
<body>
	<div>
		<?php
			navbar();
		?>
	</div>
    <div class="container" style="margin-top:8%;">
        <div class="row justify-content-center">
            <div class="col-md-15 col-lg-12">
                <div class="contact-container p-5">
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block text-center">
                            <img src="https://i.imgur.com/VRFiMzM.png" alt="Contact Image" class="img-fluid">
                        </div>
                        <div class="col-lg-7">
                            <form method="POST" class="contact-form">
                                <h2 class="text-center mb-4">Get in touch</h2>
                                
                                <div class="form-control-wrapper mb-3">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control" placeholder="Name" name="name" required>
                                </div>
                                
                                <div class="form-control-wrapper mb-3">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                                
                                <div class="form-control-wrapper mb-3">
                                    <textarea class="form-control" placeholder="Message" rows="4" name="message" required></textarea>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-custom btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="mt-5">
	<?php
		footer();
	?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>