<?php
include('../../includes/db.php');

$id = $_GET['id'] ?? 0;

// Get sale record
$sale = $conn->query("SELECT * FROM product_sales WHERE id = $id")->fetch_assoc();
if (!$sale) {
    echo "<p>❌ Sale not found.</p>";
    exit;
}

// Restore stock
$conn->query("UPDATE products SET quantity = quantity + {$sale['quantity']} WHERE id = {$sale['product_id']}");

// Delete sale
$conn->query("DELETE FROM product_sales WHERE id = $id");

header("Location: index.php");
exit;
?>
