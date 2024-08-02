<div class="container">
    <nav>
      <ul>
        <li><a href="#" class="logo">
        <li><a href="index.php" class="logo">
          <img src="Solane Logo.png" alt="">
          <span class="nav-item">GUFC Solane</span>
        </a></li>
        <li><a href="#">
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
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["Product_ID"] . "</td>";
                    echo "<td>" . $row["Product_Name"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'></td></tr>";
            }
            ?>
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
