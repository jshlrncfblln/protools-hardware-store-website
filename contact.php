<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!';

   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ProTools - Contact Us</title>
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

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
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post">
      <h3>Let's hear what you want to say.</h3>
      <input type="text" name="name" placeholder="Type in your Full Name" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="Type in your Email Address" required maxlength="50" class="box">
      <input type="text" name="number" min="0" max="9999999999" placeholder="Type in your Contact Number" required onkeypress="if(this.value.length == 10) return false;" class="box">
      <textarea name="msg" class="box" placeholder="What's your Message?" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>