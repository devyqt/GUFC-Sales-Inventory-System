<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to fetch data from the order_table
$sql = "SELECT Order_ID, Customer_Name, Product_ID, Order_Date, Order_Status FROM order_table";
$result = $conn->query($sql);

// SQL query to fetch available products from the product_table
$product_sql = "SELECT Product_ID, Product_Name, Product_Price, Product_Date FROM product_table";
$product_result = $conn->query($product_sql);

// Fetch IDs of already ordered products
$ordered_products_sql = "SELECT DISTINCT Product_ID FROM order_table";
$ordered_products_result = $conn->query($ordered_products_sql);
$ordered_products = [];
if ($ordered_products_result->num_rows > 0) {
    while ($ordered_row = $ordered_products_result->fetch_assoc()) {
        $ordered_products[] = $ordered_row['Product_ID'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORDERS</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/order.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/ordermodal.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
  
  <?php include 'navbar.html'; ?>

  <section class="main">
    <div class="tab-container">
    <ul class="tabs">

    </ul>
      <!-- Tab Content -->
      <div class="tab-content">
          <div class="order-container">
          </div>
          <!-- Add Order Button -->
          <button class="add-product-button" onclick="openProductModal()">Add Product</button>
          <button id="deleteSelectedOrders" class="btn-delete">Delete Selected</button>
    <!-- Order Table -->
    <div class="order-table">
        <table id="orderTable">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Order ID</th>                   
                    <th>Customer</th>
                    <th>Product ID</th>
                    <th>Item Name</th>
                    <th>Price</th> <!-- Added Price column -->
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
            <?php
            include 'fetch_orders.php';
            ?>

            </tbody>
        </table>
        </div>
    </section>
    </div>  

<!-- Button to open the modal -->
<button onclick="openProductModal()">Add Product</button>
<!-- Modal Structure -->
<div id="orderModal" class="modal" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
    <div class="modal-content">
        <!-- Close button -->
        <button class="close-button" onclick="closeProductModal()" aria-label="Close Modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">ADD PRODUCT</h2>
        
        <!-- Product List -->
        <div class="product-list">
            <!-- Product 1 -->
            <div class="product-item">
                <input type="checkbox" aria-label="Select Product 1">
                <img src="images/Auto-Shutoff.jpg" alt="Product Name 1" class="product-img">
                <div class="product-info">
                    <h4>11kg Auto-Shutoff Cylinder</h4>
                </div>
                <div class="product-price">
                    <p>$10.00</p>
                </div>
                <div class="product-quantity">
                    <label for="quantity1">Quantity</label>
                    <input type="number" id="quantity1" name="quantity1" value="1" min="1">
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-item">
                <input type="checkbox" aria-label="Select Product 2">
                <img src="images/1.4KGSAKTO.jpg" alt="Product Name 2" class="product-img">
                <div class="product-info">
                    <h4>1.4kg Solane Sakto</h4>
                </div>
                <div class="product-price">
                    <p>$15.00</p>
                </div>
                <div class="product-quantity">
                    <label for="quantity2">Quantity</label>
                    <input type="number" id="quantity2" name="quantity2" value="1" min="1">
                </div>
            </div>

            <!-- Product 3 -->
            <div class="product-item">
                <input type="checkbox" aria-label="Select Product 3">
                <img src="images/22KG POL.jpg" alt="Product Name 3" class="product-img">
                <div class="product-info">
                    <h4>22kg POL Cylinder</h4>
                </div>
                <div class="product-price">
                    <p>$20.00</p>
                </div>
                <div class="product-quantity">
                    <label for="quantity3">Quantity</label>
                    <input type="number" id="quantity3" name="quantity3" value="1" min="1">
                </div>
            </div>

            <!-- Product 4 -->
            <div class="product-item">
                <input type="checkbox" aria-label="Select Product 4">
                <img src="images/50KG CYLINDER.jpg" alt="Product Name 4" class="product-img">
                <div class="product-info">
                    <h4>50kg Cylinder</h4>
                </div>
                <div class="product-price">
                    <p>$25.00</p>
                </div>
                <div class="product-quantity">
                    <label for="quantity4">Quantity</label>
                    <input type="number" id="quantity4" name="quantity4" value="1" min="1">
                </div>
            </div>
        </div>

        <!-- Customer Type Section -->
        <div class="customer-type">
            <h4>Type of Customer</h4>
            <div class="customer-select">
                <div class="select-group">
                    <label for="fixCustomer">Fix Customer</label>
                    <select id="fixCustomer" name="fixCustomer" aria-label="Select Fix Customer">
                        <!-- Options here -->
                    </select>
                </div>
                <div class="select-group">
                    <label for="otherCustomer">Other</label>
                    <select id="otherCustomer" name="otherCustomer" aria-label="Select Other Customer">
                        <!-- Options here -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Order Date -->
        <div class="order-date">
            <label for="orderDate">Order Date</label>
            <input type="date" id="orderDate" name="orderDate">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit">SUBMIT</button>
    </div>
</div>

<script>
    // Function to open the modal
    function openProductModal() {
        document.getElementById("orderModal").style.display = "block";
    }

    // Function to close the modal
    function closeProductModal() {
        document.getElementById("orderModal").style.display = "none";
    }

    // Close the modal if user clicks outside the modal content
    window.onclick = function(event) {
        var modal = document.getElementById("orderModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
<script src="JS/order.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
