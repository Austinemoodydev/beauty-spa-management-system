<?php

include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Set header to return JSON
header('Content-Type: application/json');

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Daily revenue data for current month
$dailyRevenue = [];
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

for ($day = 1; $day <= $daysInMonth; $day++) {
    $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
    $query = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE DATE(paid_at) = '$date'");
    $result = $query->fetch_assoc();
    $dailyRevenue[$day] = (float)$result['total'];
}

// Monthly data for last 6 months
$monthlyData = [];
$monthlyLabels = [];
$monthlyRevenue = [];
$monthlyExpenses = [];

for ($i = 5; $i >= 0; $i--) {
    $date = date('Y-m', strtotime("-$i months"));
    $month = date('M Y', strtotime("-$i months"));
    $monthlyLabels[] = $month;
    
    // Revenue
    $query = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE DATE_FORMAT(paid_at, '%Y-%m') = '$date'");
    $result = $query->fetch_assoc();
    $monthlyRevenue[] = (float)$result['total'];
    
    // Expenses (assuming you have an expenses table)
    $query = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses WHERE DATE_FORMAT(date, '%Y-%m') = '$date'");
    $result = $query->fetch_assoc();
    $monthlyExpenses[] = (float)$result['total'];
}

// Service sales data
$serviceSales = [];
$serviceQuery = $conn->query("
    SELECT s.name, COUNT(a.id) AS count, COALESCE(SUM(p.amount), 0) AS total 
    FROM services s
    LEFT JOIN appointments a ON s.id = a.service_id
    LEFT JOIN payments p ON a.id = p.appointment_id
    WHERE DATE_FORMAT(p.paid_at, '%Y-%m') = '$currentYear-$currentMonth'
    GROUP BY s.id
    ORDER BY total DESC
    LIMIT 6
");

while ($service = $serviceQuery->fetch_assoc()) {
    $serviceSales['labels'][] = $service['name'];
    $serviceSales['sales'][] = (float)$service['total'];
}

// Prepare data for JSON response
$data = [
    'daily' => [
        'labels' => range(1, $daysInMonth),
        'revenue' => array_values($dailyRevenue)
    ],
    'monthly' => [
        'labels' => $monthlyLabels,
        'revenue' => $monthlyRevenue,
        'expenses' => $monthlyExpenses,
        'profit' => array_map(function($r, $e) { return $r - $e; }, $monthlyRevenue, $monthlyExpenses)
    ],
    'services' => [
        'labels' => $serviceSales['labels'] ?? [],
        'sales' => $serviceSales['sales'] ?? []
    ]
];

echo json_encode($data);
exit;
?>