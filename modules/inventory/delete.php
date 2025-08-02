<?php
include('../../includes/db.php');

$id = $_GET['id'] ?? 0;

// Check product
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if (!$product) {
    echo "<p>❌ Product not found.</p>";
    exit;
}

// Delete
$conn->query("DELETE FROM products WHERE id = $id");

header("Location: index.php");
exit;
?>
