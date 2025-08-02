<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$sql = "SELECT p.*, a.id AS appointment_code, c.full_name AS client_name
        FROM payments p
        LEFT JOIN appointments a ON p.appointment_id = a.id
        LEFT JOIN clients c ON a.client_id = c.id
        ORDER BY p.paid_at DESC";
$result = $conn->query($sql);
?>



<a href="add.php">➕ Record Payment</a>
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/payments.css">
<script src="../../assets/js/payments.js" defer></script>
<div class="payments-container">
    <h2>Payments</h2>
    <a href="add.php" class="add-btn">➕ Record Payment</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Appointment</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Transaction Code</th>
                <th>Date</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['client_name'] ?? 'N/A') ?></td>
                    <td>#<?= $row['appointment_code'] ?? 'N/A' ?></td>
                    <td>KES <?= number_format($row['amount'], 2) ?></td>
                    <td><?= $row['payment_method'] ?></td>
                    <td><?= $row['transaction_code'] ?? '-' ?></td>
                    <td><?= $row['paid_at'] ?></td>
                    <td><a href="receipt.php?id=<?= $row['id'] ?>" target="_blank">🧾 Receipt</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<?php include('../../includes/footer.php'); ?>