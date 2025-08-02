<?php
include('../../includes/db.php');
header('Content-Type: application/json');

// Validate and sanitize input
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Invalid payroll ID provided',
        'code' => 'INVALID_ID'
    ]);
    exit;
}

try {
    // Start transaction for data integrity
    $conn->begin_transaction();

    // 1. Verify payroll record exists and get current status
    $check_stmt = $conn->prepare("SELECT status FROM payrolls WHERE id = ? FOR UPDATE");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Payroll record not found", 404);
    }

    $current_status = $result->fetch_assoc()['status'];
    $check_stmt->close();

    // 2. Determine new status
    $new_status = ($current_status === 'paid') ? 'unpaid' : 'paid';

    // 3. Update status with additional logging
    $update_stmt = $conn->prepare("
        UPDATE payrolls 
        SET status = ?, 
            generated_at = IF(status != ?, generated_at, NOW())
        WHERE id = ?
    ");
    $update_stmt->bind_param("ssi", $new_status, $new_status, $id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows === 0) {
        throw new Exception("No changes made to payroll status", 304);
    }
    $update_stmt->close();

    // 4. Log the status change (optional - create a payroll_logs table if needed)
    // $log_stmt = $conn->prepare("INSERT INTO payroll_logs (...) VALUES (...)");
    // $log_stmt->execute();

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'data' => [
            'previous_status' => $current_status,
            'new_status' => $new_status,
            'payroll_id' => $id
        ],
        'message' => 'Payroll status updated successfully'
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    $status_code = $e->getCode() ?: 500;
    http_response_code($status_code);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code' => $status_code === 404 ? 'NOT_FOUND' : ($status_code === 304 ? 'NOT_MODIFIED' : 'SERVER_ERROR')
    ]);
} finally {
    // Ensure connection is closed
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
