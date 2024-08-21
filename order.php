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
  <link rel="stylesheet" href="CSS/modal.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
  
  <?php include 'navbar.html'; ?>

  <section class="main">
    <div class="tab-container">
    <ul class="tabs">
        <li><a href="#" class="tab-link active" data-status="Pending">Pending Orders</a></li>
        <li><a href="#" class="tab-link" data-status="Completed">Completed Orders</a></li>
    </ul>



      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Pending Orders Tab -->
        <div id="pending-orders" class="tab-pane active">
          <div class="order-container">
            <!-- Order items will be displayed here -->
          </div>
          <!-- Add Order Button -->
          <button class="add-order-button" onclick="openOrderModal()">Add Order</button>
        </div>

        <!-- Completed Orders Tab -->
        <div id="completed-orders" class="tab-pane">
          <div class="order-container">
            <!-- Order items will be displayed here -->
          </div>
        </div>
      </div>
    </div>

    <!-- Order Table -->
    <div class="order-table">
        <button id="deleteSelectedOrders" class="btn-delete">Delete Selected</button>
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

<!-- Modal HTML -->
<div id="orderModal" class="modal">
  <div class="modal-content">
    <span class="close-button" onclick="closeOrderModal()">&times;</span>
    <h3>Add Order</h3>
    <form id="addOrderForm" action="order_proc.php" method="POST">
      <div class="form-group">
        <label for="orderID">Order ID:</label> 
        <input type="text" id="orderID" name="orderID" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="itemName">Product:</label>
        <select id="itemName" name="itemName" class="form-control" required>
          <option value="" disabled selected>Select a Product</option> <!-- Default option -->
          <?php
          // Populate the dropdown with products, excluding already ordered products
          if ($product_result->num_rows > 0) {
              while($product_row = $product_result->fetch_assoc()) {
                  if (!in_array($product_row['Product_ID'], $ordered_products)) {
                      echo "<option value='" . $product_row['Product_ID'] . "'>"
                          . $product_row['Product_ID'] . " - " 
                          . $product_row['Product_Name'] . " (" 
                          . $product_row['Product_Date'] . ")"
                          . "</option>";
                  }
              }
          } else {
              echo "<option value=''>No products available</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
    <label for="orderStatus">Order Status:</label>
    <select id="orderStatus" name="orderStatus" class="form-control" disabled>
        <option value="Pending" selected>Pending</option>
        <option value="Processing">Processing</option>
        <option value="Completed">Completed</option>
        <option value="Cancelled">Cancelled</option>
    </select>
</div>

      <div class="form-group">
        <label for="orderDate">Order Date:</label>
        <input type="date" id="orderDate" name="orderDate" class="form-control" required>
      </div>   
      <button type="submit" class="btn-submit">Submit</button>
    </form>
  </div>
</div>

<script src="JS/order.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
