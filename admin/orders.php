<?php
	include("admin function.php");
	$admin_email = $_SESSION["admin_email"];
	if (!isset($_SESSION["admin_email"])) {
		header("Location: admin login.php");
		exit();
	}
	
	$sql = mysqli_query($conn,"SELECT * FROM `ordered products` WHERE `Status` != 'Completed'");
	
	if(isset($_GET['search'])) {
		$search =$_GET['search'];
		$sql = mysqli_query($conn, "SELECT * FROM `ordered products` WHERE `userAddress` LIKE '%$search%' OR `userCity` LIKE '%$search%' OR `userState` LIKE '%$search%' OR `productID` LIKE '%$search%' AND `Status` != 'Completed'");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Ordered Products</title>
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
       }
       
       .header {
           background: #212529;
           color: white;
           padding: 1.5rem;
           border-radius: 12px;
           margin-bottom: 2rem;
           display: flex;
           align-items: center;
       }
       
       .header i {
           font-size: 1.5rem;
           margin-right: 1rem;
       }
       
       .table-container {
           background: white;
           border-radius: 12px;
           box-shadow: 0 4px 6px rgba(0,0,0,0.1);
           padding: 1.5rem;
       }
       
       .table {
           margin-bottom: 0;
       }
       
       .table thead {
           background: #0d6efd;
           color: white;
       }
       
       .table th {
           padding: 1rem;
           font-weight: 600;
           border: none;
       }
       
       .table td {
           padding: 1rem;
           vertical-align: middle;
           border-bottom: 1px solid #dee2e6;
       }
       
       .table tbody tr:hover {
           background-color: #f8f9fa;
       }
   </style>
</head>
<body>
    <div class="sidebar">
		<?php sidebar() ?>
    </div>

    <div class="main-content">
        <div class="header">
            <i class="fas fa-clipboard-list"></i>
            <h2 class="m-0">Ordered Products</h2>
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
                        <th>Product Price</th>
                        <th>User ID</th>
                        <th>User Contact</th>
                        <th>User Address</th>
                        <th>User City</th>
                        <th>User State</th>
                        <th>Pin Code</th>
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
                        <td><?php echo $data['productID']; ?></td>
                        <td><?php echo $data['productPrice']; ?></td>
                        <td><?php echo $data['userID']; ?></td>
                        <td><?php echo $data['userContact']; ?></td>
                        <td><?php echo $data['userAddress']; ?></td>
                        <td><?php echo $data['userCity']; ?></td>
                        <td><?php echo $data['UserState']; ?></td>
                        <td><?php echo $data['UserPinCode']; ?></td>
						<?php if (in_array('edit', $privileges)) { ?>
							<td>
								<form method="POST" action="">
									<input type="hidden" name="id" value="<?php echo $data['productID']; ?>">
									<input type="hidden" name="timeStamp" value="<?php echo $data['Timestamp']; ?>">
									<button type="submit" class="btn btn-success mt-2" name="completed">Completed</button>
								</form>
							</td>
						<?php } ?>
                    </tr>
                    <?php
                        }
                    }
					if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['completed'])) {
						$id = $_POST['id'];
						$timeStamp = $_POST['timeStamp'];
						$accept = mysqli_query($conn, "UPDATE `ordered products` SET `Status` = 'Completed' WHERE `productID` = '$id' && `Timestamp` = '$timeStamp'");
						if ($accept) {
							echo "<script>alert('Status Updated successfully!');</script>";
							echo "<script>window.location.href = window.location.href;</script>";
						} else {
							echo "<script>alert('Error.');</script>";
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