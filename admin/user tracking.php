<?php 
include("admin function.php");

if (!isset($_SESSION["admin_email"])) {
    header("Location: admin login.php");
    exit();
}

$sql = mysqli_query($conn,"SELECT COUNT(*) AS 'COUNT', `MAC addr`FROM `interaction`GROUP BY `MAC addr`;");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Tracking</title>
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
            margin: 0 auto 1rem auto;
            max-width: 1200px;
        }
        
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        
        .table thead {
            background: #0d6efd;
            color: white;
        }
        
        .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
            text-align: left;
        }
        
        .table th {
            font-weight: 600;
            border: none;
        }
        
        .table td {
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th:nth-child(1) {
            width: 40%;
        }
        
        .table th:nth-child(2) {
            width: 30%;
        }
        
        .table th:nth-child(3) {
            width: 30%;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .btn {
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .collapse-content {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
            margin-top: 1rem;
        }

        .product-table {
            margin-top: 1rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-table .table {
            margin-bottom: 0;
        }

        .product-table thead {
            background: #e9ecef;
            color: #212529;
        }

        .product-table th {
            font-weight: 600;
            white-space: nowrap;
        }

        .product-table td {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-active {
            color: #198754;
            font-weight: 500;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: 500;
        }

        .product-table tbody tr:last-child td {
            border-bottom: none;
        }

        .btn i {
            transition: transform 0.3s;
        }

        .btn.collapsed i {
            transform: rotate(180deg);
        }

        /* Animation for collapse */
        .collapse {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <?php sidebar() ?>
    </div>

    <div class="main-content">
        <div class="header">
            <i class="fas fa-box"></i>
            <h2 class="m-0">User Tracking</h2>
        </div>
        <?php
			if(mysqli_num_rows($sql) > 0){
			while($data = mysqli_fetch_assoc($sql)){
				$MAC_addr = $data['MAC addr'];
				$users_sql = mysqli_query($conn, "SELECT * FROM `interaction` WHERE `MAC addr` = '$MAC_addr'");
				$total_num_users = mysqli_num_rows($users_sql);
				
				// Store products in array for later use
				$users = [];
				while($row = mysqli_fetch_assoc($users_sql)) {
					$users[] = $row;
				}
		?>
        <div class="table-container">
            <table class="table">
                <thead>
					<tr>
						<th>MAC address</th>
						<th>Num Of Pages Visited</th>
						<th>Actions</th>
					</tr>
				</thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['MAC addr'];?></td>
                        <td><?php echo $total_num_users;?></td>
                        <td>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#collapse-<?php echo $data['MAC addr']; ?>" 
                                    aria-expanded="false">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="collapse" id="collapse-<?php echo $data['MAC addr']; ?>">
                <div class="collapse-content">
                    <h5 class="mb-3">MAC address : <?php echo $data['MAC addr']; ?></h5>
                    <div class="product-table">
                        <table class="table">
                            <thead>
                                <tr>
									<th>Pages visited</th>
									<th>Count</th>
									<th>IP</th>
									<th>Latitude</th>
									<th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
								if(count($users) > 0){
									foreach($users as $user){
								?>
								<tr>
									<td><?php echo $user['pages visited'];?></td>
									<td><?php echo $user['count'];?></td>
									<td><?php echo $user['IP'];?></td>
									<td><?php echo $user['latitude'];?></td>
									<td><?php echo $user['longitude'];?></td>
								</tr>
								<?php
									}
								}
								?>
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>