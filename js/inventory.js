document.addEventListener('DOMContentLoaded', () => {
    // Fetch products on page load
    fetchProducts();

    // Handle form submission
    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();
        addOrUpdateProduct();
    });

    // Handle tab switching
    const tabs = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            // Remove active class from all tabs and tab panes
            tabs.forEach(t => t.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to the clicked tab and corresponding pane
            tab.classList.add('active');
            const targetPane = document.querySelector(tab.getAttribute('href'));
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });

    // Filter table rows
    document.getElementById('filterInput').addEventListener('keyup', filterTable);

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === document.getElementById('productModal')) {
            closeModal();
        }
    };
});

function fetchProducts() {
    fetch('db_operations.php')
        .then(response => response.json())
        .then(data => {
            // Assuming you have some method to display products if not using a table
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
        fetchProducts(); // Refresh the product display
    });
}

function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function filterTable() {
    const input = document.getElementById('filterInput').value.toLowerCase();
    const table = document.getElementById('coursesTable'); // Adjust if necessary
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        const productIdCell = cells[1].textContent.toLowerCase(); // Adjust index if necessary
        if (productIdCell.includes(input)) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}
