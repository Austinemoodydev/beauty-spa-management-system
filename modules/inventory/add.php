<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$suppliers = $conn->query("SELECT id, name FROM suppliers");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, supplier_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdii", $name, $price, $quantity, $supplier_id);
    $stmt->execute();

    echo "<p>✅ Product added successfully!</p><a href='index.php'>← Back</a>";
    include('../../includes/footer.php');
    exit;
}
?>

<section>
    <h2>➕ Add Product</h2>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/addproducts.css">
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price (KES):</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Initial Quantity:</label><br>
        <input type="number" name="quantity" required><br><br>

        <label>Supplier:</label><br>
        <select name="supplier_id">
            <?php while ($sup = $suppliers->fetch_assoc()): ?>
                <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">💾 Save Product</button>
    </form>
</section>

<?php include('../../includes/footer.php'); ?>