<?php
include('../../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input values
    $client_id = $_POST['client_id'];
    $staff_id = $_POST['staff_id'];
    $service_id = $_POST['service_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        // Update existing appointment
        $stmt = $conn->prepare("UPDATE appointments SET client_id=?, staff_id=?, service_id=?, appointment_date=?, appointment_time=?, status=?, notes=? WHERE id=?");
        $stmt->bind_param("iiissssi", $client_id, $staff_id, $service_id, $appointment_date, $appointment_time, $status, $notes, $id);
        $stmt->execute();
    } else {
        // Insert new appointment
        $stmt = $conn->prepare("INSERT INTO appointments (client_id, staff_id, service_id, appointment_date, appointment_time, status, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiissss", $client_id, $staff_id, $service_id, $appointment_date, $appointment_time, $status, $notes);
        $stmt->execute();
    }

    header("Location: index.php");
    exit;
}
?>
