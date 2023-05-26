<?php
include '../components/connect.php';
session_start();
if(isset($_POST['submit'])){
   $fname = $_POST['fname'];
   $fname = filter_var($fname, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE fname = ? AND password = ?");
   $select_admin->execute([$fname, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);
   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
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
   <title>Admin - Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin-login-style.css">
</head>
<body>
<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<div class="container">
   <div class="form-container">
      <form action="" method="post">
         <h3>Admin Login</h3>
         <div class="input-field">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" placeholder="First Name" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="input-field">
            <label for="pass">Password</label>
            <div class="password-toggle">
               <input type="password" name="pass" id="pass" placeholder="Password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
               <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
         </div>

         <div class="input-field">
            <input type="submit" value="LOGIN" id="submit" name="submit" disabled>
         </div>
      </form>
   </div>
</div>

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
         const emailField = document.getElementById("fname");
         const passwordField = document.getElementById("pass");
         const loginButton = document.getElementById("submit");

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