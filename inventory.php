<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>INVENTORY</title>
  <link rel="stylesheet" href="CSS/style.css">
  <link rel="stylesheet" href="CSS/inventory.css">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
  <?php include 'navbar.html'; ?>

  <section class="main">
    <div class="tab-container">
      <!-- Category Tabs -->
      <ul class="tabs">
        <li><a href="#cylinders" class="tab-link active">Cylinders</a></li>
        <li><a href="#other-products" class="tab-link">Other Products</a></li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Cylinders Tab -->
        <div id="cylinders" class="tab-pane active">
          <div class="product-container">
            <div class="box">
              <img src="images/11kg.jpg" alt="Product 1" class="product-img" style="width: 150px; height: 150px; object-fit: cover;">
              <p><strong>Product Name:</strong> Cylinder A</p>
              <p><strong>Description:</strong> High quality cylinder.</p>
            </div>
            <div class="box">
              <img src="images/14kg.jpg" alt="Product 2" class="product-img" style="width: 150px; height: 150px; object-fit: cover;">
              <p><strong>Product Name:</strong> Cylinder B</p>
              <p><strong>Description:</strong> Durable and reliable.</p>
            </div>
            <div class="box">
              <img src="images/22kg.jpg" alt="Product 3" class="product-img" style="width: 150px; height: 150px; object-fit: cover;">
              <p><strong>Product Name:</strong> Cylinder A</p>
              <p><strong>Description:</strong> High quality cylinder.</p>
            </div>
            <div class="box">
              <img src="images/50kg.jpg" alt="Product 4" class="product-img" style="width: 150px; height: 150px; object-fit: cover;">
              <p><strong>Product Name:</strong> Cylinder B</p>
              <p><strong>Description:</strong> Durable and reliable.</p>
            </div>
          </div>
          <!-- Add Product Button for Cylinders -->
          <button class="add-product-button" onclick="openModal()">Add Cylinder</button>
        </div>

        <!-- Other Products Tab -->
        <div id="other-products" class="tab-pane">
          <div class="product-container">
            <div class="box">
              <img src="images/product3.jpg" alt="Product 3" class="product-img">
              <p><strong>Product Name:</strong> Accessory A</p>
              <p><strong>Description:</strong> Essential accessory.</p>
            </div>
            <div class="box">
              <img src="images/product4.jpg" alt="Product 4" class="product-img">
              <p><strong>Product Name:</strong> Accessory B</p>
              <p><strong>Description:</strong> High-quality material.</p>
            </div>
          </div>
          <!-- Add Product Button for Other Products -->
          <button class="add-product-button" onclick="openModal()">Add Accessory</button>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal HTML -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close-button" onclick="closeModal()">&times;</span>
    <h3>Add Product</h3>
    <form id="addProductForm">
      <div class="form-group">
        <label for="productID">Product ID:</label> 
        <input type="text" id="productID" name="productID" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="productDate">Date:</label>
        <input type="date" id="productDate" name="productDate" class="form-control" required>
      </div>
      <input type="submit" value="Submit" class="btn-submit">
    </form>
  </div>
</div>

<script src="js/inventory.js"></script>
</body>
</html>
