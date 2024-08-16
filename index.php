<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SOLANE GUFC</title>
  <link rel="stylesheet" href="CSS/style.css">
  <link rel="stylesheet" href="CSS/indexstyle.css">
  <link rel="stylesheet" href="CSS/modal.css">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
  <?php include 'navbar.html'; ?>
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
      <!-- The canvas element for the chart -->
      <canvas id="salesChart"></canvas>
    </div>
  </section>
</div>

<!-- Script for the chart -->
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: '11kg Auto-Shutoff Cylinder',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    data: [160, 120, 90, 70, 80, 160, 100, 85, 90, 130, 90, 60]
                },
                {
                    label: '1.4kg Solane Sakto',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1,
                    data: [100, 90, 70, 80, 50, 70, 60, 90, 60, 70, 80, 50]
                },
                {
                    label: '22kg POL Cylinder',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    data: [70, 60, 50, 40, 60, 80, 70, 50, 80, 60, 70, 40]
                },
                {
                    label: '50kg Cylinder',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    data: [50, 40, 60, 50, 70, 90, 60, 50, 40, 50, 60, 50]
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script src="js/dashboard.js"></script>
</body>
</html>
