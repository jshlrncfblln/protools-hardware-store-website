<?php
session_start();

include 'components/connect.php';

$msg = '';

if (isset($_POST['submit'])) {
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM users WHERE email='{$email}' AND otp='{$otp}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // OTP verification successful
        $sql = "UPDATE users SET verified=1 WHERE email='{$email}'";
        mysqli_query($conn, $sql);

        $msg = "<div class='alert alert-success'>OTP verification successful. Your email address has been verified.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Invalid OTP code. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-wrapper {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        p{
            text-align: center;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-info {
            background-color: #f0faff;
            color: #007bff;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 16px;
            cursor: pointer;
            align-items: center;
        }

        input[type="submit"]:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>Email Verification</h1>
            <p>Enter the OTP code that sent to your email.</p>
            <?php echo $msg; ?>
            <form method="post">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <input type="submit" name="submit" value="Verify OTP">
            </form>
        </div>
    </div>
</body>
</html>
