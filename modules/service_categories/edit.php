<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Validate and get ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing category ID.");
}
$id = (int)$_GET['id'];

$result = $conn->query("SELECT * FROM service_categories WHERE id = $id");
$category = $result->fetch_assoc();

if (!$category) {
    die("Category not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE service_categories SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<h2>Edit Service Category</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<style>
    .edit-category-form {
        background: #fff;
        padding: 2rem 2.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        max-width: 420px;
        margin: 2rem auto;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    .edit-category-form label {
        font-weight: 500;
        color: #3a3a3a;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .edit-category-form input[type="text"],
    .edit-category-form textarea {
        padding: 0.6rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        background: #fafbfc;
        transition: border 0.2s;
    }

    .edit-category-form input:focus,
    .edit-category-form textarea:focus {
        border-color: #e75480;
        outline: none;
    }

    .edit-category-form button[type="submit"] {
        background: linear-gradient(90deg, #e75480 0%, #a8edea 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.7rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1rem;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(231, 84, 128, 0.08);
    }

    .edit-category-form button[type="submit"]:hover {
        background: linear-gradient(90deg, #a8edea 0%, #e75480 100%);
    }

    a {
        display: inline-block;
        margin: 1.5rem auto 0 auto;
        color: #e75480;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.2s;
    }

    a:hover {
        color: #a8edea;
        text-decoration: underline;
    }
</style>
<a href="index.php">← Back to Categories</a>
<form method="POST" class="edit-category-form">
    <label>Name:
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
    </label>
    <label>Description:
        <textarea name="description"><?= htmlspecialchars($category['description']) ?></textarea>
    </label>
    <button type="submit">Update Category</button>
</form>