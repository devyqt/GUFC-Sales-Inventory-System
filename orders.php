<?php
// Include your database connection file
include 'db_connection.php';

// Fetch orders data
$order_query = "SELECT o.order_id, o.Customer_Name, o.order_date, o.status, p.Product_Name, p.Product_Price, oi.quantity, (p.Product_Price * oi.quantity) AS Total_Price
                FROM orders o
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN product_table p ON oi.product_id = p.product_id";
$order_result = $conn->query($order_query);

// Fetch customer data
$customer_query = "SELECT Customer_ID, Customer_Name FROM customers"; // Replace 'customers' with your actual customer table name
$customer_result = $conn->query($customer_query);

// Fetch product data
$product_query = "SELECT product_id, Product_Name, Product_Price, quantity AS Total_Quantity FROM product_table"; // Ensure your table and columns are correct
$product_result = $conn->query($product_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORDERS</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/order.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/ordermodal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script>
    function updateOrderStatus(orderId, newStatus) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "update_status.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          console.log("Status updated successfully.");
        }
      };
      xhr.send("order_id=" + encodeURIComponent(orderId) + "&status=" + encodeURIComponent(newStatus));
    }
    
    document.addEventListener('DOMContentLoaded', function () {
      var statusSelects = document.querySelectorAll('.status-select');
      statusSelects.forEach(function (select) {
        select.addEventListener('change', function () {
          var orderId = this.getAttribute('data-order-id');
          var newStatus = this.value;
          updateOrderStatus(orderId, newStatus);
        });
      });
    });
  </script>
</head>
<body>
<div class="container">
  
  <?php include 'navbar.html'; ?>

  <section class="main">
    <div class="tab-container">
      <ul class="tabs"></ul>
      <div class="tab-content">
          <div class="order-container"></div>
          <button class="add-product-button" onclick="openProductModal()">Add Product</button>
          <button id="deleteSelectedOrders" class="btn-delete">Delete Selected</button>
          <div class="order-table">
              <table id="orderTable">
                  <thead>
                      <tr>
                          <th>Select</th>
                          <th>Order ID</th>
                          <th>Customer Name</th>
                          <th>Display Products</th>
                          <th>Status</th>
                          <th>Order Date</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      
                  <?php
                  $current_order_id = null;
                  $order_totals = [];
                  if ($order_result->num_rows > 0) {
                      while ($row = $order_result->fetch_assoc()) {
                          if ($current_order_id !== $row['order_id']) {
                              if ($current_order_id !== null) {
                                  // Output aggregated totals
                                  echo '<tr>';
                                  echo '<td colspan="3">Total Price</td>';
                                  echo '<td>₱' . number_format($order_totals[$current_order_id], 2) . '</td>';
                                  echo '</tr>';
                                  
                                  echo '</tbody></table></td></tr>';
                              }
                              $current_order_id = $row['order_id'];
                              $order_totals[$current_order_id] = 0; // Reset total for the new order
                              
                              echo '<tr class="order-row">';
                              echo '<td><input type="checkbox" class="order-checkbox" data-order-id="' . htmlspecialchars($row['order_id']) . '"></td>';
                              echo '<td>' . htmlspecialchars($row['order_id']) . '</td>';
                              echo '<td>' . htmlspecialchars($row['Customer_Name']) . '</td>';
                              echo '<td><span class="expand-icon" data-order-id="' . htmlspecialchars($row['order_id']) . '">+</span></td>';
                              echo '<td>';
                              echo '<select class="status-select" data-order-id="' . htmlspecialchars($row['order_id']) . '">';
                              echo '<option value="pending" ' . ($row['status'] === 'pending' ? 'selected' : '') . '>Pending</option>';
                              echo '<option value="completed" ' . ($row['status'] === 'completed' ? 'selected' : '') . '>Completed</option>';
                              echo '<option value="shipped" ' . ($row['status'] === 'shipped' ? 'selected' : '') . '>Shipped</option>';
                              echo '</select>';
                              echo '</td>';
                              echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                              echo '<td><button class="btn-delete" onclick="deleteOrder(' . htmlspecialchars($row['order_id']) . ')">Delete</button> ';
                              echo '<button class="btn-print" onclick="window.open(\'print_invoice.php?order_id=' . htmlspecialchars($row['order_id']) . '\', \'_blank\')">Print Invoice</button></td>';
                              echo '</tr>';
                              echo '<tr class="order-details" id="order-details-' . htmlspecialchars($row['order_id']) . '">';
                              echo '<td colspan="7">';
                              echo '<table>';
                              echo '<thead><tr><th>Product Name</th><th>Quantity</th><th>Price Per Unit</th><th>Total Price</th></tr></thead><tbody>';
                          }

                          $order_totals[$current_order_id] += $row['Total_Price']; // Accumulate total price for the order
                          
                          echo '<tr>';
                          echo '<td>' . htmlspecialchars($row['Product_Name']) . '</td>';
                          echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                          echo '<td>₱' . number_format($row['Product_Price'], 2) . '</td>';
                          echo '<td>₱' . number_format($row['Total_Price'], 2) . '</td>';
                          echo '</tr>';
                      }
                      // Output the last aggregated total
                      echo '<tr>';
                      echo '<td colspan="3">Total Price</td>';
                      echo '<td>₱' . number_format($order_totals[$current_order_id], 2) . '</td>';
                      echo '<td colspan="3"></td>';
                      echo '</tr>';
                      
                      echo '</tbody></table></td></tr>';
                  } else {
                      echo '<tr><td colspan="7">No orders available.</td></tr>';
                  }
                  ?>

                  </tbody>
              </table>
          </div>
      </div>
    </section>
