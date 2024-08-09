<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
;
$sqlProductReview = "
	SELECT * FROM review_table WHERE id = " . $_GET['pid'] . "
	ORDER BY review_id DESC 
	";

$result = $conn->query($sqlProductReview);


$sqlTotalReview = "
	SELECT * FROM review_table WHERE id = " . $_GET['pid'] . "
	ORDER BY review_id DESC 
	";

$resultTotalReview = $conn->query($sqlTotalReview);
$ratingCount = $resultTotalReview->fetch_assoc();

$sqlReviewCount = "
	SELECT COUNT(*) AS review_count FROM review_table WHERE id = " . $_GET['pid'] . "
	ORDER BY review_id DESC 
	";
$resultReviewCount = $conn->query($sqlReviewCount);
$reviewCount = $resultReviewCount->fetch_assoc();


if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}
;

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'product already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
        $message[] = 'product added to cart!';
    }

}
;

if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
    $message[] = 'cart quantity updated successfully!';
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
    header('location:index.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:index.php');
}

?>
<html>

<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</head>
<style>
    .sidebar {
        height: 100%;
        width: 0px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: transparent;
        backdrop-filter: blur(120px);
        overflow-x: hidden;
        transition: width 0.3s;
        padding-top: 20px;
        z-index: 1000;
        /* Ensure sidebar is on top of other content */
    }

    .sidebar h2 {
        font-size: 30px;

    }

    .sidebar span {
        margin-bottom: 10px;
        margin-top: 5px;
        font-size: 18px;
        font-weight: 550;
        margin-left: 10px;
    }

    .sidebar .categories {
        margin-top: 30px;
    }

    .sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 25px;
        color: black;
        display: block;
    }

    .sidebar a:hover {
        background-color: rgba(128, 0, 128, 0.63);
    }

    .content {
        margin-left: 0;
        padding: 20px;
        transition: margin-left 0.3s;
    }

    .openbtn {
        font-size: 40px;
        cursor: pointer;
        background-color: transparent;
        color: black;
        padding: 10px 15px;
        border: none;
        position: fixed;
        top: 20px;
        left: 10px;
        z-index: 1000;
    }

    ::-webkit-scrollbar-thumb {
        background: transparent;
        border-radius: 10px;
    }

    .closebtn {
        font-size: 25px;
        cursor: pointer;
        color: black;
        background-color: transparent;
        padding: 10px 15px;
        border: none;
        position: absolute;
        top: 10px;
        right: 10px;

    }

    .categories {
        margin: 20px;

    }

    .category-name-icon {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 35px;
        margin-bottom: -50px;
    }

    .cat-name {
        margin-right: 10px;
        margin-left: 15px;
        padding-bottom: 62px;
        margin-bottom: -35px;
    }

    .cat-name h2 {
        padding-top: 40px;
        color: black;
    }

    .cat {
        display: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        margin-top: 20px;
    }

    .cat a {
        display: block;
        padding: 10px 0;
        text-decoration: none;
        color: black;
    }

    .cat a:hover {
        background-color: rgba(128, 0, 128, 0.63);
        border-radius: 4px;
        padding-left: 20px;
    }

    ////////////////////////////////////////////////////////////////////////////////
    .categories {
        margin: 20px;
    }

    .brand-name-icon {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 35px;
        margin-left: 20px;
    }

    .brand-name {
        margin-right: 15px;
        padding-top: 5px;
    }

    .brand-name h2 {
        padding-top: 15px;
        margin-left: 20px;
        color: black;
    }

    .brand-name-icon .dropdown-icon {
        padding-top: 7px;
    }

    .brands {
        display: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        margin-left: 20px;
    }

    .brands a {
        display: block;
        padding: 10px 0;
        text-decoration: none;
        color: black;
    }

    .brands a:hover {
        background-color: rgba(128, 0, 128, 0.63);
        border-radius: 4px;
        padding-left: 20px;
    }

    .sidebar h5 {
        font-size: 50px;
        margin-left: 10px;
        margin-bottom: -30px;
        color: black;
        line-height: 1.2;
    }

    .reserved-cpyright {
        text-align: center;
        margin-left: 15px;
        font-size: 15px;
        margin-top: 300px;
        position: absolute;
    }

    /* Responsive layout - when the screen is less than 600px wide, make the sidebar and content stack on top of each other */
    @media screen and (max-width: 600px) {
        .sidebar {
            padding-top: 15px;
        }

        .openbtn {
            display: block;
        }

        .closebtn {
            display: none;
        }
    }
