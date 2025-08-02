<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');
$id = $_GET['id'];

$service = $conn->query("SELECT * FROM services WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT id, name FROM service_categories");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Service</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        .edit-service-form {
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

        .edit-service-form label {
            font-weight: 500;
            color: #3a3a3a;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .edit-service-form input[type="text"],
        .edit-service-form input[type="number"],
        .edit-service-form textarea,
        .edit-service-form select {
            padding: 0.6rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            background: #fafbfc;
            transition: border 0.2s;
        }

        .edit-service-form input:focus,
        .edit-service-form textarea:focus,
        .edit-service-form select:focus {
            border-color: #e75480;
            outline: none;
        }

        .edit-service-form button[type="submit"] {
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

        .edit-service-form button[type="submit"]:hover {
            background: linear-gradient(90deg, #a8edea 0%, #e75480 100%);
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">Edit Service</h2>

    <form action="update_service.php" method="POST" class="edit-service-form">
        <input type="hidden" name="id" value="<?= $service['id'] ?>">

        <label>Service Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($service['name']) ?>" required><br><br>

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($service['description']) ?></textarea><br><br>

        <label>Price (Ksh):</label>
        <input type="number" step="0.01" name="price" value="<?= $service['price'] ?>" required><br><br>

        <label>Category:</label>
        <select name="category_id" required>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $service['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Update Service</button>
    </form>

</body>

</html>