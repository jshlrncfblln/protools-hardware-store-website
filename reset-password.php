<?php
$msg = "";

include 'components/connect.php';

if (isset($_GET['reset'])) {
    $resetCode = $_GET['reset'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
    $stmt->execute([$resetCode]);

    if ($stmt->rowCount() > 0) {
        if (isset($_POST['submit'])) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];

            if ($password === $confirm_password) {
                // Update the password using SHA1 hashing algorithm
                $hashedPassword = sha1($password);
                $stmt = $conn->prepare("UPDATE users SET password = ?, verification_code = '' WHERE verification_code = ?");
                $stmt->execute([$hashedPassword, $resetCode]);

                if ($stmt) {
                    $msg = "<div class='alert alert-success'>Password updated successfully. You will be redirected to the login page shortly.</div>";
                    header("refresh:3;url=user_login.php"); // Redirect to user_login.php after 5 seconds
                    exit();
                } else {
                    $msg = "<div class='alert alert-danger'>Failed to update the password.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Reset Link does not match.</div>";
    }
} else {
    header("Location: forgot_pass.php");
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">

    <!-- Add your CSS styling here -->
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

        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
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
                <h3>Reset Password</h3>
                <?php echo $msg; ?>
                <div class="input-field">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-field">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" name="confirm-password" id="confirm-password" required>
                </div>
                <div class="input-field">
                    <input type="submit" value="Reset Password" name="submit" id="submit" class="btn">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
