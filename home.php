<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="shorcut icon" type="x-icon" href="images/protools-logo.png" sizes="16x16 32x32 48x48">
   <title>ProTools - Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
        /* CSS for full-page loader */
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

</head>
<body>
   <div class="loader-container" id="loaderContainer">
      <img src="images/Hourglass.gif" alt="Loader" id="loader">
   </div>
<?php include 'components/user_header.php'; ?>
<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/power-drills.png" alt="">
         </div>
         <div class="content">
            <h3>Power Drills</h3>
            <a href="shop.php" class="btn">Shop Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/ultratech-cement.png" alt="">
         </div>
         <div class="content">
            <h3>Cement</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/color-paint.png" alt="">
         </div>
         <div class="content">
             <h3>Paints</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/good-lumber.png" alt="">
         </div>
         <div class="content">
            <h3>Lumbers</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/dewalt-screws.jpg" alt="">
         </div>
         <div class="content">
            <h3>Hand Tools</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>


   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=Hand Tools" class="swiper-slide slide">
      <img src="images/customer-support.png" alt="">
      <h3>Hand Tools</h3>
   </a>

   <a href="category.php?category=Power Tools" class="swiper-slide slide">
      <img src="images/drill.png" alt="">
      <h3>Power Tools</h3>
   </a>

   <a href="category.php?category=Paint" class="swiper-slide slide">
      <img src="images/varnish.png" alt="">
      <h3>Paint</h3>
   </a>

   <a href="category.php?category=Electrical" class="swiper-slide slide">
      <img src="images/wire.png" alt="">
      <h3>Electrical</h3>
   </a>

   <a href="category.php?category=Measurement Tools" class="swiper-slide slide">
      <img src="images/rule.png" alt="">
      <h3>Measuring Tools</h3>
   </a>

   <a href="category.php?category=Lumber" class="swiper-slide slide">
      <img src="images/wood.png" alt="">
      <h3>Lumber</h3>
   </a>

   <a href="category.php?category=Bolt" class="swiper-slide slide">
      <img src="images/bolts.png" alt="">
      <h3>Bolt and Nuts</h3>
   </a>

   <a href="category.php?category=Brick" class="swiper-slide slide">
      <img src="images/brick.png" alt="">
      <h3>Building Bricks</h3>
   </a>

   <a href="category.php?category=Cement" class="swiper-slide slide">
      <img src="images/cement.png" alt="">
      <h3>Cement</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>₱</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>
<!-- for loader -->
<script>
        // JavaScript to fade out the loader and container after 5 seconds
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

</body>
</html>