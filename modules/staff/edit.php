<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$id = $_GET['id'];
$staff = $conn->query("SELECT * FROM staff WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("UPDATE staff SET full_name=?, phone=?, email=?, gender=?, role=?, salary=? WHERE id=?");
    $stmt->bind_param("ssssssd", $full_name, $phone, $email, $gender, $role, $salary, $id);
    $stmt->execute();

    header("Location: index.php");
}
?>

<h2>Edit Staff Member</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<form method="POST">
    <label>Full Name: <input type="text" name="full_name" value="<?= $staff['full_name'] ?>" required></label><br>
    <label>Phone: <input type="text" name="phone" value="<?= $staff['phone'] ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= $staff['email'] ?>"></label><br>
    <label>Gender:
        <select name="gender">
            <option <?= $staff['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option <?= $staff['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option <?= $staff['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </label><br>
    <label>Role: <input type="text" name="role" value="<?= $staff['role'] ?>" required></label><br>
    <label>Salary: <input type="number" step="0.01" name="salary" value="<?= $staff['salary'] ?>" required></label><br>
    <button type="submit">Update</button>
</form>
<a href="index.php">← Back to Staff</a>