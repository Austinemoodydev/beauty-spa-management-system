<?php
include('../../includes/db.php');
$id = $_GET['id'];
$conn->query("DELETE FROM staff WHERE id=$id");
header("Location: index.php");
