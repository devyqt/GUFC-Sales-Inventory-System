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
          <button class="add-product-button" onclick="openProductModal()">Add Product</button>

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

    <div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeProductModal()">&times;</span>
        <h3>Add Product</h3>
        <form id="addProductForm" action="add_product_proc.php" method="POST">
            <div class="product-list">
                <label><input type="checkbox" name="products[]" value="Abc-121-123 - 22kg POL Cylinder"> Abc-121-123 - 22kg POL Cylinder</label>
                <label><input type="checkbox" name="products[]" value="Abc-121-124 - 22kg POL Cylinder"> Abc-121-124 - 22kg POL Cylinder</label>
                <label><input type="checkbox" name="products[]" value="Abc-121-125 - 22kg POL Cylinder"> Abc-121-125 - 22kg POL Cylinder</label>
                <label><input type="checkbox" name="products[]" value="Abc-121-126 - 22kg POL Cylinder"> Abc-121-126 - 22kg POL Cylinder</label>
                <label><input type="checkbox" name="products[]" value="Abc-121-127 - 22kg POL Cylinder"> Abc-121-127 - 22kg POL Cylinder</label>
                <!-- Add more products as needed -->
            </div>
            <div class="form-group">
                <label for="productID">Product ID:</label>
                <input type="text" id="productID" name="productID" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="orderDate">Order Date:</label>
                <input type="date" id="orderDate" name="orderDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" class="btn-submit">Submit</button>
        </form>
    </div>
</div>
<script>
    function closeProductModal() {
        document.getElementById("addProductModal").style.display = "none";
    }

    function openProductModal() {
        document.getElementById("addProductModal").style.display = "block";
    }
</script>

<script src="JS/order.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
