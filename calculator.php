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
<div class="result-container" style="display: none; ">
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
    "Bathroom Renovation": [
      { name: "Hammer", price: 200, amount: 1 },
      { name: "Screwdriver", price: 150, amount: 2 },
      { name: "Power Drill", price: 3500, amount: 1 },
      { name: "Level", price: 350, amount: 1 },
      { name: "Measuring Tape", price: 200, amount: 1 },
      { name: "Utility Knife", price: 150, amount: 1 },
      { name: "Handsaw", price: 400, amount: 1 },
      { name: "Tile Cutter", price: 1500, amount: 1 },
      { name: "Caulk Gun", price: 150, amount: 1 },
      { name: "Power Sander", price: 2500, amount: 1 },
      { name: "Plumber's Wrench", price: 500, amount: 1 },
      { name: "Pliers", price: 200, amount: 1 },
      { name: "Wire Cutter", price: 200, amount: 1 },
      { name: "Safety Glasses", price: 150, amount: 1 },
      { name: "Dust Mask", price: 50, amount: 1 },
      { name: "Stepladder", price: 1500, amount: 1 },
      { name: "Drywall or Cement Board", price: 3000, amount: 2-3 },
      { name: "Spackling Paste", price: 500, amount: 1-2 },
      { name: "Sand Paper", price: 100, amount: 2-3 },
      { name: "Paint and Primer", price: 4000, amount: 1-2 },
      // Add more items with prices and amounts
    ],
    "Kitchen Renovation": [
      { name: "Countertops", price: 199.99, amount: 1 },
      { name: "Cabinets", price: 299.99, amount: 10 },
      // Add more items with prices and amounts
    ],
    "Room Addition": [
      { name: "Lumber", price: 49.99, amount: 20 },
      { name: "Drywall", price: 12.99, amount: 30 },
      // Add more items with prices and amounts
    ],
    // Add more subcategories with items
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
  calculateBtn.addEventListener("click", (event) => {
    event.preventDefault();

    const selectedSubCategory = subCategorySelect.value;

    if (selectedSubCategory) {
      const itemsList = subCategoryItems[selectedSubCategory];

      // Clear the previous items list
      const itemListDiv = document.getElementById("itemList");
      itemListDiv.innerHTML = "";

      if (itemsList && itemsList.length > 0) {
        const heading = document.createElement("h2");
        heading.textContent = "Materials and Tools to Finish " + selectedSubCategory;
        itemListDiv.appendChild(heading);

        const itemList = document.createElement("ul");
        let totalPrice = 0;

        itemsList.forEach((item) => {
          const listItem = document.createElement("li");
          listItem.textContent = `${item.name} - Amount: ${item.amount}, Price: ₱${item.price.toFixed(2)}`;
          itemList.appendChild(listItem);

          totalPrice += item.price * item.amount;
        });

        itemListDiv.appendChild(itemList);

        const totalPriceElement = document.createElement("p");
        totalPriceElement.textContent = `Total Estimated Cost: ₱${totalPrice.toFixed(2)}`;
        itemListDiv.appendChild(totalPriceElement);
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