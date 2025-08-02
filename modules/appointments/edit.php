<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$id = $_GET['id'];
$appointment = $conn->query("SELECT * FROM appointments WHERE id=$id")->fetch_assoc();

// Fetch all data into arrays to avoid fetch_assoc() pointer issues
$clients_result = $conn->query("SELECT id, full_name FROM clients");
$clients = [];
while ($row = $clients_result->fetch_assoc()) {
    $clients[] = $row;
}
$staff_result = $conn->query("SELECT id, full_name FROM staff");
$staff = [];
while ($row = $staff_result->fetch_assoc()) {
    $staff[] = $row;
}
$services_result = $conn->query("SELECT id, name FROM services");
$services = [];
while ($row = $services_result->fetch_assoc()) {
    $services[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $staff_id = $_POST['staff_id'];
    $service_id = $_POST['service_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE appointments SET client_id=?, staff_id=?, service_id=?, appointment_date=?, appointment_time=?, status=?, notes=? WHERE id=?");
    $stmt->bind_param("iiissssi", $client_id, $staff_id, $service_id, $appointment_date, $appointment_time, $status, $notes, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<h2>Edit Appointment</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/appointments.css">
<script src="../../assets/js/appointments.js" defer></script>
<form method="post" class="edit-appointment-form">
    <input type="hidden" name="id" value="<?= $appointment['id'] ?>">

    <label>Client:
        <select name="client_id">
            <?php foreach ($clients as $row): ?>
                <option value="<?= $row['id'] ?>" <?= ($row['id'] == $appointment['client_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Staff:
        <select name="staff_id">
            <?php foreach ($staff as $row): ?>
                <option value="<?= $row['id'] ?>" <?= ($row['id'] == $appointment['staff_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Service:
        <select name="service_id">
            <?php foreach ($services as $row): ?>
                <option value="<?= $row['id'] ?>" <?= ($row['id'] == $appointment['service_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Date:
        <input type="date" name="appointment_date" value="<?= $appointment['appointment_date'] ?>" required>
    </label>

    <label>Time:
        <input type="time" name="appointment_time" value="<?= $appointment['appointment_time'] ?>" required>
    </label>

    <label>Status:
        <select name="status">
            <option value="Pending" <?= $appointment['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= $appointment['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $appointment['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
    </label>

    <label>Notes:
        <textarea name="notes"><?= htmlspecialchars($appointment['notes']) ?></textarea>
    </label>

    <button type="submit">Update</button>
</form>
<div class="back-link"><a href="index.php">← Back to Appointments</a></div>