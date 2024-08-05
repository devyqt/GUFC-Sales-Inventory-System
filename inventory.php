<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HOME</title>
  <link rel="stylesheet" href="CSS/style2.css" />
  <link rel="stylesheet" href="CSS/modal.css">
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <style>
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
        <li><a href="index.php">
          <i class="fas fa-home"></i>
          <span class="nav-item">Home</span>
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

    <section class="main">
      <section class="main-course">
        <br>
        <h1>INVENTORY</h1>
        <div class="filter-container">
          <input type="text" id="filterInput" placeholder="Search product...">
          <button class="sort-button" onclick="sortTable()">Sort by Date</button> <button class="add-product-button" onclick="openModal()">Add Product</button>
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
              <td>012-432-325</td>
              <td>HTML</td>
              <td>2024-08-01</td>
            </tr>
            <tr>
              <td>2</td>
              <td>132-532-342</td>
              <td>2024-07-15</td>
            </tr>
            <tr>
              <td>352-322-342</td>
              <td>JavaScript</td>
              <td>2024-06-30</td>
            </tr>
          </tbody>
        </table>
      </section>
    </section>
  </div>

  <!-- Modal HTML -->
  <div id="productModal" class="modal">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <h3>Add a New Product</h3>
      <br>
      <form id="addProductForm">
        <label for="productID">Product ID: &nbsp; </label> 
        <input type="text" id="productID" name="productID" required><br><br>
        <label for="productName">Product Name: &nbsp; </label>
        <input type="text" id="productName" name="productName" required><br><br>
        <label for="productDate">Date:</label>
        <input type="date" id="productDate" name="productDate" required><br><br>
        <input type="submit" value="Submit" 
      </form>
    </div>
  </div>

  <script>
    function openModal() {
  document.getElementById('productModal').style.display = 'block';
  document.body.style.overflow = 'hidden'; // Disable scrolling on the body
}

function closeModal() {
  document.getElementById('productModal').style.display = 'none';
  document.body.style.overflow = 'auto'; // Re-enable scrolling on the body
}


    // Close modal if user clicks outside of it
    window.onclick = function(event) {
      let modal = document.getElementById('productModal');
      if (event.target == modal) {
        closeModal();
      }
    }

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
