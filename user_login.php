<?php
require_once "components/connect.php";

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

$message = '';
$messageClass = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message from the session
    $messageClass = 'success';
} else {
    $messageClass = ''; // Add this line to ensure empty message class if no message is present
}

if (isset($_POST['submit'])) {
    // Process login form data
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Validate input
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: user_login.php"); // Redirect back to the login page
        exit();
    }

    // Check if the user exists and is verified
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND email_verified = 1");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        // User found and verified, check the password
        if (sha1($password) === $row['password']) {
            // Password matched, set the user_id in session and redirect to the home page or any other authenticated page
            $_SESSION['user_id'] = $row['id'];
            header("Location: home.php");
            exit();
        } else {
            $message = 'Invalid email or password.';
        }
    } else {
        $message = 'Invalid email or password.';
    }

    $_SESSION['message'] = $message;
    header("Location: user_login.php"); // Redirect back to the login page
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - ProTools</title>

   <!-- icon logo -->
   <link rel="shortcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <!-- Custom font link -->
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet" />
   
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/index-style.css">

   <style>
      .message {
         font-size: 15px;
         margin-top: 10px;
         display: flex;
         align-items: center;
         justify-content: center;
         text-align: center;
      }

      .message.success {
         color: red;
      }

      .message.error {
         color: red;
      }
   </style>

      <!-- style for loader -->
      <style>
         html, body {
            height: 100%;
            margin: 0;
            padding: 0;
         }
         
         .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            z-index: 9999;
            transition: opacity 0.5s;
         }
         
         .hidden {
            opacity: 0;
         }

         #loader {
            width: 100px;  /* Set the desired width */
            height: auto;  /* Maintain aspect ratio */
         }
   </style>

   <!-- script for loader -->
   <script>
      window.addEventListener("load", function() {
            var loaderContainer = document.getElementById("loaderContainer");
            var loader = document.getElementById("loader");
            
            setTimeout(function() {
                loaderContainer.classList.add("hidden");
                setTimeout(function() {
                    loaderContainer.style.display = "none";
                }, 500);
            }, 2000);
        });
    </script>
</head>
<body>

<div class="loader-container" id="loaderContainer">
  <img src="images/Hourglass.gif" alt="Loader" id="loader">
</div>

<div class="user-header">
   <?php include 'components/user_header.php'; ?>
</div><br><br><br><br><br><br><br><br>
<div class="container">
   <div class="form-container">
      <form action="" method="post">
         <h3>USER LOGIN</h3>
         <?php if (!empty($message)) : ?> <!-- Add this line to check if the message is not empty -->
         <div class="message <?php echo $messageClass; ?>">
            <?php echo $message; ?>
         </div><br>
         <?php endif; ?>
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
            <div class="forgot-password">
               <a href="forgot_pass.php">Forgot Password?</a>
            </div>
      </div>

         <div class="input-field">
            <input type="submit" value="LOGIN" id="submit" name="submit" disabled>
         </div>
         <div class="register-now">
            <span>Not yet a Member? </span> <a href="user_register.php">Register now!</a>
         </div>
      </form>
   </div>
</div><br><br><br><br><br><br><br><br><br><br>
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
      const loginButton = document.getElementById("submit");
      const messageElement = document.querySelector(".message");

      emailField.addEventListener("input", toggleLoginButton);
      passwordField.addEventListener("input", toggleLoginButton);

      function toggleLoginButton() {
         if (emailField.value.trim() !== "" && passwordField.value.trim() !== "") {
            loginButton.disabled = false;
         } else {
            loginButton.disabled = true;
         }
      }

      // Hide the message after 3 seconds
      setTimeout(function() {
         messageElement.style.display = "none";
      }, 3000);
   });
</script>
</body>
</html>