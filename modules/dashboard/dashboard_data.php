<?php
ob_start();
header('Content-Type: application/json');

include('../../includes/db.php');

// Turn on MySQLi exceptions for better error visibility (during development)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $dailyRevenue = [];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE DATE(paid_at) = ?");
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $dailyRevenue[$day] = (float)$result['total'];
        $stmt->close();
    }

    $monthlyLabels = [];
    $monthlyRevenue = [];
    $monthlyExpenses = [];

    for ($i = 5; $i >= 0; $i--) {
        $date = date('Y-m', strtotime("-$i months"));
        $month = date('M Y', strtotime("-$i months"));
        $monthlyLabels[] = $month;

        // Revenue
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE DATE_FORMAT(paid_at, '%Y-%m') = ?");
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $monthlyRevenue[] = (float)$result['total'];
        $stmt->close();

        // Expenses — update `date_column_name` to your actual column
        // Expenses (corrected)
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses WHERE DATE_FORMAT(expense_date, '%Y-%m') = ?");
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $monthlyExpenses[] = (float)$result['total'];
        $stmt->close();
    }

    $serviceSales = [
        'labels' => [],
        'sales' => []
    ];

    $dateYM = "$currentYear-$currentMonth";
    $stmt = $conn->prepare("
        SELECT s.name, COUNT(a.id) AS count, COALESCE(SUM(p.amount), 0) AS total 
        FROM services s
        LEFT JOIN appointments a ON s.id = a.service_id
        LEFT JOIN payments p ON a.id = p.appointment_id
        WHERE DATE_FORMAT(p.paid_at, '%Y-%m') = ?
        GROUP BY s.id
        ORDER BY total DESC
        LIMIT 6
    ");
    $stmt->bind_param('s', $dateYM);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $serviceSales['labels'][] = $row['name'];
        $serviceSales['sales'][] = (float)$row['total'];
    }
    $stmt->close();

    // Build response
    $data = [
        'daily' => [
            'labels' => range(1, $daysInMonth),
            'revenue' => array_values($dailyRevenue)
        ],
        'monthly' => [
            'labels' => $monthlyLabels,
            'revenue' => $monthlyRevenue,
            'expenses' => $monthlyExpenses,
            'profit' => array_map(function ($r, $e) {
                return $r - $e;
            }, $monthlyRevenue, $monthlyExpenses)
        ],
        'services' => $serviceSales
    ];

    // Output pretty JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'error' => 'Something went wrong.',
        'details' => $e->getMessage()
    ]);
}
exit;
