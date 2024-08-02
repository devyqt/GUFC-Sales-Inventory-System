<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to the login page
    exit;
}
// If the user is logged in, the rest of the page will be displayed
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HOME</title>
  <link rel="stylesheet" href="CSS/style2.css" />
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <style>
    /* Add your styles here */
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    th {
      background-color: #ffff;
    }
    .filter-container {
      margin-bottom: 10px;
    }
    .filter-container input {
      padding: 5px;
      font-size: 14px;
    }
    .sort-button {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <nav>
      <ul>
        <li><a href="#" class="logo">
          <img src="images/Solane Logo.png" alt="">
          <span class="nav-item">GUFC Solane</span>
        </a></li>
        <li><a href="#">
          <i class="fas fa-home"></i>
          <span class="nav-item">Home</span>
        </a></li>
        <li><a href="">
          <i class="fas fa-user"></i>
          <span class="nav-item">INVENTORY</span>
        </a></li>
        <li><a href="logout.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nav-item">Log out</span>
        </a></li>
      </ul>
    </nav>

    <section class="main">
      

      <section class="main-course">
        <h1>My Courses</h1>
        <div class="filter-container">
          <input type="text" id="filterInput" placeholder="Search for courses...">
          <button class="sort-button" onclick="sortTable()">Sort by Date</button>
        </div>
        <table id="coursesTable">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>HTML</td>
              <td>2024-08-01</td>
            </tr>
            <tr>
              <td>2</td>
              <td>CSS</td>
              <td>2024-07-15</td>
            </tr>
            <tr>
              <td>3</td>
              <td>JavaScript</td>
              <td>2024-06-30</td>
            </tr>
          </tbody>
        </table>
      </section>
    </section>
  </div>

  <script>
    function filterTable() {
      const input = document.getElementById('filterInput').value.toLowerCase();
      const table = document.getElementById('coursesTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        for (let j = 1; j < cells.length; j++) { // Start from 1 to skip Product ID
          if (cells[j].textContent.toLowerCase().includes(input)) {
            found = true;
            break;
          }
        }
        rows[i].style.display = found ? '' : 'none';
      }
    }

    function sortTable() {
      const table = document.getElementById('coursesTable');
      const rows = Array.from(table.getElementsByTagName('tbody')[0].getElementsByTagName('tr'));
      const sortedRows = rows.sort((a, b) => {
        const dateA = new Date(a.getElementsByTagName('td')[2].textContent);
        const dateB = new Date(b.getElementsByTagName('td')[2].textContent);
        return dateA - dateB;
      });
      sortedRows.forEach(row => table.getElementsByTagName('tbody')[0].appendChild(row));
    }

    document.getElementById('filterInput').addEventListener('keyup', filterTable);
  </script>
</body>
</html>



