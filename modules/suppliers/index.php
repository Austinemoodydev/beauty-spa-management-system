<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY created_at DESC");
?>

<section>
    <h2>📦 Suppliers</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="add.php">➕ Add Supplier</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact Person</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php while ($s = $suppliers->fetch_assoc()): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['contact_person']) ?></td>
                <td><?= htmlspecialchars($s['phone']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['address']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $s['id'] ?>">✏️</a>
                    <a href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Are you sure?')">🗑️</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>