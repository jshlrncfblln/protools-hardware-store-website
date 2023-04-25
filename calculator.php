<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ProTools - Calculate your Project</title>
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <link rel="stylesheet" href="css/calcu-style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="container">
      <div class="form-container">
        <form action="" method="post">
          <h3>Project Calculator</h3>

          <div class="input-field">
          <label for="category">Project Category</label>
            <select name="project-category" id="category">
              <option value="">Select Project Category</option>
              <option value="house">House Project</option>
              <option value="building">Building Project</option>
            </select>
          </div>

          <div class="input-field">
          <label for="type">Project Type</label>
            <select name="project-type" id="type">
              <option value="">Select Project Type</option>
              <option value="bath">Bathroom</option>
              <option value="pool">Swimming Pool</option>
              <option value="kitchen">Kitchen</option>
              <option value="room">Room</option>
            </select>
          </div>
          <button type="submit" name="submit" id="submit">Calculate</button>
        </form>
      </div>
</div>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>