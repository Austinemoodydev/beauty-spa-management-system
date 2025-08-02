<?php
require 'vendor/autoload.php'; // Or include manually if no Composer

use Dompdf\Dompdf;

// Start capturing HTML
ob_start();
include 'daily_report_content.php'; // This file contains your full HTML report
$html = ob_get_clean();

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Output to browser
$dompdf->stream("Daily_Report_" . date('Y-m-d') . ".pdf", [
    "Attachment" => false // Set to true to force download
]);
exit;
