<?php
include('function.php');

$userIP = $_SERVER['REMOTE_ADDR'];
$current_page_url = $_SERVER['REQUEST_URI'];
$string = exec('getmac');
$mac = substr($string, 0, 17);

if(isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
    $latitude = $_COOKIE['latitude'];
    $longitude = $_COOKIE['longitude'];
    
    $check_sql = mysqli_query($conn,"SELECT count FROM interaction WHERE `MAC addr` = '$mac' AND `pages visited` = '$current_page_url' LIMIT 1");
    
    if(mysqli_num_rows($check_sql) > 0) {
        $update_sql = mysqli_query($conn,"UPDATE interaction SET count = count + 1 WHERE `MAC addr` = '$mac' AND `pages visited` = '$current_page_url'");
    } else {
        $insert_sql = mysqli_query($conn,"INSERT INTO `interaction`(`MAC addr`, `IP`, `latitude`, `longitude`, `pages visited`, `count`) 
        VALUES ('$mac', '$userIP','$latitude', '$longitude', '$current_page_url', 1)");
    }
}

// Check if user is logged in and get user email
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user_email'];
$parameter = $_GET['param'];
$selectedColor = $_GET['color'];
$quantity = $_GET['quantity'];


// Get user data
$user = mysqli_query($conn, "SELECT * FROM `signup` WHERE `email` = '$email'");
if (!$user || mysqli_num_rows($user) === 0) {
    echo "User not found.";
    exit();
}

$Userdata = mysqli_fetch_assoc($user);

// User Data
$Userid = $Userdata['id'];
$Username = $Userdata['name'];
$Useremail = $Userdata['email'];
$Usercontact = $Userdata['contact'];
$Userstatus = $Userdata['Status'];

$currentMonthName = date("F");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['address'], $_POST['city'], $_POST['pinCode'])) {
        $userAddress = $_POST['address'];
        $userCity = $_POST['city'];
        $userState = $_POST['state'];
        $userPinCode = $_POST['pinCode'];
        
        if ($parameter == 'requestATC') {
            // Handle multiple products from cart
            $ATC_products = mysqli_query($conn, "SELECT a.*, p.productID, p.price, p.productName FROM `atcproduct` a JOIN `products` p ON a.productID = p.productID WHERE a.userID ='$Userid'");

            $success = true;
            while ($product = mysqli_fetch_assoc($ATC_products)) {
                $productID = $product['productID'];
                $productPrice = $product['price'] * $quantity;
                $productColor = $product['productColor'];
                $productQuantity = $product['productQuantity'];
                $productName = $product['productName'];
                $current_date = date("Y-m-d");
                $currentMonthName = date("F");
                if($Userstatus == 'Accepted') {
                    $query = "INSERT INTO `ordered products` (`productID`, `productPrice`, `userID`, `userContact`, `userAddress`, `userCity`, `UserState`, `UserPinCode`, `orderDate`,`orderMonth`, `productColor`, `productQuantity`) VALUES ('$productID', '$productPrice', '$Userid', '$Usercontact', '$userAddress', '$userCity', '$userState', '$userPinCode','$current_date','$currentMonthName', '$productColor', '$productQuantity')";
                        
                    date_default_timezone_set("Asia/Kolkata");
                    $current_time = date("Y/m/d H:i");
                    
                    $notification = mysqli_query($conn, "INSERT INTO `notifications`(`title`, `detail`, `timestamp`) VALUES ('$Username ordered $productName','$Username ordered $productName with price: $$productPrice × $productQuantity','$current_time')");
                    
                    if (!mysqli_query($conn, $query)) {
                        $success = false;
                        echo "Error: " . mysqli_error($conn);
                        break;
                    }
                } else {
                    $success = false;
                    echo "<script>alert('Your registration request has not been accepted yet')</script>";
                }
            }
            
            if ($success) {
                mysqli_query($conn, "DELETE FROM `atcproduct` WHERE `userID` = '$Userid'");
                header("Location: index.php");
                exit();
            }
        } else {
            // Handle single product purchase
            $product = mysqli_query($conn, "SELECT * FROM `products` WHERE `productID` = '$parameter'");
            if ($product && $Productdata = mysqli_fetch_assoc($product)) {
                $productID = $Productdata['productID'];
                $productName = $Productdata['productName'];
                $productPrice = $Productdata['price'];
                
                // Get color and quantity from the previous page
                $productColor = isset($_GET['color']) ? $_GET['color'] : '';
                $productQuantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
                
                $current_date = date("Y-m-d");
                if($Userstatus == 'Accepted') {
                    $query = "INSERT INTO `ordered products` (`productID`, `productPrice`, `userID`, `userContact`, `userAddress`, `userCity`, `UserState`, `UserPinCode`, `orderDate`,`orderMonth`, `productColor`, `productQuantity`) VALUES ('$productID', '$productPrice', '$Userid', '$Usercontact', '$userAddress', '$userCity', '$userState', '$userPinCode', '$current_date','$currentMonthName', '$productColor', '$productQuantity')";
                    
                    date_default_timezone_set("Asia/Kolkata");
                    $current_time = date("Y/m/d H:i");
					$currentMonthName = date("F");
                    $productName = $productName;
                    
                    $notification = mysqli_query($conn, "INSERT INTO `notifications`(`title`, `detail`, `timestamp`) 
                        VALUES ('$Username ordered $productName','$Username ordered $productName with price: $$productPrice × $productQuantity','$current_time')");
                    
                    if (mysqli_query($conn, $query)) {
                        echo "<script>alert('Order Placed!')</script>";
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "<script>alert('Your registration request has not been accepted yet')</script>";
                }
            } else {
                echo "<script>alert('Invalid product parameter.');</script>";
            }
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>



<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verx - Complete Your Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: white;
            font-family: 'Inter', sans-serif;
        }
        .checkout-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05), 0 -10px 30px rgba(0,0,0,0.05);
            padding: 2rem;
            margin: 2rem 0;
        }
        .form-control, .form-select {
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: none;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .btn-submit {
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 10px;
            background: #0d6efd;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: #0b5ed7;
        }
        .product-summary {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .product-summary:last-child {
            border-bottom: none;
        }
        .total-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div>
        <?php navbar(); ?>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Shipping Information Form -->
            <div class="col-lg-8">
                <div class="checkout-card">
                    <h3 class="mb-4">Shipping Information</h3>
                    <form method="POST">
                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <h6><?php echo $Username; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <h6><?php echo $Useremail; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <h6><?php echo $Usercontact; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">User ID</label>
                                <h6><?php echo $Userid; ?></h6>
                            </div>
                            <hr>
                            <!-- Address Information -->
                            <div class="col-12">
                                <label class="form-label">Street Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="d-flex justify-content-around">
                                <div class="col-md-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">State</label>
                                    <select class="form-select" name="state" required>
                                        <option value="" selected disabled>Select State</option>
                                        <option value="Maharastra">Maharashtra</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Goa">Goa</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Pin Code</label>
                                    <input type="number" class="form-control" name="pinCode" required>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-submit w-100">
                                    <i class="fas fa-lock me-2"></i>Complete Purchase
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="col-lg-4">
                <div class="checkout-card">
                    <h4 class="mb-4">Order Summary</h4>
                    <?php
                    if ($parameter != 'requestATC') {
                        // Single product purchase
                        $product = mysqli_query($conn, "SELECT * FROM `products` WHERE `productID` = '$parameter'");
                        if ($Productdata = mysqli_fetch_assoc($product)) {
                            $productID = $Productdata['productID'];
                            $productName = $Productdata['productName'];
                            $productCategory = $Productdata['category'];
                            $productPrice = $Productdata['price'];
                            ?>
                            <div class="product-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Product:  </span>
                                    <span class="fw-bold"><?php echo $productName; ?></span>
                                </div>
								<div class="d-flex justify-content-between mb-2">
                                    <span>Quantity : </span>
                                    <span class="fw-bold"><?php echo $quantity; ?></span>
                                </div>
								<div class="d-flex justify-content-between mb-2">
                                    <span>Color : </span>
                                    <span class="fw-bold"><?php echo $selectedColor; ?></span>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <span>Price</span>
                                    <span class="total-price">$<?php echo $productPrice * $quantity; ?></span>
                                </div>
                            </div>
                            <?php
                        }
                    }else{ //$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
					$ATC_products = mysqli_query($conn, "
						SELECT p.productID AS productID, p.price, p.productName
						FROM `atcproduct` a 
						JOIN `products` p ON a.productID = p.productID 
						WHERE a.userID = '$Userid'");
					
						if (mysqli_num_rows($ATC_products) > 0) {
							while ($ATCdata = mysqli_fetch_assoc($ATC_products)) {
								?>
								<div class="product-summary">
									<div class="d-flex justify-content-between mb-2">
										<span>Product: </span>
										<span class="fw-bold"><?php echo $ATCdata['productName']; ?></span>
									</div>
									<div class="d-flex justify-content-between">
										<span>Price</span>
										<span>$<?php echo $ATCdata['price']; ?></span>
									</div>
								</div>
								<?php
							}
						}
					}
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div>
        <?php footer(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>