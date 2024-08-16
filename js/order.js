// JavaScript for managing the Orders page

// Function to open the Add Order modal
function openOrderModal() {
    const modal = document.getElementById("orderModal");
    modal.style.display = "block";
}

// Function to close the Add Order modal
function closeOrderModal() {
    const modal = document.getElementById("orderModal");
    modal.style.display = "none";
}

// Function to handle tab switching
document.querySelectorAll('.tab-link').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        // Remove active class from all tabs
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.classList.remove('active');
        });
        // Add active class to the clicked tab
        this.classList.add('active');

        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });

        // Show the selected tab pane
        const targetPane = document.querySelector(this.getAttribute('href'));
        targetPane.classList.add('active');
    });
});

// Function to add a new order to the table
document.getElementById('addOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Get form data
    const orderID = document.getElementById('orderID').value;
    const customerName = document.getElementById('customerName').value;
    const orderDate = document.getElementById('orderDate').value;

    // Create a new row in the table
    const table = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();

    // Insert cells in the row
    const selectCell = newRow.insertCell(0);
    const orderIDCell = newRow.insertCell(1);
    const orderDateCell = newRow.insertCell(2);
    const customerCell = newRow.insertCell(3);
    const statusCell = newRow.insertCell(4);
    const actionCell = newRow.insertCell(5);

    // Set cell content
    selectCell.innerHTML = '<input type="checkbox" class="select-order">';
    orderIDCell.textContent = orderID;
    orderDateCell.textContent = orderDate;
    customerCell.textContent = customerName;
    statusCell.textContent = 'Pending';
    actionCell.innerHTML = '<button class="btn-edit" onclick="editOrder(this)">Edit</button> <button class="btn-delete" onclick="deleteOrder(this)">Delete</button>';

    // Reset form and close modal
    document.getElementById('addOrderForm').reset();
    closeOrderModal();
});

// Function to edit an existing order
function editOrder(button) {
    const row = button.parentNode.parentNode;
    const orderID = row.cells[1].textContent;
    const orderDate = row.cells[2].textContent;
    const customerName = row.cells[3].textContent;

    // Populate the modal with existing data
    document.getElementById('orderID').value = orderID;
    document.getElementById('customerName').value = customerName;
    document.getElementById('orderDate').value = orderDate;

    // Remove the row after editing is complete
    row.parentNode.removeChild(row);

    // Open the modal for editing
    openOrderModal();
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
    if (event.target == modal) {
        closeOrderModal();
    }
}
