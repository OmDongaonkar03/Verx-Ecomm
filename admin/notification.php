<?php 
include("admin function.php");
$admin_email = $_SESSION["admin_email"];

if (!isset($admin_email)) {
    header("Location: admin login.php");
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM notifications WHERE NOT EXISTS (SELECT * FROM admin_notification WHERE admin_notification.admin_title = notifications.title AND admin_notification.admin_email = '$admin_email')");

if (!$sql) {
    echo "</script>alert(Error: " . mysqli_error($conn) . ")</script>";
}
$rows = mysqli_num_rows($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>
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
       .notification-card {
           background: white;
           border-radius: 12px;
           box-shadow: 0 4px 6px rgba(0,0,0,0.1);
           margin-bottom: 1rem;
           transition: transform 0.2s;
       }
       .notification-header {
           padding: 1rem;
           border-bottom: 1px solid #dee2e6;
           display: flex;
           justify-content: space-between;
           align-items: center;
       }
       
       .notification-body {
           padding: 1rem;
       }
       
       .notification-time {
           color: #6c757d;
           font-size: 0.875rem;
       }
       
       .notification-type {
           padding: 0.25rem 0.75rem;
           border-radius: 50px;
           font-size: 0.875rem;
       }
       
       .type-order {
           background: #e3f2fd;
           color: #0d6efd;
       }
       
       .type-user {
           background: #f8f9fa;
           color: #212529;
       }
       
       .type-system {
           background: #fff3cd;
           color: #856404;
       }
       
       .clear-all {
           color: white;
           text-decoration: none;
           font-size: 0.9rem;
       }
       
       .clear-all:hover {
           color: #dee2e6;
       }
	   .seen-btn {
			border: 1px solid #ddd;
			border-radius: 4px;
			padding: 5px 10px;
			margin-top: 10px;
			font-size: 0.9em;
		}
   </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <?php sidebar() ?>
    </div>

    <div class="main-content" id="main">
        <div class="header">
            <h2 class="m-0"><i class="fas fa-bell me-2"></i>Notifications</h2>
            <span class="badge bg-danger"><?php echo $rows; ?> unread</span>
        </div>
        <?php 
        if($rows > 0){
            while($data = mysqli_fetch_assoc($sql)){ ?>
                <div class="notification-container">
                    <div class="notification-card">
                        <div class="notification-header">    
                            <span class="notification-time">
                                <?php echo $data['timestamp']; ?>
                            </span>
                        </div>
                        <div class="notification-body">
                            <h6><?php echo $data['title']; ?></h6>
                            <p class="mb-0"><?php echo $data['detail']; ?></p>
							<?php 
							$privileges_query = mysqli_query($conn, "SELECT privileges FROM `admin details` WHERE `admin_email` = '$admin_email'");
							$privileges_data = mysqli_fetch_assoc($privileges_query);
							$privileges = explode(",", $privileges_data['privileges']);
							if (in_array('edit', $privileges)) { ?>
								<form method="POST" class="mt-3">
									<input type="hidden" name="title" value="<?php echo $data['title']; ?>">
									<input type="hidden" name="detail" value="<?php echo $data['detail']; ?>">
									<button class="btn btn-primary btn-sm" type="submit" name="seen">
										Mark as seen
									</button>
								</form>
							<?php } ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            echo '<div class="alert alert-info">No new notifications</div>';
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seen'])){
            $admin_title = $_POST['title'];
            $admin_detail = $_POST['detail'];
            
            $insert_sql = mysqli_query($conn, "INSERT INTO `admin_notification` 
                (`admin_title`, `admin_detail`, `admin_email`) 
                VALUES ('$admin_title', '$admin_detail', '$admin_email')"
            );
            
            if($insert_sql){
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>