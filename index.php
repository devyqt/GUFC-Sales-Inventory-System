<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SOLANE GUFC</title>
  <link rel="stylesheet" href="CSS/indexstyle.css">
  <link rel="stylesheet" href="CSS/modal.css">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <nav>
      <ul>
        <li><a href="#" class="logo">
          <img src="images/Solane Logo.png" alt="">
          <span class="nav-item">GUFC Solane</span>
        </a></li>
        <li><a href="index.php">
          <i class="fas fa-home"></i>
          <span class="nav-item">DASHBOARD</span>
        </a></li>
        <li><a href="inventory.php">
          <i class="fas fa-user"></i>
          <span class="nav-item">INVENTORY</span>
        </a></li>
        <li><a href="logout.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nav-item">Log out</span>
        </a></li>
      </ul>
    </nav>
    <section class="dashboard">
      <h2>DASHBOARD</h2>
      <br>
      <div class="metrics">
        <div class="total-products"><span>Total Products</span><p>1,202</p></div>
        <div class="out-of-stock"><span>Out of Stock</span><p>67</p></div>
        <div class="sales-today"><span>Sales Today</span><p>234</p></div>
        <div class="total-sales"><span>Total Sales</span><p>803</p></div>
      </div>
      <div class="sales-overview">
        <h3>Sales Overview</h3>
        <!-- Placeholder for chart -->
      </div>
    </section>
  </div>
  <script src="js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <canvas id="salesChart"></canvas>
</body>
</html>
