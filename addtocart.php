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

$total = 0;
$email = $_SESSION['user_email'];

$query = mysqli_query($conn, "SELECT id FROM signup WHERE email='$email'");
if ($query) {
    $user = mysqli_fetch_assoc($query);
    $uid = $user['id'];
} else {
    die("Error: Unable to fetch user data");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $pid = $_POST['product_id'];
        $new_quantity = $_POST['quantity'];
        
        $update_query = mysqli_query($conn, "UPDATE atcproduct SET productQuantity = $new_quantity WHERE userID='$uid' AND productID='$pid'");
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['remove_item'])) {
		
		$cart_query = mysqli_query($conn, "SELECT * FROM atcproduct WHERE userID='$uid' ORDER BY productID");
		$cart_items = mysqli_fetch_all($cart_query, MYSQLI_ASSOC);
		
		$product_position = $_POST['product_position'];
		
		if (isset($cart_items[$product_position]) && 
			$cart_items[$product_position]['productID'] == $_POST['product_id']) {
			mysqli_query($conn, "DELETE FROM atcproduct WHERE userID='$uid' AND productID='".$cart_items[$product_position]['productID']."'");
		}else{
			echo "<script>alert('error')</script>";
		}
		
		//header("Location: " . $_SERVER['PHP_SELF']);
		//exit();
	}
}

$cart_items_sql = "SELECT a.*, p.* FROM atcproduct a JOIN products p ON a.productID = p.productID WHERE a.userID='$uid'";
$cart_items_query = mysqli_query($conn, $cart_items_sql);

$cart_data = array();
$total = 0;

if ($cart_items_query && mysqli_num_rows($cart_items_query) > 0) {
    while ($item = mysqli_fetch_assoc($cart_items_query)) {
        $cart_data[] = $item;
        $total += ($item['productPrice'] * $item['productQuantity']);
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verx - Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .cart-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 2rem;
        }
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .quantity-selector {
            width: 120px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        .quantity-btn {
            border: none;
            background: none;
            padding: 0.5rem 1rem;
            color: #0d6efd;
            cursor: pointer;
        }
        .quantity-input {
            width: 40px;
            border: none;
            text-align: center;
            -moz-appearance: textfield;
        }
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
        }
        .summary-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <?php navbar(); ?>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Shopping Cart</h4>
                        <span class="text-muted"><?php echo count($cart_data); ?> items</span>
                    </div>

                    <?php
                    if (!empty($cart_data)) {
                        foreach ($cart_data as $index => $item) {
                            $img = substr($item["product_Image"], 3);
                            ?>
                            <div class="border-top py-4">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-2">
                                        <img src="<?php echo $img; ?>" alt="Product" class="product-img">
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <a href="product.php?id=<?php echo $item['productID']; ?>" class="text-decoration-none text-dark">
                                            <h6><?php echo $item["productName"]; ?></h6>
                                        </a>
                                        <p class="text-muted mb-0">
                                            Category: <?php echo $item["category"]; ?>
                                        </p>
                                        <?php if(!empty($item["productColor"])): ?>
                                        <p class="text-muted mb-0">
                                            Color: <?php echo $item["productColor"]; ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="quantity-selector d-flex align-items-center">
                                            <form method="POST" class="d-flex quantity-form">
                                                <input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
                                                <button type="button" class="quantity-btn" onclick="updateQty(this, -1)">-</button>
                                                <input type="number" name="quantity" class="quantity-input" 
                                                       value="<?php echo $item['productQuantity']; ?>" 
                                                       min="1" data-price="<?php echo $item['productPrice']; ?>" readonly>
                                                <button type="button" class="quantity-btn" onclick="updateQty(this, 1)">+</button>
                                                <button type="submit" name="update_quantity" class="btn btn-sm btn-outline-primary ms-2">Update</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <span class="fw-bold item-total">$<?php echo $item['productPrice'] * $item['productQuantity']; ?></span>
                                    </div>

                                    <form method="POST">
										<input type="hidden" id="5" name="product_position" value="<?php echo $index; ?>">
										<input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
										<button type="submit" name="remove_item" class="remove-btn">
											<i class="fas fa-times"></i>
										</button>
									</form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5>Your cart is empty</h5>
                            <p class="text-muted">Browse our products and add items to your cart</p>
                            <a href="productpage.php" class="btn btn-primary">Continue Shopping</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold cart-total">$<?php echo $total; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="fw-bold">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-primary h5 mb-0 cart-total">$<?php echo $total; ?></span>
                    </div>

                    <?php if (!empty($cart_data)) { ?>
                        <!--<div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo Code">
                                <button type="button" class="btn btn-outline-primary">Apply</button>
                            </div>
                        </div>-->
                        <a href="buynow.php?param=requestATC&total=<?php echo $total; ?>" 
                            class="btn btn-primary w-100 py-2">
                            <i class="fas fa-lock me-2"></i>Proceed to Checkout
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php footer(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function updateQty(btn, change) {
        const form = btn.closest('form');
        const input = form.querySelector('.quantity-input');
        const pricePerUnit = parseFloat(input.dataset.price);
        let value = parseInt(input.value) + change;
        value = Math.max(1, value);
        input.value = value;
        
        // Update item total
        const itemTotal = form.closest('.row').querySelector('.item-total');
        itemTotal.textContent = '$' + (value * pricePerUnit).toFixed(2);
        
        // Update cart total
        updateCartTotal();
    }

    function updateCartTotal() {
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value);
            const price = parseFloat(input.dataset.price);
            total += quantity * price;
        });
        
        // Update all total displays
        document.querySelectorAll('.cart-total').forEach(el => {
            el.textContent = '$' + total.toFixed(2);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const summaryTotal = document.querySelector('.text-primary.h5');
        if (summaryTotal) {
            summaryTotal.classList.add('cart-total');
        }
    });
	
	document.addEventListener('DOMContentLoaded', function() {
		const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
	
		hiddenInputs.forEach(function(input) {
			input.addEventListener('change', function() {
				alert('Hidden input has been altered!');
			});
		});
	});

    </script>
</body>
</html>