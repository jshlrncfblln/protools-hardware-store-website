<?php
require_once "components/connect.php";
require 'vendor/autoload.php';

session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
   header("location: user_login.php");
   exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get user data from database
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_data = $select_user->fetch(PDO::FETCH_ASSOC);

// Check if user has already verified their email
if ($user_data['email_verified'] == 1) {
   header("location: home.php");
   exit();
}

// Check if verification code exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $verification_code = $_GET['code'];

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND verification_code = ?");
    $select_user->execute([$user_id, $verification_code]);
    $user_data = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        // Update user record in database
        $update_user = $conn->prepare("UPDATE `users` SET email_verified = 1, verification_code = NULL WHERE id = ?");
        $update_user->execute([$user_id]);

        // Show verifying animation
        echo '<html>
                <head>
                    <title>Email Verification</title>
                    <style>
                        .loader {
                            border: 16px solid #f3f3f3;
                            border-top: 16px solid #3498db;
                            border-radius: 50%;
                            width: 120px;
                            height: 120px;
                            animation: spin 2s linear infinite;
                            margin: 100px auto;
                        }

                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                </head>
                <body>
                    <h1>Email Verification</h1>
                    <div class="loader"></div>
                    <p>Please wait while we verify your email...</p>
                </body>
            </html>';

        // Redirect to dashboard page after a short delay
        echo '<script>
                setTimeout(function() {
                    window.location.href = "home.php";
                }, 3000); // Delay in milliseconds (3 seconds)
            </script>';

        exit();
    }
}

// Display error message if verification code is invalid or missing
$message = "Invalid verification code. Please check your email and try again.";
?>

<html>
<head>
   <title>Email Verification</title>
</head>
<body>
   <h1>Email Verification</h1>
   <p><?php echo $message; ?></p>
</body>
</html>
