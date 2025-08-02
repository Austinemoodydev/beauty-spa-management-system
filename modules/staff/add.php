<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("INSERT INTO staff (full_name, phone, email, gender, role, salary) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $full_name, $phone, $email, $gender, $role, $salary);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<!-- CSS Links -->
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/add-staff.css">

<!-- Add Staff Form -->
<div class="add-staff-container">
    <h2>Add Staff Member</h2>

    <form class="staff-form" method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="role">Role/Position</label>
            <input type="text" id="role" name="role" required>
        </div>

        <div class="form-group">
            <label for="salary">Salary (KES)</label>
            <input type="number" id="salary" name="salary" step="0.01" required>
        </div>

        <button type="submit" class="btn-primary">➕ Add Staff</button>
    </form>

    <a href="index.php" class="back-link">← Back to Staff</a>
</div>