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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = (int) $_POST['quantity'];
    $conn->query("UPDATE products SET quantity = quantity + $quantity WHERE id = $id");

    echo "<p>✅ Stock updated successfully!</p><a href='index.php'>← Back</a>";
    include('../../includes/footer.php');
    exit;
}
?>
<link rel="stylesheet" href="../../assets/css/styles.css">
<section style="background:#fff;padding:1.5rem 2rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.06);max-width:400px;margin:2rem auto;font-family:Arial,sans-serif;text-align:center;">
    <h2 style="color:#e75480;font-size:1.4rem;margin-bottom:0.8rem;">🔄 Restock Product</h2>

    <p style="color:#444;font-size:0.95rem;margin-bottom:1.2rem;">

        <strong><?= htmlspecialchars($product['name']) ?></strong> (Current: <?= $product['quantity'] ?> units)
    </p>
    <form method="POST" style="display:flex;flex-direction:column;align-items:center;gap:0.8rem;">
        <label style="font-weight:500;color:#333;font-size:0.95rem;">Add Quantity:</label>
        <input type="number" name="quantity" min="1" required
            style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:6px;font-size:0.95rem;text-align:center;transition:border 0.2s;">
        <button type="submit"
            style="background:linear-gradient(90deg,#e75480 0%,#a8edea 100%);color:#fff;border:none;border-radius:6px;padding:0.5rem 1rem;font-size:1rem;font-weight:600;cursor:pointer;width:100%;transition:background 0.2s;">
            ➕ Update Stock
        </button>
    </form>
</section>


<?php include('../../includes/footer.php'); ?>