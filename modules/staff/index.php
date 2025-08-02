<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$result = $conn->query("SELECT * FROM staff");
?>

<section>
    <h2>Staff Members</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="add.php">➕ Add New Staff</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Role</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['role'] ?></td>
                <td><?= number_format($row['salary'], 2) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this staff member?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>