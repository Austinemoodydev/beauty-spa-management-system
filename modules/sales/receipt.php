<?php
include('../../includes/db.php');

// Get sale by ID
$sale_id = $_GET['id'] ?? 0;

$sql = "SELECT s.*, p.name AS product_name, p.price AS unit_price
        FROM product_sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();
$sale = $result->fetch_assoc();

if (!$sale) {
    echo "<p>❌ Sale not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title> The loft Products Sale Receipt </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .receipt {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 2px dashed #999;
        }

        h2 {
            text-align: center;
        }

        .details {
            margin-top: 20px;
        }

        .details td {
            padding: 4px 0;
        }

        .print-btn {
            text-align: center;
            margin-top: 20px;
        }

        .print-btn button {
            padding: 10px 20px;
        }
    </style>
</head>

<body>

    <div class="receipt">
        <h2>🧾 Product Sale Receipt</h2>
        <table class="details">
            <tr>
                <td><strong>Receipt ID:</strong></td>
                <td>#<?= $sale['id'] ?></td>
            </tr>
            <tr>
                <td><strong>Product:</strong></td>
                <td><?= htmlspecialchars($sale['product_name']) ?></td>
            </tr>
            <tr>
                <td><strong>Unit Price:</strong></td>
                <td>KES <?= number_format($sale['unit_price'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Quantity:</strong></td>
                <td><?= $sale['quantity'] ?></td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td>KES <?= number_format($sale['total'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Method:</strong></td>
                <td><?= $sale['payment_method'] ?></td>
            </tr>
            <?php if ($sale['payment_method'] == 'Mpesa'): ?>
                <tr>
                    <td><strong>Mpesa Code:</strong></td>
                    <td><?= $sale['transaction_code'] ?: '-' ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td><strong>Date:</strong></td>
                <td><?= $sale['sold_at'] ?></td>
            </tr>
        </table>

        <div class="print-btn">
            <button onclick="window.print()">🖨️ Print Receipt</button>
        </div>
    </div>

</body>

</html>