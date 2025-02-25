<?php
	include('function.php');
	
	$email = $_SESSION['user_email'];
	$sql = mysqli_query($conn,"SELECT * FROM `signup` WHERE `email` = '$email'");
	if(mysqli_num_rows($sql) > 0){
		if($data = mysqli_fetch_assoc($sql)){
			$id = $data['id'];
			$name = $data['name'];
			$email = $data['email'];
			$contact = $data['contact'];
			$password = $data['password'];
			$status = $data['Status'];
			$dp = $data['Profile photo'];
		}
	}
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if($status == 'Accepted'){
			if(isset($_POST['name_update_req'])){
				$updatedName = $_POST['newName'];
				$nameSQL = mysqli_query($conn,"UPDATE `signup` SET `name`= '$updatedName' WHERE `id` = '$id'");
				echo "<script> alert('Name Changed Succefully') </script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
			if(isset($_POST['email_update_req'])){
				$updatedEmail = $_POST['newEmail'];
				$emailSQL = mysqli_query($conn,"UPDATE `signup` SET `email`= '$updatedEmail' WHERE `id` = '$id'");
				session_destroy();
				
				session_start();
				$_SESSION["user_email"] = $updatedEmail;
				echo "<script> alert('Email Changed Succefully') </script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
			if(isset($_POST['contact_update_req'])){
				$updatedContact = $_POST['newContact'];
				$contactSQL = mysqli_query($conn,"UPDATE `signup` SET `name`= '$updatedname' WHERE `id` = '$id'");
				echo "<script> alert('Contact Changed Succefully') </script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
			if(isset($_POST['password_update_req'])){
				$oldPass = $_POST['oldPass'];
				$updatedPass = $_POST['newPass'];
				if($oldPass == $password){
					$passSQL = mysqli_query($conn,"UPDATE `signup` SET `password`= '$updatedPass' WHERE `id` = '$id'");
				}else{
					echo "<script> alert('Something Went Wrong') </script>";
				}
				echo "<script> alert('Password Changed Succefully') </script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
			
			$uploaddir = 'uploads/';
			if (isset($_POST['profile_pic_update'])) {
				// Check if a file is uploaded
				if (empty($_FILES['profile_pic']['tmp_name'])) {
					echo "<script>alert('No file uploaded.');</script>";
					exit;
				}
				$uploadfile = $uploaddir . basename($_FILES['profile_pic']['name']);
				
				if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadfile)) {
					$updatedp = mysqli_query($conn,"UPDATE `signup` SET `Profile photo`='$uploadfile'");
					
					echo "<script>alert('File uploaded successfully.');</script>";
					echo "<script>window.location.href = window.location.href;</script>";
				} else {
					echo "<script>alert('Error: Failed to upload file.');</script>";
				}
			}
		}else{
				echo "<script> alert('Your registration request has not been accepted yet') </script>";
		}
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .option-section {
            border-radius: 4px;
            overflow: hidden;
        }
        .option-header {
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 10px;
        }
        .option-header:hover {
            background-color: #f8f9fa;
        }
        .change-form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: #f8f9fa;
        }
        .change-form.active {
            max-height: 300px;
            padding: 15px;
        }
        .change-form .form-content {
            opacity: 0;
            transition: opacity 0.3s;
        }
        .change-form.active .form-content {
            opacity: 1;
        }
    </style>
  </head>
  <body>
	<div>
		<?php
			navbar();
		?>
	</div>
	<div class="mt-3">
		<section style="bg-light">
			<div class="container py-5">
				<div class="row">
				<div class="col-lg-4">
					<!--profile pic sec -->
					<div class="card mb-4">
						<div class="card-body text-center">
							<div class="position-relative d-inline-block">
								<img src="<?php echo $data['Profile photo'];?>" alt="avatar"
									class="rounded-circle img-fluid" style="width: 150px;">
								<button class="btn btn-light btn-sm position-absolute bottom-0 end-0 rounded-circle p-2"
										data-bs-toggle="modal" data-bs-target="#profilePicModal">
									<i class="fas fa-pencil-alt"></i>
								</button>
							</div>
							<h5 class="my-3"><?php echo $name; ?></h5>
							<p class="text-muted mb-1">User</p>
						</div>
					</div>
					<div class="modal fade" id="profilePicModal" tabindex="-1" aria-labelledby="profilePicModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="profilePicModalLabel">Change Profile Picture</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form method="POST" enctype="multipart/form-data">
										<div class="mb-3">
											<label for="profilePicInput" class="form-label">Select new profile picture</label>
											<input type="file" class="form-control" id="profilePicInput" name="profile_pic" accept="image/*" required>
										</div>
										<div class="text-center">
											<button type="submit" class="btn btn-primary" name="profile_pic_update">Upload Picture</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!--profile pic sec ends-->
				</div>
				<div class="col-lg-8">
					<div class="card mb-4">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">User ID </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?php 
										echo $id;
									?></p>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Full Name</p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?php 
										echo $name;
									?></p>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Email</p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0">
										<?php
											echo $email;
										?>
									</p>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Contact</p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0">
										<?php
											echo $contact;
										?>
									</p>
								</div>
							</div>
							<hr>
							<div class="row justify-content-evenly">
								<div class="col-sm-3">
									<p class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeDetailsModal">
										Change Details <i class="fas fa-edit fa-10xs" style="margin-left:10px"></i>
									</p>
								</div>
								<!-- Modal -->
								<div class="modal fade" id="changeDetailsModal" tabindex="-1" aria-labelledby="changeDetailsModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="changeDetailsModalLabel">Change Details</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<!-- Name Section -->
												<div class="option-section mb-2">
													<div class="option-header d-flex justify-content-between align-items-center" data-section="name">
														<span>Change Name</span>
														<i class="fa fa-chevron-down transition-transform"></i>
													</div>
													<form class="change-form" id="nameForm" method="POST">
														<div class="form-content">
															<div class="mb-3">
																<?php echo " Current Name : $name" ?>
															</div>
															<div class="mb-3">
																<label class="form-label">New Name</label>
																<input type="text" class="form-control" placeholder="Enter new name" name="newName" required>
															</div>
															<button type="submit" class="btn btn-primary" name="name_update_req">Save Changes</button>
														</div>
													</form>
												</div>
							
												<!-- Email Section -->
												<div class="option-section mb-2">
													<div class="option-header d-flex justify-content-between align-items-center" data-section="email">
														<span>Change Email</span>
														<i class="fa fa-chevron-down"></i>
													</div>
													<form class="change-form" id="emailForm" method="POST">
														<div class="form-content">
															<div class="mb-3">
																<?php echo " Current Email : $email" ?>
															</div>
															<div class="mb-3">
																<label class="form-label">New Email</label>
																<input type="email" class="form-control" placeholder="Enter new email" name="newEmail" required>
															</div>
															<button type ="submit" class="btn btn-primary" name="email_update_req">Save Changes</button>
														</div>
													</form>
												</div>
							
												<!-- Contact Section -->
												<div class="option-section mb-2">
													<div class="option-header d-flex justify-content-between align-items-center" data-section="contact">
														<span>Change Contact</span>
														<i class="fa fa-chevron-down"></i>
													</div>
													<form class="change-form" id="contactForm" method="POST">
														<div class="form-content">
															<div class="mb-3">
																<?php echo " Current Contact : $contact" ?>
															</div>
															<div class="mb-3">
																<label class="form-label">New Contact</label>
																<input type="tel" class="form-control" placeholder="Enter new contact" name="newContact" required>
															</div>
															<button type="submit" class="btn btn-primary" name="contact_update_req">Save Changes</button>
														</div>
													</form>
												</div>
							
												<!-- Password Section -->
												<div class="option-section mb-2">
													<div class="option-header d-flex justify-content-between align-items-center" data-section="password">
														<span>Change Password</span>
														<i class="fa fa-chevron-down"></i>
													</div>
													<form class="change-form" id="passwordForm" method="POST">
														<div class="form-content">
															<div class="mb-3">
																<label class="form-label">Old Password</label>
																<input type="password" class="form-control" name="oldPass" placeholder="Enter old password" required>
															</div>
															<div class="mb-3">
																<label class="form-label">New Password</label>
																<input type="password" class="form-control" name="newPass" placeholder="Enter new password" required>
															</div>
															<button type="submit" class="btn btn-primary" name="password_update_req">Save Changes</button>
														</div>
													</form>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<a href="LandingPage.php" class="btn btn-danger">
										Log-out <i class="fa fa-sign-out fa-10xs" style="margin-left:10px"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</section>
	</div>
	<div class="mt-5">
		<?php
			footer()
		?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script>
        document.addEventListener('DOMContentLoaded', function() {
            const optionHeaders = document.querySelectorAll('.option-header');
            
            optionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const section = this.dataset.section;
                    const form = document.getElementById(section + 'Form');
                    const icon = this.querySelector('.fa');
                    const isCurrentlyActive = form.classList.contains('active');
                    
                    // Close all forms
                    document.querySelectorAll('.change-form').forEach(form => {
                        form.classList.remove('active');
                    });
                    
                    // Reset all icons
                    document.querySelectorAll('.option-header .fa').forEach(icon => {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                    
                    // If the clicked form wasn't active, open it
                    if (!isCurrentlyActive) {
                        form.classList.add('active');
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    }
                });
            });
        });
    </script>
  </body>
</html>