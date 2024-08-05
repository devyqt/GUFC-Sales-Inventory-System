<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HOME</title>
  <link rel="stylesheet" href="CSS/indexstyle.css" />
  <link rel="stylesheet" href="CSS/modal.css">
  <!-- FOR THE TABLE -->
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
    .sort-button, .edit-button, .delete-button {
      cursor: pointer;
      margin: 0 5px;
    }
    .edit-button {
      color: blue;
    }
    .delete-button {
      color: red;
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
          <input type="text" id="filterInput" placeholder="Search product id...">
          <button class="sort-button" onclick="sortTable()">Sort by Date</button>
          <button class="add-product-button" onclick="openModal()">Add Product</button>
        </div>
        
        <table id="coursesTable">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Product Quantity</th>
              <th>Date</th>
              <th>Actions</th> <!-- Added Actions column -->
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
      <h3>Add/Edit Product</h3>
      <br>
      <form id="addProductForm">
        <label for="productID">Product ID: &nbsp; </label> 
        <input type="text" id="productID" name="productID" required><br><br>
        <label for="productName">Product Name: &nbsp; </label>
        <input type="text" id="productName" name="productName" required><br><br>
        <label for="productQty">Quantity: &nbsp; </label>
        <input type="text" id="productQty" name="productQty" required><br><br>
        <label for="productDate">Date:</label>
        <input type="date" id="productDate" name="productDate" required><br><br>
        <input type="submit" value="Submit">
      </form>
    </div>
  </div>

      <!-- JavaScript -->
<script>
    let sortDirection = 'asc'; // Initialize sort direction

    document.addEventListener('DOMContentLoaded', () => {
        fetchProducts();

        document.getElementById('addProductForm').addEventListener('submit', function(event) {
            event.preventDefault();
            addOrUpdateProduct();
        });

        document.querySelector('.sort-button').addEventListener('click', sortTable); // Link the sort button
    });

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
                        <td>
                          <button class="edit-button" onclick="editProduct('${product.Product_ID}')">Edit</button>
                          <button class="delete-button" onclick="deleteProduct('${product.Product_ID}')">Delete</button>
                        </td>
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

    function openModal(product) {
        const modal = document.getElementById('productModal');
        if (product) {
            // Populate form fields if editing
            document.getElementById('productID').value = product.Product_ID;
            document.getElementById('productName').value = product.Product_Name;
            document.getElementById('productQty').value = product.Product_Qty;
            document.getElementById('productDate').value = product.date;
        } else {
            // Clear form fields if adding
            document.getElementById('productID').value = '';
            document.getElementById('productName').value = '';
            document.getElementById('productQty').value = '';
            document.getElementById('productDate').value = '';
        }
        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('productModal').style.display = 'none';
    }

    // Close modal only when clicking on the overlay (not the modal content)
    document.getElementById('productModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });

    function filterTable() {
        const input = document.getElementById('filterInput').value.toLowerCase();
        const table = document.getElementById('coursesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const productIdCell = cells[0].textContent.toLowerCase(); // Product_ID is in the first column
            if (productIdCell.includes(input)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
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

    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            fetch('db_operations.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ Product_ID: productId })
            })
            .then(response => response.text())
            .then(message => {
                alert(message);
                fetchProducts(); // Refresh the table
            });
        }
    }

    document.getElementById('filterInput').addEventListener('keyup', filterTable);
</script>




</body>
</html>
