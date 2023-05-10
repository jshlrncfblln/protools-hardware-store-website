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
<br><br>
<div class="container">
      <div class="form-container">
        <form action="" method="post">
          <h3>Project Calculator</h3>
          <p>Let's calculate your project and see how much it cost it.</p>
          <!-- main project category -->
          <div class="form-group">
          <label for="projectCategory">Project Category</label>
            <select id="projectCategory" class="form-control">
              <option value="">Select Project Category</option>
              <option value="house">House Project</option>
              <option value="building">Building Project</option>
            </select>
          </div>
          <!-- Sub project category -->
          <div class="form-group">
          <label for="subCategory">Project Type</label>
            <select id="subCategory" class="form-control">
              <option value="" select="selected">Select Project Type</option>
            </select>
          </div>
          <!-- Calculate Button -->
          <button type="submit" name="calculate" id="calculate">CALCULATE</button>
        </form>
      </div>
</div>
<br>
<div class="result-container">
  <div class="result-form-container">
    <form action="">
      <div class="itemListResult" id="itemList"></div>
    </form>
  </div>
</div>
<br>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<script>
  const projectTypeSelect = document.getElementById("projectCategory");
  const subCategorySelect = document.getElementById("subCategory");
  const itemListDiv = document.getElementById("itemList");
  const calculateBtn = document.getElementById("calculate");
  const resultContainer = document.querySelector(".result-container");

  // Event listener for when the calculate button is clicked
  calculateBtn.addEventListener("click", (event) => {
    event.preventDefault();

    // Show the result container
    resultContainer.style.display = "block";
  });

  // Define the sub categories for each project type
  const subCategories = {
    house: ["Bathroom Renovation", "Kitchen Renovation", "Room Addition"],
    building: ["Hotel", "Office Building", "Retail Space"]
  };

  // Define the list of items for each sub category
  const subCategoryItems = {
    "Bathroom Renovation": ["Tiles", "Sand", "Gravel", "Cement"],
    "Kitchen Renovation": ["Countertops", "Cabinets", "Sink", "Faucet"],
    "Room Addition": ["Lumber", "Drywall", "Insulation", "Paint"],
    "Hotel": ["Concrete", "Steel", "Glass", "Furniture"],
    "Office Building": ["Brick", "Aluminum", "Wood", "Computers"],
    "Retail Space": ["Flooring", "Lighting", "Shelving", "Merchandise"]
  };

  // Event listener for when a project type is selected
  projectTypeSelect.addEventListener("change", (event) => {
    // Clear the sub category select options
    subCategorySelect.innerHTML = "";

    // Get the selected project type value
    const projectCategory = event.target.value;

    // Create a new option for each sub category
    if (projectCategory) {
      subCategories[projectCategory].forEach((subCategory) => {
        const option = document.createElement("option");
        option.value = subCategory;
        option.text = subCategory;
        subCategorySelect.add(option);
      });

      // Show the sub category select
      subCategorySelect.style.display = "block";
    } else {
      // Hide the sub category select if no project type is selected
      subCategorySelect.style.display = "none";
    }
  });

  // Event listener for when the calculate button is clicked
  document.getElementById("calculateBtn").addEventListener("click", (event) => {
    event.preventDefault();

    const selectedSubCategory = subCategorySelect.value;

    if (selectedSubCategory) {
      const itemsList = subCategoryItems[selectedSubCategory];

      // Clear the previous items list
      itemListDiv.innerHTML = "";

      if (itemsList && itemsList.length > 0) {
        const heading = document.createElement("h2");
        heading.textContent = "Items for " + selectedSubCategory;
        itemListDiv.appendChild(heading);

        const itemList = document.createElement("ul");
        itemsList.forEach((item) => {
          const listItem = document.createElement("li");
          listItem.textContent = item;
          itemList.appendChild(listItem);
        });

        itemListDiv.appendChild(itemList);
      }

      // Show the items list
      itemListDiv.style.display = "block";
    } else {
      // Hide the items list if no sub category is selected
      itemListDiv.style.display = "none";
    }
  });
</script>
</body>
</html>