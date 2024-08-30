<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>INVENTORY</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/modal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/inventory.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
</head>
<body>

<?php include 'navbar.html'; ?>

<div class="container">
  <section class="main">
    <div class="tab-container">
      <!-- Category Tabs -->
      <ul class="tabs">
        <li><a href="#cylinders" class="tab-link active">Cylinders</a></li>
        <li><a href="#other-products" class="tab-link">Other Products</a></li>
      </ul>

<!-- Tab Content -->
<div class="tab-content">
        <!-- Products Tab -->
        <div id="cylinders" class="tab-pane active">
          <div class="inventory-container">
          <div class="Invbox">
        <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
        <p><strong>Quantity:</strong> <span id="first-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> 11kg POL Cylinder</p>
        <p><strong>Quantity:</strong> <span id="second-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> 1.4kg Solane Sakto</p>
        <p><strong>Quantity:</strong> <span id="third-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> 22kg POL Cylinder</p>
        <p><strong>Quantity:</strong> <span id="fourth-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> 50kg Cylinder</p>
        <p><strong>Quantity:</strong> <span id="fifth-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> POL Regulator</p>
        <p><strong>Quantity:</strong> <span id="sixth-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> AS Regulator</p>
        <p><strong>Quantity:</strong> <span id="seventh-total">0</span></p>
      </div>
      <div class="Invbox">
        <p><strong>Product Name:</strong> Hose with Clamps</p>
        <p><strong>Quantity:</strong> <span id="eight-total">0</span></p>
      </div>

          </div>
          <!-- Add Product Button for Cylinders -->
          <button class="add-product-button" onclick="openModal()">Add Cylinder</button>
        </div>


    
    <button id="deleteSelected" class="btn-delete">Delete Selected</button>
    <button onclick="printTable()" class="btn-print no-print">Print Table</button>
    <!-- Product Table -->
    <div class="product-table" id="printableArea">
        
    <table id="productTable" class="print-table">
    <thead>
        <tr>
                <th>Select</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Expiration Date</th>
                <th>Status</th>
                <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Product rows will be populated here by JavaScript -->
    </tbody>
</table>
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
        <label for="productType">Product Type:</label>
        <select id="productType" name="productType" class="form-control" required onchange="toggleProductOptions()">
          <option value="" disabled selected>Select Type</option>
          <option value="cylinder">Cylinder</option>
          <option value="non-cylinder">Non-Cylinder</option>
        </select>
      </div>
      <div class="form-group">
        <label for="productName">Product Name:</label>
        <select id="productName" name="productName" class="form-control" required onchange="updateProductPrice()">
          <option value="" disabled selected>Select Product</option>
          <!-- Options will be populated based on selection -->
        </select>
      </div>
      <div class="form-group" id="hoseLengthGroup" style="display: none;">
        <label for="hoseLength">Hose Length (in meters):</label>
        <input type="number" id="hoseLength" name="hoseLength" class="form-control" min="0" step="0.1">
      </div>
      <div class="form-group">
        <label for="productQuantity">Quantity:</label>
        <input type="number" id="productQuantity" name="productQuantity" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="productIDs">Product IDs:</label>
        <!-- Modified to allow multiple product IDs separated by commas -->
        <input type="text" id="productIDs" name="productIDs" class="form-control" placeholder="Enter multiple Product IDs separated by commas" required>
      </div>
      <div class="form-group">
        <label for="productPrice">Product Price:</label>
        <input type="text" id="productPrice" name="productPrice" class="form-control" readonly>
      </div>
      <div class="form-group">
        <label for="productDate">Date:</label>
        <input type="date" id="productDate" name="productDate" class="form-control" required onchange="updateExpirationDate()">
      </div>
      <div class="form-group">
        <label for="expirationDate">Expiration Date:</label>
        <input type="date" id="expirationDate" name="expirationDate" class="form-control">
      </div>
      <button type="submit" class="btn-submit">Submit</button>
    </form>
    <div id="cameraOverlay" style="display: none;">
      <video id="video" width="300" height="200" autoplay></video>
    </div>
  </div>
</div>


<script src="JS/inventory.js"></script>

<script>
  function printTable() {
    // Clone the table and remove the Action column
    var table = document.getElementById('productTable').cloneNode(true);
    var rows = table.querySelectorAll('tr');
    
    rows.forEach(function(row) {
      var cells = row.querySelectorAll('th, td');
      if (cells.length > 6) { // Check if there is more than 6 columns
        row.removeChild(cells[cells.length - 1]); // Remove last cell (Action column)
      }
    });

    var printContent = document.getElementById('printableArea').innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = '<html><head><title>Print Table</title>' +
      '<style>table { width: 100%; border-collapse: collapse; }' +
      'th, td { border: 1px solid black; padding: 8px; text-align: left; }' +
      '</style></head><body>' + printContent + '</body></html>';

    window.print();
    document.body.innerHTML = originalContent;
  }
</script>

</body>
</html>
