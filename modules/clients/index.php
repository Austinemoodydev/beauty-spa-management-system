<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$result = $conn->query("SELECT * FROM clients");
?>

<section>

    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/client.css">
    <script src="../../assets/js/client.js" defer></script>
    <h2>Clients</h2>
    <a href="add.php" class="add-btn">➕ Add New Client</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['gender'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this client?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>