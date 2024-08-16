document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('salesChart').getContext('2d');

    var salesChart = new Chart(ctx, {
        type: 'line', // or 'bar', 'pie', etc.
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // Replace with your labels
            datasets: [{
                label: 'Sales',
                data: [120, 150, 180, 220, 300, 250, 320], // Replace with your data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
