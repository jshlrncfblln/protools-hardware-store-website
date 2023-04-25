<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['password']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - ProTools</title>
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/index-style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
   <div class="container">
      <div class="form-container">
        <form action="" method="post">
          <h3>Login</h3>
          <p>Login to your account first and enjoy shopping.</p>
          <div class="input-field">
            <input type="email" name="email" id="email" required oninput="this.value = this.value.replace(/\s/g, '')">
            <label for="username">Email</label>
          </div>
          <div class="input-field">
            <input type="password" name="password" id="password" required oninput="this.value = this.value.replace(/\s/g, '')">
            <label for="password">Password</label>
            <span class="show-hide" onclick="showHidePassword()"></span>
          </div>
          <div class="forgot-password">
            <a href="#">Forgot password?</a>
          </div>
          <button type="submit" name="submit" id="submit">Login</button>
          <div class="register-now">
            <span>Not a member yet?</span> <a href="user_register.php">Register now!</a>
          </div>
        </form>
      </div>
    </div>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/index.js"></script>

</body>
</html>