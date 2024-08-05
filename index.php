<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HOME</title>
  <link rel="stylesheet" href="CSS/indexstyle.css" />
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
        <h1>DASHBOARD</h1>
        <div class="filter-container">
          <input type="text" id="filterInput" placeholder="Search product ID...">
          <button class="sort-button" onclick="sortTable()">Sort by Date</button> 
          <button class="add-product-button" onclick="openModal()">View Sales</button>
        </div>
        
        <table id="coursesTable">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Product Quantity</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <!-- Rows will be dynamically added here -->
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
        <input type="submit" value="Submit">
      </form>
    </div>
  </div>

  <script>
    let sortDirection = 'asc'; // Initialize sort direction

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

    function fetchProducts() {
      fetch('db_operations.php')
        .then(response => response.json())
        .then(data => {
          const tbody = document.querySelector('#coursesTable tbody');
          tbody.innerHTML = ''; // Clear existing rows
          data.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${product.Product_ID}</td>
              <td>${product.Product_Name}</td>
              <td>${product.Product_Qty}</td>
              <td>${product.date}</td>
            `;
            tbody.appendChild(row);
          });
        });
    }

    function addOrUpdateProduct() {
      const form = document.getElementById('addProductForm');
      const formData = new FormData(form);
      
      fetch('db_operations.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(message => {
        alert(message);
        form.reset(); // Clear the form fields
        closeModal(); // Close the modal
        fetchProducts(); // Refresh the table
      });
    }

    function filterTable() {
      const input = document.getElementById('filterInput').value.toLowerCase();
      const table = document.getElementById('coursesTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        const productIdCell = cells[0].textContent.toLowerCase(); // Product_ID is in the first column
        rows[i].style.display = productIdCell.includes(input) ? '' : 'none';
      }
    }

    function sortTable() {
      const table = document.getElementById('coursesTable');
      const tbody = table.getElementsByTagName('tbody')[0];
      const rows = Array.from(tbody.getElementsByTagName('tr'));

      rows.sort((a, b) => {
        const dateA = new Date(a.getElementsByTagName('td')[3].textContent);
        const dateB = new Date(b.getElementsByTagName('td')[3].textContent);

        return sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
      });

      sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';

      rows.forEach(row => tbody.appendChild(row));
    }

    document.getElementById('filterInput').addEventListener('keyup', filterTable);

    // Initial fetch
    fetchProducts();
  </script>
</body>
</html>
