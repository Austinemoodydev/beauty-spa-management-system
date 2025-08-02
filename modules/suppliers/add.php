<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $contact_person, $phone, $email, $address);
    $stmt->execute();

    echo "<p>✅ Supplier added successfully!</p><a href='index.php'>← Back</a>";
    include('../../includes/footer.php');
    exit;
}
?>

<section>
    <h2>➕ Add Supplier</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Contact Person:</label><br>
        <input type="text" name="contact_person" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br><br>

        <label>Address:</label><br>
        <textarea name="address"></textarea><br><br>

        <button type="submit">💾 Save Supplier</button>
    </form>
</section>

<?php include('../../includes/footer.php'); ?>