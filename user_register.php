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

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Email already exists! Please Try Again!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm Password not matched! Please Try Again!';
        } else {
            $verification_code = sha1(rand()); // Generate verification code

            $insert_user = $conn->prepare("INSERT INTO `users`(fname, sname, email, password, verification_code) VALUES(?,?,?,?,?)");
            $insert_user->execute([$fname, $sname, $email, $cpass, $verification_code]);
            $message[] = 'You Successfully Registered, Login Now!';

            // Send verification email
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debugging
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'joshua.laurence.fabi@gmail.com';
                $mail->Password = 'ziwbzzqguwnueqis';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('joshua.laurence.fabi@gmail.com');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $verification_link = 'http://localhost/protools-hardware-store-website/verify.php?code=' . $verification_code;
                $mail->Body = "Please click the following link to verify your email address: <a href=\"$verification_link\">$verification_link</a>";

                $mail->send();
                $message[] = 'Verification link has been sent to your email. Please check your inbox.';
            } catch (Exception $e) {
                $message[] = 'Verification email could not be sent. Please try again later.';
            }
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
   <title>ProTools - Create New Account</title>
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet" />
   <link rel="stylesheet" href="css/register-style.css">
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
</head>
<body>
<div class="user-header">
  <?php include 'components/user_header.php'; ?>
</div>
<br>
<br>
<br>
<br>
<div class="container">
      <div class="form-container">
        <form action="" method="post">
          <h3>Register</h3>
          <br>
          <br>
          <!-- FIRST NAME FIELD -->
          <div class="input-field">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" value="<?php if (isset($_POST['submit'])) { echo $fname; } ?>" required>
          </div>
          <!-- SURNAME FIELD -->
          <div class="input-field">
            <label for="sname">Surname</label>
            <input type="text" name="sname" id="sname" required>
          </div>
          <!-- EMAIL FIELD -->
          <div class="input-field">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
          </div>
          <!-- PASSWORD FIELD -->
          <div class="input-field">
            <label for="password">Password</label>
            <div class="password-toggle">
              <input type="password" name="password" id="password" required>
              <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
          </div>
          <!-- CONFIRM PASSWORD FIELD -->
          <div class="input-field">
            <label for="confirm-password">Confirm Password</label>
            <div class="password-toggle">
              <input type="password" name="confirm-password" id="confirm-password" required>
              <i class="far fa-eye-slash toggle-password" aria-hidden="true" onclick="togglePasswordVisibility(this)"></i>
            </div>
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
          <div class="captcha-container">
                <div class="captcha-wrapper">
                  <canvas id="canvas" width="300" height="100"></canvas>
                  <button id="reload-button">
                    <i class="fa-solid fa-arrow-rotate-right"></i>
                  </button>
                </div>
                <input type="text" id="user-input" placeholder="Enter the text in the image" />
                <button id="next">Submit</button>
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