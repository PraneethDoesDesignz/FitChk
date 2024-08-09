<?php

include 'config.php';

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

   $select = mysqli_query($conn, "SELECT * FROM `login` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'User Already Exists!';
   }else{
      mysqli_query($conn, "INSERT INTO login ( username, email, password) VALUES('".$name."', '".$email."','".$pass."' )") or die('query failed');
      $message[] = 'Registered Successfully!';
      header('location:login.php');
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="log_reg.css">

</head>
<body>
<div id="video-bg">
        <video autoplay muted loop plays-inline class="background-clip">
            <source src="Images/bg-clip.mp4" type="video/mp4">
</div>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   <section>
<div id="php-form">

   <form action="register.php" method="post">
      <h1>Sign Up</h1>
      <div class="input-box">
        <span class="icon"><ion-icon name="person"></ion-icon></span>
        <input type="text" name="name" required autocomplete>
        <label>Username</label>
      </div>
      <div class="input-box">
        <span class="icon"><ion-icon name="mail"></ion-icon></span>
        <input type="email" name="email" required>
        <label>Email</label>
      </div>
      <div class="input-box">
        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
        <input type="password" name="password" required>
        <label>Password</label>
      </div>
      <div class="input-box">
        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
        <input type="password" name="cpassword" required>
        <label>Confirm Password</label>
      </div>
      <input type="submit" name="submit" id="btn" value="Register Now">
      <div class="login-link">
      <p>Already have an account? <a href="login.php">Login Now!</a></p>
      </div>
   </form>

</div>
</section>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>