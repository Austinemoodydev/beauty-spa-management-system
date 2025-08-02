<?php
include('../../includes/db.php');
session_start();

if (!isset($_POST['start_date'], $_POST['end_date'])) {
    die(json_encode(['error' => 'Missing payroll period.']));
}

$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];

// Get global commission percentage
$r = $conn->query("SELECT setting_value FROM settings WHERE setting_key='global_commission_percentage'");
$g = $r->fetch_assoc();
$global_rate = floatval($g['setting_value'] ?? 0);

// Fetch active staff
$staffs = $conn->query("SELECT id, full_name FROM staff WHERE is_active=1");

while ($staff = $staffs->fetch_assoc()) {
    $staff_id = $staff['id'];

    // Total sales
    $s = $conn->prepare("
        SELECT SUM(p.amount) AS total_sales
        FROM appointments a
        JOIN payments p ON p.appointment_id = a.id
        WHERE a.staff_id = ? AND DATE(p.paid_at) BETWEEN ? AND ?
    ");
    $s->bind_param("iss", $staff_id, $start_date, $end_date);
    $s->execute();
    $sales_result = $s->get_result();
    $sales = $sales_result->fetch_assoc()['total_sales'] ?? 0;
    $s->close();

    // Total tips
    $t = $conn->prepare("
        SELECT SUM(t.amount) AS total_tips
        FROM tips t
        JOIN appointments a ON a.id = t.appointment_id
        WHERE t.staff_id = ? AND DATE(t.tip_date) BETWEEN ? AND ?
    ");
    $t->bind_param("iss", $staff_id, $start_date, $end_date);
    $t->execute();
    $tips_result = $t->get_result();
    $tips = $tips_result->fetch_assoc()['total_tips'] ?? 0;
    $t->close();

    // Total appointments
    $a = $conn->prepare("
        SELECT COUNT(*) AS total_appointments
        FROM appointments
        WHERE staff_id = ? AND appointment_date BETWEEN ? AND ?
    ");
    $a->bind_param("iss", $staff_id, $start_date, $end_date);
    $a->execute();
    $appointments_result = $a->get_result();
    $appointments = $appointments_result->fetch_assoc()['total_appointments'] ?? 0;
    $a->close();

    // Calculate commission and total earnings
    $total_commission = ($sales * $global_rate) / 100;
    $total_earnings = $total_commission + $tips;
    $note = "Includes tips: KES " . number_format($tips, 2);

    // Check if payroll record already exists for this period
    $check = $conn->prepare("SELECT id FROM payrolls WHERE staff_id = ? AND start_date = ? AND end_date = ?");
    $check->bind_param("iss", $staff_id, $start_date, $end_date);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if ($exists) {
        // Update existing record
        $update = $conn->prepare("
            UPDATE payrolls 
            SET total_appointments = ?, 
                total_sales = ?, 
                commission_rate = ?, 
                total_commission = ?, 
                earnings = ?, 
                notes = ?,
                status = 'unpaid'
            WHERE id = ?
        ");
        $update->bind_param(
            "iddddsi",
            $appointments,
            $sales,
            $global_rate,
            $total_commission,
            $total_earnings,
            $note,
            $exists['id']
        );
        $update->execute();
        $update->close();
    } else {
        // Insert new payroll record
        $ins = $conn->prepare("
            INSERT INTO payrolls 
            (staff_id, start_date, end_date, total_appointments, 
             total_sales, commission_rate, total_commission, 
             earnings, notes, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'unpaid')
        ");
        $ins->bind_param(
            "issidddds",
            $staff_id,
            $start_date,
            $end_date,
            $appointments,
            $sales,
            $global_rate,
            $total_commission,
            $total_earnings,
            $note
        );
        $ins->execute();
        $ins->close();
    }
}

header("Location: payroll_report.php?start_date=$start_date&end_date=$end_date");
exit;
?>