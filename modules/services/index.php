<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');
$services = $conn->query("SELECT * FROM services");
?>

<h2>🧴 Services</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<a href="add.php">➕ Add Service</a>
<table border="1" cellpadding="8">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php while ($s = $services->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['description']) ?></td>
            <td>
                <a href="edit_service.php?id=<?= $s['id'] ?>">✏️ Edit</a> |
                <a href="delete_service.php?id=<?= $s['id'] ?>" onclick="return confirm('Are you sure you want to delete this service?')">🗑️ Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>