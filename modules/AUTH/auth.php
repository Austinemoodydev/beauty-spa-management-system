<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}

function checkRole($required_role_id) {
    return $_SESSION['role_id'] <= $required_role_id;
}
?>
