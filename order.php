<?php
include 'db_connection.php';

// Initialize an array to hold products
$products = [];
$products_sql = "
    SELECT 
        product_id, 
        Product_Name, 
        Product_Price, 
        quantity
    FROM product_table
    WHERE status = 'available' AND product_id NOT IN (
        SELECT DISTINCT product_id FROM order_details
    )";
$products_result = $conn->query($products_sql);
if ($products_result) {
    while ($row = $products_result->fetch_assoc()) {
        $products[$row['product_id']] = [
            'name' => $row['Product_Name'],
            'price' => $row['Product_Price'],
            'quantity' => $row['quantity']
        ];
    }
} else {
    die("Error: " . $conn->error);
}


// Fetch data from order_details along with customer and product information
$order_sql = "
    SELECT 
        o.order_id, 
        c.Customer_Name, 
        p.Product_Name, 
        o.quantity, 
        p.Product_Price, 
        (o.quantity * p.Product_Price) AS Total_Price, 
        o.status, 
        o.order_date
    FROM order_details o
    JOIN product_table p ON o.product_id = p.Product_ID
    JOIN customer_table c ON o.customer_id = c.Customer_ID
    ORDER BY o.order_id";
$order_result = $conn->query($order_sql);
if (!$order_result) {
    die("Error: " . $conn->error);
}

// SQL query to fetch customers from customer_table
$customer_sql = "SELECT Customer_ID, Customer_Name FROM customer_table";
$customer_result = $conn->query($customer_sql);
if (!$customer_result) {
    die("Error: " . $conn->error);
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
        <button class="close-button" onclick="closeProductModal()" aria-label="Close Modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">ADD PRODUCT</h2>
        
        <form method="POST" action="add_order.php">
    <!-- Loop through products and quantities -->
    <?php foreach ($products as $product_id => $details): ?>
        <div class="product-item">
            <input type="checkbox" name="products[]" value="<?php echo htmlspecialchars($product_id); ?>">
            <img src="images/<?php echo htmlspecialchars($product_id); ?>.jpg" alt="<?php echo htmlspecialchars($details['name']); ?>" class="product-img">
            <div class="product-info">
                <h4><?php echo htmlspecialchars($details['name']); ?></h4>
            </div>
            <div class="product-price">
                <p>₱<?php echo number_format($details['price'], 2); ?></p>
            </div>
            <div class="product-quantity">
                <label for="quantity<?php echo htmlspecialchars($product_id); ?>">Quantity</label>
                <input type="number" id="quantity<?php echo htmlspecialchars($product_id); ?>" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" value="1" min="1" max="<?php echo $details['quantity']; ?>">
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Customer and date fields -->
    <div class="customer-type">
        <h4>Select Customer</h4>
        <div class="customer-select">
            <select id="customerSelect" name="customer_id" aria-label="Select Customer">
                <?php while ($customer_row = $customer_result->fetch_assoc()): ?>
                    <option value="<?php echo $customer_row['Customer_ID']; ?>"><?php echo htmlspecialchars($customer_row['Customer_Name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="order-date">
        <label for="orderDate">Order Date</label>
        <input type="date" id="orderDate" name="orderDate" required>
    </div>

    <button type="submit" class="btn-submit">SUBMIT</button>
</form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event listeners to expand icons
        document.querySelectorAll('.expand-icon').forEach(function (icon) {
            icon.addEventListener('click', function () {
                var orderId = this.getAttribute('data-order-id');
                var detailsRow = document.getElementById('order-details-' + orderId);
                if (detailsRow) {
                    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                        detailsRow.style.display = 'table-row';
                        this.textContent = '-';
                    } else {
                        detailsRow.style.display = 'none';
                        this.textContent = '+';
                    }
                }
            });
        });

        // Add event listeners for status select elements
        var statusSelects = document.querySelectorAll('.status-select');
        statusSelects.forEach(function (select) {
            select.addEventListener('change', function () {
                var orderId = this.getAttribute('data-order-id');
                var newStatus = this.value;
                updateOrderStatus(orderId, newStatus);
            });
        });
    });

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

    function openProductModal() {
        document.getElementById('orderModal').style.display = 'block';
    }

    function closeProductModal() {
        document.getElementById('orderModal').style.display = 'none';
    }

    function deleteOrder(orderId) {
        if (confirm("Are you sure you want to delete this order?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_order.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    alert("Order deleted successfully.");
                    location.reload(); // Reload page to reflect changes
                }
            };
            xhr.send("order_id=" + encodeURIComponent(orderId));
        }
    }

    document.getElementById('deleteSelectedOrders').addEventListener('click', function() {
        var selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        var orderIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-order-id'));

        if (orderIds.length > 0 && confirm("Are you sure you want to delete the selected orders?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_selected_orders.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    alert("Selected orders deleted successfully.");
                    location.reload(); // Reload page to reflect changes
                }
            };
            xhr.send("order_ids=" + encodeURIComponent(orderIds.join(',')));
        }
    });
</script>

<script src="JS/order.js"></script>
</body>
</html>
