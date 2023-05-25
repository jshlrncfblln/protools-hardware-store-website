<?php
require_once "components/connect.php";
require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

// Check if the verification code is provided in the URL
if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];
    $verification_code = filter_var($verification_code, FILTER_SANITIZE_STRING);

    // Find the user with the provided verification code
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE verification_code = ?");
    $select_user->execute([$verification_code]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        // User found, update their status to verified
        $user_id = $row['id'];
        $update_user = $conn->prepare("UPDATE `users` SET email_verified = 1 WHERE id = ?");
        $update_user->execute([$user_id]);

        $message = 'Your email has been verified successfully! You can now login.';
        $_SESSION['message'] = $message;
        header("Refresh: 8; URL=user_login.php"); // Redirect to the login page
        exit();
    } else {
        $message = 'Invalid verification code.';
        $_SESSION['message'] = $message;
        header("Location: user_register.php"); // Redirect back to the registration page
        exit();
    }
} else {
    $message = 'Verification code not found.';
    $_SESSION['message'] = $message;
    header("Location: user_register.php"); // Redirect back to the registration page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifying Account</title>

    <!-- logo icon -->
    <link rel="shortcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">

    <!-- style for loading animation -->
    <style>
         html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

</head>
<body>
<!-- container for loader -->
<div class="loader-container" id="loader-container">
    <img src="images/Hourglass.gif" alt="Loading Animation" id="loader">
</div>

</body>
</html>