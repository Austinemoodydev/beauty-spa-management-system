<?php
include('../../includes/db.php');

$id = $_GET['id'];
$conn->query("DELETE FROM services WHERE id = $id");

header("Location: index.php");
