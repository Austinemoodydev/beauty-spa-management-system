<?php
require_once '../../vendor/autoload.php'; // or the path to your mpdf/autoload.php
include('../../includes/db.php');

$month = $_GET['month'] ?? date('Y-m');

// Fetch data
$service_sales = $conn->query("SELECT SUM(amount) AS total FROM payments WHERE DATE_FORMAT(paid_at, '%Y-%m') = '$month'")->fetch_assoc()['total'] ?? 0;
$product_sales = $conn->query("SELECT SUM(total) AS total FROM product_sales WHERE DATE_FORMAT(sold_at, '%Y-%m') = '$month'")->fetch_assoc()['total'] ?? 0;

$appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE DATE_FORMAT(appointment_date, '%Y-%m') = '$month'")->fetch_assoc()['total'] ?? 0;

// Prepare HTML
$html = "
<h2>Monthly Report - " . date('F Y', strtotime($month)) . "</h2>
<ul>
    <li><strong>Appointments:</strong> $appointments</li>
    <li><strong>Service Revenue:</strong> KES " . number_format($service_sales, 2) . "</li>
    <li><strong>Product Sales:</strong> KES " . number_format($product_sales, 2) . "</li>
    <li><strong>Total Revenue:</strong> KES " . number_format($service_sales + $product_sales, 2) . "</li>
</ul>
";

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('monthly_report_' . $month . '.pdf', 'I'); // or 'D' to force download
