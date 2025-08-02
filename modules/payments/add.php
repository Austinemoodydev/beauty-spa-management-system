<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

$appointments = $conn->query("
    SELECT a.id, c.full_name AS client_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.id
    ORDER BY a.appointment_date DESC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];
    $method = $_POST['payment_method'];
    $transaction_code = $_POST['transaction_code'] ?? null;
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO payments (appointment_id, amount, payment_method, transaction_code, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $appointment_id, $amount, $method, $transaction_code, $notes);
    $stmt->execute();

    header("Location: index.php");
}
?>

<h2>Record Payment</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<style>
    /* Example inline styles for the payment form */
    form {
        background: #fff;
        padding: 24px;
        border-radius: 8px;
        max-width: 420px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    form label {
        display: block;
        margin-bottom: 12px;
        font-weight: 500;
    }

    form input[type="number"],
    form input[type="text"],
    form select,
    form textarea {
        width: 100%;
        padding: 7px 10px;
        margin-top: 4px;
        border: 1px solid #bbb;
        border-radius: 4px;
        font-size: 1em;
        box-sizing: border-box;
    }

    form textarea {
        min-height: 60px;
        resize: vertical;
    }

    button[type="submit"] {
        background: #2e8b57;
        color: #fff;
        border: none;
        padding: 10px 22px;
        border-radius: 4px;
        font-size: 1em;
        cursor: pointer;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background: #256b45;
    }

    #mpesaField {
        margin-bottom: 12px;
    }

    a {
        color: #2e8b57;
        text-decoration: none;
        font-size: 1em;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
<link rel=" stylesheet" href="../../assets/css/styles.css">
<form method="POST">
    <label>Appointment:
        <select name="appointment_id" required>
            <option value="">--Select Appointment--</option>
            <?php while ($row = $appointments->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    #<?= $row['id'] ?> - <?= $row['client_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </label><br>

    <label>Amount: <input type="number" step="0.01" name="amount" required></label><br>

    <label>Payment Method:
        <select name="payment_method" id="method" required onchange="toggleMpesaField()">
            <option value="Cash">Cash</option>
            <option value="Mpesa">M-Pesa</option>
        </select>
    </label><br>

    <div id="mpesaField" style="display:none;">
        <label>M-Pesa Transaction Code: <input type="text" name="transaction_code" maxlength="50"></label><br>
    </div>

    <label>Notes: <textarea name="notes"></textarea></label><br>

    <button type="submit">Save Payment</button>
</form>
<a href="index.php">← Back to Payments</a>

<script>
    function toggleMpesaField() {
        var method = document.getElementById("method").value;
        document.getElementById("mpesaField").style.display = method === "Mpesa" ? "block" : "none";
    }
</script>