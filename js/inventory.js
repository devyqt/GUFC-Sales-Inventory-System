document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();

    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();
        addOrUpdateProduct();
    });

    document.getElementById('deleteSelected').addEventListener('click', deleteSelectedProducts);
    enforceSerialNumberLimit();
});

function fetchProducts() {
    fetch('db_operations.php')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched data:', data); // Ensure this logs the correct data
            
            const quantities = {
                '11kg Auto-Shutoff Cylinder': 0,
                '11kg POL Cylinder': 0,
                '1.4kg Solane Sakto': 0,
                '22kg POL Cylinder': 0,
                '50kg Cylinder': 0,
                'POL Regulator': 0,
                'AS Regulator': 0,
                'Hose with Clamps': 0
            };

            data.forEach(product => {
                if (quantities.hasOwnProperty(product.Product_Name)) {
                    quantities[product.Product_Name] += parseInt(product.quantity, 10);
                }
            });

            console.log('Aggregated quantities:', quantities); // Ensure this logs correct quantities

            // Update the HTML with the quantities
            document.getElementById('first-total').textContent = quantities['11kg Auto-Shutoff Cylinder'];
            document.getElementById('second-total').textContent = quantities['11kg POL Cylinder'];
            document.getElementById('third-total').textContent = quantities['1.4kg Solane Sakto'];
            document.getElementById('fourth-total').textContent = quantities['22kg POL Cylinder'];
            document.getElementById('fifth-total').textContent = quantities['50kg Cylinder'];
            document.getElementById('sixth-total').textContent = quantities['POL Regulator'];
            document.getElementById('seventh-total').textContent = quantities['AS Regulator'];
            document.getElementById('eight-total').textContent = quantities['Hose with Clamps'];

            populateProductTable(data); // Populate the table with the fetched data
        })
        .catch(error => console.error('Error:', error));
}

