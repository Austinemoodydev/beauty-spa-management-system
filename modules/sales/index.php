<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$sql = "SELECT s.*, p.name AS product_name 
        FROM product_sales s
        JOIN products p ON s.product_id = p.id
        ORDER BY s.sold_at DESC";
$result = $conn->query($sql);
?>

<section>
    <h2>Product Sales</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <a href="add.php">➕ Record Sale</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Method</th>
            <th>Transaction Code</th>
            <th>Date</th>
            <th>Receipt</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>KES <?= number_format($row['total'], 2) ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td><?= $row['transaction_code'] ?? '-' ?></td>
                <td><?= $row['sold_at'] ?></td>
                <td><a href="receipt.php?id=<?= $row['id'] ?>" target="_blank">🧾 Receipt</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include('../../includes/footer.php'); ?>