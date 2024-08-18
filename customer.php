<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>SOLANE GUFC : CUSTOMER</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/customer.css">
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
                    <th>Customers</th> 
                    <th>Register Date</th>
                    <th>Mail</th>
                    <th>Phone</th> 
                    <th>Location</th> 
                    <th>Total Order</th>
                    <th>Actions</th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#CS-00002</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar1.svg" alt="">
                            <span class="fw-bold ms-1">Francis Andrei</span>
                        </a>
                    </td>
                    <td>12/03/2021</td>
                    <td>francis123@gmail.com</td>
                    <td>202-555-0983</td>
                    <td>Sta.Mesa</td>
                    <td>18</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary"  data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CS-00006</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar2.svg" alt="">
                            <span class="fw-bold ms-1">Mia Carryl</span>
                        </a>
                    </td>
                    <td>12/03/2021</td>
                    <td>mia123@gmail.com</td>
                    <td>303-555-0151</td>
                    <td>Marikina</td>
                    <td>4568</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CS-00004</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar3.svg" alt="">
                            <span class="fw-bold ms-1">Marc Plarisan</span>
                        </a>
                    </td>
                    <td>16/03/2021</td>
                    <td>marc123@gmail.com</td>
                    <td>843-555-0175</td>
                    <td>Taguig</td>
                    <td>05</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CS-00008</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar4.svg" alt="">
                            <span class="fw-bold ms-1">El Cherry</span>
                        </a>
                    </td>
                    <td>25/02/2021</td>
                    <td>cherry123@gmail.com</td>
                    <td>404-555-0100</td>
                    <td>Saan ba</td>
                    <td>14</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CS-00018</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar5.svg" alt="">
                            <span class="fw-bold ms-1">Sir Ely</span>
                        </a>
                    </td>
                    <td>16/02/2021</td>
                    <td>ely123@gmail.com</td>
                    <td>502-555-0118</td>
                    <td>not sure</td>
                    <td>03</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CS-00014</strong></td>
                    <td>
                        <a href="customer-detail.html">
                            <img class="avatar rounded" src="assets/images/xs/avatar6.svg" alt="">
                            <span class="fw-bold ms-1">Rommel Acob</span>
                        </a>
                    </td>
                    <td>18/01/2021</td>
                    <td>rommel123@gmail.com</td>
                    <td>502-555-0133</td>
                    <td>Marikina</td>
                    <td>02</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#expedit">
                                <i class="icofont-edit text-success"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary deleterow">
                                <i class="icofont-ui-delete text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
