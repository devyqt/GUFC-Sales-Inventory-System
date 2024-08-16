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
                    <th>Order Date</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Order rows will be populated here by JavaScript -->
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
    <form id="addOrderForm">
      <div class="form-group">
        <label for="orderID">Order ID:</label> 
        <input type="text" id="orderID" name="orderID" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName" class="form-control" required>
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
