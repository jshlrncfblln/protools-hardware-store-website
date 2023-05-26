<?php

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
$msg = "";

include "components/connect.php"; // Update this line with the correct path to your connect.php file

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $code = md5(rand());

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        $stmt->execute([$code, $email]);

        if ($stmt) {
            echo "<div style='display: none;'>";
            $mail = new PHPMailer();
            
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'joshua.laurence.fabi@gmail.com';
                $mail->Password   = 'mhvudscuslqjtfts';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('ProTools - ByteCode');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'no reply';
                $mail->Body    = 'Here is the verification link <b><a href="http://localhost/protools-hardware-store-website/reset-password.php?reset='.$code.'">http://localhost/protools-hardware-store-website/reset-password.php?reset='.$code.'</a></b>';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";
            $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>$email - This email address was not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password?</title>
    <link rel="shortcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 100%;
        }

        .form-container h3 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333333;
        }

        .input-field {
            margin-bottom: 20px;
        }

        .input-field label {
            font-size: 14px;
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }

        .input-field input[type="email"],
        .input-field input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333333;
        }

        .input-field input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #009688;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .input-field input[type="submit"]:hover {
            background-color: #00796b;
        }

        .social-icons {
            text-align: center;
            margin-top: 10px;
        }

        .social-icons a {
            color: #009688;
            text-decoration: none;
        }

        .social-icons a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center; /* Center the message */
        }

        .alert.alert-success {
            color: green;
        }

        .alert.alert-danger {
            color: red;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <h3>Forgot Password</h3>
                <?php echo $msg; ?>
                <div class="input-field">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter Your Email" required>
                </div>
                <div class="input-field">
                    <input type="submit" value="Send Reset Link" name="submit" class="btn">
                </div>
                <div class="social-icons">
                    <p>Back to! <a href="user_login.php">Login</a>.</p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
