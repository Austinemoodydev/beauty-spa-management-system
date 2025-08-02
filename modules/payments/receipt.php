<?php
include('../../includes/db.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid receipt ID.");
}

// Fetch payment details with client and appointment info
$sql = "SELECT p.*, a.id AS appointment_code, c.full_name AS client_name, c.phone, c.email
        FROM payments p
        LEFT JOIN appointments a ON p.appointment_id = a.id
        LEFT JOIN clients c ON a.client_id = c.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    die("Receipt not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment Receipt #<?= $payment['id'] ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7f7fa;
            padding: 0;
            margin: 0;
        }

        .receipt-box {
            max-width: 350px;
            margin: 40px auto;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            background: #fff;
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            box-shadow: 0 2px 12px rgba(231, 84, 128, 0.08);
        }

        .title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #e75480;
            text-align: center;
            margin-bottom: 1.2rem;
            letter-spacing: 1px;
        }

        .info {
            font-size: 0.98rem;
            color: #333;
            margin-bottom: 1.2rem;
        }

        .info label {
            display: inline-block;
            min-width: 120px;
            font-weight: 500;
            color: #e75480;
            margin-bottom: 2px;
        }

        .info strong {
            color: #388e3c;
        }

        .print-btn {
            margin-top: 1.2rem;
            text-align: center;
        }

        .print-btn button {
            background: linear-gradient(90deg, #e75480 0%, #a8edea 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(231, 84, 128, 0.08);
        }

        .print-btn button:hover {
            background: linear-gradient(90deg, #a8edea 0%, #e75480 100%);
        }

        @media print {
            .print-btn {
                display: none;
            }

            .receipt-box {
                box-shadow: none;
                border: none;
                margin: 0;
            }

            body {
                background: #fff;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-box">
        <div class="title">The loft Receipt</div>
        <div class="info">
            <label>Receipt #:</label> <?= $payment['id'] ?><br>
            <label>Client Name:</label> <?= htmlspecialchars($payment['client_name'] ?? 'N/A') ?><br>
            <label>Phone:</label> <?= $payment['phone'] ?? 'N/A' ?><br>
            <label>Email:</label> <?= $payment['email'] ?? 'N/A' ?><br>
            <label>Appointment #:</label> <?= $payment['appointment_code'] ?? 'N/A' ?><br>
            <label>Payment Date:</label> <?= date('d M Y, H:i', strtotime($payment['paid_at'])) ?><br>
            <label>Payment Method:</label> <?= $payment['payment_method'] ?><br>
            <?php if ($payment['payment_method'] === 'Mpesa'): ?>
                <label>M-Pesa Code:</label> <?= $payment['transaction_code'] ?><br>
            <?php endif; ?>
            <label>Amount Paid:</label> <strong>KES <?= number_format($payment['amount'], 2) ?></strong><br>
            <?php if (!empty($payment['notes'])): ?>
                <label>Notes:</label> <?= htmlspecialchars($payment['notes']) ?><br>
            <?php endif; ?>
        </div>
        <div class="print-btn">
            <button onclick="window.print()">🖨️ Print Receipt</button>
        </div>
    </div>
</body>

</html>