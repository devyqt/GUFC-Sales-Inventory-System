document.addEventListener('DOMContentLoaded', () => {
    // Fetch products and quantities on page load
    fetchProducts();
    fetchQuantities();

    function fetchQuantities() {
        fetch('fetch_quantities.php')
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log data to check its format
                document.getElementById('first-total').textContent = data['11kg Auto-Shutoff Cylinder'] || 0;
                document.getElementById('second-total').textContent = data['11kg POL Cylinder'] || 0;
                document.getElementById('third-total').textContent = data['1.4kg Solane Sakto'] || 0;
                document.getElementById('fourth-total').textContent = data['22kg POL Cylinder'] || 0;
                document.getElementById('fifth-total').textContent = data['50kg Cylinder'] || 0;
                document.getElementById('sixth-total').textContent = data['POL Regulator'] || 0;
                document.getElementById('seventh-total').textContent = data['AS Regulator'] || 0;
                document.getElementById('eight-total').textContent = data['Hose with Clamps'] || 0;
            })
            .catch(error => console.error('Error fetching quantities:', error));
    }
    

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

// Function to toggle product options based on the selected product type
function toggleProductOptions() {
    const productType = document.getElementById('productType').value;
    const productName = document.getElementById('productName');
    const hoseLengthGroup = document.getElementById('hoseLengthGroup');

    // Clear existing product options and hide hose length input group by default
    productName.innerHTML = '';
    hoseLengthGroup.style.display = 'none';

    // Populate the product name options based on the selected product type
    if (productType === 'cylinder') {
        const cylinderOptions = [
            '11kg Auto-Shutoff Cylinder',
            '11kg POL Cylinder',
            '50kg Cylinder',
            '1.4kg Solane Sakto',
            '22kg POL Cylinder'
        ];

        cylinderOptions.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option;
            opt.textContent = option;
            productName.appendChild(opt);
        });
    } else if (productType === 'non-cylinder') {
        const nonCylinderOptions = [
            'Hose with Clamps',
            'AS Regulator',
            'POL Regulator'
        ];

        nonCylinderOptions.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option;
            opt.textContent = option;
            productName.appendChild(opt);
        });
    }

    // Show hose length input field if "Hose with Clamps" is selected
    productName.addEventListener('change', () => {
        if (productName.value === 'Hose with Clamps') {
            hoseLengthGroup.style.display = 'block';
        } else {
            hoseLengthGroup.style.display = 'none';
        }
    });
}

// Function to update the product price based on the selected product and hose length
function updateProductPrice() {
    const productName = document.getElementById('productName').value;
    const productPriceInput = document.getElementById('productPrice');
    const hoseLengthInput = document.getElementById('hoseLength');

    const fixedPrices = {
        '1.4kg Solane Sakto': 170,
        '11kg Auto-Shutoff Cylinder': 1025,
        '11kg POL Cylinder': 1025,
        'POL Regulator': 570,
        'AS Regulator': 570,
        'Hose with Clamps': 280,
        '22kg POL Cylinder': 2100,
        '50kg Cylinder': 5890
    };

    // Update the price based on the selected product
    if (fixedPrices[productName] !== undefined) {
        if (productName === 'Hose with Clamps') {
            // Calculate price based on hose length if applicable
            const lengthInMeters = parseFloat(hoseLengthInput.value) || 0;
            productPriceInput.value = (fixedPrices['Hose with Clamps'] + (100 * lengthInMeters)).toFixed(2);  // Adjust price calculation
        } else {
            productPriceInput.value = fixedPrices[productName].toFixed(2);
        }
    } else {
        productPriceInput.value = '';
    }
}

// Initialize event listeners
document.getElementById('productType').addEventListener('change', toggleProductOptions);
document.getElementById('productName').addEventListener('change', updateProductPrice);
document.getElementById('hoseLength').addEventListener('input', updateProductPrice);

function fetchProducts() {
    fetch('db_operations.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#productTable tbody');
            tableBody.innerHTML = '';

            data.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="product-checkbox" value="${product.product_id}"></td>
                    <td>${product.product_id}</td>
                    <td>${product.Product_Name}</td>
                    <td>${product.Product_Date}</td>
                    <td>${product.expiration_date}</td>
                    <td>${product.status}</td>
                    <td><button onclick="deleteProduct('${product.product_id}')">Delete</button></td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching products:', error)); // Handle fetch errors
}

function addOrUpdateProduct() {
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);

    const productIDs = document.getElementById('productIDs').value.split(/\s*,\s*|\s*\n\s*/);
    productIDs.forEach((id, index) => formData.append(`productIDs[${index}]`, id));

    fetch('db_operations.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(message => {
        alert(message);
        form.reset();
        closeModal();
        fetchProducts();
    })
    .catch(error => console.error('Error adding/updating product:', error)); // Handle fetch errors
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
        .catch(error => console.error('Error deleting product:', error)); // Handle errors
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
        .catch(error => console.error('Error deleting selected products:', error)); // Handle errors
    }
}

function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}