</div>

<div id="orderModal" class="modal" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
    <div class="modal-content">
        <!-- Close button -->
        <button class="close-button" onclick="closeProductModal()" aria-label="Close Modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">ADD PRODUCT</h2>
        
        <!-- Form to add product to the order -->
        <form method="POST" action="add_order.php">
        <div class="product-list">
            <?php
            if ($product_result->num_rows > 0) {
                while ($product_row = $product_result->fetch_assoc()) {
                    $product_name = $product_row['Product_Name'];
                    $product_price = $product_row['Product_Price'];
                    $total_quantity = $product_row['Total_Quantity'];
                    $safe_product_name = preg_replace('/[^a-zA-Z0-9]/', '_', $product_name);

                    echo '<div class="product-item">';
                    echo '<input type="checkbox" name="products[]" value="' . htmlspecialchars($product_name) . '" aria-label="Select Product ' . htmlspecialchars($product_name) . '">';
                    echo '<img src="images/' . htmlspecialchars($product_name) . '.jpg" alt="' . htmlspecialchars($product_name) . '" class="product-img">';
                    echo '<div class="product-info">';
                    echo '<h4>' . htmlspecialchars($product_name) . '</h4>';
                    echo '<p>₱' . number_format($product_price, 2) . ' per unit</p>';
                    echo '</div>';
                    echo '<div class="product-quantity">';
                    echo '<label for="quantity_' . htmlspecialchars($safe_product_name) . '">Quantity:</label>';
                    echo '<input type="number" id="quantity_' . htmlspecialchars($safe_product_name) . '" name="quantities[' . htmlspecialchars($product_name) . ']" min="1" max="' . htmlspecialchars($total_quantity) . '" step="1" value="1">';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products available in the inventory.</p>';
            }
            ?>
        </div>
        <!-- Dropdown for selecting customer -->
        <div class="dropdown">
            <label for="customerName">Customer Name:</label>
            <select id="customerName" name="customerName" required aria-required="true">
                <option value="" disabled selected aria-disabled="true">Select a customer</option>
                <?php
                if ($customer_result->num_rows > 0) {
                    while ($customer_row = $customer_result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($customer_row['Customer_Name']) . '">' . htmlspecialchars($customer_row['Customer_Name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No customers available</option>';
                }
                ?>
            </select>
        </div>
        
        <!-- Button to submit the form -->
        <button type="submit" class="btn-submit">Add Order</button>
        </form>
    </div>
</div>

<script src="JS/ordermodal.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
