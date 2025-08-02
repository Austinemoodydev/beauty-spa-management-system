<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$date = $_GET['date'] ?? date('Y-m-d');
$role = $_GET['role'] ?? '';

// Validate & sanitize input
$date = date('Y-m-d', strtotime($date)); // ensure proper format

// Prepare SQL
$sql = "
    SELECT s.role, 
           COALESCE(SUM(p.amount), 0) AS total_paid
    FROM staff s
    LEFT JOIN appointments a ON a.staff_id = s.id AND a.appointment_date = ?
    LEFT JOIN payments p ON p.appointment_id = a.id AND DATE(p.paid_at) = ?
";

$params = [$date, $date];

if (!empty($role)) {
    $sql .= " WHERE s.role = ?";
    $params[] = $role;
}

$sql .= " GROUP BY s.role";

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<section>
    <h2>💼 Role-Based Payment Report – <?= htmlspecialchars($date) ?></h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">

    <form method="GET" style="margin-bottom: 1rem;">
        <label>Date:
            <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
        </label>
        <label>Role:
            <input type="text" name="role" value="<?= htmlspecialchars($role) ?>" placeholder="e.g. Therapist">
        </label>
        <button type="submit">Filter</button>
        <a href="role_report.php">Reset</a>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Role</th>
            <th>Total Sales (KES)</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['role'] ?? 'N/A') ?></td>
                    <td>KES <?= number_format($row['total_paid'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No data found for this date.</td>
            </tr>
        <?php endif; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>