document.addEventListener("DOMContentLoaded", function () {
    const monthInput = document.getElementById("monthFilter");
    const currentMonth = new Date().toISOString().slice(0, 7);
    monthInput.value = currentMonth;

    monthInput.addEventListener("change", () => loadCharts(monthInput.value));
    loadCharts(currentMonth);
});

function loadCharts(month) {
    fetch(`dashboard_data.php?month=${month}`)
        .then((res) => res.json())
        .then((data) => {
            renderBarChart('revenueChart', "Last 7 Days Revenue", data.daily.labels, data.daily.revenue);
            renderLineChart('monthlyChart', "Monthly Revenue", data.monthly.labels, data.monthly.revenue);
            renderBarChart('expenseChart', "Monthly Expenses", data.monthly.labels, data.monthly.expenses);
            renderPieChart('serviceChart', "Service Sales", data.services.labels, data.services.sales);
        });
}

function renderBarChart(id, label, labels, data) {
    new Chart(document.getElementById(id).getContext("2d"), {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: "#4CAF50"
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

function renderLineChart(id, label, labels, data) {
    new Chart(document.getElementById(id).getContext("2d"), {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: "#007bff",
                fill: false
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

function renderPieChart(id, label, labels, data) {
    new Chart(document.getElementById(id).getContext("2d"), {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0']
            }]
        },
        options: { responsive: true }
    });
}
