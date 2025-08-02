<?php
// Database connection
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Fetch service categories
$categories = [];
$result = $conn->query("SELECT id, name FROM service_categories");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Service</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f7fa;
            margin: 0;
            padding: 0;
        }

        main,
        .main-content {
            margin-left: 220px;
            /* assuming aside is 220px wide */
            padding: 40px 20px 20px 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            letter-spacing: 1px;
        }

        form {
            background: #fff;
            max-width: 500px;
            margin: 0 auto;
            padding: 32px 28px 24px 28px;
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        label {
            font-weight: 500;
            color: #444;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 10px 12px;
            border: 1px solid #cfd8dc;
            border-radius: 5px;
            font-size: 1rem;
            background: #f9fafb;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: #7e57c2;
            outline: none;
            background: #fff;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        button[type="submit"] {
            background: #7e57c2;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }

        button[type="submit"]:hover {
            background: #5e35b1;
        }

        @media (max-width: 700px) {

            main,
            .main-content {
                margin-left: 0;
                padding: 16px 4vw;
            }

            form {
                max-width: 100%;
                padding: 18px 8px;
            }
        }
    </style>

</head>

<body>

    <h2 style="text-align:center;">Add New Service</h2>

    <form action="insert_service.php" method="POST">
        <label for="name">Service Name:</label>
        <input type="text" name="name" required>

        <label for="description">Description:</label>
        <textarea name="description" rows="4"></textarea>

        <label for="price">Price (Ksh):</label>
        <input type="number" step="0.01" name="price" required>

        <label for="category_id">Category:</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Service</button>
    </form>

</body>

</html>