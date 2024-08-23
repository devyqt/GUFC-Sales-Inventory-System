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
        <!-- Cylinders Tab -->
        <div id="cylinders" class="tab-pane active">
          <div class="product-container">
            <div class="box">
              <img src="images/11kg.jpg" alt="Product 1" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
              <p><strong>Description:</strong> High quality cylinder.</p>
            </div>
            <div class="box">
              <img src="images/14kg.jpg" alt="Product 2" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 1.4kg Solane Sakto</p>
              <p><strong>Description:</strong> Durable and reliable.</p>
            </div>
            <div class="box">
              <img src="images/22kg.jpg" alt="Product 3" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 22kg POL Cylinder</p>
              <p><strong>Description:</strong> High quality cylinder.</p>
            </div>
            <div class="box">
              <img src="images/50kg.jpg" alt="Product 4" class="product-img" style="width: 230px; height: 230px; object-fit: cover;">
              <p><strong>Product Name:</strong> 50kg Cylinder</p>
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
                    <th>Price</th>
                    <th>Date</th>
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
        <label for="productID">Product ID:</label> 
        <input type="text" id="productID" name="productID" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="productName">Product Name:</label>
        <select id="productName" name="productName" class="form-control" required>
          <option value="">Select Cylinder</option>
          <option value="11kg Auto-Shutoff Cylinder">11kg Auto-Shutoff Cylinder</option>
          <option value="1.4kg Solane Sakto">1.4kg Solane Sakto</option>
          <option value="22kg POL Cylinder">22kg POL Cylinder</option>
          <option value="50kg Cylinder">50kg Cylinder</option>
        </select>
      </div>
      <div class="form-group">
        <label for="productPrice">Product Price</label> 
        <input type="text" id="productPrice" name="productPrice" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="productDate">Date:</label>
        <input type="date" id="productDate" name="productDate" class="form-control" required>
      </div>
      <button type="submit" class="btn-submit">Submit</button>
    </form>
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
