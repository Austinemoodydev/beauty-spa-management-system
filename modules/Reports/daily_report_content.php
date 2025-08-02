<?php
include_once('../../includes/db.php');
include_once('../../includes/header.php');
include_once('../../includes/aside.php');

// Define today's date
$today = date('Y-m-d');
$formattedDate = date('F j, Y');

// Get total appointments for today
$appointmentsQuery = $conn->query("
    SELECT COUNT(*) AS total 
    FROM appointments 
    WHERE appointment_date = '$today'
");
$appointments = ($appointmentsQuery && $appointmentsQuery->num_rows > 0)
    ? (int)$appointmentsQuery->fetch_assoc()['total']
    : 0;

// Get total sales for today
$salesQuery = $conn->query("
    SELECT SUM(amount) AS total_paid 
    FROM payments 
    WHERE DATE(paid_at) = '$today'
");
$total_paid = ($salesQuery && $salesQuery->num_rows > 0)
    ? (float)$salesQuery->fetch_assoc()['total_paid']
    : 0.00;

// Get average sale per appointment
$avg_sale = $appointments > 0 ? $total_paid / $appointments : 0;

// Get staff performance for today
$staff_result = $conn->query("
    SELECT s.full_name, 
           COUNT(a.id) AS total_appointments, 
           COALESCE(SUM(p.amount), 0) AS total_sales
    FROM staff s
    LEFT JOIN appointments a 
        ON s.id = a.staff_id AND a.appointment_date = '$today'
    LEFT JOIN payments p 
        ON a.id = p.appointment_id AND DATE(p.paid_at) = '$today'
    GROUP BY s.id
    ORDER BY total_sales DESC
");
?>
<main>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dailyreport.css">

    <div class="report-header">
        <h1 class="report-title">Daily Performance Report</h1>
        <div class="report-date"><?= htmlspecialchars($formattedDate) ?></div>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-title">Total Appointments</div>
            <div class="card-value"><?= $appointments ?></div>
            <div class="card-description">Scheduled for today</div>
        </div>

        <div class="summary-card">
            <div class="card-title">Total Revenue</div>
            <div class="card-value currency">KES <?= number_format($total_paid, 2) ?></div>
            <div class="card-description">From all services</div>
        </div>

        <div class="summary-card">
            <div class="card-title">Average Sale</div>
            <div class="card-value currency">KES <?= number_format($avg_sale, 2) ?></div>
            <div class="card-description">Per appointment</div>
        </div>
    </div>

    <div class="performance-section">
        <div class="section-header">
            <h2 class="section-title">Staff Performance</h2>
        </div>

        <table class="performance-table">
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Appointments</th>
                    <th>Total Sales</th>
                    <th>Avg. Sale</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($staff_result && $staff_result->num_rows > 0): ?>
                    <?php while ($staff = $staff_result->fetch_assoc()):
                        $staff_avg = $staff['total_appointments'] > 0
                            ? $staff['total_sales'] / $staff['total_appointments']
                            : 0;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($staff['full_name']) ?></td>
                            <td><?= $staff['total_appointments'] ?></td>
                            <td class="highlight-cell">KES <?= number_format($staff['total_sales'], 2) ?></td>
                            <td>KES <?= number_format($staff_avg, 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No staff activity recorded for today</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="action-buttons">
        <button class="btn btn-secondary" onclick="window.location.href='export_pdf.php?date=<?= $today ?>'">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-primary print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
</main>

<?php include_once('../../includes/footer.php'); ?>