<?php
include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:admin_login.php');
}
if(isset($_POST['submit'])){
   $username = $_POST['username'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $fname = $_POST['fname'];
   $fname = filter_var($fname, FILTER_SANITIZE_STRING);
   $sname = $_POST['sname'];
   $sname = filter_var($sname, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE username = ?");
   $select_admin->execute([$username]);

   if($select_admin->rowCount() > 0){
      $message[] = 'username already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admins`(username, fname, sname, password) VALUES(?,?,?,?)");
         $insert_admin->execute([$username, $fname, $sname, $cpass]);
         $message[] = 'new admin registered successfully!';
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register New Admin User</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin-register-style.css">
   <link rel="stylesheet" href="../css/admin_style.css">


</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="register-container">
   <div class="register-form-container">
      <form action="" method="post">
         <h3>New Admin User</h3><br>
         <div class="input-field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="input-field">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="input-field">
            <label for="sname">Surname</label>
            <input type="text" name="sname" id="sname" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="input-field">
            <label for="pass">Password</label>
            <div class="password-toggle">
               <input type="password" name="pass" id="pass" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
               <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
         </div>

         <div class="input-field">
            <label for="cpass">Confirm Password</label>
            <div class="password-toggle">
               <input type="password" name="cpass" id="cpass" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
               <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
         </div>

         <div class="input-field">
            <input type="submit" value="REGISTER" id="submit" name="submit">
         </div>
      </form>
   </div>
</div>



<script src="../js/admin_script.js"></script>
<script>
   function togglePasswordVisibility(icon) {
      var passwordField = icon.previousElementSibling;
      if (passwordField.type === 'password') {
         passwordField.type = 'text';
         icon.classList.remove('fa-eye-slash');
         icon.classList.add('fa-eye');
      } else {
         passwordField.type = 'password';
         icon.classList.remove('fa-eye');
      }
   }

</script>
</body>
</html>