</style>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>
    <!-- HEADER STARTS -->
    <section id="header">
        <div class="sidebar" id="sidebar">
            <h5>SHOP<br>BY</h5>
            <ion-icon name="close-outline" class="closebtn" onclick="closeNav()"></ion-icon>
            <div class="categories">
                <div class="category-name-icon" onclick="toggleDropdown()">
                    <div class="cat-icon"><ion-icon name="list-outline" class="cat-icon"></ion-icon></div>
                    <div class="cat-name">
                        <h2>Categories</h2>
                    </div>
                    <div class="dropdown-icon"><ion-icon name="chevron-down-outline"></ion-icon></div>
                </div>
                <div class="cat">
                    <span>
                        <?php

                        $select_category = "SELECT category, category_id FROM products GROUP BY category_id";
                        $result_category = mysqli_query($conn, $select_category) or die("query failed");
                        while ($row_data = mysqli_fetch_assoc($result_category)) {
                            $category_title = ucfirst($row_data["category"]);
                            $category_id = $row_data["category_id"];
                            echo "<a href='index.php?category=$category_title'>$category_title</a>";
                        }
                        ?>
                    </span>
                </div>
            </div>

            <hr>

            <div class="brand-name-icon" onclick="toggleDropdownBrand()">
                <div class="brand-icon"><ion-icon name="shirt" class="brand-icon"></ion-icon></div>
                <div class="brand-name">
                    <h2>Brands</h2>
                </div>
                <div class="dropdown-icon"><ion-icon name="chevron-down-outline"></ion-icon></div>
            </div>
            <div class="brands">
                <span>
                    <?php
                    $select_brands = "SELECT brand, brand_id FROM products GROUP BY brand_id";
                    $result_brands = mysqli_query($conn, "$select_brands") or die("query failed");
                    while ($row_data = mysqli_fetch_assoc($result_brands)) {
                        $brand_title = $row_data["brand"];
                        $brand_id = $row_data["brand_id"];
                        echo " <a href = 'index.php?brand=$brand_title'>$brand_title</a>";
                    }
                    ?>
                </span>
            </div>

            <hr>
            <div class="reserved-cpyright">Copyright © 2024, <br> FitChk // Your Ultimate Shopping Companion<br> All
                Rights Reserved.</div>
        </div>




        <div class="content" id="content">
            <button class="openbtn" onclick="openNav()">&#9776;</button>
            <a href="about.php"> <img src="Images/LOGO.png" class="logo"> </a>
        </div>
        <?php
        $select_user = mysqli_query($conn, "SELECT * FROM `login` WHERE id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user);
        }
        ;
        ?>
        <p><b> Welcome, <span><?php echo $fetch_user['username']; ?></span> </b></p>

        <div>
            <ul id="navbar">
                <li> <a href="index.php"> Home </a></li>
                <li> <a class="active" href="shop.php"> Shop </a></li>
                <li> <a href="about.php"> About </a></li>
                <li> <a href="contact.php"> Contact </a></li>
                <?php
                $select_product = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
                $row_count = mysqli_num_rows($select_product);
                ?>
                <div class="cart-icon">
                    <li> <span class="icon"><ion-icon onclick="location.href='cart.php'" name="cart"></ion-icon></span>
                        <span class="cart-span"><?php echo $row_count; ?> </span>
                    </li>
                </div>
                <li> <a href="index.php?logout=<?php echo $user_id; ?>"
                        onclick="return confirm('Are you sure you want to Logout?');" class="delete-btn">Logout</a></li>

            </ul>
        </div>
    </section>
    <!-- HEADER ENDS -->

    <!-- PRODUCTS -->
    <section class="product-detail">

        <?php
        if (isset($_GET['pid'])) {
            $pid = $_GET['pid'];
            $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id= '$pid'") or die('query failed');
            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    ?>
                    <div class="popup-card">
                        <div class="popup-card-detail">
                            <div class="popup-img">
                                <figure>
                                    <img src="images/<?php echo $fetch_product['image']; ?>" id="mainImage">
                                </figure>
                                <div class="thumb-list">
                                    <ul>
                                        <li><img src="images/<?php echo $fetch_product['image']; ?>" id="image"></li>
                                        <li><img src="images/<?php echo $fetch_product['thumb2']; ?>" id="thumb2"></li>
                                        <li><img src="images/<?php echo $fetch_product['thumb3']; ?>" id="thumb3"></li>
                                        <li><img src="images/<?php echo $fetch_product['thumb4']; ?>" id="thumb4"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info">
                            <form method="post" class="box" action="">
                                <div class="brand-name">
                                    <h5 class="product-title"> <?php echo $fetch_product['brand']; ?> </h5>
                                </div>
                                <div class="name"><span><?php echo $fetch_product['name']; ?></span></div>
                                <h3>
                                    <div class="price">₹<?php echo $fetch_product['price']; ?> </div>
                                </h3>
                                <div class="qty-product">
                                    <span>Quantity</span>
                                <input type="number" min="1" name="product_quantity" value="1">
                                </div>
                                    
                                    <button class="desc-btn" name="add_to_cart"> <i onclick="location.href='cart.php'"
                                    class='bx bxs-cart-alt' id="cart-icon"></i></button>
                                
                                <p> <?php echo $fetch_product['detail']; ?></p>
                                
                                <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>

    </section>
    <!-- PRODUCTS END-->

    <!-- REVIEW AND RATING -->
    <div class="container">
        <h1 class="mt-5 mb-5">Review & Ratings</h1>
        <div class="card">
            <div class="card-header">Here's What Customers Think</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <h1 class="text-warning mt-4 mb-4">
                            <b><span id="average_rating"><?php
                            if (isset($ratingCount['user_rating']) && $ratingCount['user_rating'] !== NULL) {
                                echo $ratingCount['user_rating'];
                            } else {
                                echo "0";
                            }
                            ?></span> / 5</b>
                        </h1>
                        <div class="mb-3">
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                        </div>
                        <h3><span id="total_review"><?= $reviewCount['review_count']; ?></span> Reviews</h3>
                    </div>
                    <!-- <div class="col-sm-4">
                    <p>
                        <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>
                        <div class="progress-label-right">(<span id="total_five_star_review"></span>)</div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" id="five_star_progress"></div>
                        </div>
                    </p>
                </div> -->
                    <div class="col-sm-4 text-center">
                        <h3 class="mt-4 mb-3">Write Review Here</h3>
                        <button type="button" name="add_review" id="add_review" class="btn btn-primary">Review</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5" id="review_content">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $timestamp = $row['datetime'];
                    $date = date("Y-m-d", strtotime($timestamp));
                    ?>
                    <div class="row mb-3">
                        <div class="col-sm-11">
                            <div class="card">
                                <div class="card-header"><b><?= $row['user_name']; ?></b></div>
                                <div class="card-body">
                                    <?= $row['user_review']; ?>
                                </div>
                                <div class="card-footer text-right">On <?= $date; ?> </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No Reviews";
            }
            ?>
        </div>
    </div>
    <!-- REVIEW AND RATING ENDS -->



    <!-- CSS FOR REVIEW AND RATING -->
    <style>
        .container {
            max-width: 890px;
            margin: auto;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to bottom right, #4e54c8, #8f94fb);
            color: #fff;
            overflow: hidden;
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-size: 1.5rem;
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        #review_content {
            width: 110%;
        }

        .btn-primary {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
            /* Adjust vertical spacing */
            width: 90%;
        }

        .btn-primary:hover {
            background-color: #f4511e;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }

        .col-sm-4 {
            margin-left: 60px;
        }

        .col-sm-11 {
            width: 500px;
        }

        .progress {
            height: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .progress-bar {
            border-radius: 5px;
        }

        .main_star {
            color: #ffc107;
            font-size: 1.8rem;
            margin-right: 5px;
        }

        .star-light {
            color: #ffc107;
            font-size: 1.8rem;
        }

        .review-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f5f5f5;
            overflow: hidden;
        }

        .review-card .card-header {
            background-color: #2196f3;
            color: #fff;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .review-card .card-body {
            padding: 20px;
        }

        .review-card .card-footer {
            background-color: #f5f5f5;
            padding: 15px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            text-align: right;
        }

        .submit_star {
            cursor: pointer;
            font-size: 2rem;
            transition: color 0.2s;
            color: #aaa;
            /* Default star color */
        }

        .submit_star.star-filled,
        .submit_star.star-filled~.submit_star {
            color: #ffc107;
            /* Color of filled stars */
        }

        .modal-content {
            height: 350px;
        }
    </style>
    <!-- CSS FOR REVIEW AND RATING ENDS -->

    <hr style="width: 50%; height: 2.5px; margin-left: 50px; background-color: black;">
    <br>
    <hr style="width: 50%; height: 2.5px; margin-left: 45%; background-color: black">

    <!-- FOOTER SECTION -->
    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="Images/LOGO.png">
            <h4><br> Contact Us</h4>
            <p><strong>Address:</strong> GCET Cheeryal(V), Keesara(M), Telangana-501 301</p>
            <p><strong>Phone:</strong> +91 1234567890</p>
            <p><strong>Hours:</strong> 10:00AM to 10PM, Mon-Sat</p>
            <div class="follow">
                <h4> Follow Us!</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="about.php"> About Us</a>
            <a href="#"> Delivery Information</a>
            <a href="privacypol.html"> Privacy Policy</a>
            <a href="t&c.html"> Terms And Conditions</a>
            <a href="contact.php"> Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="addproduct.php">Become A Seller </a>
            <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to Logout?');">Sign Out</a>            <a href="#">Track My Order</a>
            <a href="contact.php">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p> From App Store Or Google Play</p>
            <div class="row">
                <img src="Images/app.jpg">
                <img src="Images/play.jpg">
            </div>
            <p> Secured Payment Using </p>
            <img src="Images/pay.png">
            <p></p>
            <img src="Images/upi.png">
        </div>
        <div class="copyright">
            <h4>© 2024, FitChk // Your Ultimate Shopping Companion</h4>
        </div>
    </footer>
    <!-- FOOTER SECTION ENDS -->
</body>

</html>

<div id="review_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" hidden id="pid" name="pid" value="<?= $_GET['pid']; ?>">
                <h4 class="text-center mt-2 mb-4">
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                </h4>
                <div class="form-group">
                    <input type="text" name="user_name" id="user_name" class="form-control"
                        placeholder="Enter Your Name" />
                </div>
                <div class="form-group">
                    <textarea name="user_review" id="user_review" class="form-control"
                        placeholder="Type Review Here"></textarea>
                </div>
                <div class="form-group text-center mt-4">
                    <button type="button" class="btn btn-primary" id="save_review">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {

        var rating_data = 0;

        $('#add_review').click(function () {

            $('#review_modal').modal('show');

        });

        $(document).on('mouseenter', '.submit_star', function () {

            var rating = $(this).data('rating');

            reset_background();

            for (var count = 1; count <= rating; count++) {

                $('#submit_star_' + count).addClass('text-warning');

            }

        });

        function reset_background() {
            for (var count = 1; count <= 5; count++) {

                $('#submit_star_' + count).addClass('star-light');

                $('#submit_star_' + count).removeClass('text-warning');

            }
        }

        $(document).on('mouseleave', '.submit_star', function () {

            reset_background();

            for (var count = 1; count <= rating_data; count++) {

                $('#submit_star_' + count).removeClass('star-light');

                $('#submit_star_' + count).addClass('text-warning');
            }

        });

        $(document).on('click', '.submit_star', function () {

            rating_data = $(this).data('rating');

        });

        $('#save_review').click(function () {

            var user_name = $('#user_name').val();

            var user_review = $('#user_review').val();

            var pid = $('#pid').val();

            if (user_name == '' || user_review == '') {
                alert("Please Fill Both Field");
                return false;
            }
            else {
                $.ajax({
                    url: "submit_rating.php",
                    method: "POST",
                    data: { pid: pid, rating_data: rating_data, user_name: user_name, user_review: user_review },
                    success: function (data) {
                        $('#review_modal').modal('hide');
                        window.location.href = "view_detail.php?pid=" + pid;

                        load_rating_data();

                        alert(data);
                    }
                })
            }

        });

        load_rating_data();

        function load_rating_data() {
            $.ajax({
                url: "submit_rating.php",
                method: "POST",
                data: { action: 'load_data' },
                dataType: "JSON",
                success: function (data) {
                    // $('#average_rating').text(data.average_rating);
                    // $('#total_review').text(data.total_review);

                    var count_star = 0;

                    $('.main_star').each(function () {
                        count_star++;
                        if (Math.ceil(data.average_rating) >= count_star) {
                            $(this).addClass('text-warning');
                            $(this).addClass('star-light');
                        }
                    });

                    $('#total_five_star_review').text(data.five_star_review);

                    $('#total_four_star_review').text(data.four_star_review);

                    $('#total_three_star_review').text(data.three_star_review);

                    $('#total_two_star_review').text(data.two_star_review);

                    $('#total_one_star_review').text(data.one_star_review);

                    $('#five_star_progress').css('width', (data.five_star_review / data.total_review) * 100 + '%');

                    $('#four_star_progress').css('width', (data.four_star_review / data.total_review) * 100 + '%');

                    $('#three_star_progress').css('width', (data.three_star_review / data.total_review) * 100 + '%');

                    $('#two_star_progress').css('width', (data.two_star_review / data.total_review) * 100 + '%');

                    $('#one_star_progress').css('width', (data.one_star_review / data.total_review) * 100 + '%');
                }
            })
        }

    });

