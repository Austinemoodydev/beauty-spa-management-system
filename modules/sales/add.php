<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Fetch available products
$products = $conn->query("SELECT id, name, price, quantity FROM products WHERE quantity > 0");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $payment_method = $_POST['payment_method'];
    $transaction_code = $_POST['transaction_code'] ?? null;

    // Get selected product
    $product = $conn->query("SELECT price, quantity FROM products WHERE id = $product_id")->fetch_assoc();
    $price = $product['price'];
    $stock = $product['quantity'];

    // Validate
    if ($quantity > $stock) {
        echo "<p style='color:red;text-align:center;'>❌ Not enough stock. Available: $stock</p>";
    } else {
        $total = $price * $quantity;

        // Insert sale
        $stmt = $conn->prepare("INSERT INTO product_sales (product_id, quantity, total, payment_method, transaction_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidds", $product_id, $quantity, $total, $payment_method, $transaction_code);
        $stmt->execute();

        // Reduce stock
        $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");

        echo "<p style='color:green;text-align:center;'>✅ Sale recorded successfully!</p>";
        echo "<p style='text-align:center;'><a href='index.php'>← Back to Sales</a></p>";
        include('../../includes/footer.php');
        exit;
    }
}
?>
<link rel="stylesheet" href="../../assets/css/styles.css">
<section style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f8f9fc;">
    <div style="background:#fff;padding:25px 35px;border-radius:10px;width:350px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <h2 style="text-align:center;color:#4A90E2;margin-bottom:20px;">➕ Record Product Sale</h2>
        <link rel="stylesheet" href="../../assets/css/styles.css">
        <form method="POST" style="display:flex;flex-direction:column;gap:12px;">

            <label style="font-weight:bold;color:#333;">Product:</label>
            <select name="product_id" required style="padding:8px;border:1px solid #ccc;border-radius:6px;">
                <option value="">-- Select Product --</option>
                <?php while ($p = $products->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>">
                        <?= htmlspecialchars($p['name']) ?> (KES <?= number_format($p['price'], 2) ?> | Stock: <?= $p['quantity'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label style="font-weight:bold;color:#333;">Quantity:</label>
            <input type="number" name="quantity" min="1" required style="padding:8px;border:1px solid #ccc;border-radius:6px;">

            <label style="font-weight:bold;color:#333;">Payment Method:</label>
            <select name="payment_method" required style="padding:8px;border:1px solid #ccc;border-radius:6px;">
                <option value="Cash">Cash</option>
                <option value="Mpesa">Mpesa</option>
            </select>

            <label style="font-weight:bold;color:#333;">Transaction Code (for Mpesa):</label>
            <input type="text" name="transaction_code" style="padding:8px;border:1px solid #ccc;border-radius:6px;">

            <button type="submit" style="padding:10px;background:#4A90E2;color:#fff;font-weight:bold;border:none;border-radius:6px;cursor:pointer;transition:0.3s;">
                💾 Submit Sale
            </button>
        </form>
    </div>
</section>

<?php include('../../includes/footer.php'); ?>