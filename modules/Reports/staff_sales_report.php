<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Get selected month and role filter from URL or use defaults
$month = $_GET['month'] ?? date('Y-m');
$roleFilter = $_GET['role'] ?? '';

// Base SQL query
$sql = "
    SELECT 
        s.id,
        s.full_name AS staff_name,
        s.role,
        COUNT(DISTINCT a.id) AS total_appointments,
        COALESCE(SUM(p.amount), 0) AS total_sales,
        COALESCE(SUM(t.amount), 0) AS total_tips
    FROM staff s
    LEFT JOIN appointments a ON a.staff_id = s.id
    LEFT JOIN payments p ON p.appointment_id = a.id AND DATE_FORMAT(p.paid_at, '%Y-%m') = ?
    LEFT JOIN tips t ON t.appointment_id = a.id AND DATE_FORMAT(t.tip_date, '%Y-%m') = ?
";

// Prepare parameters for binding
$params = [$month, $month];

// Add role filter if provided
if (!empty($roleFilter)) {
    $sql .= " WHERE s.role = ?";
    $params[] = $roleFilter;
}

// Finish query
$sql .= " GROUP BY s.id, s.full_name, s.role ORDER BY total_sales DESC";

// Prepare and execute query
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<section>
    <h2>📊 Staff Sales Report – <?= htmlspecialchars($month) ?></h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">

    <form method="GET" style="margin-bottom: 1rem;">
        <label>Month:
            <input type="month" name="month" value="<?= htmlspecialchars($month) ?>" required>
        </label>
        <label>Role:
            <input type="text" name="role" value="<?= htmlspecialchars($roleFilter) ?>" placeholder="e.g. Therapist">
        </label>
        <button type="submit">Filter</button>
        <a href="staff_sales_report.php">Reset</a>
    </form>

    <button onclick="window.print()" style="margin-bottom: 10px;">🖨️ Print Report</button>
    <a href="export_pdf.php?month=<?= urlencode($month) ?>&role=<?= urlencode($roleFilter) ?>" target="_blank">📄 Export PDF</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Role</th>
                <th>Total Appointments</th>
                <th>Total Sales (KES)</th>
                <th>Total Tips (KES)</th>
                <th>Net Earnings (KES)</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()):
                    $net = $row['total_sales'] + $row['total_tips'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['staff_name']) ?></td>
                        <td><?= htmlspecialchars($row['role'] ?? '-') ?></td>
                        <td><?= (int)$row['total_appointments'] ?></td>
                        <td><?= number_format($row['total_sales'], 2) ?></td>
                        <td><?= number_format($row['total_tips'], 2) ?></td>
                        <td><?= number_format($net, 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No data found for this month.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>