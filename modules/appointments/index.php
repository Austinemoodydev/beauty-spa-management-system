<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// ✅ Join with clients, staff, services
$result = $conn->query("
    SELECT a.*, 
           c.full_name AS client_name, 
           s.full_name AS staff_name, 
           sv.name AS service_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.id
    JOIN staff s ON a.staff_id = s.id
    JOIN services sv ON a.service_id = sv.id
    ORDER BY a.appointment_date DESC
");
?>
<div class="appointments-container">
    <h2>Appointments</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/appointments.css">
    <script src="../../assets/js/appointments.js" defer></script>
    <link rel="stylesheet" href="index.css">
    <a href="add.php" class="add-btn">➕ Add Appointment</a>
    <table border="1">
        <tr>
            <th>Client</th>
            <th>Staff</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['staff_name']) ?></td>
                <td><?= htmlspecialchars($row['service_name']) ?></td>
                <td><?= $row['appointment_date'] ?></td>
                <td><?= $row['appointment_time'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this appointment?')">🗑️</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>