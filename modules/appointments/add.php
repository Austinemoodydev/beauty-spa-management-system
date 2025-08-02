<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Fetch clients, staff, and services
$clients = $conn->query("SELECT id, full_name FROM clients")->fetch_all(MYSQLI_ASSOC);
$staff = $conn->query("SELECT id, full_name FROM staff")->fetch_all(MYSQLI_ASSOC);
$services = $conn->query("SELECT id, name FROM services")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    // ✅ Insert into appointments table and include staff_id, service_id
    $stmt = $conn->prepare("INSERT INTO appointments (client_id, staff_id, service_id, appointment_date, appointment_time, status, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiissss", $client_id, $staff_id, $service_id, $appointment_date, $appointment_time, $status, $notes);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<h2>Add Appointment</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/appointments.css">
<script src="../../assets/js/appointments.js" defer></script>


<form method="post" class="add-appointment-form">
    <label>Client:</label>
    <select name="client_id" required>
        <?php foreach ($clients as $row): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Service:</label>
    <select name="service_id" required>
        <?php foreach ($services as $row): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Staff:</label>
    <select name="staff_id" required>
        <?php foreach ($staff as $row): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Date:</label>
    <input type="date" name="appointment_date" required>

    <label>Time:</label>
    <input type="time" name="appointment_time" required>

    <label>Status:</label>
    <select name="status">
        <option value="Pending">Pending</option>
        <option value="Completed">Completed</option>
        <option value="Cancelled">Cancelled</option>
    </select>

    <label>Notes:</label>
    <textarea name="notes"></textarea>

    <button type="submit">Save Appointment</button>
</form>
<div class="back-link"><a href="index.php">← Back to Appointments</a></div>