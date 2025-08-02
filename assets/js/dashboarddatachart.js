document.addEventListener('DOMContentLoaded', function () {
    const monthInput = document.getElementById('monthFilter');
    const month = monthInput.value;

    function loadDashboard(month) {
        fetch(`dashboard_data.php?month=${month}`)
            .then(res => res.json())
            .then(data => {
                renderChart('revenueChart', 'Daily Revenue', data.daily.labels, data.daily.revenue);
                renderChart('monthlyChart', 'Monthly Revenue', data.monthly.labels, data.monthly.revenue);
                renderChart('expenseChart', 'Monthly Expenses', data.monthly.labels, data.monthly.expenses);
                renderChart('serviceChart', 'Service Sales', data.services.labels, data.services.sales);
            });
    }

    function renderChart(canvasId, label, labels, data) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label,
                    data,
                    backgroundColor: '#4CAF50'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    loadDashboard(month);

    monthInput.addEventListener('change', () => {
        loadDashboard(monthInput.value);
    });
});
