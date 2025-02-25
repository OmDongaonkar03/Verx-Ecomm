<?php
	include("admin function.php");
	$admin_email = $_SESSION["admin_email"];
	$sql = mysqli_query($conn,"SELECT * FROM `products`");
	if (!isset($_SESSION["admin_email"])) {
		header("Location: admin login.php");
		exit();
	}
	
	if(isset($_GET['search'])) {
		$search =$_GET['search'];
		$sql = mysqli_query($conn, "SELECT * FROM `products` WHERE `productName` LIKE '%$search%' OR `category` LIKE '%$search%' OR `description` LIKE '%$search%' OR `productID` LIKE '%$search%'");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Product Details</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
       body {
           display: flex;
           background: #f8f9fa;
       }
       
       .sidebar {
           min-height: 100vh;
           background: #1a237e;
           width: 250px;
           position: fixed;
           left: 0;
           top: 0;
           padding: 1.5rem;
           z-index: 1000;
           transition: all 0.3s;
       }
       
       .sidebar.collapsed {
           width: 60px;
       }
       
       .sidebar.collapsed .nav-link span,
       .sidebar.collapsed h3 span {
           display: none;
       }
       
       .nav-link {
           color: #fff !important;
           padding: 0.5rem;
           margin:0 0 6px 0;
           border-radius: 8px;
           transition: all 0.3s;
           display: flex;
           align-items: center;
           white-space: nowrap;
       }
       
       .nav-link:hover {
           background: rgba(255,255,255,0.15);
           transform: translateX(5px);
       }
       
       .nav-link i {
           margin-right: 12px;
           width: 20px;
           font-size: 1.1rem;
       }
       
       .main-content {
           margin-left: 250px;
           flex: 1;
           padding: 2rem;
           transition: all 0.3s;
       }
       
       .main-content.expanded {
           margin-left: 60px;
       }
       
       .header {
           background: #212529;
           color: white;
           padding: 1.5rem;
           border-radius: 12px;
           margin-bottom: 2rem;
           display: flex;
           align-items: center;
           justify-content: space-between;
       }
       
       .table-container {
           background: white;
           border-radius: 12px;
           box-shadow: 0 4px 6px rgba(0,0,0,0.1);
           padding: 1.5rem;
           overflow-x: auto;
       }
       
       .table thead {
           background: #0d6efd;
           color: white;
       }
       
       .table th {
           padding: 1rem;
           font-weight: 600;
           white-space: nowrap;
       }
       
       .table td {
           padding: 1rem;
           vertical-align: middle;
       }
       
       .table tbody tr:hover {
           background-color: #f8f9fa;
       }
       
       .description-cell {
           max-width: 200px;
           overflow: hidden;
           text-overflow: ellipsis;
           white-space: nowrap;
       }
   </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <?php sidebar() ?>
    </div>

    <div class="main-content" id="main">
        <div class="header">
            <h2 class="m-0">Product Details</h2>
        </div>
		
		
		<div class="mb-4 px-3">
			<form method="GET">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search..." aria-label="Search" name="search">
					<button class="btn btn-primary" type="submit">
						<i class="fas fa-search me-2"></i>Search
					</button>
				</div>
			</form>
		</div>
		
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Colors</th>
                        <th>Status</th>
						<?php 
						$privileges_query = mysqli_query($conn, "SELECT privileges FROM `admin details` WHERE `admin_email` = '$admin_email'");
						$privileges_data = mysqli_fetch_assoc($privileges_query);
						$privileges = explode(",", $privileges_data['privileges']);
						if (in_array('edit', $privileges)) { ?>
							<th>Action</th>
						<?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($sql) > 0) {
                        while ($data = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                        <td><?php echo $data['productID'];?></td>
                        <td><?php echo $data['productName'];?></td>
                        <td><?php echo $data['category'];?></td>
                        <td><?php echo $data['price'];?></td>
                        <td><?php echo $data['discount'];?></td>
                        <td class="description-cell"><?php echo $data['description'];?></td>
                        <td><?php echo $data['quantity'];?></td>
                        <td><?php echo implode(", ", explode(",", $data['colors']));?></td>
                        <td><?php echo $data['status'];?></td>
						<?php if (in_array('edit', $privileges)) { ?>
							<td>
								<form method="POST" action="">
									<input type="hidden" name="id" value="<?php echo $data['productID']; ?>">
									<button type="submit" class="btn btn-danger mt-2" name="remove">Remove</button>
								</form>
							</td>
						<?php } ?>
                    </tr>
                    <?php
                        }
                    }
					if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove'])) {
						$id = $_POST['id'];
						$accept = mysqli_query($conn, "DELETE FROM `products` WHERE `productID` = '$id'");
						if ($accept) {
							echo "<script>alert('Removed successfully!');</script>";
							echo "<script>window.location.href = window.location.href;</script>";
						} else {
							echo "<script>alert('Error while Removing.');</script>";
						}
					}
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>