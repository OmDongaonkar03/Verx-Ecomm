<?php
include_once 'function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerX - Modern Fashion for Every Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<style>
		.updtheightimg{
			height:680px;
		}
	</style>
</head>
<body>
    <!-- Navigation -->
    <div>
		<?php
			navbar();
		?>
	</div>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="2000">
                <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="d-block w-100 updtheightimg" alt="Summer Collection">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Summer Collection 2024</h5>
                    <p>Fresh Styles, Unbeatable Comfort</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="2000">
                <img src="https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="d-block w-100 updtheightimg" alt="Urban Streetwear">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Urban Streetwear</h5>
                    <p>Bold Designs, Uncompromising Quality</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1638604587609-fbb8469f4234?q=80&w=1934&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="d-block w-100 updtheightimg" alt="Sustainable Fashion">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Sustainable Fashion</h5>
                    <p>Style That Cares for the Planet</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Featured Collections -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Collections</h2>
        <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
            <div class="card" style="width: 18rem;">
                <img src="https://www.snitch.co.in/cdn/shop/files/dfd1340e2976273e55ae259c16a349d1.jpg?v=1733139247" class="card-img-top" alt="Casual Wear">
                <div class="card-body">
                    <h5 class="card-title">Casual Essentials</h5>
                    <p class="card-text">Comfortable and stylish everyday wear for the modern individual.</p>
                    <a href="productpage.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            <div class="card" style="width: 18rem;">
                <img src="https://cdn.shopify.com/s/files/1/0420/7073/7058/files/e3102811d5cb676f0951b89d990d7248.jpg?v=1732601799&width=600" class="card-img-top" alt="Professional Wear">
                <div class="card-body">
                    <h5 class="card-title">Magestic Match</h5>
                    <p class="card-text">Sophisticated and sleek clothing for the workplace and beyond.</p>
                    <a href="productpage.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            <div class="card" style="width: 18rem;">
                <img src="https://www.snitch.co.in/cdn/shop/files/d46409a4e00da36e6ff934bca86fb44d.jpg?v=1733139219" class="card-img-top" alt="Active Wear">
                <div class="card-body">
                    <h5 class="card-title">Active Lifestyle</h5>
                    <p class="card-text">Performance-driven clothing for fitness enthusiasts and adventurers.</p>
                    <a href="productpage.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            <div class="card" style="width: 18rem;">
                <img src="https://www.snitch.co.in/cdn/shop/files/0bb1602ee9ce4a3ee1867e663822f2c9.jpg?v=1733139195https://images.unsplash.com/photo-1558543578-b05c18577712?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="Accessories">
                <div class="card-body">
                    <h5 class="card-title">Look-Up</h5>
                    <p class="card-text">Comfortable and stylish everyday wear for the modern individual.</p>
                    <a href="productpage.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
	
	<!-- size finder -->
	<div class="card text-bg-dark m-5">
		<img src="https://9thson.com/wp-content/uploads/2020/08/Men_Round_Updated_with_5XL_2.jpg" class="card-img" alt="...">
	</div>
	
	<!-- Ethical Production Story -->
	<div class="d-flex flex-row flex-wrap justify-content-center gap-4">
		<div class="card" style="width: 18rem;">
			  <img src="https://images.unsplash.com/photo-1499971442178-8c10fdf5f6ac?q=80&w=1891&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="...">
			  <div class="card-body">
				<p class="card-text">At VerX, <b>transparency is our promise.</b> We meticulously track every step of our production process, ensuring that each garment is created under fair, safe, and ethical working conditions. Our factories are regularly audited to maintain the highest standards of worker welfare and environmental responsibility.</p>
			  </div>
		</div>
		<div class="card" style="width: 18rem;">
			  <img src="https://images.unsplash.com/photo-1580287486990-b08ff7d0dfca?q=80&w=1891&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="...">
			  <div class="card-body">
				<p class="card-text">We believe in <b>empowering workers</b> through fair wages, safe working environments, and comprehensive benefits. Our partnerships with manufacturing facilities prioritize worker rights, provide ongoing training programs, and ensure that every individual involved in creating our clothing is treated with dignity and respect.</p>
			  </div>
		</div>
		<div class="card" style="width: 18rem;">
			  <img src="https://images.unsplash.com/photo-1524275539700-cf51138f679b?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="...">
			  <div class="card-body">
				<p class="card-text">Our commitment to <b>sustainability goes beyond design.</b> We source materials responsibly, focusing on organic, recycled, and low-impact fabrics. From responsibly harvested cotton to innovative recycled polyester, each material is chosen to minimize environmental footprint while maintaining the highest quality standards.</p>
			  </div>
		</div>
	</div>

    <!-- Customer Reviews Accordion -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Customer Reviews</h2>
        <div class="accordion" id="customerReviews">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Quality and Comfort
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                    <div class="accordion-body">
                        <strong>Exceptional Quality!</strong> I've been shopping at VerX for years, and their commitment to quality never disappoints. The fabrics are soft, durable, and the fit is always perfect.
                        <div class="text-end">- Sarah M., Verified Buyer</div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Style and Versatility
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                    <div class="accordion-body">
                        <strong>Versatile and Trendy!</strong> VerX understands modern fashion. Their pieces easily transition from work to weekend, and always keep me looking stylish.
                        <div class="text-end">- Michael T., Verified Buyer</div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Customer Service
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                    <div class="accordion-body">
                        <strong>Outstanding Support!</strong> The customer service team at VerX goes above and beyond. Returns are hassle-free, and they truly care about customer satisfaction.
                        <div class="text-end">- Emma R., Verified Buyer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Signup -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Stay Updated with VerX</h5>
                        <p class="card-text text-center">Subscribe to our newsletter and get 10% off your first order!</p>
                        <form>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email" aria-label="Email" aria-describedby="newsletter-signup">
                                <button class="btn btn-primary" type="submit" id="newsletter-signup">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
		footer();
	?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
