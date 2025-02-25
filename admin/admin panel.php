<?php
include("admin function.php");
if (!isset($_SESSION["admin_email"])) {
    header("Location: admin login.php");
    exit();
}
//total users
$user_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM `signup`");
$user_data = mysqli_fetch_assoc($user_query);
$total_users = $user_data['total'];

//total products
$product_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM `products`");
$product_data = mysqli_fetch_assoc($product_query);
$total_product = $product_data['total'];


//total orders
$order_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM `ordered products` WHERE `Status` = 'In Queue'");
$order_data = mysqli_fetch_assoc($order_query);
$total_order = $order_data['total'];

//total MSG
$msg_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM `connect request`");
$msg_data = mysqli_fetch_assoc($msg_query);
$msg_order = $msg_data['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>VerX Admin Panel</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
        /* Previous styles remain unchanged */
        .sidebar {
            min-height: 100vh;
            background: #1a237e;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #fff !important;
            padding: 0.5rem;
			margin:0 0 6px 0;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            background: #f5f5f5;
            min-height: 100vh;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .welcome-text {
            background: linear-gradient(135deg, #1a237e, #3949ab);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
   </style>
</head>
<body>
   <div class="container-fluid p-0">
       <div class="row g-0">
           <!-- Sidebar -->
           <div class="col-auto">
               <?php sidebar() ?>
           </div>

           <!-- Main Content -->
           <div class="col">
               <div class="main-content">
                   <div class="welcome-text">
                       <h2>Welcome to VerX Admin Dashboard</h2>
                       <p class="mb-0">Manage your store efficiently from one place</p>
                   </div>
                   
                   <h2 class="mb-4">Dashboard Overview</h2>
                   <div class="row g-4">
                       <div class="col-md-3">
                           <div class="stats-card">
                               <h4 class="text-primary mb-3">
                                   <i class="fas fa-users"></i>
                                   Total Users
                               </h4>
                               <h2><?php echo $total_users;?></h2>
                           </div>
                       </div>
                       <div class="col-md-3">
                           <div class="stats-card">
                               <h4 class="text-success mb-3">
                                   <i class="fas fa-shopping-cart"></i>
                                   Orders In Queue
                               </h4>
                               <h2><?php echo $total_order;?></h2>
                           </div>
                       </div>
                       <div class="col-md-3">
                           <div class="stats-card">
                               <h4 class="text-warning mb-3">
                                   <i class="fas fa-box"></i>
                                   Products
                               </h4>
                               <h2><?php echo $total_product;?></h2>
                           </div>
                       </div>
                       <div class="col-md-3">
                           <div class="stats-card">
                               <h4 class="text-info mb-3">
                                   <i class="fas fa-comments"></i>
                                   Messages
                               </h4>
                               <h2><?php echo $msg_order;?></h2>
                           </div>
                       </div>
                   </div>

                   <div class="row mt-4">
                       <div class="col-md-6">
                           <div class="info-card">
                               <h4>Latest Updates</h4>
                               <ul class="list-unstyled mt-3">
                                   <li class="mb-2"><i class="fas fa-circle text-primary me-2"></i> Dashboard updated with new features</li>
                                   <li class="mb-2"><i class="fas fa-circle text-success me-2"></i> System performance improved</li>
                                   <li class="mb-2"><i class="fas fa-circle text-warning me-2"></i> Search bar feature added for quick data finding</li>
                               </ul>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="info-card">
                               <h4>Quick Tips</h4>
                               <ul class="list-unstyled mt-3">
                                   <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Use the sidebar for quick navigation</li>
                                   <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Monitor your stats daily</li>
                                   <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Keep your inventory updated</li>
                               </ul>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>