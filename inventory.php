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
<div>

<?php include 'navbar.html'; ?>

<!-- Admin Profile Component Start -->
<div class="admin-profile">
    <div class="profile-info">
        <div class="profile-name">Solane Admin</div>
        <div class="profile-role">Admin Profile</div>
    </div>
    <div class="profile-image">
        <img src="images/admin logo.png" alt="Profile Avatar"> <!-- Replace with the path to your image -->
    </div>
    <div class="settings-icon">
        <img src="images/settings.png" alt="Settings"> <!-- Replace with the path to your settings icon -->
    </div>
  </div>
  <!-- Admin Profile Component End -->

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

        <!-- JavaScript to handle modal -->
        <script>
            function openSortModal() {
                document.getElementById("sortModal").style.display = "block";
            }

            function closeSortModal() {
                document.getElementById("sortModal").style.display = "none";
            }

            // Close the modal if user clicks outside the modal content
            window.onclick = function(event) {
                var modal = document.getElementById("sortModal");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>



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
                    <th>Quantity</th>
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
    <!-- Add the serial number camera icon -->
    <span class="camera-icon" title="Serial Number Camera">
      <img src="images/serialCam.jpg" alt="Camera Icon">
    </span>
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
        <label for="productPrice">Product Price:</label> 
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
