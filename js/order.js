// Function to open the order modal
function openOrderModal() {
    updateDropdown();
    clearModalForm();
    document.getElementById('orderModal').style.display = 'block';
}

// Function to close the order modal
function closeOrderModal() {
    document.getElementById('orderModal').style.display = 'none';
}

// Function to handle form submission via AJAX
document.getElementById('addOrderForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Collect form data
    var formData = new FormData(this);

    // Send data to order_proc.php
    fetch('order_proc.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order added successfully!');
            closeOrderModal();
            loadOrders(); // Refresh the order table
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Function to load orders into the table
function loadOrders() {
    fetch('fetch_orders.php')
        .then(response => response.text())
        .then(data => {
            document.querySelector('.order-table tbody').innerHTML = data;
            updateDropdown(); // Update dropdown after loading orders
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to update the dropdown based on selected products
function updateDropdown() {
    const itemNameDropdown = document.getElementById('itemName');

    // Fetch the list of ordered products
    fetch('get_ordered_products.php')
        .then(response => response.json())
        .then(orderedProducts => {
            // Show all options initially
            for (let i = 0; i < itemNameDropdown.options.length; i++) {
                itemNameDropdown.options[i].style.display = 'block';
            }

            // Hide options that are already ordered
            const options = itemNameDropdown.options;
            for (let i = 0; i < options.length; i++) {
                if (orderedProducts.includes(options[i].value)) {
                    options[i].style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to clear the form fields in the modal
function clearModalForm() {
    const form = document.getElementById('addOrderForm');
    form.reset(); // Reset form fields

    // Ensure dropdown is reset properly
    updateDropdown();
}

// Function to delete an order
function deleteOrder(orderId) {
    if (!confirm('Are you sure you want to delete this order?')) {
        return;
    }

    // Send the order ID to delete_orders.php
    fetch('delete_orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ orderId: orderId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order deleted successfully!');
            loadOrders(); // Refresh the order table
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to delete selected orders
document.getElementById('deleteSelectedOrders').addEventListener('click', function() {
    // Collect selected orders
    var selectedOrders = [];
    document.querySelectorAll('input[name="selectOrder"]:checked').forEach(function(checkbox) {
        var row = checkbox.closest('tr');
        var orderId = row.querySelector('td:nth-child(2)').textContent; // Adjust the index as needed
        selectedOrders.push(orderId);
    });

    if (selectedOrders.length === 0) {
        alert('No orders selected for deletion.');
        return;
    }

    // Send the selected orders to delete_orders.php
    fetch('delete_orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ orders: selectedOrders })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Selected orders deleted successfully!');
            loadOrders(); // Refresh the order table
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Function to handle status change
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('status-dropdown')) {
        const dropdown = event.target;
        const orderId = dropdown.getAttribute('data-order-id');
        const newStatus = dropdown.value;

        // Send the new status to the server
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ orderId: orderId, status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order status updated successfully!');
                loadOrders(); // Refresh the order table
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});


// Existing code...

// Function to print invoice
function printInvoice(orderID) {
    fetch(`get_order_details.php?orderID=${orderID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const invoiceContent = `
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .invoice { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; }
                            .invoice-header { text-align: center; margin-bottom: 20px; }
                            .invoice-details { margin-bottom: 20px; }
                            .invoice-item { margin-bottom: 10px; }
                            .invoice-footer { text-align: center; margin-top: 20px; }
                        </style>
                    </head>
                    <body>
                        <div class="invoice">
                            <div class="invoice-header">
                                <h1>Invoice</h1>
                                <p>Order ID: ${data.order.Order_ID}</p>
                            </div>
                            <div class="invoice-details">
                                <p><strong>Customer Name:</strong> ${data.order.Customer_Name}</p>
                                <p><strong>Order Date:</strong> ${data.order.Order_Date}</p>
                                <p><strong>Status:</strong> ${data.order.Order_Status}</p>
                            </div>
                            <div class="invoice-item">
                                <p><strong>Product ID:</strong> ${data.order.Product_ID}</p>
                                <p><strong>Product Name:</strong> ${data.order.Product_Name}</p>
                                <p><strong>Product Price:</strong> ${data.order.Product_Price}</p>
                            </div>
                            <div class="invoice-footer">
                                <button onclick="window.print()">Print</button>
                            </div>
                        </div>
                    </body>
                    </html>
                `;
                const printWindow = window.open('', '_blank');
                printWindow.document.write(invoiceContent);
                printWindow.document.close();
                printWindow.focus();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Existing code...




