<?php
// Database connection
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

?>
<link rel="stylesheet" href="../../assets/css/styles.css">
<style>
    .service-insert-message {
        background: #fff;
        padding: 2rem 2.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        max-width: 420px;
        margin: 2rem auto;
        text-align: center;
        font-size: 1.15rem;
        color: #333;
    }

    .service-insert-message.success {
        border-left: 6px solid #4caf50;
    }

    .service-insert-message.error {
        border-left: 6px solid #e75480;
    }

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        color: #e75480;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #a8edea;
        text-decoration: underline;
    }
</style>
<?php

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];

$stmt = $conn->prepare("INSERT INTO services (name, description, price, category_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdi", $name, $description, $price, $category_id);

echo '<div class="service-insert-message ' . ($stmt->execute() ? 'success' : 'error') . '">';
if ($stmt->affected_rows > 0) {
    echo "Service added successfully.";
} else {
    echo "Error: " . $stmt->error;
}
echo '<br><a href="index.php" class="back-link">← Back to Services</a>';
echo '</div>';

$stmt->close();
$conn->close();
