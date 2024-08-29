<?php
// Include your database connection file
include 'db_connection.php';

// Define a fixed set of colors
$fixedColors = ['#0000FF', '#008000', '#00FFFF', '#800080']; // Blue, Green, Cyan, Purple

// SQL query to fetch completed orders with product prices
$sql = "SELECT 
            p.Product_Name, 
            SUM(o.quantity) as Total_Quantity, 
            p.Product_Price,
            DATE(o.order_date) as Order_Date
        FROM order_details o
        JOIN product_table p ON o.product_id = p.Product_ID
        WHERE o.status = 'completed'
        GROUP BY p.Product_Name, p.Product_Price, DATE(o.order_date)";
$result = $conn->query($sql);

$salesData = [];
$productNames = [];
$salesCounts = [];
$colors = [];

// Initialize today's sales and total sales
$salesToday = 0;
$totalSales = 0;
$today = date('Y-m-d');
$colorIndex = 0; // Index to cycle through the fixed colors

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate today's sales
        if ($row['Order_Date'] == $today) {
            $salesToday += ($row['Product_Price'] * $row['Total_Quantity']);
        }
        
        // Calculate total sales
        $totalSales += ($row['Product_Price'] * $row['Total_Quantity']);
        
        $productNames[] = $row['Product_Name'];
        $salesCounts[] = $row['Total_Quantity'];
        
        // Assign a fixed color to each product
        $colors[] = $fixedColors[$colorIndex % count($fixedColors)];
        $colorIndex++;
    }
}

// Encode the data for use in JavaScript
$salesData = [
    'labels' => $productNames,
    'data' => $salesCounts,
    'colors' => $colors
];

// Initialize total products to zero
$totalProducts = 0;

// SQL query to get the total count of distinct products
$sqlTotalProducts = "SELECT COUNT(DISTINCT Product_ID) AS total_products FROM product_table";
$resultTotalProducts = $conn->query($sqlTotalProducts);

if ($resultTotalProducts && $resultTotalProducts->num_rows > 0) {
    $rowTotalProducts = $resultTotalProducts->fetch_assoc();
    $totalProducts = $rowTotalProducts['total_products'];
}

// Define the products to check for
$productsToCheck = ["11kg Auto-Shutoff Cylinder", "50kg Cylinder"];

// Initialize an array to hold missing products
$missingProducts = [];

// Loop through each product to check if it exists in the product_table
foreach ($productsToCheck as $productName) {
    $sqlCheckProduct = "SELECT * FROM product_table WHERE Product_Name = ?";
    $stmt = $conn->prepare($sqlCheckProduct);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $resultCheckProduct = $stmt->get_result();

    // If the product is not found in the table, add it to the missing products array
    if ($resultCheckProduct->num_rows === 0) {
        $missingProducts[] = $productName;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SOLANE GUFC</title>
  <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/indexstyle.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/modal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
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
            <a href="profile.php">Admin Profile</a>
            <a href="order_invoice.php">Order Invoice</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

  <section class="dashboard">
    <h2>DASHBOARD</h2>
    <br>
    <div class="metrics">
        <div class="total-products"><span>Total Products</span><p id="totalProducts"><?php echo $totalProducts; ?></p></div>
        <div class="sales-today"><span>Sales Today</span><p id="salesToday"><?php echo number_format($salesToday, 2); ?></p></div>
        <div class="total-sales"><span>Total Sales</span><p id="totalSales"><?php echo number_format($totalSales, 2); ?></p></div>
    </div>
    
    <div class="right-box">
        <!-- Google Map Container -->
        <div class="store-map">
            <h4>Store Location</h4>
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15441.577753574738!2d121.1214809!3d14.6335372!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b9f23793fe4f%3A0x75ecbe87baad67d9!2sSolane%20Gufc!5e0!3m2!1sen!2sph!4v1724744063862!5m2!1sen!2sph" width="100%" height="300" style="border:0; border-radius:10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <!-- Conditionally display the Out of Stock Warning -->
        <?php if (!empty($missingProducts)): ?>
            <div class="out-of-stock-warning">
                <h4>Out of Stock Warning!</h4>
                <p>The following items are out of stock: <?php echo implode(', ', $missingProducts); ?>.</p>
            </div>
        <?php endif; ?>
    </div>

<div class="sales-overview">
<h3>Sales Overview</h3>
<!-- The canvas element for the chart -->
<canvas id="salesChart"></canvas>
</div>
<script src="js/settings.js"></script>
</section>
</body>
</html>
