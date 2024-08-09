<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['submit'])){
    $brand = $_POST['brand'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder1 = 'img/'.$image;

    $thumb2 = $_FILES['thumb2']['name'];
    $thumb2_tmp_name = $_FILES['thumb2']['tmp_name'];
    $thumb2_folder2 = 'img/'.$thumb2;

    $thumb3 = $_FILES['thumb1']['name'];
    $thumb3_tmp_name = $_FILES['thumb3']['tmp_name'];
    $thumb3_folder3 = 'img/'.$thumb3;

    $thumb4 = $_FILES['thumb1']['name'];
    $thumb4_tmp_name = $_FILES['thumb4']['tmp_name'];
    $thumb4_folder1 = 'img/'.$thumb4;

    $query = mysqli_query($conn, "INSERT INTO `products`(`brand`, `name`, `price`, `image`, `thumb2`, `thumb3`, `thumb4`, `detail`)
     VALUES ('$brand','$name','$price','$image','$thumb2','$thumb3','$thumb4','$detail')") or die('query failed');
     if($query){
        move_uploaded_file($image_tmp_name, $image_folder1);
        move_uploaded_file($thumb2_tmp_name, $thumb2_folder2);
        move_uploaded_file($thumb3_tmp_name, $thumb3_folder3);
        move_uploaded_file($thumb4_tmp_name, $thumb4_folder4);
        echo "Product Successfully Added!";
        header('location: index.php');
     }else{
        echo 'query failed'.mysqli_error($error);
     }

}
?>

<html>
    <head>
        <title>Online Shopping Site for all your Clothing Apparels</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <?php
        if(isset($message)){
          foreach($message as $message){
            echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
          }
        }
    ?>
    <section id="header"> 
            <a href="about.php"> <img src="Images/LOGO.png" class="logo"> </a>
            <?php
              $select_user = mysqli_query($conn, "SELECT * FROM `login` WHERE id = '$user_id'") or die('query failed');
              if(mysqli_num_rows($select_user) > 0){
              $fetch_user = mysqli_fetch_assoc($select_user);
              };
            ?>

            <p><b> Welcome, <span><?php echo $fetch_user['username']; ?></span> </b></p>

            <div>
                <ul id="navbar">
                    <li> <a class="active" href="index.php"> Home </a></li>
                    <li> <a href="shop.php"> Shop </a></li>
                    <li> <a href="about.php"> About </a></li>
                    <li> <a href="contact.php"> Contact </a></li>
                    <li> <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to Logout?');" class="delete-btn">Logout</a></li>

                </ul>
            </div>
    </section>
        <form method="post" class="product-form">
            <h1> Add Product </h1>
            <div class="input-field">
                <label> Product Brand </label>
                <input type="text" name="brand">
            </div>
            <div class="input-field">
                <label> Product Name </label>
                <input type="text" name="name">
            </div> 
            <div class="input-field">
                <label> Product Price </label>
                <input type="text" name="price">
            </div> 
            <div class="input-field">
                <label> Image 1 </label>
                <input type="file" name="image" accept="image/jpg, image/png, image/webp">
            </div> 
            <div class="input-field">
                <label> Image 2 </label>
                <input type="file" name="thumb2" accept="image/jpg, image/png, image/webp">
            </div> 
            <div class="input-field">
                <label> Image 3 </label>
                <input type="file" name="thumb3" accept="image/jpg, image/png, image/webp">
            </div> 
            <div class="input-field">
                <label> Image 4 </label>
                <input type="file" name="thumb4" accept="image/jpg, image/png, image/webp">
            </div> 
            <div class="input-field">
                <label> Product Details </label>
                <textarea name="detail"></textarea>
            </div> 
            <input type="submit" namee="submit" value="Add Product">            
        </form>
    </body>
</html>