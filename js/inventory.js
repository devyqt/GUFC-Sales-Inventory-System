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

// Function to enforce serial number input limit
function enforceSerialNumberLimit() {
    const quantityInput = document.getElementById('productQuantity');
    const serialNumbersTextarea = document.getElementById('serialNumbers');
    const serialError = document.getElementById('serialError');
    
    quantityInput.addEventListener('input', () => {
        const maxQuantity = parseInt(quantityInput.value, 10);
        const serialNumbers = serialNumbersTextarea.value.split('\n').map(sn => sn.trim()).filter(sn => sn.length > 0);

        if (serialNumbers.length > maxQuantity) {
            serialError.style.display = 'block';
        } else {
            serialError.style.display = 'none';
        }
    });

    serialNumbersTextarea.addEventListener('input', () => {
        const maxQuantity = parseInt(quantityInput.value, 10);
        const serialNumbers = serialNumbersTextarea.value.split('\n').map(sn => sn.trim()).filter(sn => sn.length > 0);

        if (serialNumbers.length > maxQuantity) {
            serialError.style.display = 'block';
            serialNumbersTextarea.value = serialNumbers.slice(0, maxQuantity).join('\n');
        } else {
            serialError.style.display = 'none';
        }
    });
}

// Call this function to initialize serial number limit enforcement
enforceSerialNumberLimit();

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
                    <td>${product.Product_Name}</td>
                    <td>${product.quantity}</td>
                    <td>${product.Product_Date}</td>
                    <td>${product.expiration_date}</td>
                    <td>${product.status}</td>
                    <td><button onclick="deleteProduct('${product.Product_ID}')">Delete</button></td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error)); // Handle errors
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
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchProducts(); // Refresh the product table
        })
        .catch(error => console.error('Error:', error)); // Handle errors
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
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_ids: productIds })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchProducts(); // Refresh the product table
        })
        .catch(error => console.error('Error:', error)); // Handle errors
    }
}

function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}
