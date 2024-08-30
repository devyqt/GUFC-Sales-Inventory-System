<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORDERS</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/order.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/ordermodal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </script>
</head>
<body>
<div class="container">
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
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>
</div>
<section class="order-section">
  <div class="tabs-wrapper">
    <ul class="tab-list"></ul>
    <div class="content-wrapper">
      <div class="order-header">
        <h3 class="title">Order Information</h3>
        <div class="order-actions">
        <button class="button-add-product" onclick="openProductModal()">Add Product</button>
          <button id="button-delete-selected" class="button-delete">Delete Selected</button>



        <!-- Add Order Modal -->
<div id="orderModal" class="modal">
  <div class="modal-content">
    <span class="close-button" onclick="closeProductModal()">&times;</span>
    <h2>ADD NEW ORDER</h2>
    <div class="modal-body">
      <!-- Product Checkboxes Section -->
      <div class="product-checkboxes">
        <h5>Select Products</h5>
        <div class="checkbox-grid">
          <!-- Product 1 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product1" class="product-checkbox">
            <label for="product1" class="product-details">
              <span class="product-name">1.4kg Auto-Shutoff Cylinder</span>
              <span class="product-price">$10</span>
              <input type="number" id="quantity1" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 2 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product2" class="product-checkbox">
            <label for="product2" class="product-details">
              <span class="product-name">11kg Solane Sakto</span>
              <span class="product-price">$20</span>
              <input type="number" id="quantity2" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 3 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product3" class="product-checkbox">
            <label for="product3" class="product-details">
              <span class="product-name">11KG Cylinder</span>
              <span class="product-price">$30</span>
              <input type="number" id="quantity3" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 4 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product4" class="product-checkbox">
            <label for="product4" class="product-details">
              <span class="product-name">22kg POL Cylinder</span>
              <span class="product-price">$40</span>
              <input type="number" id="quantity4" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 5 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product5" class="product-checkbox">
            <label for="product5" class="product-details">
              <span class="product-name"> 50KG Cylinder</span>
              <span class="product-price">$50</span>
              <input type="number" id="quantity5" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 6 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product6" class="product-checkbox">
            <label for="product6" class="product-details">
              <span class="product-name">POL Regulator</span>
              <span class="product-price">$60</span>
              <input type="number" id="quantity6" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 7 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product7" class="product-checkbox">
            <label for="product7" class="product-details">
              <span class="product-name">POL Regulator (De Salpak)</span>
              <span class="product-price">$70</span>
              <input type="number" id="quantity7" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
          <!-- Product 8 -->
          <div class="checkbox-item">
            <input type="checkbox" id="product8" class="product-checkbox">
            <label for="product8" class="product-details">
              <span class="product-name">Hose with Clamps</span>
              <span class="product-price">$80</span>
              <input type="number" id="quantity8" class="product-quantity" min="1" placeholder="Qty">
            </label>
          </div>
        </div>
      </div>
      <!-- Customer and Order Details Section -->
      <div class="order-details">
        <div class="customer-group">
          <div class="customer-type">
            <label for="customerType">Type of Customer:</label>
            <select id="customerType" name="customerType">
              <option value="regular">Regular</option>
              <option value="vip">VIP</option>
              <option value="new">New</option>
              <!-- Add more options as needed -->
            </select>
          </div>
          <div class="customer-other">
            <label for="otherCustomer">Other:</label>
            <select id="otherCustomer">
              <option value="option1">Option 1</option>
              <option value="option2">Option 2</option>
              <!-- Add more options as needed -->
            </select>
          </div>
        </div>
        <label for="orderDate">Order Date:</label>
        <input type="date" id="orderDate" name="orderDate" required>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="button-submit">Add Order</button>
    </div>
  </div>
</div>


<script src="JS/order.js"></script>
<script src="JS/settings.js"></script>

        </div>
      </div>
      <div class="order-list">
        <div class="order-table-container">
          <table id="order-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Display Products</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Table rows go here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
