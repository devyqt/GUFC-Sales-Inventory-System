<?php
// Include your database connection file
include 'db_connection.php';

// Fetch customer data from the database
$sql = "SELECT Customer_ID, Customer_Name, Address, Contact_Person, Position_of_Contact_Person, Contact_Number FROM customer_table";
$result = $conn->query($sql);

// Initialize an empty array to store customer data
$customers = [];    

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SOLANE GUFC : CUSTOMER</title>
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/customer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/settings.css?v=<?php echo time(); ?>">
    <!-- Font Awesome Cdn Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
       
    </style>
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
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>
</div>


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Customers Information</h3>
        <button id="openModal" type="button" class="btn btn-primary btn-set-task">
            <i class="icofont-plus-circle me-2 fs-6"></i>Add Customer
        </button>
    </div>

    <!-- Add Customer Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Add New Customer</h2>
            </div>
            <div class="modal-body">
                <form id="addCustomerForm" action="add_customer.php" method="POST">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text-box" class="form-control" id="customerName" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="customerAddress" name="customer_address" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactPerson" class="form-label">Contact Person</label>
                        <input type="text" class="form-control" id="contactPerson" name="contact_person" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contact_number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table id="myProjectTable" class="table table-hover align-middle mb-0" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Contact Person</th>
                    <th>Position</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><strong>#<?php echo str_pad($customer['Customer_ID'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                        <td>
                            <a href="customer-detail.php?id=<?php echo $customer['Customer_ID']; ?>">
                                <span class="fw-bold ms-1"><?php echo htmlspecialchars($customer['Customer_Name']); ?></span>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($customer['Address']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Person']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Position_of_Contact_Person']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Number']); ?></td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <!-- Edit Button -->
                                <a href="edit_customer.php?id=<?php echo $customer['Customer_ID']; ?>" class="btn btn-outline-secondary me-2" style="background:none; border:none;">
                                    <img src="images/editCustomer.png" alt="Edit" style="width:18px; height:18px;"/>
                                </a>
                                <!-- Delete Button -->
                                <form action="delete_customer.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['Customer_ID']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary" style="background:none; border:none;">
                                        <img src="images/deleteCustomer.png" alt="Delete" style="width:18px; height:18px;"/>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="js/settings.js"></script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openModal");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // Get the close button
    var closeBtn = document.getElementById("closeModal");

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks on the close button, close the modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>

