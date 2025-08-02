<?php
// Database connection
include('../../includes/db.php');

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];

$stmt = $conn->prepare("UPDATE services SET name=?, description=?, price=?, category_id=? WHERE id=?");
$stmt->bind_param("ssdii", $name, $description, $price, $category_id, $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Update failed: " . $stmt->error;
}
