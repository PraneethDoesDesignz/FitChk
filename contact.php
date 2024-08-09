<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
;

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
    <title>Contact FitChk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
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
        background-color: #e3e6f3;
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
                <li> <a href="shop.php"> Shop </a></li>
                <li> <a href="about.php"> About </a></li>
                <li> <a class="active" href="contact.php"> Contact </a></li>
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

    <section id="page-header" class="about-header">
        <h2>#Let's_Talk</h2>
        <p> Need some help? Contact Us! </p>
    </section>

    <section id="contact-details" class="section-p1">
        <div class="details">
            <span> Get In Touch </span>
            <h2> Visit Our Location or Contact Us Today!</h2>
            <h3> Head Office </h3>
            <div>
                <li>
                    <i class="fa-solid fa-map-location-dot"></i>
                    <p> GCET Cheeryal(V), Keesara (M), Medchal dist., Telangana-501 301</p>
                </li>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <p>info@gcet.edu</p>
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    <p>+91 0123456789</p>
                </li>
                <li>
                    <i class="fa-solid fa-clock"></i>
                    <p>Monday To Saturday: 9:00am to 3:30pm</p>
                </li>
            </div>
        </div>
        <div class="map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3804.726691940926!2d78.62845237468585!3d17.52055539895604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9d3704b16971%3A0x522242e0977760ef!2sGeethanjali%20College%20of%20Engineering%20and%20Technology!5e0!3m2!1sen!2sus!4v1715333531390!5m2!1sen!2sus"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <section id="form-details">
        <form action="https://api.web3forms.com/submit" method="POST">
            <span> Leave A Message </span>
            <h2> We'd Love To Hear From You </h2>
            <input type="hidden" name="access_key" value="9e12c783-8948-4b22-a19d-272ed06d9ccd" required>
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="text" name="email" placeholder="Your Email" required>
            <textarea name="message" cols="30" rows="10" placeholder="Enter Message"></textarea>
            <button type="submit" class="normal">Submit</button>
        </form>

        <div class="people">
            <div>
                <img src="Images/People/1.png">
                <p><span>T.A Sai Praneeth</span>B.Tech CSE-3C<br> Phone Number: +91 0123456789<br> Email:
                    contact@example.com</p>
            </div>
            <div>
                <img src="Images/People/nashwitha.png">
                <p><span>S. Nashwitha</span>B.Tech CSE-3C<br> Phone Number: +91 0123456789<br> Email:
                    contact@example.com</p>
            </div>
            <div>
                <img src="Images/People/3.png">
                <p><span>M. Charan</span>B.Tech CSE-3C<br> Phone Number: +91 0123456789<br> Email: contact@example.com
                </p>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4> Sign Up For Newsletters!</h4>
            <p>Get E-mail Updates About Our <span>Latest Offers and Drops!</span></p>
        </div>
        <div class="form">
            <form name="submit-to-google-sheet">
                <input type="email" name="Email" placeholder="Your E-mail Address" required>
                <button type="sumbit"><img src="Images/send-icon.png" width="30px"></button>
            </form>
            <span id="msg"></span>
        </div>
    </section>
    <!-- NEWSLETTER ENDS -->

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
            <h4>© 2024, FitChk // Your Ultimate Shopping Companion</h5>
        </div>
    </footer>
    <!-- FOOTER SECTION ENDS -->

    <script>
        const scriptURL = 'https://script.google.com/macros/s/AKfycbw7N5q7I8Ys4ySAo-2eXI8ySmxeiPZaQ83QosEBP1LjtXsW1NagK6THEFRiT45vLtwkPw/exec'
        const form = document.forms['submit-to-google-sheet']
        const msg = document.getElementById("msg")

        form.addEventListener('submit', e => {
            e.preventDefault()
            fetch(scriptURL, { method: 'POST', body: new FormData(form) })
                .then(response => {
                    msg.innerHTML = "Thank You For Subscribing!"
                    setTimeout(function () {
                        msg.innerHTML = ""
                    }, 5000)
                    form.reset()
                })
                .catch(error => console.error('Error!', error.message))
        })
    </script>

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


</body>

</html>