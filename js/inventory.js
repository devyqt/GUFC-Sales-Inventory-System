document.addEventListener('DOMContentLoaded', () => {
    // Fetch products on page load
    fetchProducts();

    // Handle form submission
    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();
        addOrUpdateProduct();
    });

    // Handle delete button clicks
    document.getElementById('deleteSelected').addEventListener('click', deleteSelectedProducts);

    // Enforce serial number limit
    enforceSerialNumberLimit();
});

// Function to fetch products and populate the table
function fetchProducts() {
    fetch('db_operations.php') // Ensure this URL is correct
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#productTable tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Group products by name
            const groupedProducts = data.reduce((acc, product) => {
                if (!acc[product.Product_Name]) {
                    acc[product.Product_Name] = {
                        ...product,
                        total_quantity: 0
                    };
                }
                acc[product.Product_Name].total_quantity += parseInt(product.quantity, 10);
                return acc;
            }, {});

            // Create table rows for each grouped product
            Object.values(groupedProducts).forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="product-checkbox" value="${product.product_id}"></td>
                    <td>${product.Product_Name}</td>
                    <td>${product.total_quantity}</td>
                    <td>${product.Product_Date}</td>
                    <td>${product.expiration_date}</td>
                    <td>${product.status}</td>
                    <td>
                        <button onclick="viewSerials('${product.product_id}')">View Serial Numbers</button>
                        <button onclick="deleteProduct('${product.product_id}')">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error)); // Handle errors
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', fetchProducts);




// Function to view serial numbers for a product
function viewSerials(productId) {
    fetch(`db_operations.php?product_id=${productId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                const product = data[0]; // Get the first (and only) product object

                // Ensure serial_numbers is a string and handle possible null/undefined
                const serialNumbers = product.serial_numbers || '';
                const serials = serialNumbers.split(/\r?\n/).map(sn => ({ serial_number: sn.trim() })).filter(sn => sn.serial_number);

                const serialTableBody = document.getElementById('serialTableBody');
                serialTableBody.innerHTML = '';

                // Create rows for each serial number
                serials.forEach(serial => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${serial.serial_number}</td>
                        <td>${product.Product_Date || 'N/A'}</td>
                        <td>${product.expiration_date || 'N/A'}</td>
                        <td>${product.status || 'N/A'}</td>
                    `;
                    serialTableBody.appendChild(tr);
                });

                // Show the modal after populating it
                document.getElementById('serialModal').style.display = 'block';
            } else {
                // Show a user-friendly message in the modal or on the page
                document.getElementById('serialTableBody').innerHTML = '<tr><td colspan="4">No serials found for this product.</td></tr>';
                document.getElementById('serialModal').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching product serials:', error);
            // Optionally display an error message to the user
            document.getElementById('serialTableBody').innerHTML = '<tr><td colspan="4">An error occurred while fetching serial numbers. Please try again later.</td></tr>';
            document.getElementById('serialModal').style.display = 'block';
        });
}



// Function to add or update a product
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
    })
    .catch(error => console.error('Error:', error)); // Handle errors
}

// Function to delete a single product
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

// Function to delete selected products
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

// Function to open the product modal
function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

function closeSerialModal() {
    document.getElementById('serialModal').style.display = 'none'; // Close serial modal too if needed
}

// Function to close the product modal
function closeModal() {
    document.getElementById('productModal').style.display = 'none';  
}
