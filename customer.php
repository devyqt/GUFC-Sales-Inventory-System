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
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>SOLANE GUFC : CUSTOMER</title>
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CSS/customer.css?v=<?php echo time(); ?>">
    <!-- Font Awesome Cdn Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
  <?php include 'navbar.html'; ?>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Customers Information</h3>
        <button type="button" class="btn btn-primary btn-set-task" data-bs-toggle="modal" data-bs-target="#expadd">
            <i class="icofont-plus-circle me-2 fs-6"></i>Add Customer
        </button>
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
                            <a href="customer-detail.html">
                                <span class="fw-bold ms-1"><?php echo htmlspecialchars($customer['Customer_Name']); ?></span>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($customer['Address']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Person']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Position_of_Contact_Person']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Number']); ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <button type="button" class="btn btn-outline-secondary deleterow">
                                    <i class="icofont-ui-delete text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
