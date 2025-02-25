<?php include ('function.php');
	
	$email = $_SESSION['user_email'];
	$user = mysqli_query($conn, "SELECT * FROM `signup` WHERE `email` = '$email'");
	$Userdata = mysqli_fetch_assoc($user);
	$userid = $Userdata['id'];
	
	$wishlist_query = mysqli_query($conn,"SELECT * FROM `wishlist`");
	$wishlist_count = mysqli_num_rows($wishlist_query);
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['clearAll'])){
			$clearAll = mysqli_query($conn,"TRUNCATE TABLE `wishlist`");
			if($clearAll){
				echo "<script>alert('Wishlist Cleared Successfully')</script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}else{
				echo "<script>alert('Failed to Clear Wishlist')</script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
		}
		if(isset($_POST['clearProd'])){
			$prodID = $_POST['prodID'];
			$product_remove_query = mysqli_query($conn,"DELETE FROM `wishlist` WHERE `productID` = '$prodID'");
			if($product_remove_query){
				echo "<script>alert('Product Removed Successfully')</script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}else{
				echo "<script>alert('Failed to Remove Product')</script>";
				echo "<script>window.location.href = window.location.href;</script>";
			}
		}
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VerX Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .wishlist-item {
            transition: all 0.3s ease;
        }
        
        .wishlist-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .remove-wishlist {
            position: absolute;
            right: 15px;
            top: 15px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .remove-wishlist:hover {
            background: #dc3545;
            color: white;
        }

        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .discount-badge {
            position: absolute;
            left: 15px;
            top: 15px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .empty-wishlist {
            text-align: center;
            padding: 50px 0;
        }

        .empty-wishlist i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
    </style>
  </head>
  <body>
    <div>
        <?php
            navbar();
        ?>
    </div>

    <div class="container my-5">
        <?php if($wishlist_count > 0) { ?>
            <form method="POST">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">My Wishlist (<?php echo $wishlist_count; ?>)</h2>
                    <button class="btn btn-outline-danger" type="submit" name="clearAll">Clear All</button>
                </div>
        
                <div class="row g-4">
                    <?php
                        while($data = mysqli_fetch_assoc($wishlist_query)){
                    ?>
                    <div class="col-12">
                        <div class="card wishlist-item">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <img src="<?= $data['productImage'] ?>" class="product-img rounded" alt="Product">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="card-title mb-3"><?php echo $data['productName'];?></h5>
                                        <p class="text-muted mb-3">Product ID: <?php echo $data['productID']; ?></p>
                                        <p class="mb-0 text-uppercase"><small>Category: <?php echo $data['productCategory'];?></small></p>
                                    </div>
                                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                        <h4 class="mb-2 me-5">$<?php echo $data['productPrice'];?></h4>
                                        <p class="text-success mb-3"><i class="fas fa-truck me-2"></i>Free Shipping</p>
                                        <div class="d-grid gap-2">
                                            <input type="hidden" name="prodID" value="<?php echo $data['productID']; ?>">
                                            <input type="hidden" name="productPrice" value="<?php echo $data['productPrice']; ?>">
                                            <input type="hidden" name="productName" value="<?php echo $data['productName']; ?>">
											
											<a href="buynow.php?param=<?php echo $data['productID']; ?>" class="btn btn-primary">Buy Now</a>
                                        </div>
                                    </div>
                                    <button class="remove-wishlist" type="submit" name="clearProd">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </form>
        <?php } else { ?>
            <div class="empty-wishlist">
                <i class="far fa-heart"></i>
                <h3>Your wishlist is empty</h3>
                <p class="text-muted mb-4">Explore our products and add your favorites to the wishlist!</p>
                <a href="productpage.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php } ?>
    </div>

    <div>
        <?php
            footer();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>