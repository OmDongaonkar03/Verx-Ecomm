<?php
include 'function.php';

$email = $_SESSION['user_email'];
$user = mysqli_query($conn, "SELECT * FROM signup WHERE email = '$email'");
$Userdata = mysqli_fetch_assoc($user);
$Userid = $Userdata['id'];
$Username = $Userdata['name'];

$limit = isset($_POST['limit']) ? $_POST['limit'] : 5;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['seemore'])) {
        $limit += 3;
    }
    if (isset($_POST['seeless']) && $limit > 3) {
        $limit -= 3;
    }
}

$base_query = "SELECT * FROM products";

$sql = mysqli_query($conn, "$base_query ORDER BY price ASC LIMIT $limit");

if(isset($_POST['filter'])) {
    $where_conditions = [];
    
    $categories = [];
    if(isset($_POST['mens'])) $categories[] = 'men';
    if(isset($_POST['womens'])) $categories[] = 'women';
    if(isset($_POST['kids'])) $categories[] = 'kids';
    
    if(!empty($categories)) {
        $categories_str = "'" . implode("','", $categories) . "'";
        $where_conditions[] = "category IN ($categories_str)";
    }
    
    if(isset($_POST['discount'])) {
        $discount = $_POST['discount'];
        $where_conditions[] = "discount >= $discount";
    }
    
    if(isset($_POST['min']) && isset($_POST['max'])) {
        $min = $_POST['min'];
        $max = $_POST['max'];
        if($min != '' && $max != '') {
            $where_conditions[] = "price BETWEEN $min AND $max";
        }
    }
    
    $where_clause = empty($where_conditions) ? "" : "WHERE " . implode(" AND ", $where_conditions);
    $sql = mysqli_query($conn, "$base_query $where_clause ORDER BY price ASC LIMIT $limit");
}

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = mysqli_query($conn, "$base_query WHERE productName LIKE '%$search%' OR description LIKE '%$search%' ORDER BY price ASC LIMIT $limit");
}

// Wishlist handling
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addwish'])) {
    $wishlist_query = mysqli_query($conn, "INSERT INTO wishlist (userId, productID, productName, productPrice, productImage, productCategory) VALUES ('$Userid', '$_POST[prodID]', '$_POST[prodName]', '$_POST[prodPrice]', '$_POST[prodImage]', '$_POST[prodCategory]')"
    );
    
    if($wishlist_query) {
        echo "<script>
			alert('Product Added To Wishlist');
            window.location.href = window.location.href;
        </script>";
    } else {
        echo "<script>
            alert('Failed To Add Product To Wishlist');
            window.location.href = window.location.href;
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VerX Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .filter-section h5 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .filter-group {
            margin-bottom: 15px;
        }
        .price-input {
            width: 80px;
        }
        .filter-badge {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 15px;
            margin: 5px;
            display: inline-block;
        }
        .product-card {
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php navbar(); ?>

    <!-- Search Bar -->
    <div class="container-fluid py-3 bg-light">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="d-flex" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Filter Section -->
            <div class="col-lg-3 col-md-4">
                <form method="POST" class="filter-section">
                    <h5>Filters</h5>
                    
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <h6>Category</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mens" name="mens">
                            <label class="form-check-label" for="mens">Mens</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="womens" name="womens">
                            <label class="form-check-label" for="womens">Womens</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="kids" name="kids">
                            <label class="form-check-label" for="kids">Kids</label>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <h6>Price Range</h6>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" class="form-control price-input" placeholder="Min" name="min">
                            <span>-</span>
                            <input type="number" class="form-control price-input" placeholder="Max" name="max">
                        </div>
                    </div>

                    <!-- Discount Filter -->
                    <div class="filter-group">
                        <h6>Discount</h6>
                        <fieldset>
                            <div class="form-check">
                                <input type="radio" name="discount" value="10">
                                <label class="form-check-label">10% or more</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="discount" value="20">
                                <label class="form-check-label">20% or more</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="discount" value="30">
                                <label class="form-check-label">30% or more</label>
                            </div>
                        </fieldset>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" name="filter">Apply Filters</button>
                </form>
            </div>

            <!-- Products Section -->
            <div class="col-lg-9 col-md-8">
                <div class="d-flex flex-column gap-4">
                    <?php
                    if (mysqli_num_rows($sql) > 0) {
                        while ($data = mysqli_fetch_assoc($sql)) {
                            $image = substr($data["product_Image"], 3);
                    ?>
                            <div class="card product-card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Product Image -->
                                        <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                            <div class="bg-image rounded">
                                                <img src="<?= $image ?>" class="w-100" alt="<?= $data['productName'] ?>">
                                            </div>
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5><?= $data['productName'] ?></h5>
                                                <?php
                                                $checkWishlist = mysqli_query($conn, "SELECT * FROM wishlist WHERE userId = '$Userid' AND productID = '".$data['productID']."'");
                                                if (mysqli_num_rows($checkWishlist) > 0) {
                                                    echo '<i class="fas fa-heart fs-5 text-danger"></i>';
                                                } else {
                                                ?>
                                                    <form method="POST">
                                                        <input type="hidden" name="prodID" value="<?= $data['productID'] ?>">
                                                        <input type="hidden" name="prodPrice" value="<?= $data['price'] ?>">
                                                        <input type="hidden" name="prodName" value="<?= $data['productName'] ?>">
                                                        <input type="hidden" name="prodImage" value="<?= $image ?>">
                                                        <input type="hidden" name="prodCategory" value="<?= $data['category'] ?>">
                                                        <button type="submit" class="btn btn-outline-primary" name="addwish">
                                                            <i class="far fa-heart fs-5"></i>
                                                        </button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                            <h6 class="text-muted">ID: <?= $data['productID'] ?></h6>
                                            <p class="mt-3"><?= $data['description'] ?></p>
                                            <p class="text-uppercase"><strong>Category:</strong> <?= $data['category'] ?></p>
                                        </div>
                                        
                                        <!-- Pricing and Actions -->
                                        <div class="col-md-6 col-lg-3 col-xl-3 border-start">
                                            <div class="d-flex flex-row align-items-center mb-1">
                                                <h4 class="mb-1 me-1">$<?= $data['price'] ?></h4>
                                                <span class="badge bg-danger"><?= $data['discount'] ?>% OFF</span>
                                            </div>
                                            <p class="text-success mb-4"><i class="fas fa-truck me-2"></i>Free shipping</p>
                                            <div class="d-flex flex-column gap-2">
                                                <a href="productDetails.php?param=<?= $data['productID'] ?>" class="btn btn-outline-primary">View Details</a>
                                            </div>
                                            <p class="mt-3 text-uppercase">
                                                <strong>Status:</strong> 
                                                <span class="<?= $data['status'] == 'In Stock' ? 'text-success' : 'text-danger' ?>">
                                                    <?= $data['status'] ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="alert alert-info text-center">No products found.</div>';
                    }
                    ?>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-center gap-3 my-4">
                        <form method="POST">
                            <input type="hidden" name="limit" value="<?= $limit ?>">
                            <button type="submit" class="btn btn-primary" name="seemore">See More</button>
                        </form>
                        <?php if($limit > 3): ?>
                        <form method="POST">
                            <input type="hidden" name="limit" value="<?= $limit ?>">
                            <button type="submit" class="btn btn-outline-primary" name="seeless">See Less</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php footer(); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>