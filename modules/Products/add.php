<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../../includes/db.php');

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity']);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');
?>

<!-- Add Product Page Layout -->
<link rel="stylesheet" href="../../assets/css/styles.css">

<link rel="stylesheet" href="/assets/css/addproducts.css">

<main class="add-product-layout">
    <div class="form-wrapper">
        <h2 class="form-heading">➕ Add New Product</h2>
        <form method="post">
            <label>
                Name:
                <input type="text" name="name" required>
            </label>
            <label>
                Description:
                <textarea name="description"></textarea>
            </label>
            <label>
                Price:
                <input type="number" step="0.01" name="price" required>
            </label>
            <label>
                Quantity:
                <input type="number" name="quantity" required>
            </label>
            <button type="submit">Save</button>
        </form>
    </div>
</main>

<?php include('../../includes/footer.php'); ?>