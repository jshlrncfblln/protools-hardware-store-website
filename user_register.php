<?php
require_once "components/connect.php";
require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    // Process form data
    $fname = $_POST['fname'];
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $sname = $_POST['sname'];
    $sname = filter_var($sname, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['password']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['confirm-password']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Validation
    $errors = [];

    if (empty($fname)) {
        $errors[] = "First name is required.";
    }

    if (empty($sname)) {
        $errors[] = "Last name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    } elseif (strlen($_POST['password']) < 6) {
        $errors[] = "Password should be at least 6 characters long.";
    }

    if ($_POST['password'] !== $_POST['confirm-password']) {
        $errors[] = "Confirm password does not match.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: user_register.php"); // Redirect back to the registration page
        exit();
    }

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message = 'Email already exists! Please try again.';
        $_SESSION['message'] = $message;
        header("Location: user_register.php"); // Redirect back to the registration page
        exit();
    } else {
        $verification_code = sha1(rand()); // Generate verification code

        $insert_user = $conn->prepare("INSERT INTO `users`(fname, sname, email, password, verification_code) VALUES(?,?,?,?,?)");
        $insert_user->execute([$fname, $sname, $email, $cpass, $verification_code]);
        $message = 'You have successfully registered! Please check your email for verification.';
        $_SESSION['message'] = $message;

        // Send verification email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'joshua.laurence.fabi@gmail.com';
            $mail->Password = 'kaydticjfcfiexfi';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('joshua.laurence.fabi@gmail.com');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $verification_link = 'http://localhost/protools-hardware-store-website/verify.php?code=' . $verification_code;
            $mail->Body = "Please click the following link to verify your email address: <a href=\"$verification_link\">$verification_link</a>";

            $mail->send();
        } catch (Exception $e) {
            $message = 'Verification email could not be sent. Please try again later.';
            $_SESSION['message'] = $message;
            header("Location: user_register.php"); // Redirect back to the registration page
            exit();
        }

        $_SESSION['message'] = $message;
        header("Location: user_register.php"); // Redirect back to the registration page
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ProTools - Create New Account</title>
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet" />
   <link rel="stylesheet" href="css/register-style.css">
   <style>
    .error{
      color: red;
      font-size: 12px;
    }
   </style>
  <script>
		function openPopup() {
      document.getElementById("overlay").style.display = "block";
      document.getElementById("popup").style.display = "block";
    }

    function closePopup() {
      document.getElementById("overlay").style.display = "none";
      document.getElementById("popup").style.display = "none";
    }
	</script>
     <style>
        /* CSS for full-page loader */
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
   <script>
        // JavaScript to fade out the loader and container after 5 seconds
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
</div>
<br><br>
<div class="container">
      <div class="form-container">
        <form action="" method="post" id="registerForm">
          <h3>Register</h3>
          <br><br>
          <!-- FIRST NAME FIELD -->
          <div class="input-field">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" required>
            <span id="fnameError" class="error"></span>
          </div>
          <!-- SURNAME FIELD -->
          <div class="input-field">
            <label for="sname">Surname</label>
            <input type="text" name="sname" id="sname" required>
            <span id="snameError" class="error"></span>
          </div>
          <!-- EMAIL FIELD -->
          <div class="input-field">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
            <span id="emailError" class="error"></span>
          </div>
          <!-- PASSWORD FIELD -->
          <div class="input-field">
            <label for="password">Password</label>
            <div class="password-toggle">
              <input type="password" name="password" id="password" required>
              <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this, 'password')"></i>
            </div>
            <span id="passwordError" class="error"></span>
          </div>
          <!-- CONFIRM PASSWORD FIELD -->
          <div class="input-field">
            <label for="confirm-password">Confirm Password</label>
            <div class="password-toggle">
              <input type="password" name="confirm-password" id="confirm-password" required>
              <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this, 'confirm-password')"></i>
            </div>
            <span id="confirmPasswordError" class="error"></span>
          </div>

          <!-- Checkbox for terms and conditions -->
          <div class="checkbox-container">
            <div class="checkbox">
              <input type="checkbox" name="terms" id="terms" required>
              <span class="checkmark"></span>
            </div>
            <label class="checkbox-label" for="terms">I agree to the <a href="#" onclick="showPopup()">Terms and Condition</a></label>
          </div>
          <!-- End checkbox for terms and conditions -->
                
          <!-- Background overlay -->
          <div class="overlay" id="overlay" onclick="closePopup()"></div>

          <!-- Pop-up window -->
          <div class="popup" id="popup">
            <div class="content">
            <button class="close" onclick="closePopup()"><i class="fas fa-times"></i></button>
              <h2>Terms and Conditions</h2>
              1. Payment: You should clearly state the accepted methods of payment, the currency accepted, and the procedure for payment. You may also want to include details about any fees or taxes that may apply. <br>
              <br>
              2. Shipping and delivery: It is important to provide information about shipping and delivery, such as shipping options, estimated delivery times, and shipping costs. You should also outline any restrictions on shipping to certain locations or on certain products. <br>
              <br>
              3. Returns and refunds: Your terms and conditions should include information about the return policy and how to request a refund. You may want to specify the conditions for accepting returns, such as the timeframe for returns and the condition of the product. <br>
              <br>
              4. Privacy policy: Your e-commerce shop website should have a clear and concise privacy policy that outlines how you collect, use, and protect personal information. You should also provide information on how customers can opt-out of receiving marketing emails or other communications. <br>
              <br>
              5. Intellectual property: You may want to include a section on intellectual property that outlines how your website content can be used, such as trademarks, copyrights, and patents. <br>
              <br>
              6. Limitation of liability: It is important to include a limitation of liability clause that limits your liability in the event of any damages or losses incurred by the customer. <br>
              <br>
              7. Governing law: You should specify which laws govern your e-commerce shop website, such as the laws of the country or state where the business is located. <br>
              <br>
              8. Modifications: You should include a clause that allows you to modify the terms and conditions at any time, and outline how customers will be notified of any changes. <br>
              <br>
              9. Dispute resolution: You may want to include a section on dispute resolution that outlines the process for resolving any disputes that may arise between you and your customers. <br>
              <br>
              10. Termination: Your terms and conditions should include a section that outlines the circumstances under which you may terminate the customer's account, such as for breach of the terms and conditions or fraudulent activity. <br><br>
              <br>
            </div>    
          </div>         
          <br>
          <!-- SUBMIT BUTTON -->
          <div class="input-field">
            <input type="submit" value="Register" id="submit" name="submit" disabled>
          </div>
          <div class="login-now">
            <span>Already a Member? </span> <a href="user_login.php">Login now!</a>
          </div>
        </form>
      </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="footer">
  <?php include 'components/footer.php'; ?>
