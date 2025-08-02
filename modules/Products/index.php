<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Fetch all products from the database
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>

<main class="content-wrapper">
    <link rel="stylesheet" href="../../assets/css/product.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="page-header">
        <h1><i class="fas fa-box-open"></i> Product Inventory</h1>
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($p = $products->fetch_assoc()): ?>
                            <tr>
                                <td class="product-name"><?= htmlspecialchars($p['name'] ?? '') ?></td>
                                <td class="product-desc"><?= htmlspecialchars($p['description'] ?? '') ?></td>
                                <td class="text-right">KES <?= isset($p['price']) ? number_format((float)$p['price'], 2) : '0.00' ?></td>
                                <td class="text-center">
                                    <span class="stock-badge <?= ($p['quantity'] ?? 0) > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                        <?= $p['quantity'] ?? '0' ?>
                                    </span>
                                </td>
                                <td class="text-center actions">
                                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $p['id'] ?>" class="btn-action delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center no-products">
                                <i class="fas fa-box-open fa-2x"></i>
                                <p>No products found. Add your first product!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include('../../includes/footer.php'); ?>