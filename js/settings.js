function toggleDropdown() {
    var dropdown = document.getElementById("settingsDropdown");
    if (dropdown.style.display === "none" || dropdown.style.display === "") {
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
}

// Optional: Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.settings-icon img')) {
        var dropdowns = document.getElementsByClassName("settings-dropdown");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
}