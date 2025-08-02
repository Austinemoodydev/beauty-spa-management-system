<?php
include('../../includes/db.php');

$id = $_GET['id'] ?? null;

if ($id && is_numeric($id)) {
    $id = (int) $id;
    $stmt = $conn->prepare("DELETE FROM payrolls WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: payroll_report.php?start_date={$_GET['start_date']}&end_date={$_GET['end_date']}&staff_id={$_GET['staff_id']}");
exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/payroll.css">
</head>

</html>