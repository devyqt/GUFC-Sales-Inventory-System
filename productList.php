<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PRODUCT LIST</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/modal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/productList.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
</head>
<body>

<?php include 'navbar.html'; ?>

<div class="admin-profile">
    <div class="profile-info">
        <div class="profile-name">Solane Admin</div>
        <div class="profile-role">Admin Profile</div>
    </div>
    <div class="profile-image">
        <img src="images/admin logo.png" alt="Profile Avatar"> <!-- Replace with the path to your image -->
    </div>
    <div class="settings-icon">
        <img src="images/settings.png" alt="Settings" onclick="toggleDropdown()"> <!-- Replace with the path to your settings icon -->
        
        <!-- Settings Dropdown -->
        <div class="settings-dropdown" id="settingsDropdown">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

<div class="container">
  <section class="mainList">
      <div class="tab-content">

      <h2>PRODUCT LIST</h2>
        <!-- Cylinders Tab -->
        <div id="cylinders" class="tab-pane active">
          <div class="productList-container">
            <div class="box">
              <img src="images/14kg.jpg" alt="Product 1" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 1.4kg Auto-Shutoff Cylinder</p>
              <p><strong>Description:</strong> Safer butane alternative.</p>
            </div>
            <div class="box">
              <img src="images/11kg.jpg" alt="Product 2" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 11kg Solane Sakto</p>
              <p><strong>Description:</strong> Ideal cooking fuel.</p>
            </div>
            <div class="box">
              <img src="images/11 KG POL.jpg" alt="Product 3" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 11KG Cylinder</p>
              <p><strong>Description:</strong> Ideal cooking fuel.</p>
            </div>
            <div class="box">
              <img src="images/22kg.jpg" alt="Product 4" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 22kg POL Cylinder</p>
              <p><strong>Description:</strong> Versatile commercial applications.</p>
            </div>
            <div class="box">
              <img src="images/50kg.jpg" alt="Product 5" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 50KG Cylinder</p>
              <p><strong>Description:</strong> Extensive industrial uses.</p>
            </div>
            <div class="box">
              <img src="images/DE ROSKAS.jpg" alt="Product 6" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> POL Regulator (De Roskas)</p>
              <p><strong>Description:</strong> Secure gas connection.</p>
            </div>
            <div class="box">
              <img src="images/DE SALPAK.jpg" alt="Product 7" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> POL Regulator (De Salpak)</p>
              <p><strong>Description:</strong> Reliable safety mechanism.</p>
              </div>
            <div class="box">
              <img src="images/HOSE WITH CLAMPS.jpg" alt="Product 8" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> Hose with Clamps</p>
              <p><strong>Description:</strong> Durable gas transfer.</p>
          </div>
      <div class="tab-content">
      <h2>OTHER PRODUCT</h2>
        <!-- Cylinders Tab -->
        <div id="cylinders" class="tab-pane active">
          <div class="productList-container">
            <div class="box">
              <img src="images/butane gas.jpg" alt="Product 1" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> Butane Gas</p>
              <p><strong>Description:</strong> Portable fuel solution.</p>
            </div>
            <div class="box">
              <img src="images/GAS STOVE.jpg" alt="Product 2" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> Gas Stove</p>
              <p><strong>Description:</strong> Efficient cooking appliance.</p>
            </div>
            <div class="box">
              <img src="images/MOTOLITE.jpg" alt="Product 3" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> Motolite</p>
              <p><strong>Description:</strong> Reliable power source.</p>
          </div>
    <script src="js/settings.js"></script>
</section>
</body>
</html>