</script>


<script>
    const mainImage = document.querySelector('#mainImage');
    const image = document.querySelector('#image');
    const imageSrc = document.querySelector('#image').src;
    const thumb2 = document.querySelector('#thumb2');
    const thumb2Src = document.querySelector('#thumb2').src;
    const thumb3 = document.querySelector('#thumb3');
    const thumb3Src = document.querySelector('#thumb3').src;
    const thumb4 = document.querySelector('#thumb4');
    const thumb4Src = document.querySelector('#thumb4').src;
    image.addEventListener('click', () => {
        mainImage.src = imageSrc
    })
    thumb2.addEventListener('click', () => {
        mainImage.src = thumb2Src
    })
    thumb3.addEventListener('click', () => {
        mainImage.src = thumb3Src
    })
    thumb4.addEventListener('click', () => {
        mainImage.src = thumb4Src
    })
</script>

<script>
    function toggleDropdown() {
        var dropdownContent = document.querySelector('.cat');
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
            dropdownContent.style.maxHeight = "0";
        } else {
            dropdownContent.style.display = "block";
            dropdownContent.style.maxHeight = dropdownContent.scrollHeight + "px";
        }
    }
</script>

<script>
    function toggleDropdownBrand() {
        var dropdownContent = document.querySelector('.brands');
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
            dropdownContent.style.maxHeight = "0";
        } else {
            dropdownContent.style.display = "block";
            dropdownContent.style.maxHeight = dropdownContent.scrollHeight + "px";
        }
    }
</script>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
    function openNav() {
        document.getElementById("sidebar").style.width = "350px";
        document.getElementById("content").style.marginLeft = "350px";
        document.getElementsByClassName("openbtn")[0].style.display = 'none';
    }

    function closeNav() {
        document.getElementById("sidebar").style.width = "0";
        document.getElementById("content").style.marginLeft = "0";
        document.getElementsByClassName("openbtn")[0].style.display = 'block';
    }
</script>

</body>

</html>