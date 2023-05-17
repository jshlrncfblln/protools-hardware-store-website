<?php
include 'components/connect.php';

session_start();

if (isset($_GET['verification'])) {
    $verificationCode = $_GET['verification'];

    $select_user = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
    $select_user->execute([$verificationCode]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $update_user = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        $update_user->execute([$user['id']]);
        $message = 'Your email has been verified successfully!';
    } else {
        $message = 'Invalid verification code!';
    }
} else {
    $message = 'Invalid verification code!';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        .message {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <div class="message">
            <p><?php echo $message; ?></p>
        </div>
    </div>
</body>
</html>
