<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$today = date('Y-m-d');
$formattedDate = date('F j, Y');

// Revenue data
$payments = $conn->query("
    SELECT 
        SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END) AS cash,
        SUM(CASE WHEN payment_method = 'mpesa' THEN amount ELSE 0 END) AS mpesa,
        SUM(amount) AS total,
        (SELECT SUM(amount) FROM payments WHERE DATE(paid_at) = DATE_SUB('$today', INTERVAL 1 DAY)) AS yesterday_total
    FROM payments
    WHERE DATE(paid_at) = '$today'
")->fetch_assoc();

// Calculate percentage change
$percentageChange = 0;
if ($payments['yesterday_total'] > 0) {
    $percentageChange = (($payments['total'] - $payments['yesterday_total']) / $payments['yesterday_total'] * 100);
}

// Appointments data
$appointments = $conn->query("
    SELECT 
        COUNT(*) AS total,
        (SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = DATE_SUB('$today', INTERVAL 1 DAY)) AS yesterday_total
    FROM appointments
    WHERE DATE(appointment_date) = '$today'
")->fetch_assoc();

// Clients data
$clients = $conn->query("
    SELECT 
        COUNT(*) AS total,
        (SELECT COUNT(*) FROM clients WHERE DATE(created_at) = DATE_SUB('$today', INTERVAL 1 DAY)) AS new_today,
        (SELECT COUNT(*) FROM clients WHERE DATE(created_at) = DATE_SUB('$today', INTERVAL 7 DAY)) AS new_week
    FROM clients
")->fetch_assoc();

// Top performing staff
$topStaff = $conn->query("
    SELECT s.full_name, SUM(p.amount) as sales, COUNT(a.id) as appointments
    FROM staff s
    JOIN appointments a ON a.staff_id = s.id
    JOIN payments p ON p.appointment_id = a.id
    WHERE DATE(p.paid_at) = '$today'
    GROUP BY s.id
    ORDER BY sales DESC
    LIMIT 5
");
?>
<main class="dashboard-container">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashbordindex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <h1 class="dashboard-heading">
        <i class="fas fa-chart-line"></i> Dashboard Overview
        <span class="date-badge"><?= $formattedDate ?></span>
    </h1>

    <!-- Dashboard Controls -->
    <div class="dashboard-controls">
        <div class="filter-group">
            <span class="filter-label"><i class="fas fa-filter"></i> Filter:</span>
            <input type="month" id="monthFilter" class="filter-select" value="<?= date('Y-m') ?>">
            <select class="filter-select">
                <option>All Services</option>
                <option>Massage</option>
                <option>Facial</option>
                <option>Manicure</option>
            </select>
        </div>
        <div class="action-buttons">
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <h4>Total Revenue</h4>
            <p class="metric-value currency">KES <?= number_format($payments['total'] ?? 0, 2) ?></p>
            <div class="metric-change <?= $percentageChange >= 0 ? 'positive' : 'negative' ?>">
                <i class="fas fa-arrow-<?= $percentageChange >= 0 ? 'up' : 'down' ?>"></i>
                <?= number_format(abs($percentageChange), 2) ?>% from yesterday
            </div>
        </div>

        <div class="metric-card">
            <h4>Today's Appointments</h4>
            <p class="metric-value"><?= $appointments['total'] ?? 0 ?></p>
            <div class="metric-change">
                <i class="fas fa-calendar-check"></i>
                <?= ($appointments['yesterday_total'] ?? 0) > 0 ?
                    round(($appointments['total'] - $appointments['yesterday_total']) / $appointments['yesterday_total'] * 100, 2) : '0' ?>% change
            </div>
        </div>

        <div class="metric-card">
            <h4>Payment Methods</h4>
            <p class="metric-value currency">Cash: KES <?= number_format($payments['cash'] ?? 0, 2) ?></p>
            <p class="metric-value currency">M-Pesa: KES <?= number_format($payments['mpesa'] ?? 0, 2) ?></p>
        </div>

        <div class="metric-card">
            <h4>Client Base</h4>
            <p class="metric-value"><?= $clients['total'] ?? 0 ?> Total</p>
            <div class="metric-change">
                <i class="fas fa-user-plus"></i>
                <?= $clients['new_today'] ?? 0 ?> new today
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="top-performers">
        <h2 class="section-title">
            <i class="fas fa-trophy"></i> Top Performing Staff (Today)
        </h2>

        <?php if ($topStaff && $topStaff->num_rows > 0): ?>
            <div class="staff-list">
                <?php $rank = 1; ?>
                <?php while ($staff = $topStaff->fetch_assoc()): ?>
                    <div class="staff-card">
                        <div class="staff-avatar"><?= substr($staff['full_name'], 0, 1) ?></div>
                        <div class="staff-info">
                            <div class="staff-name"><?= htmlspecialchars($staff['full_name']) ?></div>
                            <div class="staff-sales">
                                <?= $staff['appointments'] ?> appointments •
                                <span>KES <?= number_format($staff['sales'], 2) ?></span>
                            </div>
                        </div>
                        <div class="staff-rank">#<?= $rank++ ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #666;">No staff performance data available for today</p>
        <?php endif; ?>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="fas fa-chart-bar"></i> Revenue Analytics</h3>
            </div>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Service Distribution</h3>
            </div>
            <canvas id="serviceChart" height="300"></canvas>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../assets/js/charts.js"></script>

<?php include('../../includes/footer.php'); ?>