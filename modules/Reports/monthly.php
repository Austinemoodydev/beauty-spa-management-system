<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$month = $_GET['month'] ?? date('Y-m'); // e.g. 2025-07

// Total Service Sales
$service_sales_query = "
    SELECT SUM(amount) AS total 
    FROM payments 
    WHERE DATE_FORMAT(paid_at, '%Y-%m') = '$month'
";
$service_sales = $conn->query($service_sales_query)->fetch_assoc()['total'] ?? 0;


$product_sales_query = "
 SELECT SUM(total) AS total 
 FROM product_sales 
 WHERE DATE_FORMAT(sold_at, '%Y-%m') = '$month'
";
$product_sales = $conn->query($product_sales_query)->fetch_assoc()['total'] ?? 0;

// Appointments
$appointments_query = "
    SELECT COUNT(*) AS total 
    FROM appointments 
    WHERE DATE_FORMAT(appointment_date, '%Y-%m') = '$month'
";
$appointments = $conn->query($appointments_query)->fetch_assoc()['total'] ?? 0;
?>

<section>
    <h2>📆 Monthly Report - <?= date('F Y', strtotime($month)) ?></h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="export_pdf.php?month=<?= $month ?>" target="_blank">📥 Export PDF</a>


    <form method="GET">
        <label>Select Month:</label>
        <input type="month" name="month" value="<?= $month ?>" onchange="this.form.submit()">
    </form>

    <ul>
        <li><strong>Appointments:</strong> <?= $appointments ?></li>
        <li><strong>Service Revenue:</strong> KES <?= number_format($service_sales, 2) ?></li>
        <li><strong>Product Sales:</strong> KES <?= number_format($product_sales, 2); ?></li>

        </li>

        <li><strong>Total Revenue:</strong> KES <?= number_format($service_sales + $product_sales, 2) ?></li>
    </ul>
</section>

<?php include('../../includes/footer.php'); ?>