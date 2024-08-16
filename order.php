<?php
// Include your database connection file
include 'db_connection.php';

// SQL query to fetch data from the order_table
$sql = "SELECT Order_ID, Customer_Name, Product_ID, Order_Date FROM order_table";
$result = $conn->query($sql);

// SQL query to fetch available products from the product_table
$product_sql = "SELECT Product_ID, Product_Name, Product_Price FROM product_table";
$product_result = $conn->query($product_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORDERS</title>
  <link rel="stylesheet" href="CSS/style.css">
  <link rel="stylesheet" href="CSS/order.css">
  <link rel="stylesheet" href="CSS/modal.css">
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
        <li><a href="#pending-orders" class="tab-link active">Pending Orders</a></li>
        <li><a href="#completed-orders" class="tab-link">Completed Orders</a></li>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Check if there are results and display them
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Fetch product details from product_table
                    $product_query = "SELECT Product_Name, Product_Price FROM product_table WHERE Product_ID = '" . $row['Product_ID'] . "'";
                    $product_result_inner = $conn->query($product_query);
                    $product_row = $product_result_inner->fetch_assoc();

                    echo "<tr>";
                    echo "<td><input type='checkbox' name='selectOrder'></td>";
                    echo "<td>" . $row['Order_ID'] . "</td>";
                    echo "<td>" . $row['Customer_Name'] . "</td>";
                    echo "<td>" . $row['Product_ID'] . "</td>";
                    echo "<td>" . ($product_row ? $product_row['Product_Name'] : 'Unknown') . "</td>";
                    echo "<td>" . ($product_row ? $product_row['Product_Price'] : 'N/A') . "</td>"; // Display Price
                    echo "<td>" . $row['Order_Date'] . "</td>";
                    echo "<td>
                            <button class='btn-edit'>Edit</button>
                            <button class='btn-delete' onclick='deleteOrder(\"" . $row['Order_ID'] . "\")'>Delete</button>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No orders found</td></tr>";
            }
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
          <?php
          // Populate the dropdown with products
          if ($product_result->num_rows > 0) {
              while($product_row = $product_result->fetch_assoc()) {
                  echo "<option value='" . $product_row['Product_ID'] . "'>" . $product_row['Product_Name'] . "</option>";
              }
          } else {
              echo "<option value=''>No products available</option>";
          }
          ?>
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
