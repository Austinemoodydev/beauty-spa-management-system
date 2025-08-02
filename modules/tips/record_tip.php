<?php
include('../../includes/db.php');
include('../../includes/header.php');
include('../../includes/aside.php');

// Fetch all completed appointments (including staff_id)
$appointments = $conn->query("
    SELECT a.id, a.staff_id, c.full_name AS client, s.full_name AS staff, a.appointment_date
    FROM appointments a
    JOIN clients c ON c.id = a.client_id
    JOIN staff s ON s.id = a.staff_id
    WHERE a.status = 'Completed'
    ORDER BY a.appointment_date DESC
");

// Fetch all active staff
$staffList = [];
$staff_query = $conn->query("SELECT id, full_name FROM staff WHERE is_active = 1");
while ($s = $staff_query->fetch_assoc()) {
    $staffList[$s['id']] = $s['full_name'];
}

// Handle tip form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $staff_id = $_POST['staff_id'];
    $amount = $_POST['amount'];
    $tip_date = $_POST['tip_date'] ?? date('Y-m-d');

    if (!empty($appointment_id) && !empty($staff_id) && !empty($amount)) {

        // ✅ 1. Insert tip into tips table
        $stmt = $conn->prepare("INSERT INTO tips (appointment_id, staff_id, amount, tip_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $appointment_id, $staff_id, $amount, $tip_date);

        if ($stmt->execute()) {
            // ✅ 2. Update Payroll to include this tip
            // Check if payroll already exists for this staff and date
            $check = $conn->prepare("SELECT id, total_commission, notes FROM payrolls 
                                     WHERE staff_id = ? AND start_date = ? AND end_date = ?");
            $check->bind_param("iss", $staff_id, $tip_date, $tip_date);
            $check->execute();
            $result = $check->get_result();

            if ($row = $result->fetch_assoc()) {
                // Update existing payroll
                $new_commission = $row['total_commission'] + $amount;

                // Append tips note
                $old_note = $row['notes'] ?? '';
                $updated_note = trim($old_note . " | Includes tips: KES " . number_format($new_commission, 2));

                $update = $conn->prepare("UPDATE payrolls SET total_commission = ?, notes = ? WHERE id = ?");
                $update->bind_param("dsi", $new_commission, $updated_note, $row['id']);
                $update->execute();
            } else {
                // Create new payroll record
                $notes = "Includes tips: KES " . number_format($amount, 2);
                $insertPayroll = $conn->prepare("INSERT INTO payrolls (staff_id, start_date, end_date, total_appointments, total_sales, commission_rate, total_commission, notes) 
                                                 VALUES (?, ?, ?, 0, 0, 0, ?, ?)");
                $insertPayroll->bind_param("issds", $staff_id, $tip_date, $tip_date, $amount, $notes);
                $insertPayroll->execute();
            }

            echo "<p style='color:green;'>✅ Tip recorded and staff earnings updated successfully!</p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to record tip.</p>";
        }
    } else {
        echo "<p style='color:red;'>⚠️ All fields are required.</p>";
    }
}
?>

<h2>💰 Record Tip</h2>
<link rel="stylesheet" href="../../assets/css/styles.css">
<style>
    .tip-form {
        background: #fff;
        padding: 1.5rem 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 2rem auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .tip-form select,
    .tip-form input {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        background: #f9f9f9;
    }

    .tip-form button {
        background: linear-gradient(90deg, #4CAF50 0%, #45a049 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.6rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1rem;
        transition: background 0.2s;
    }

    .tip-form button:hover {
        background: linear-gradient(90deg, #45a049 0%, #4CAF50 100%);
    }
</style>

<form method="POST" class="tip-form">
    <label>Appointment:
        <select name="appointment_id" required onchange="updateStaffId(this)">
            <option value="">-- Select Appointment --</option>
            <?php while ($row = $appointments->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" data-staff-id="<?= $row['staff_id'] ?>">
                    <?= htmlspecialchars($row['client']) ?> → <?= htmlspecialchars($row['staff']) ?> (<?= $row['appointment_date'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
    </label>

    <label>Staff Member:
        <select name="staff_id" id="staffSelect" required>
            <option value="">-- Auto-selected Staff --</option>
            <?php foreach ($staffList as $id => $name): ?>
                <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Tip Amount (KES):
        <input type="number" name="amount" min="1" required>
    </label>

    <label>Tip Date:
        <input type="date" name="tip_date" value="<?= date('Y-m-d') ?>" required>
    </label>

    <button type="submit">💾 Save Tip</button>
</form>

<script>
    function updateStaffId(select) {
        let selectedOption = select.options[select.selectedIndex];
        let staffId = selectedOption.getAttribute('data-staff-id');

        if (staffId) {
            document.getElementById('staffSelect').value = staffId;
        } else {
            document.getElementById('staffSelect').selectedIndex = 0;
        }
    }
</script>

<?php include('../../includes/footer.php'); ?>