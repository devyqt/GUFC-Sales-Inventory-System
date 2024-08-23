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

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.expand-icon').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var orderId = this.getAttribute('data-order-id');
            var detailsRow = document.getElementById('order-details-' + orderId);
            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                detailsRow.style.display = 'table-row';
                this.innerHTML = '-';
            } else {
                detailsRow.style.display = 'none';
                this.innerHTML = '+';
            }
        });
    });
});

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        window.location.href = 'delete_order.php?order_id=' + orderId;
    }
}

document.getElementById('deleteSelectedOrders').addEventListener('click', function() {
    var selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(checkbox => checkbox.getAttribute('data-order-id'));
    if (selectedOrders.length > 0 && confirm('Are you sure you want to delete selected orders?')) {
        window.location.href = 'delete_order.php?order_ids=' + selectedOrders.join(',');
    }
});

document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.key === "Escape") {
        closeProductModal();
    }
};
