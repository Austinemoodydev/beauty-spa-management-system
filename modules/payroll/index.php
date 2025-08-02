<?php
include_once '../../includes/header.php';
include_once '../../includes/aside.php';
include_once '../../includes/db.php';
?>

<div class="payroll-container">
    <div class="payroll-card">
        <div class="payroll-header">
            <h1 class="payroll-title">Generate Payroll Report</h1>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="../../assets/css/payroll.css">
            <link rel="stylesheet" href="../../assets/css/styles.css">
        </div>

        <form method="POST" action="generate_payroll.php" class="payroll-form">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-calculator"></i> Generate Payroll
            </button>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>