function populateProductTable(products) {
    const tbody = document.querySelector('#productTable tbody');
    tbody.innerHTML = ''; // Clear any existing rows

    products.forEach(product => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td><input type="checkbox" class="product-checkbox" value="${product.product_id}"></td>
            <td>${product.Product_Name}</td>
            <td>${product.quantity}</td>
            <td>${product.Product_Date}</td>
            <td>${product.expiration_date}</td>
            <td>${product.status}</td>
            <td>${product.serial_number}</td>
            <td>
                <button onclick="editProduct(${product.product_id})">EDIT</button>
                <button onclick="deleteProductBySerial('${product.serial_number}')">DELETE</button>
            </td>
        `;

        tbody.appendChild(row);
    });
}

function openSortModal() {
    document.getElementById("sortModal").style.display = "block";
}

function closeSortModal() {
    document.getElementById("sortModal").style.display = "none";
}

// Close the modal if user clicks outside the modal content
window.onclick = function(event) {
    var modal = document.getElementById("sortModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function toggleProductOptions() {
    const productType = document.getElementById('productType').value;
    const productName = document.getElementById('productName');
    const hoseLengthGroup = document.getElementById('hoseLengthGroup');

    productName.innerHTML = '';
    hoseLengthGroup.style.display = 'none';

    if (productType === 'cylinder') {
        const cylinderOptions = [
            '',
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
            '',
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
}

document.getElementById('productName').addEventListener('change', () => {
    const selectedProduct = document.getElementById('productName').value;
    const hoseLengthGroup = document.getElementById('hoseLengthGroup');

    if (selectedProduct === 'Hose with Clamps') {
        hoseLengthGroup.style.display = 'block';
    } else {
        hoseLengthGroup.style.display = 'none';
    }

    updateProductPrice(); // Update price when product changes
});

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

    if (fixedPrices[productName] !== undefined) {
        if (productName === 'Hose with Clamps') {
            const lengthInMeters = parseFloat(hoseLengthInput.value) || 0;
            productPriceInput.value = fixedPrices['Hose with Clamps'] + (100 * lengthInMeters);  // Adjust price calculation
        } else {
            productPriceInput.value = fixedPrices[productName];
        }
    } else {
        productPriceInput.value = '';
    }
}


function updateExpirationDate() {
    const productType = document.getElementById('productType').value;
    const productDateInput = document.getElementById('productDate');
    const expirationDateInput = document.getElementById('expirationDate');

    if (productType === 'cylinder' && productDateInput.value) {
        const productDate = new Date(productDateInput.value);
        const expirationDate = new Date(productDate);
        expirationDate.setFullYear(expirationDate.getFullYear() + 3); // Add 3 years

        // Format expiration date to yyyy-mm-dd
        const year = expirationDate.getFullYear();
        const month = String(expirationDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const day = String(expirationDate.getDate()).padStart(2, '0');

        expirationDateInput.value = `${year}-${month}-${day}`;
    } else if (productType === 'non-cylinder') {
        expirationDateInput.value = 'N/A'; // Set expiration date to N/A for non-cylinders
    } else {
        expirationDateInput.value = ''; // Clear expiration date if product type is not selected
    }
}

// Ensure the modal is ready with the correct options on load
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('productType').addEventListener('change', toggleProductOptions);
    document.getElementById('productDate').addEventListener('change', updateExpirationDate);
});

function enforceSerialNumberLimit() {
    const quantityInput = document.getElementById('productQuantity');
    const serialNumbersTextarea = document.getElementById('serialNumbers');
    const serialError = document.getElementById('serialError');
    
    function checkSerialNumberLimit() {
        const maxQuantity = parseInt(quantityInput.value, 10);
        const serialNumbers = serialNumbersTextarea.value.split('\n').map(sn => sn.trim()).filter(sn => sn.length > 0);

        // Count valid serial numbers
        let count = 0;
        serialNumbers.forEach(sn => {
            // Check if the serial number includes hose length
            if (sn.includes(' - ')) {
                count++;
            }
        });

        if (count > maxQuantity) {
            serialError.style.display = 'block';
            serialNumbersTextarea.value = serialNumbers.slice(0, maxQuantity).join('\n');
        } else {
            serialError.style.display = 'none';
        }
    }

    quantityInput.addEventListener('input', checkSerialNumberLimit);
    serialNumbersTextarea.addEventListener('input', checkSerialNumberLimit);
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
        form.reset();
        closeModal();
        fetchProducts();
    })
    .catch(error => console.error('Error:', error));
}

function deleteProductBySerial(serialNumber) {
    if (confirm(`Are you sure you want to delete the product with serial number ${serialNumber}?`)) {
        fetch('db_operations.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ serial_number: serialNumber })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchProducts(); // Refresh the table after deletion
        })
        .catch(error => console.error('Error:', error));
    }
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
            fetchProducts();
        })
        .catch(error => console.error('Error:', error));
    }
}

function deleteSelectedProducts() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkboxes).map(checkbox => checkbox.value);

    if (productIds.length === 0) {
        alert('No products selected.');
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
            fetchProducts(); // Refresh the table after deletion
        })
        .catch(error => console.error('Error:', error));
    }
}

function applyFilters() {
    const serialNum = document.getElementById('serialNum').value.toLowerCase();
    const dateOrder = document.querySelector('input[name="dateOrder"]:checked')?.value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    const table = document.getElementById('productTable');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    console.log('Serial Number Filter:', serialNum);
    console.log('Date Order:', dateOrder);
    console.log('Start Date:', startDate);
    console.log('End Date:', endDate);

    // Filter rows
    const filteredRows = rows.filter(row => {
        const serialNumber = row.cells[6].textContent.toLowerCase();
        const productDate = row.cells[3].textContent;

        console.log('Row Serial Number:', serialNumber);
        console.log('Row Product Date:', productDate);

        let showRow = true;

        if (serialNum && !serialNumber.includes(serialNum)) {
            showRow = false;
        }

        if (startDate && endDate) {
            const productDateObj = new Date(productDate);
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(endDate);

            if (productDateObj < startDateObj || productDateObj > endDateObj) {
                showRow = false;
            }
        }

        return showRow;
    });

    console.log('Filtered Rows:', filteredRows);

    // Sort rows by date
    if (dateOrder) {
        filteredRows.sort((a, b) => {
            const dateA = new Date(a.cells[3].textContent);
            const dateB = new Date(b.cells[3].textContent);
            return dateOrder === 'ascending' ? dateA - dateB : dateB - dateA;
        });
    }

    console.log('Sorted Rows:', filteredRows);

    // Update table with filtered and sorted rows
    const tbody = table.querySelector('tbody');
    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row));

    closeSortModal();
}




function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

