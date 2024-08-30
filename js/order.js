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
