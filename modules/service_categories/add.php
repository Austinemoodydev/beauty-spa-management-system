<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO service_categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    header("Location: index.php");
}
?>

<h2>Add Service Category</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<form method="POST" class="add-category-form">
    <label>Name: <input type="text" name="name" required></label>
    <label>Description: <textarea name="description"></textarea></label>
    <button type="submit">Add</button>
</form>
<a href="index.php">← Back to Categories</a>

<style>
    /* Add Service Category Form Styles */
    form.add-category-form {
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

    form.add-category-form label {
        font-weight: 500;
        color: #3a3a3a;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    form.add-category-form input[type="text"],
    form.add-category-form textarea {
        padding: 0.6rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        background: #fafbfc;
        transition: border 0.2s;
    }

    form.add-category-form input:focus,
    form.add-category-form textarea:focus {
        border-color: #e75480;
        outline: none;
    }

    form.add-category-form button[type="submit"] {
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

    form.add-category-form button[type="submit"]:hover {
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