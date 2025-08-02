<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');


$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$staff_id = $_GET['staff_id'] ?? '';

if (!$start_date || !$end_date) {
    echo '<div class="payroll-container"><div class="payroll-card">';
    echo '<p class="text-warning">⚠️ Please select a date range first</p>';
    echo '</div></div>';
    include('../../includes/footer.php');
    exit;
}
?>

<div class="payroll-container">
    <div class="payroll-card">
        <div class="payroll-header">
            <h1 class="payroll-title">Payroll Report</h1>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="../../assets/css/payroll.css">
            <link rel="stylesheet" href="../../assets/css/styles.css">
            <div>
                <span class="text-muted">Period: <?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?></span>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="payroll_report.php" class="filter-form">
            <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
            <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

            <div class="form-group">
                <label class="form-label">Filter by Staff</label>
                <select name="staff_id" class="form-control">
                    <option value="">All Staff</option>
                    <?php
                    $staffs = $conn->query("SELECT id, full_name FROM staff");
                    while ($s = $staffs->fetch_assoc()):
                    ?>
                        <option value="<?= $s['id'] ?>" <?= $staff_id == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['full_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
        </form>

        <div class="print-actions">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="export_pdf.php?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&staff_id=<?= $staff_id ?>"
                target="_blank" class="btn btn-secondary">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Total Sales</th>
                        <th>Total Tips</th>
                        <th>Commission Rate</th>
                        <th>Total Earnings</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, s.full_name,
                            (SELECT COALESCE(SUM(t.amount), 0)
                             FROM tips t
                             JOIN appointments a ON a.id = t.appointment_id
                             WHERE t.staff_id = p.staff_id
                             AND DATE(t.tip_date) BETWEEN p.start_date AND p.end_date) AS total_tips
                            FROM payrolls p
                            JOIN staff s ON s.id = p.staff_id
                            WHERE p.start_date = ? AND p.end_date = ?";

                    $params = [$start_date, $end_date];
                    $types = "ss";

                    if (!empty($staff_id)) {
                        $sql .= " AND s.id = ?";
                        $params[] = $staff_id;
                        $types .= "i";
                    }

                    $sql .= " ORDER BY p.total_commission DESC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $results = $stmt->get_result();

                    while ($row = $results->fetch_assoc()):
                        $status = $row['status'] ?? 'unpaid';
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td>KES <?= number_format($row['total_sales'], 2) ?></td>
                            <td>KES <?= number_format($row['total_tips'], 2) ?></td>
                            <td><?= number_format($row['commission_rate'], 2) ?>%</td>
                            <td>KES <?= number_format($row['earnings'], 2) ?></td>
                            <td>
                                <span class="status-badge status-<?= $status ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="edit_payroll.php?id=<?= $row['id'] ?>" class="action-link">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" onclick="return togglePayrollStatus(<?= $row['id'] ?>)" class="action-link">
                                        <i class="fas fa-sync-alt"></i> Toggle
                                    </a>
                                    <a href="delete_payroll.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Are you sure you want to delete this payroll record?')"
                                        class="action-link text-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function togglePayrollStatus(id) {
        if (!confirm('Are you sure you want to toggle the payroll status?')) return false;

        fetch('toggle_payroll_status.php?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Find the status badge element
                    const statusBadge = document.querySelector(`#status-${id}`);
                    statusBadge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    statusBadge.className = `status-badge status-${data.new_status}`;

                    // Optional: Show a success message
                    alert('Payroll status updated successfully');
                } else {
                    alert('Failed to update payroll status.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred while updating the status.');
            });

        return false;
    }
</script>

<?php include('../../includes/footer.php'); ?>