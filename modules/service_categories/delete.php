<?php
include('../../includes/db.php');

$id = $_GET['id'];

// Optional: Check if this category is used by any services
$check = $conn->query("SELECT COUNT(*) AS total FROM services WHERE category_id = $id");
$data = $check->fetch_assoc();

if ($data['total'] > 0) {
    echo "⚠️ Cannot delete category: It's assigned to one or more services.<br>";
    echo "<a href='index.php'>← Back to Categories</a>";
    exit;
}

// Delete category
$conn->query("DELETE FROM service_categories WHERE id = $id");
header("Location: index.php");
