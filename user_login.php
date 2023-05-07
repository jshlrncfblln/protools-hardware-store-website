<?php
include 'components/connect.php';
session_start();
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};
if (isset($_POST['submit'])) {

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['password']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      if ($row['email_verified'] == 1) {
         // Email is verified, proceed with login
         if ($row['password'] === $pass) {
            $_SESSION['user_id'] = $row['id'];
            header('location: home.php');
         } else {
            $message[] = 'Incorrect username or password!';
         }
      } else {
         $message[] = 'Please verify your email before logging in. Check your inbox for the verification email.';
      }
   } else {
      $message[] = 'Incorrect username or password!';
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- Custom font link -->
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet" />
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/index-style.css">
</head>
<body>
   
<div class="user-header">
   <?php include 'components/user_header.php'; ?>
</div>
<div class="container">
   <div class="form-container">
      <form action="" method="post">
         <h3>Welcome User!</h3>
         <br><br>
         <div class="input-field">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
         </div>
         <div class="input-field">
            <label for="password">Password</label>
            <div class="password-toggle">
               <input type="password" name="password" id="password" required>
               <i class="far fa-eye-slash toggle-pssword" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
         </div>
         <div class="input-field">
            <input type="submit" value="LOGIN" id="login-btn" disabled>
         </div>
         <div class="register-now">
            <span>Not yet a Member? </span> <a href="user_register.php">Register now!</a>
         </div>
      </form>
   </div>
</div>
<div class="footer">
   <?php include 'components/footer.php'; ?>
</div>

<script src="js/script.js"></script>
<script>
   function togglePasswordVisibility(icon) {
      var passwordField = icon.previousElementSibling;
      if (passwordField.type === 'password') {
         passwordField.type = 'text';
         icon.classList.remove('fa-eye-slash');
         icon.classList.add('fa-eye');
      } else {
         passwordField.type = 'password';
         icon.classList.add('fa-eye-slash');
         icon.classList.remove('fa-eye');
      }
   }
   document.addEventListener("DOMContentLoaded", function() {
      const emailField = document.getElementById("email");
      const passwordField = document.getElementById("password");
      const loginButton = document.getElementById("login-btn");

      emailField.addEventListener("input", toggleLoginButton);
      passwordField.addEventListener("input", toggleLoginButton);

      function toggleLoginButton() {
         if (emailField.value.trim() !== "" && passwordField.value.trim() !== "") {
            loginButton.disabled = false;
         } else {
            loginButton.disabled = true;
         }
      }
   });
</script>

</body>
</html>