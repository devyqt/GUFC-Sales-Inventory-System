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

document.querySelectorAll('.expand-icon').forEach(function (icon) {
    icon.addEventListener('click', function () {
        var orderId = this.getAttribute('data-order-id');
        var detailsRow = document.getElementById('order-details-' + orderId);
        if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
            detailsRow.style.display = 'table-row';
            this.textContent = '-';
        } else {
            detailsRow.style.display = 'none';
            this.textContent = '+';
        }
    });
});

function deleteOrder(orderId) {
    if (confirm("Are you sure you want to delete this order?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_order.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Order deleted successfully.");
                location.reload(); // Reload page to reflect changes
            }
        };
        xhr.send("order_id=" + encodeURIComponent(orderId));
    }
}

document.getElementById('deleteSelectedOrders').addEventListener('click', function() {
    var selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
    var orderIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-order-id'));

    if (orderIds.length > 0 && confirm("Are you sure you want to delete the selected orders?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_selected_orders.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Selected orders deleted successfully.");
                location.reload(); // Reload page to reflect changes
            }
        };
        xhr.send("order_ids=" + encodeURIComponent(orderIds.join(',')));
    }
});