</div>
<script src="js/script.js"></script>
<script>
  //getting the id for validation
const form = document.getElementById("registerForm");
const fnameInput = document.getElementById("fname");
const snameInput = document.getElementById("sname");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm-password");

//getting the id for error message
const fnameError = document.getElementById("fnameError");
const snameError = document.getElementById("snameError");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError");


fnameInput.addEventListener("input", validateFirstName);
snameInput.addEventListener("input", validateSurName);
emailInput.addEventListener("input", validateEmail);
passwordInput.addEventListener("input", validatePassword);
confirmPasswordInput.addEventListener("input", validateConfirmPassword);

function validateForm(){
  const fnameValid = validateFirstName();
  const snameValid = validateSurName();
  const emailValid = validateEmail();
  const passValid = validatePassword();
  const cpassValid = validateConfirmPassword();

  if (fnameValid && snameValid && emailValid && passValid && cpassValid){
    form.submit();
  }
}

function validateFirstName() {
  const firstNameValue = fnameInput.value.trim();
  const regex = /^[A-Za-z]+$/;

  if (firstNameValue === "") {
    fnameInput.classList.remove("error");
    fnameError.textContent = "";
    return true;
  } else if (!regex.test(firstNameValue)) {
    fnameInput.classList.add("error");
    fnameError.textContent = "First name must consist only of letters";
    return false;
  } else {
    fnameInput.classList.remove("error");
    fnameError.textContent = "";
    return true;
  }
}

function validateSurName() {
  const surnameValue = snameInput.value.trim();
  const regex = /^[A-Za-z]+$/;

  if (surnameValue === "") {
    snameInput.classList.remove("error");
    snameError.textContent = "";
    return true;
  } else if (!regex.test(surnameValue)) {
    snameInput.classList.add("error");
    snameError.textContent = "Surname must consist only of letters";
    return false;
  } else {
    snameInput.classList.remove("error");
    snameError.textContent = "";
    return true;
  }
}

function validateEmail() {
  const emailValue = emailInput.value.trim();
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (emailValue === "") {
    emailInput.classList.remove("error");
    emailError.textContent = "";
    return true;
  } else if (!regex.test(emailValue)) {
    emailInput.classList.add("error");
    emailError.textContent = "Invalid email format";
    return false;
  } else {
    emailInput.classList.remove("error");
    emailError.textContent = "";
    return true;
  }
}

function validatePassword() {
  const passwordValue = passwordInput.value;
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

  if (passwordValue === "") {
    passwordInput.classList.remove("error");
    passwordError.textContent = "";
    return true;
  } else if (!regex.test(passwordValue)) {
    passwordInput.classList.add("error");
    passwordError.textContent = "Password must be 8 characters long and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number";
    return false;
  } else {
    passwordInput.classList.remove("error");
    passwordError.textContent = "";
    return true;
  }
}

function validateConfirmPassword() {
  const confirmPasswordValue = confirmPasswordInput.value;
  const passwordValue = passwordInput.value;

  if (confirmPasswordValue === "") {
    confirmPasswordInput.classList.remove("error");
    confirmPasswordError.textContent = "";
    return true;
  } else if (confirmPasswordValue !== passwordValue) {
    confirmPasswordInput.classList.add("error");
    confirmPasswordError.textContent = "Passwords do not match";
    return false;
  } else {
    confirmPasswordInput.classList.remove("error");
    confirmPasswordError.textContent = "";
    return true;
  }
}
function togglePasswordVisibility(element, fieldId) {
  const field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
    element.classList.remove("fa-eye-slash");
    element.classList.add("fa-eye");
  } else {
    field.type = "password";
    element.classList.remove("fa-eye");
    element.classList.add("fa-eye-slash");
  }
}


    // Display terms and conditions popup
    function showPopup() {
      document.getElementById("overlay").style.display = "block";
      document.getElementById("popup").style.display = "block";
    }

    // Close terms and conditions popup
    function closePopup() {
      document.getElementById("overlay").style.display = "none";
      document.getElementById("popup").style.display = "none";
    }
    // Disable register button if terms checkbox is unchecked
    document.getElementById("terms").addEventListener("change", function() {
      if (this.checked) {
        document.getElementById("submit").disabled = false;
      } else {
        document.getElementById("submit").disabled = true;
      }
    });
</script>
</body>
</html>