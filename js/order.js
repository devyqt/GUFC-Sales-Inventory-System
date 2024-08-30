    function openProductModal() {
        document.getElementById("orderModal").style.display = "block";
    }

    function closeProductModal() {
        document.getElementById("orderModal").style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("orderModal");
        if (event.target === modal) {
            closeProductModal();
        }
    }

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.key === "Escape") {
            closeProductModal();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = document.getElementById('quantity' + this.id.replace('product', ''));
                const maxQuantity = this.getAttribute('data-quantity');
                quantityInput.setAttribute('max', maxQuantity);
            });
        });
    });

    document.querySelector('.button-submit').addEventListener('click', function() {
        const formData = new FormData();
        const customerId = document.getElementById('customerType').value;
        const orderDate = document.getElementById('orderDate').value;
        
        formData.append('customerType', customerId);
        formData.append('orderDate', orderDate);
    
        const products = [];
        document.querySelectorAll('.product-checkbox:checked').forEach((checkbox, index) => {
            const productName = checkbox.nextElementSibling.querySelector('.product-name').textContent;
            const quantity = document.getElementById(`quantity${index}`).value;
            products.push({ productName, quantity });
        });
    
        formData.append('products', JSON.stringify(products));
    
        // Debugging: Check data being sent
        console.log('Form Data:', Array.from(formData.entries()));
    
        fetch('process_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            // Optionally, refresh the page or update the UI
        })
        .catch(error => console.error('Error:', error));
    });
    
    

