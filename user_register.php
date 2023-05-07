<?php

require_once 'components/connect.php'; // includes the database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // starts the session

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $fname = $_POST['fname'];
   $fname = filter_var($fname, FILTER_SANITIZE_STRING);

   $sname = $_POST['sname'];
   $sname = filter_var($sname, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);

   $cpassword = sha1($_POST['confirm-password']);
   $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($password != $cpassword){
         $message[] = 'confirm password not matched!';
      }else{
         $verification_code = md5(rand()); // generates a random verification code
         $insert_user = $conn->prepare("INSERT INTO `users`(fname, sname, email, password, verification_code) VALUES(?,?,?,?,?)");
         $insert_user->execute([$fname, $sname, $email, $cpassword, $verification_code]); // inserts the user data into the database
         
         // sends the verification email to the user
         $mail = new PHPMailer(true);
         try {
            //Server settings
            $mail->SMTPDebug = 0;                      
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                    
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'your-email@gmail.com';                    
            $mail->Password   = 'your-email-password';                              
            $mail->SMTPSecure = 'tls';         
            $mail->Port       = 587;                                    
         
            //Recipients
            $mail->setFrom('your-email@gmail.com', 'Your Name');
            $mail->addAddress($email, $fname.' '.$sname);     
         
            // Set email content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Email Verification';

            // Email styles
            $styles = "
              /* Main styles */
              body {
                  font-family: Arial, sans-serif;
                  color: #333333;
              }
              h1 {
                  font-size: 24px;
                  font-weight: bold;
                  color: #333333;
                  margin-top: 0;
                  margin-bottom: 20px;
              }
              p {
                  font-size: 16px;
                  line-height: 1.5;
                  margin-top: 0;
                  margin-bottom: 20px;
              }
              a {
                  color: #ffffff;
                  background-color: #1a73e8;
                  border-radius: 4px;
                  display: inline-block;
                  font-size: 16px;
                  font-weight: bold;
                  text-align: center;
                  text-decoration: none;
                  padding: 12px 24px;
              }
              a:hover {
                  background-color: #0d47a1;
              }
              /* Responsive styles */
              @media (max-width: 600px) {
                  h1 {
                    font-size: 20px;
                  }
                  p {
                    font-size: 14px;
                  }
                  a {
                    font-size: 14px;
                    padding: 8px 16px;
                  }
              }
            ";

            // Email body
            $body = '
              <html>
                  <head>
                    <title>Email Verification</title>
                  </head>
                  <body>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="center">
                              <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="background-color: #ffffff; padding: 40px;">
                                      <h1>Email Verification</h1>
                                      <p>Please click the link below to verify your email address:</p>
                                      <p><a href="http://your-website.com/verify.php?email='.$email.'&code='.$verification_code.'">Verify Email Address</a></p>
                                    </td>
                                </tr>
                              </table>
                          </td>
                        </tr>
                    </table>
                    <style>'.$styles.'</style>
                  </body>
              </html>
            ';

            $mail->Body = $body;
         
            $mail->send();
            $message[] = 'A verification email has been sent to your email address. Please click the link in the email to verify your email address and complete your registration.';
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
<?php $message; ?>
<div class="container">
      <div class="form-container">
        <form action="" method="post">
          <h3>Register</h3>
          <br>
          <br>
          <!-- FIRST NAME FIELD -->
          <div class="input-field">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" required>
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
            <input type="submit" value="Register" id="register-btn"disabled>
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
<script src="js/script_captcha.js"></script>
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

    // Generate captcha
    function generateCaptcha() {
      var canvas = document.getElementById("canvas");
      var ctx = canvas.getContext("2d");
      var characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      var captchaLength = 6;
      var captchaText = "";
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      for (var i = 0; i < captchaLength; i++) {
        var char = characters[Math.floor(Math.random() * characters.length)];
        captchaText += char;
        ctx.font = "50px Arial";
        ctx.fillStyle = "#000000";
        ctx.fillText(char, i * 50 + 20, 70);
      }
      return captchaText;
    }

    // Reload captcha
    document.getElementById("reload-button").addEventListener("click", function() {
      document.getElementById("user-input").value = "";
      var captchaText = generateCaptcha();
    });

    // Validate captcha
    document.getElementById("next").addEventListener("click", function() {
      var userInput = document.getElementById("user-input").value;
      var captchaText = document.getElementById("canvas").textContent;
      if (userInput === captchaText) {
        document.getElementById("terms").checked = true;
        document.getElementById("terms").disabled = true;
        document.getElementById("register-btn").disabled = false;
      } else {
        alert("Captcha incorrect. Please try again.");
      }
    });

    // Disable register button if terms checkbox is unchecked
    document.getElementById("terms").addEventListener("change", function() {
      if (this.checked) {
        document.getElementById("register-btn").disabled = false;
      } else {
        document.getElementById("register-btn").disabled = true;
      }
    });
</script>
</body>
</html>