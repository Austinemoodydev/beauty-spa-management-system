<?php
// Include the MySQLi DB connection
include_once '../../includes/db.php';
include_once '../../includes/header.php';
include_once '../../includes/aside.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Global Commission Setting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">


</head>

<body>

    <h2>Set Global Commission Percentage</h2>

    <form method="post">
        <label for="percentage">Commission Percentage (%):</label>
        <input type="number" step="0.01" min="0" max="100" name="percentage" required>
        <input type="submit" name="submit" value="Save">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $percentage = $_POST['percentage'];

        // Check if the setting exists
        $check_sql = "SELECT * FROM settings WHERE setting_key = 'global_commission_percentage'";
        $result = $conn->query($check_sql);

        if ($result && $result->num_rows > 0) {
            // Update it
            $update_sql = "UPDATE settings SET setting_value = ? WHERE setting_key = 'global_commission_percentage'";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("s", $percentage);
            $stmt->execute();
            echo "<p style='color:green;'>Global commission updated to {$percentage}%</p>";
        } else {
            // Insert it
            $insert_sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('global_commission_percentage', ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("s", $percentage);
            $stmt->execute();
            echo "<p style='color:green;'>Global commission set to {$percentage}%</p>";
        }
    }

    // Display current commission
    $select_sql = "SELECT setting_value FROM settings WHERE setting_key = 'global_commission_percentage'";
    $result = $conn->query($select_sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<p><strong>Current Global Commission:</strong> " . $row['setting_value'] . "%</p>";
    } else {
        echo "<p><strong>Current Global Commission:</strong> Not set</p>";
    }
    ?>

    <?php include_once '../../includes/footer.php'; ?>
</body>

</html>