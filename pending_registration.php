<?php
	include_once('function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Registration Pending</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
       body {
           background: #f8f9fa;
           min-height: 100vh;
           display: flex;
           align-items: center;
           justify-content: center;
       }
       
       .message-card {
           background: white;
           border-radius: 12px;
           box-shadow: 0 4px 6px rgba(0,0,0,0.1);
           padding: 2.5rem;
           text-align: center;
           max-width: 650px;
           margin: 1rem;
       }
       
       .icon {
           color: #1a237e;
           font-size: 4rem;
           margin-bottom: 1.5rem;
       }
       
       .status {
           color: #0d6efd;
           font-weight: 600;
           margin-bottom: 1rem;
       }
       
       .back-btn {
           background: #1a237e;
           border: none;
           margin-top: 2rem;
           padding: 0.8rem 2rem;
       }
       
       .back-btn:hover {
           background: #0d1b69;
       }

       .divider {
           border-top: 1px solid #dee2e6;
           margin: 1.5rem 0;
       }
   </style>
</head>
<body>
   <div class="message-card">
       <i class="fas fa-clock icon"></i>
       <h2 class="status">Thank You for Choosing Us!</h2>
       <p class="lead mb-3">Your registration request has been sent successfully!</p>
       
       <p class="mb-3">We're excited to have you join our community. Our admin team is currently reviewing your application to ensure the best experience for all our users.</p>
       
       <div class="alert alert-info" role="alert">
           <i class="fas fa-info-circle me-2"></i>
           This process typically takes 24 hours.
       </div>

       <div class="divider"></div>

       <h5 class="mb-3">What happens next?</h5>
       <p class="mb-2">1. Our team reviews your application</p>
       <p class="mb-3">2. Once You are accepted then you can buy products from our platform</p>


       <a href="index.php" class="btn btn-lg back-btn text-white">
           <i class="fas fa-arrow-right me-2"></i>Explore VerX
       </a>
   </div>
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>