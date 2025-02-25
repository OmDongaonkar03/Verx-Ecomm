<?php
    include_once 'function.php';
    
    $email = $_SESSION['user_email'];
    $userdata = mysqli_query($conn,"SELECT * FROM `signup` WHERE `email`='$email'");
    $userdata = mysqli_fetch_assoc($userdata);
    $userID = $userdata['id'];
    $userName = $userdata['name'];
	
    if(isset($_GET['param'])){
        $id = $_GET['param'];
        $sql = mysqli_query($conn, "SELECT * FROM `products` WHERE `productID` = '$id'");
        $data = mysqli_fetch_assoc($sql);
        
        $name = $data['productName'];
        $category = $data['category'];
        $price = $data['price'];
        $discount = $data['discount'];
        $description = $data['description'];
        $image1 = substr($data["product_Image"], 3);
        $image2 = substr($data["product_image2"], 3);
        $image3 = substr($data["product_image3"], 3);
        $image4 = substr($data["product_image4"], 3);
        
        $quantity = $data['quantity'];
        $status = $data['status'];
        $color = explode(",", $data['colors']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['ATCrequest'])) {
			$check = mysqli_query($conn, "SELECT * FROM `atcproduct` WHERE `productID` = '$id' AND `userID` = '$userID'");
			if (mysqli_num_rows($check) > 0) {
				echo "<script>alert('Product Already In Cart')</script>";
			} else {
				$selectedcolor = $_POST['Selectedcolors'];
				$quantity = $_POST['quantity'];
				$ATC_query = "INSERT INTO `atcproduct`(`productID`, `productPrice`, `productColor`, `productQuantity`, `userID`) VALUES ('$id', '$price', '$selectedcolor', '$quantity', '$userID')";
				
				date_default_timezone_set("Asia/Kolkata");
				$current_time = date("Y/m/d H:i");
				
				$notification = mysqli_query($conn, "INSERT INTO `notifications`(`title`, `detail`, `timestamp`) VALUES ('$userName added $name in Cart','$userName added $name in Cart price: $price × $quantity','$current_time')");
				
				if (mysqli_query($conn, $ATC_query)) {
					header("Location:addtocart.php");
					exit();
				} else {
					echo "Error: " . mysqli_error($conn);
				}
			}
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verx Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-top: 2rem;
        }
        .gallery-container {
            position: relative;
            overflow: hidden;
            background: #fff;
            padding: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
			margin-top:110px;
        }
        .product-image {
            width: auto;
            height: 400px;
            border-radius: 10px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: rgba(13, 110, 253, 0.1);
        }
        .product-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .spec-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .spec-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .spec-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .spec-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: #212529;
        }
        .color-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }
        .color-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .color-badge:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            background: #198754;
            color: white;
            display: inline-block;
        }
		.color-options {
			padding: 10px;
		}
		
		.color-checkbox-wrapper {
			position: relative;
			display: inline-block;
			cursor: pointer;
			margin-right: 10px;
		}
		
		.color-checkbox {
			position: absolute;
			opacity: 0;
			cursor: pointer;
		}
		
		.color-badge {
			display: inline-block;
			padding: 8px 16px;
			border-radius: 4px;
			background-color: #f0f0f0;
			border: 2px solid transparent;
			transition: all 0.2s ease;
		}
		
		.color-checkbox:checked + .color-badge {
			border-color: #007bff;
			background-color: #e6f3ff;
		}
		
		.color-checkbox:focus + .color-badge {
			box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
		}
		
		/* Hover effect */
		.color-badge:hover {
			background-color: #e9ecef;
		}
        .btn-action {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-cart {
            background: white;
            color: #0d6efd;
            border: 2px solid #0d6efd;
        }
        .btn-cart:hover {
            background: #0d6efd;
            color: white;
            transform: translateY(-2px);
        }
        .btn-buy {
            background: #0d6efd;
            color: white;
        }
        .btn-buy:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .product-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 2rem;
            margin-top: 2rem;
        }
        .carousel {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        .carousel-inner {
            height: auto;
            max-height: 800px;
            border-radius: 15px;
            overflow: hidden;
        }
        .carousel-item img {
            width: 100%;
            height: auto;
            max-height: 800px;
            object-fit: contain;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
			filter: invert(1) brightness(0);
            padding: 20px;
            border-radius: 50%;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 10%;
            opacity: 1;
        }
        .discount-badge {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @media (max-width: 768px) {
            .carousel {
                max-width: 100%;
            }
            .carousel-inner {
                max-height: 600px;
            }
            .product-specs {
                grid-template-columns: 1fr;
            }
            .spec-item {
                padding: 1rem;
            }
        }
        @media (max-width: 576px) {
            .carousel-inner {
                max-height: 400px;
            }
            .color-options {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div>
        <?php navbar(); ?>
    </div>

    <div class="container py-5">
        <div class="product-card">
            <div class="row g-0">
                <!-- Product Gallery -->
                <div class="col-lg-6">
                    <div class="gallery-container">
                        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="<?php echo $image1; ?>" class="d-block" alt="Product Image 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?php echo $image2; ?>" class="d-block" alt="Product Image 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?php echo $image3; ?>" class="d-block" alt="Product Image 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="<?php echo $image4; ?>" class="d-block" alt="Product Image 4">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-lg-6">
                    <div class="p-5">
                        <h2 class="display-6 mb-4"><?php echo $name; ?></h2>
                        
                        <!-- Product Specifications -->
                        <div class="product-specs">
                            <div class="spec-item">
                                <div class="spec-label">Stock Status</div>
                                <div class="spec-value">
                                    <i class="fas fa-box me-2"></i>
                                    <?php echo $quantity; ?> units
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <div class="spec-label">Price</div>
                                <div class="spec-value text-primary">
                                    <i class="fas fa-tag me-2"></i>
                                    $<?php echo $price; ?>
                                </div>
                            </div>

                            <?php if($discount > 0): ?>
                            <div class="spec-item">
                                <div class="spec-label">Discount</div>
                                <div class="spec-value">
                                    <span class="discount-badge">
                                        <i class="fas fa-percent me-2"></i>
                                        <?php echo $discount; ?>% OFF
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Color Options -->
                    <div class="mb-4">
							<h5 class="mb-3">Available Colors</h5>
							<form method="POST" id="productForm" class="product-form">
							<div class="color-options">
								<?php foreach ($color as $c): ?>
								<label class="color-checkbox-wrapper">
									<input type="radio" name="Selectedcolors" value="<?php echo trim($c); ?>" class="color-checkbox" required>
									<span class="color-badge">
										<?php echo trim($c); ?>
									</span>
								</label>
								<?php endforeach; ?>
							</div>
						<div class="spec-item mt-4">
							<div class="spec-label">Quantity</div>
							<div class="spec-value">
								<div class="input-group quantity-selector">
									<button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
									<input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="<?php echo $quantity; ?>" readonly>
									<button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
								</div>
							</div>
						</div>

                        <!-- Product Description -->
                        <div class="product-details">
                            <h5 class="mb-3">Product Description</h5>
                            <p class="lead text-muted"><?php echo $description; ?></p>
                        </div>

                        <!-- Action Buttons -->
                        <form class="d-grid gap-3 mt-4" method="POST">
                           <div class="d-grid gap-3 mt-4">
								<?php if($status == 'active'): ?>
									<a href="buynow.php?param=<?php echo $id; ?>& color=<?php echo $selectedcolor; ?>& quantity=<?php echo $quantity ; ?>" class="btn btn-action btn-buy">
										<i class="fas fa-bolt me-2"></i>Buy Now
									</a>
									<button type="submit" class="btn btn-action btn-cart" name="ATCrequest">
										<i class="fas fa-shopping-cart me-2"></i>Add to Cart
									</button>
								<?php else: ?>
									<button class="btn btn-action btn-danger" disabled>
										<i class="fas fa-times me-2"></i>Currently Unavailable
									</button>
								<?php endif; ?>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div>
        <?php footer(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		function updateQuantity(change) {
			const input = document.getElementById('quantity');
			const currentValue = parseInt(input.value);
			const maxValue = parseInt(input.max);
			const newValue = currentValue + change;
			
			if (newValue >= 1 && newValue <= maxValue) {
				input.value = newValue;
			}
		}
		
		// Form validation
		document.getElementById('productForm').addEventListener('submit', function(e) {
			const colorSelected = document.querySelector('input[name="Selectedcolors"]:checked');
			if (!colorSelected) {
				e.preventDefault();
				alert('Please select a color');
				return false;
			}
			return true;
		});
		
		function updateBuyNowLink() {
			const buyNowLink = document.querySelector('.btn-buy');
			const selectedColor = document.querySelector('input[name="Selectedcolors"]:checked')?.value || '';
			const quantity = document.getElementById('quantity').value;
			const baseUrl = buyNowLink.href.split('?')[0];
			const productId = '<?php echo $id; ?>';
			
			buyNowLink.href = `${baseUrl}?param=${productId}&color=${encodeURIComponent(selectedColor)}&quantity=${quantity}`;
		}
		
		document.querySelectorAll('input[name="Selectedcolors"]').forEach(radio => {
			radio.addEventListener('change', updateBuyNowLink);
		});
		
		document.getElementById('quantity').addEventListener('change', updateBuyNowLink);
		document.querySelectorAll('.quantity-selector button').forEach(btn => {
			btn.addEventListener('click', () => setTimeout(updateBuyNowLink, 0));
		});
</script>
</body>
</html>