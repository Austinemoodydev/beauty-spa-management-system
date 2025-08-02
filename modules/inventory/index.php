<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$result = $conn->query("SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id");
?>

<section>
    <h2>📦 Inventory</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="add.php">➕ Add Product</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Restock</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['supplier_name'] ?? 'N/A') ?></td>
                <td>KES <?= number_format($row['price'], 2) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><a href="restock.php?id=<?= $row['id'] ?>">➕</a></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete product?')">🗑️</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>