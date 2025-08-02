<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$id = $_GET['id'] ?? 0;
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (!$product) {
    echo "<p>❌ Product not found.</p>";
    include('../../includes/footer.php');
    exit;
}

$suppliers = $conn->query("SELECT id, name FROM suppliers");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = (float) $_POST['price'];
    $supplier_id = $_POST['supplier_id'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, supplier_id = ? WHERE id = ?");
    $stmt->bind_param("sdii", $name, $price, $supplier_id, $id);
    $stmt->execute();

    echo "<p>✅ Product updated successfully!</p><a href='index.php'>← Back</a>";
    include('../../includes/footer.php');
    exit;
}
?>

<section class="edit-product-form">
    <h2>✏️ Edit Product</h2>

    <link rel="stylesheet" href="../../assets/css/editproduct.css">

    <link rel="stylesheet" href="../../assets/css/styles.css">

    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

        <label>Price (KES):</label><br>
        <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required><br><br>

        <label>Supplier:</label><br>
        <select name="supplier_id">
            <?php while ($sup = $suppliers->fetch_assoc()): ?>
                <option value="<?= $sup['id'] ?>" <?= $sup['id'] == $product['supplier_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sup['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">💾 Update Product</button>
    </form>
</section>

<?php include('../../includes/footer.php'); ?>