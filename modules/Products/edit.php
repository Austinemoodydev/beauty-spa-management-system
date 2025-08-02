<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');
$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?");
    $stmt->bind_param("ssdii", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity'], $id);
    $stmt->execute();
    header('Location: index.php');
    exit;
}
?>
<h2>✏️ Edit Product</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">

<form method="post" style="width: 350px; margin: 50px auto; padding: 20px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 8px; text-align: right; font-family: Arial, sans-serif; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <label style="display:block; margin-bottom:8px; font-weight:bold;">Name:</label>
    <input name="name" value="<?= htmlspecialchars($product['name']) ?>"
        style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;"><br>

    <label style="display:block; margin-bottom:8px; font-weight:bold;">Description:</label>
    <textarea name="description"
        style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;"><?= htmlspecialchars($product['description']) ?></textarea><br>

    <label style="display:block; margin-bottom:8px; font-weight:bold;">Price:</label>
    <input type="number" name="price" value="<?= $product['price'] ?>"
        style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;"><br>

    <label style="display:block; margin-bottom:8px; font-weight:bold;">Quantity:</label>
    <input type="number" name="quantity" value="<?= $product['quantity'] ?>"
        style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;"><br>

    <button type="submit"
        style="background:#28a745; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
        Update
    </button>
</form>