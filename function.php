<?php
include("config.php");
session_start();

if(isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $sql = mysqli_query($conn, "SELECT * FROM `signup` WHERE `email` = '$email'");
    
    if($sql && mysqli_num_rows($sql) > 0) {
        $data = mysqli_fetch_assoc($sql);
    } else {
        echo "No user found with that email.";
    }
    $result = mysqli_query($conn, "SELECT count(*) AS total FROM `atcproduct` WHERE `userID` ='$data[id]'");
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $row['total'] = 0;
    }
}
$notification = mysqli_query($conn, "SELECT * FROM `user_notification`");

function navbar() {
    global $row, $notification;
	
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light container-fluid">
        <div class="container">
            <a href="index.php" class="navbar-brand">VerX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">VerX</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 gap-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productpage.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="AboutUs.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contactUs.php">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addtocart.php"><i class="fa fa-shopping-cart fa-10xs" aria-hidden="true"></i> Cart(' . $row['total'] . ')</a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link" href="wishlist.php"><i class="fas fa-heart fa-10xs"></i> Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link position-relative" role="button" data-bs-toggle="modal" data-bs-target="#notificationModal">
                                <i class="fa fa-bell fa-10xs"></i> Notification
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="userpage.php"><i class="fa fa-user fa-10xs" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 300px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">';
    
    if (mysqli_num_rows($notification) > 0) {
        while ($data_notification = mysqli_fetch_assoc($notification)) {
            echo
			'<a href="'. $data_notification['notification_link'] .'" class="text-decoration-none">
				<div class="notification-item border-bottom p-3">
					<div class="d-flex justify-content-between align-items-top">
						<h6 class="mb-2 text-dark">' . $data_notification['notification_title'] . '</h6>
						<small class="text-muted ms-2">' . $data_notification['timestamp'] . '</small>
					</div>
					<p class="text-muted mb-0">' . $data_notification['notification_detail'] . '</p>
				</div>
			</a>';
        }
    }
    
    echo '
				</div>
			</div>
		</div>
    </div>
    <style>
    .modal-body::-webkit-scrollbar {
        display: none;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    </style>';
}


	
	function footer(){
		echo'
		<footer class="bg-light">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-12 mb-4 mt-5">
						<h5>About VerX</h5>
						<p>We are more than just a clothing brand. VerX is a lifestyle, committed to providing high-quality, stylish, and sustainable fashion for individuals who dare to express themselves.</p>
					</div>
					<div class="col-lg-6 col-md-12 mb-4 mt-5">
						<h5 class="text-uppercase">Quick Links</h5>
						<ul class="list-unstyled">
							<li><a href="#" class="text-dark text-decoration-none">Size Guide</a></li>
							<li><a href="#" class="text-dark text-decoration-none">Shipping & Returns</a></li>
							<li><a href="#" class="text-dark text-decoration-none">Contact Us</a></li>
							<li><a href="#" class="text-dark text-decoration-none">Sustainability Commitment</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="text-center p-3 mt-5" style="background-color: rgba(0, 0, 0, 0.05);">
				© 2024 VerX. All Rights Reserved.
			</div>
		</footer>
		';
	}
?>