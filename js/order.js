// Function to open the Add Order modal
function openOrderModal() {
    const modal = document.getElementById("orderModal");
    modal.style.display = "block";
    loadProductOptions(); // Load product options when modal opens
}

// Function to close the Add Order modal
function closeOrderModal() {
    const modal = document.getElementById("orderModal");
    modal.style.display = "none";
}

// Function to load product options into the dropdown
function loadProductOptions() {
    const itemSelect = document.getElementById('itemName');

    fetch('db_operations.php') // Adjust to the correct path if necessary
        .then(response => response.json())
        .then(data => {
            itemSelect.innerHTML = ''; // Clear existing options

            if (data.length > 0) {
                data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = JSON.stringify({
                        name: product.Product_Name,
                        id: product.Product_ID,
                        price: product.Product_Price // Include price in the option value
                    });
                    option.textContent = `${product.Product_Name}`;
                    itemSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No Products';
                itemSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error fetching product data:', error);
        });
}

// Function to handle form submission
document.getElementById('addOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Get form data
    const orderID = document.getElementById('orderID').value;
    const customerName = document.getElementById('customerName').value;
    const orderDate = document.getElementById('orderDate').value;
    const itemDetails = JSON.parse(document.getElementById('itemName').value); // Parse item details from JSON

    // Prepare data for sending to the server
    const formData = new FormData();
    formData.append('orderID', orderID);
    formData.append('customerName', customerName);
    formData.append('orderDate', orderDate);
    formData.append('productID', itemDetails.id);

    // Send data to the server
    fetch('process_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Add the new row to the table
            addOrderRow(orderID, orderDate, customerName, itemDetails.name, itemDetails.price);

            // Reset form and close modal
            document.getElementById('addOrderForm').reset();
            closeOrderModal();
        } else {
            alert('Failed to add order. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Function to add a row to the order table
function addOrderRow(orderID, orderDate, customerName, itemName, itemPrice) {
    const table = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();

    // Insert cells in the row
    const selectCell = newRow.insertCell(0);
    const orderIDCell = newRow.insertCell(1);
    const orderDateCell = newRow.insertCell(2);
    const customerCell = newRow.insertCell(3);
    const itemNameCell = newRow.insertCell(4);
    const itemPriceCell = newRow.insertCell(5);
    const statusCell = newRow.insertCell(6);
    const actionCell = newRow.insertCell(7);

    // Set cell content
    selectCell.innerHTML = '<input type="checkbox" class="select-order">';
    orderIDCell.textContent = orderID;
    orderDateCell.textContent = orderDate;
    customerCell.textContent = customerName;
    itemNameCell.textContent = itemName; // Display item name
    itemPriceCell.textContent = `$${itemPrice.toFixed(2)}`; // Display item price
    statusCell.textContent = 'Pending';
    actionCell.innerHTML = '<button class="btn-delete" onclick="deleteOrder(this)">Delete</button>';
}

// Function to fetch and display orders
function loadOrders() {
    fetch('get_orders.php') // Fetch existing orders
        .then(response => response.json())
        .then(data => {
            data.forEach(order => {
                addOrderRow(order.Order_ID, order.Order_Date, order.Customer_Name, order.Product_Name, order.Product_Price);
            });
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
        });
}

// Function to delete an order from the table
function deleteOrder(button) {
    const row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

// Function to delete selected orders
document.getElementById('deleteSelectedOrders').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('.select-order:checked');
    checkboxes.forEach(checkbox => {
        checkbox.parentNode.parentNode.remove();
    });
});

// Function to close modal if clicked outside of it
window.onclick = function(event) {
    const modal = document.getElementById("orderModal");
    if (event.target === modal) {
        closeOrderModal();
    }
}

// Load orders when the page loads
window.onload = loadOrders;
