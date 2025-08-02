<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$id = $_GET['id'];
$supplier = $conn->query("SELECT * FROM suppliers WHERE id = $id")->fetch_assoc();

if (!$supplier) {
    echo "<p>❌ Supplier not found.</p>";
    include('../../includes/footer.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE suppliers SET name = ?, contact_person = ?, phone = ?, email = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $contact_person, $phone, $email, $address, $id);
    $stmt->execute();

    echo "<p>✅ Supplier updated!</p><a href='index.php'>← Back</a>";
    include('../../includes/footer.php');
    exit;
}
?>

<section>
    <h2>✏️ Edit Supplier</h2>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($supplier['name']) ?>" required><br><br>

        <label>Contact Person:</label><br>
        <input type="text" name="contact_person" value="<?= htmlspecialchars($supplier['contact_person']) ?>" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($supplier['email']) ?>"><br><br>

        <label>Address:</label><br>
        <textarea name="address"><?= htmlspecialchars($supplier['address']) ?></textarea><br><br>

        <button type="submit">💾 Update Supplier</button>
    </form>
</section>

<?php include('../../includes/footer.php'); ?>
