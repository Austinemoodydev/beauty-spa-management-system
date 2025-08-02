<?php
require_once '../../vendor/autoload.php';
include('../../includes/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$staff_id = $_GET['staff_id'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

if (!$staff_id || !$start_date || !$end_date) {
    die("Missing required parameters.");
}

// Get staff info and payroll
$stmt = $conn->prepare("
    SELECT p.*, s.full_name
    FROM payrolls p
    JOIN staff s ON s.id = p.staff_id
    WHERE p.staff_id = ? AND p.start_date = ? AND p.end_date = ?
    LIMIT 1
");
$stmt->bind_param("iss", $staff_id, $start_date, $end_date);
$stmt->execute();
$payroll = $stmt->get_result()->fetch_assoc();

if (!$payroll) {
    die("No payroll data found.");
}

// Get detailed appointments within the period
$appointments = $conn->prepare("
    SELECT a.id, a.appointment_date, s.name AS service_name, p.amount
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    JOIN payments p ON p.appointment_id = a.id
    WHERE a.staff_id = ? AND DATE(p.paid_at) BETWEEN ? AND ?
    ORDER BY a.appointment_date ASC
");
$appointments->bind_param("iss", $staff_id, $start_date, $end_date);
$appointments->execute();
$details = $appointments->get_result();

// Generate HTML
ob_start();
?>

<h2>Payroll Breakdown for <?= htmlspecialchars($payroll['full_name']) ?></h2>
<p><strong>Period:</strong> <?= $start_date ?> to <?= $end_date ?></p>
<p><strong>Total Sales:</strong> KES <?= number_format($payroll['total_sales'], 2) ?></p>
<p><strong>Commission Rate:</strong> <?= number_format($payroll['commission_rate'], 2) ?>%</p>
<p><strong>Total Commission:</strong> KES <?= number_format($payroll['total_commission'], 2) ?></p>

<h3>Appointment Breakdown</h3>
<button onclick="window.print()" style="margin: 10px 0; padding: 5px 12px; font-size: 16px;">
    🖨️ Print Breakdown
</button>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Service</th>
            <th>Amount Paid (KES)</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $details->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['service_name']) ?></td>
            <td><?= number_format($row['amount'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
$html = ob_get_clean();

// Setup Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream to browser
$filename = "staff_payroll_{$staff_id}_{$start_date}_to_{$end_date}.pdf";
$dompdf->stream($filename, ["Attachment" => false]);
exit;
?>
