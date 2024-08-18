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

    // Handle delete button clicks
    document.getElementById('deleteSelected').addEventListener('click', deleteSelectedProducts);

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
            const tableBody = document.querySelector('#productTable tbody');
            tableBody.innerHTML = ''; // Clear the existing table rows

            data.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="product-checkbox" value="${product.Product_ID}"></td>
                    <td>${product.Product_ID}</td>
                    <td>${product.Product_Name}</td>
                    <td>${product.Product_Price}</td>
                    <td>${product.Product_Date}</td>
                    <td><button onclick="deleteProduct('${product.Product_ID}')">Delete</button></td>
                `;
                tableBody.appendChild(row);
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
        fetchProducts(); // Refresh the product table
    });
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('db_operations.php', {
            method: 'DELETE',
            body: JSON.stringify({ Product_ID: productId })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchProducts(); // Refresh the product table
        });
    }
}

function deleteSelectedProducts() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkboxes).map(cb => cb.value);

    if (productIds.length === 0) {
        alert('No products selected for deletion.');
        return;
    }

    if (confirm('Are you sure you want to delete the selected products?')) {
        fetch('db_operations.php', {
            method: 'DELETE',
            body: JSON.stringify({ Product_IDs: productIds })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchProducts(); // Refresh the product table
        });
    }
}

function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}