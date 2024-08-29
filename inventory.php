<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>INVENTORY</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/modal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/inventory.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
</head>
<div>

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
            <a href="profile.php">Admin Profile</a>
            <a href="order_invoice.php">Order Invoice</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>
  <div class="container">
  <section class="main">
    <div class="inventory-tab-container">

      <!-- Category Tabs -->
      <ul class="tabs">
        <li><a href="#cylinders" class="tab-link active">Products</a></li>
        <li><a href="#other-products" class="tab-link">Other Products</a></li>
      </ul>
      <!-- Products Tab -->
      <div id="cylinders" class="tab-pane active">
          <div class="inventory-container">
            <div class="Invbox">
              <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
              <p><strong>Quantity:</strong> <span id="first-total">200</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> 11kg POL Cylinder</p>
              <p><strong>Quantity:</strong> <span id="second-total">370</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> 1.4kg Solane Sakto</p>
              <p><strong>Quantity:</strong> <span id="third-total">250</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> 22kg POL Cylinder</p>
              <p><strong>Quantity:</strong> <span id="fourth-total">89</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> 50kg Cylinder</p>
              <p><strong>Quantity:</strong> <span id="fifth-total">67</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> POL Regulator</p>
              <p><strong>Quantity:</strong> <span id="sixth-total">324</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> AS Regulator</p>
              <p><strong>Quantity:</strong> <span id="seventh-total">180</span></p>
            </div>
            <div class="Invbox">
              <p><strong>Product Name:</strong> Hose with Clamps</p>
              <p><strong>Quantity:</strong> <span id="eight-total">60</span></p>
            </div>
          </div>
                <!-- Add Product Button for Cylinders -->
        <button class="add-product-button" onclick="openModal()">Add Cylinder</button>
      </div>

      <!-- Other Products Tab -->
      <div id="other-products" class="tab-pane">
        <div class="other-container">
          <div class="Other-item">
            <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
            <p><strong>Quantity:</strong> <span id="first-total">200</span></p>
          </div>
          <div class="Other-item">
            <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
            <p><strong>Quantity:</strong> <span id="first-total">200</span></p>
          </div>
          <div class="Other-item">
            <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
            <p><strong>Quantity:</strong> <span id="first-total">200</span></p>
          </div>
          <div class="Other-item">
            <p><strong>Product Name:</strong> 11kg Auto-Shutoff Cylinder</p>
            <p><strong>Quantity:</strong> <span id="first-total">200</span></p>
          </div>
       <!-- Add Product Button for Other Products -->
       <button class="add-accessory-button" onclick="openModal()">Add Accessory</button>
    </div>
  </section>
</div>

    </div>
  </section>
 
  
        <!-- Sort Button -->
        </div>
    <button class="sort-button" onclick="openSortModal()">
            <img src="images/Sorting.png" alt="Sort" class="sort-icon"> Sort
        </button>
        </div>
</div>
        <!-- Sort Modal -->
        <div id="sortModal" class="sort-modal" role="dialog" aria-labelledby="sortTitle" aria-modal="true">
            <div class="sort-modal-content">
                <!-- Close button -->
                <button class="close-button" onclick="closeSortModal()" aria-label="Close Modal">&times;</button>
                <h2 class="sort-modal-title" id="sortTitle">Sort</h2>
                
                <!-- Sort options and content -->
                <div class="sort-section">
                    <label>Product No.</label>
                    <div class="sort-options">
                        <label>
                            <input type="radio" name="productNumber" value="0-9">
                            0-9
                        </label>
                        <label>
                            <input type="radio" name="productNumber" value="9-0">
                            9-0
                        </label>
                    </div>
                </div>

                <div class="sort-section">
                    <label>Date</label>
                    <div class="sort-options">
                        <label>
                            <input type="radio" name="dateOrder" value="ascending">
                            Ascending
                        </label>
                        <label>
                            <input type="radio" name="dateOrder" value="descending">
                            Descending
                        </label>
                    </div>
                </div>

                <div class="sort-section">
                    <label>Date Range</label>
                    <div class="date-range">
                        <input type="date" id="startDate" name="startDate">
                        <span>To</span>
                        <input type="date" id="endDate" name="endDate">
                    </div>
                </div>

                <div class="sort-actions">
                    <button type="button" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-apply">Apply Now</button>
                </div>
            </div>
        </div>

    <button id="deleteSelected" class="btn-delete">Delete Selected</button>
    <button onclick="printTable()" class="btn-print no-print">Print Table</button>
   
   
    <table id="productTable">
    <thead>
        <tr>
            <th>Select</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Product Date</th>
            <th>Expiration Date</th>
            <th>Status</th>
            <th>Serial Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be dynamically inserted here -->
    </tbody>
</table>

<!-- Modal HTML -->
<div id="productModal" class="modal">
  <div class="modal-content">
    <span class="close-button" onclick="closeModal()">&times;</span>
    <span class="camera-icon" title="Serial Number Camera">
      <img src="images/serialCam.jpg" alt="Camera Icon">
    </span>
    <h3>Add Product</h3>
    <form id="addProductForm">
      <div class="form-group">
        <label for="productID">Product ID:</label>
        <input type="text" id="productID" name="productID" class="form-control" readonly>
      </div>
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
      <div class="form-group">
        <label for="serialNumbers">Serial Numbers (one per line, include hose length if applicable):</label>
        <textarea id="serialNumbers" name="serialNumbers" class="form-control" placeholder="Enter serial numbers with hose length, one per line, e.g., SN001 - 2.5" rows="4"></textarea>
        <div id="serialError" style="color: red; display: none;">The number of serial numbers exceeds the quantity.</div>
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