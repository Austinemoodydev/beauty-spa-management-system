<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("INSERT INTO clients (full_name, phone, email, gender) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone, $email, $gender);
    $stmt->execute();

    header("Location: index.php");
}
?>

<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/client.css">
<script src="../../assets/js/client.js" defer></script>

<h2>Add New Client</h2>

<form method="POST" class="add-client-form">
    <label>Full Name: <input type="text" name="full_name" required></label>
    <label>Phone: <input type="text" name="phone" required></label>
    <label>Email: <input type="email" name="email"></label>
    <label>Gender:
        <select name="gender">
            <option>Female</option>
            <option>Male</option>
            <option>Other</option>
        </select>
    </label>
    <button type="submit">Add Client</button>
</form>
<div class="back-link"><a href="index.php">← Back to Clients</a></div>
