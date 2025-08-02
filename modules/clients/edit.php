<?php
include('../../includes/db.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch client data
$client = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();

    if (!$client) {
        die("Client not found.");
    }
} else {
    die("Invalid client ID.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE clients SET full_name = ?, phone = ?, email = ?, gender = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $phone, $email, $gender, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?updated=1");
    exit;
}
?>


<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/client.css">
<script src="../../assets/js/client.js" defer></script>

<h2>Edit Client</h2>

<form method="POST" class="edit-client-form">
    <label>Full Name: <input type="text" name="full_name" value="<?= $client['full_name'] ?>" required></label>
    <label>Phone: <input type="text" name="phone" value="<?= $client['phone'] ?>" required></label>
    <label>Email: <input type="email" name="email" value="<?= $client['email'] ?>"></label>
    <label>Gender:
        <select name="gender">
            <option <?= $client['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option <?= $client['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option <?= $client['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </label>
    <button type="submit">Update</button>
</form>
<div class="back-link"><a href="index.php">← Back to Clients</a></div>