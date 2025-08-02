<?php
require_once '../../vendor/autoload.php'; // Path to autoload if using Composer
include('../../includes/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

// Get payroll period
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (!$start_date || !$end_date) {
    die("Start and End date are required.");
}

// Fetch payroll records
$stmt = $conn->prepare("
    SELECT p.*, s.full_name
    FROM payrolls p
    JOIN staff s ON s.id = p.staff_id
    WHERE p.start_date = ? AND p.end_date = ?
    ORDER BY p.total_commission DESC
");
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$results = $stmt->get_result();

// Start output buffering
ob_start();
?>

<h2>Payroll Report (<?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?>)</h2>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Staff</th>
            <th>Total Sales (KES)</th>
            <th>Commission Rate (%)</th>
            <th>Total Commission (KES)</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $results->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= number_format($row['total_sales'], 2) ?></td>
            <td><?= number_format($row['commission_rate'], 2) ?></td>
            <td><?= number_format($row['total_commission'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
$html = ob_get_clean();

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream to browser
$filename = "payroll_report_{$start_date}_to_{$end_date}.pdf";
$dompdf->stream($filename, ["Attachment" => false]); // Set to true to force download
exit;
?>
