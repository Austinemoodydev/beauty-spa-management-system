<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$id = $_GET['id'] ?? 0;

// Fetch sale
$sale = $conn->query("SELECT * FROM product_sales WHERE id = $id")->fetch_assoc();
if (!$sale) {
    echo "<p>❌ Sale not found.</p>";
    include('../../includes/footer.php');
    exit;
}

// Fetch product
$product = $conn->query("SELECT id, name, quantity, price FROM products WHERE id = {$sale['product_id']}")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_quantity = (int) $_POST['quantity'];
    $payment_method = $_POST['payment_method'];
    $transaction_code = $_POST['transaction_code'];

    $old_quantity = $sale['quantity'];
    $stock_change = $old_quantity - $new_quantity;

    // Check if enough stock
    if ($stock_change > 0 && $product['quantity'] < $stock_change) {
        echo "<p style='color:red;'>❌ Not enough stock to reduce by $stock_change units.</p>";
    } else {
        // Update sale
        $new_total = $product['price'] * $new_quantity;
        $stmt = $conn->prepare("UPDATE product_sales SET quantity = ?, total = ?, payment_method = ?, transaction_code = ? WHERE id = ?");
        $stmt->bind_param("idssi", $new_quantity, $new_total, $payment_method, $transaction_code, $id);
        $stmt->execute();

        // Adjust stock
        $conn->query("UPDATE products SET quantity = quantity + $stock_change WHERE id = {$product['id']}");

        echo "<p style='color:green;'>✅ Sale updated successfully!</p>";
        echo "<p><a href='index.php'>← Back to Sales</a></p>";
        include('../../includes/footer.php');
        exit;
    }
}
?>

<section>
    <h2>✏️ Edit Sale #<?= $sale['id'] ?></h2>
    <form method="POST">
        <p><strong>Product:</strong> <?= htmlspecialchars($product['name']) ?> (Price: <?= number_format($product['price'], 2) ?>)</p>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" min="1" value="<?= $sale['quantity'] ?>" required><br><br>

        <label>Payment Method:</label><br>
        <select name="payment_method" required>
            <option value="Cash" <?= $sale['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
            <option value="Mpesa" <?= $sale['payment_method'] == 'Mpesa' ? 'selected' : '' ?>>Mpesa</option>
        </select><br><br>

        <label>Transaction Code:</label><br>
        <input type="text" name="transaction_code" value="<?= htmlspecialchars($sale['transaction_code']) ?>"><br><br>

        <button type="submit">💾 Save Changes</button>
    </form>
</section>

<?php include('../../includes/footer.php'); ?>
