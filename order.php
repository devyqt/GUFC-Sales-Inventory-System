<?php
// Include your database connection file
include 'db_connection.php';

// Fetch products and their quantities from the product_variants table
$sqlProducts = "
    SELECT pt.product_id, pt.Product_Name, pt.Product_Price, pv.quantity
    FROM product_table pt
    INNER JOIN product_variants pv ON pt.product_id = pv.product_id
    WHERE pv.status = 'Available'
";
$resultProducts = $conn->query($sqlProducts);

// Check for query errors
if (!$resultProducts) {
    die("Error in SQL query: " . $conn->error);
}

$products = []; // Initialize $products as an empty array
if ($resultProducts->num_rows > 0) {
    // Store products in an array
    while ($row = $resultProducts->fetch_assoc()) {
        $products[] = $row;
    }
}

// Fetch customers for the dropdown
$sqlCustomers = "SELECT customer_id, customer_name FROM customer_table";
$resultCustomers = $conn->query($sqlCustomers);

// Check for query errors
if (!$resultCustomers) {
    die("Error in SQL query: " . $conn->error);
}

$customers = []; // Initialize $customers as an empty array
if ($resultCustomers->num_rows > 0) {
    // Store customers in an array
    while ($row = $resultCustomers->fetch_assoc()) {
        $customers[] = $row;
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
    <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                <img src="images/admin logo.png" alt="Profile Avatar">
            </div>
            <div class="settings-icon">
                <img src="images/settings.png" alt="Settings" onclick="toggleDropdown()">
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
                                            <?php if (!empty($products)): ?>
                                                <?php foreach ($products as $index => $product): ?>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" id="product<?= $index ?>" class="product-checkbox" data-quantity="<?= htmlspecialchars($product['quantity']) ?>">
                                                        <label for="product<?= $index ?>" class="product-details">
                                                            <span class="product-name"><?= htmlspecialchars($product['Product_Name']) ?></span>
                                                            <span class="product-price">₱<?= htmlspecialchars($product['Product_Price']) ?></span>
                                                            <input type="number" id="quantity<?= $index ?>" class="product-quantity" min="1" max="<?= htmlspecialchars($product['quantity']) ?>" placeholder="Qty">
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>No products available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Customer and Order Details Section -->
                                    <div class="order-details">
                                        <div class="customer-group">
                                            <div class="customer-type">
                                                <label for="customerType">Customer:</label>
                                                <select id="customerType" name="customerType">
                                                    <?php if (!empty($customers)): ?>
                                                        <?php foreach ($customers as $customer): ?>
                                                            <option value="<?= htmlspecialchars($customer['customer_id']) ?>"><?= htmlspecialchars($customer['customer_name']) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No customers available.</option>
                                                    <?php endif; ?>
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

<script src="JS/order.js"></script>
<script src="JS/settings.js"></script>

</body>
</html>
