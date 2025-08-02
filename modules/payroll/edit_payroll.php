<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Validate and get payroll ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) die("Invalid payroll ID.");
$id = (int)$_GET['id'];

// Fetch payroll record
$stmt = $conn->prepare("SELECT p.*, s.full_name FROM payrolls p LEFT JOIN staff s ON p.staff_id = s.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$payroll = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$payroll) die("Payroll not found.");

// Get global commission %
$glob = $conn->query("SELECT setting_value FROM settings WHERE setting_key = 'global_commission_percentage'");
$gr = $glob->fetch_assoc();
$global_percent = floatval($gr['setting_value'] ?? 0);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $del = $conn->prepare("DELETE FROM payrolls WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();
        header("Location: payroll_report.php?deleted=1");
        exit;
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $total_appointments = (int)$_POST['total_appointments'];
    $total_sales = (float)$_POST['total_sales'];
    $commission_rate = $global_percent;
    $total_commission = (float)$_POST['total_commission'];
    $notes = $_POST['notes'] ?? '';

    $u = $conn->prepare("
        UPDATE payrolls 
        SET start_date=?, end_date=?, total_appointments=?, total_sales=?, commission_rate=?, total_commission=?, notes=?
        WHERE id=?");
    $u->bind_param("ssiddssi", $start_date, $end_date, $total_appointments, $total_sales, $commission_rate, $total_commission, $notes, $id);
    $u->execute();
    $u->close();

    header("Location: payroll_report.php?updated=1");
    exit;
}
?>

<div class="payroll-container">
    <div class="payroll-card">
        <div class="payroll-header">
            <h1 class="payroll-title">Edit Payroll Record</h1>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="../../assets/css/styles.css">
            <link rel="stylesheet" href="../../assets/css/payroll.css">

            <a href="payroll_report.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Report
            </a>
        </div>

        <form method="POST" class="payroll-form">
            <div class="form-group">
                <label class="form-label">Staff Member</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($payroll['full_name']) ?>" disabled>
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($payroll['start_date']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($payroll['end_date']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Total Appointments</label>
                <input type="number" name="total_appointments" class="form-control" value="<?= htmlspecialchars($payroll['total_appointments']) ?>" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Sales (KES)</label>
                <input type="number" step="0.01" name="total_sales" class="form-control" value="<?= htmlspecialchars($payroll['total_sales']) ?>" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Commission Rate (%)</label>
                <input type="number" step="0.01" name="commission_rate" class="form-control" value="<?= number_format($global_percent, 2) ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Total Commission (KES)</label>
                <input type="number" step="0.01" name="total_commission" class="form-control" value="<?= htmlspecialchars($payroll['total_commission']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($payroll['notes'] ?? '') ?></textarea>
            </div>

            <div class="form-actions" style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Payroll
                </button>

                <button type="submit" name="delete" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this payroll record? This action cannot be undone.')">
                    <i class="fas fa-trash-alt"></i> Delete Payroll
                </button>
            </div>
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>