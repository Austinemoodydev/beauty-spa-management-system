<?php
include('../../includes/db.php');

$id = $_GET['id'] ?? 0;

$supplier = $conn->query("SELECT * FROM suppliers WHERE id = $id")->fetch_assoc();

if ($supplier) {
    $conn->query("DELETE FROM suppliers WHERE id = $id");
}

header("Location: index.php");
exit;
?>
