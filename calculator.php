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
<br><br><br><br><br><br><br><br><br>
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
<div class="result-container" style="display: none; ">
  <div class="result-form-container">
    <form action="">
      <div class="itemListResult" id="itemList"></div>
    </form>
  </div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
      { name: "Hammer", price: 200, amount: ["1 unit"] },
      { name: "Screwdriver", price: 150, amount: ["1 unit"] },
      { name: "Power Drill", price: 3500, amount: ["1 unit"] },
      { name: "Level", price: 350, amount: ["1 unit"] },
      { name: "Measuring Tape", price: 200, amount: ["1 unit"] },
      { name: "Utility Knife", price: 150, amount: ["1 unit"] },
      { name: "Handsaw", price: 400, amount: ["1 unit"] },
      { name: "Tile Cutter", price: 1500, amount: ["1 unit"] },
      { name: "Caulk Gun", price: 150, amount: ["1 unit"] },
      { name: "Power Sander", price: 2500, amount: ["1 unit"] },
      { name: "Plumber's Wrench", price: 500, amount: ["1 unit"] },
      { name: "Pliers", price: 200, amount: ["1 unit"] },
      { name: "Wire Cutter", price: 200, amount: ["1 unit"] },
      { name: "Safety Glasses", price: 150, amount: ["1 unit"] },
      { name: "Dust Mask", price: 50, amount: ["1 unit"] },
      { name: "Stepladder", price: 1500, amount: ["1 unit"] },
      { name: "Drywall or Cement Board", price: 3000, amount: ["2 - 3 sheets"] },
      { name: "Spackling Paste", price: 500, amount: ["1 - 2 kilograms"] },
      { name: "Sand Paper", price: 100, amount: ["2 - 3 sheets"] },
      { name: "Paint and Primer", price: 4000, amount: ["1 - 2 gallons"] },
      { name: "Wall Tiles or Backsplash Tiles", price: 10000, amount: ["100 - 200 pcs"] },
      { name: "Grout", price: 1000, amount: ["1 - 2 bags"] },
      { name: "Adhesive or Thin-set Mortar", price: 5000, amount: ["2 - 3 bags"] },
      { name: "Shower Pan or Tub", price: 10000, amount: ["1 unit"] },
      { name: "Faucet and Showerhead", price: 5000, amount: ["1 unit each"] },
      { name: "Toilet", price: 7000, amount: ["1 unit"] },
      { name: "Sink and Vanity", price: 10000, amount: ["1 unit each"] },
      { name: "Mirror", price: 2000, amount: ["1 unit"] },
      { name: "Lighting Fixtures", price: 5000, amount: ["2 - 3 units"] },
      { name: "Caulk or Sealant", price: 200, amount: ["1 - 2 tubes"] },
      { name: "Towel Bars and Toilet Paper Holder", price: 2000, amount: ["2 - 3 units"] },
      { name: "Flooring Materials ", price: 15000, amount: ["100 - 200 pieces"] },
      { name: "Baseboard or Trim", price: 3000, amount: ["10 - 15 pieces"] },
      { name: "Plumbing Pipes and Fittings ", price: 10000, amount: ["2/4 inch"] },
      { name: "Electrical Wiring and Outlets", price: 10000, amount: ["14 guages wire and 2-4 outlets"] },

      // Add more items with prices and amounts
    ],
    "Kitchen Renovation": [
      { name: "Countertops", price: 12000, amount: ["10 square meters"] },
      { name: "Kitchen Cabinet Set (upper and lower cabinets)", price: 100000, amount: [" "] },
      { name: "Stainlees Steel Sink", price: 5000, amount: ["1 unit"] },
      { name: "Single-handle kitchen faucet", price: 5000, amount: ["1 unit"] },
      { name: "Ceramic Tiles", price: 1000, amount: ["20 square meters"] },
      { name: "Ceiling-mounted LED lights", price: 1000, amount: ["4 units"] },
      { name: "Interior wall paint", price: 2500, amount: ["2 gallons"] },
      { name: "Glass or mosaic tile backsplash", price: 3000, amount: ["5 square meters"] },
      { name: "Screws, nails, and other fasteners", price: 500, amount: ["1 box each"] },
      { name: "Adhesives and sealants ", price: 500, amount: ["2 - 3 tubes"] },
      // Add more items with prices and amounts
    ],
    "Room Addition": [
      { name: "Lumber", price: 20000, amount: ["100 - 200 pcs"] },
      { name: "Drywall", price: 10000, amount: ["30 - 40 sheets"] },
      { name: "Insulation", price: 5000, amount: ["10 - 20 rolls"] },
      { name: "Paint", price: 5000, amount: ["10 - 15 gallons"] },
      { name: "Nails, screws, and other fasteners", price: 1000, amount: ["1000 pcs each"] },
      { name: "Tools (e.g., hammers, screwdrivers, levels, etc.)", price: 1000, amount: ["1 unit each"] },
      // Add more items with prices and amounts
    ],
    // Add more subcategories with items
    "Hotel": [
      { name: "Concrete", price: 20000, amount: ["20000 cubic meters"] },
      { name: "Steel", price: 500000, amount: ["200 metric tons"] },
      { name: "Glass", price: 200000, amount: ["15 square meters (per room)"] },
      { name: "Furniture", price: 2000000, amount: ["15 pcs each (per room)"] },
      { name: "Plumbing fittings and pipes", price: 500000, amount: ["15 square meters (per room)"] },
      { name: "Electrical wiring and outlets", price: 36000, amount: ["15 square meters (per room)"] }
      // Add more items with prices and amounts
    ],
    "Office Building": [
      { name: "Brick", price: 100, amount: ["20000 pieces"] },
      { name: "Aluminum", price: 1000, amount: ["5000 square meters"] },
      { name: "Glass", price: 2000, amount: ["4000 square meters"] },
      { name: "Wood", price: 2000, amount: ["1000 cubic meters"] },
      // Add more items with prices and amounts
    ],
    "Retail Space": [
      { name: "Flooring", price: 1000, amount: ["20000 pcs"] },
      { name: "Lighting", price: 5000, amount: ["5 units each"] },
      { name: "Shelving", price: 20000, amount: ["10 units"] },
      // Add more items with prices and amounts
    ],
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

        let amountCount = 0;
        if (Array.isArray(item.amount)) {
          // Calculate the total count from the array elements
          item.amount.forEach((amount) => {
            const amountNumbers = amount.match(/\d+/g);
            const count = amountNumbers ? amountNumbers.reduce((a, b) => Number(a) + Number(b)) : 0;
            amountCount += count;
          });
        }

        listItem.textContent = `${item.name} - Amount: ${item.amount.join(" - ")}, Price: ₱${item.price.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        itemList.appendChild(listItem);

        totalPrice += item.price * amountCount;
      });

      itemListDiv.appendChild(itemList);

      const totalPriceElement = document.createElement("p");
      totalPriceElement.textContent = `Total Estimated Cost for Your Project: ₱${totalPrice.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
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