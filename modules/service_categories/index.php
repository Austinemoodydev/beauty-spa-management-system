<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$categories = $conn->query("SELECT * FROM service_categories");
?>

<section>
    <h2>Service Categories</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="add.php">➕ Add Category</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td><?= htmlspecialchars($cat['description']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $cat['id'] ?>">✏️ Edit</a> |
                    <a href="delete.php?id=<?= $cat['id'] ?>" onclick="return confirm('Delete this category?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php');
