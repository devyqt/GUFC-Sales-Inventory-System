<?php
// Include your database connection file
include 'db_connection.php';

// Define a fixed set of colors
$fixedColors = ['#0000FF', '#008000', '#00FFFF', '#800080']; // Blue, Green, Cyan, Purple

// SQL query to fetch completed orders with product prices
$sql = "SELECT 
            p.Product_Name, 
            COUNT(*) as Total_Sales, 
            p.Product_Price,
            o.order_date as Order_Date
        FROM order_details o
        JOIN product_table p ON o.product_id = p.product_id
        WHERE o.status = 'completed'
        GROUP BY p.Product_Name, p.Product_Price";
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
        if (date('Y-m-d', strtotime($row['Order_Date'])) == $today) {
            $salesToday += ($row['Product_Price'] * $row['Total_Sales']);
        }
        
        // Calculate total sales
        $totalSales += ($row['Product_Price'] * $row['Total_Sales']);
        
        $productNames[] = $row['Product_Name'];
        $salesCounts[] = $row['Total_Sales'];
        
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
$sqlTotalProducts = "SELECT COUNT(DISTINCT product_id) AS total_products FROM product_table";
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
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</head>
<body>
<div class="container">
  <?php include 'navbar.html'; ?>

  <!-- Admin Profile Component Start -->
  <div class="admin-profile">
    <div class="profile-info">
        <div class="profile-name">Solane Admin</div>
        <div class="profile-role">Admin Profile</div>
    </div>
    <div class="profile-image">
        <img src="images/admin logo.png" alt="Profile Avatar"> <!-- Replace with the path to your image -->
    </div>
    <div class="settings-icon">
        <img src="images/settings.png" alt="Settings"> <!-- Replace with the path to your settings icon -->
    </div>
  </div>
  <!-- Admin Profile Component End -->

  <section class="dashboard">
    <h2>DASHBOARD</h2>
    <br>
    <div class="metrics">
        <div class="total-products"><span>Total Products</span><p id="totalProducts"><?php echo $totalProducts; ?></p></div>
        <div class="sales-today"><span>Sales Today</span><p id="salesToday"><?php echo number_format($salesToday, 2); ?></p></div>
        <div class="total-sales"><span>Total Sales</span><p id="totalSales"><?php echo number_format($totalSales, 2); ?></p></div>
    </div>
    
    <div class="right-box">

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
  </section>

</div>

<!-- Script for the chart -->
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesData = <?php echo json_encode($salesData); ?>;
    
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesData.labels,
            datasets: [{
                label: 'Sales per Product',
                backgroundColor: salesData.colors,
                borderColor: salesData.colors,
                borderWidth: 1,
                data: salesData.data
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
</body>
</html>
