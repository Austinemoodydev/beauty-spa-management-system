<?php
// Connect to DB
include '../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Log raw data for debugging
file_put_contents("mpesa_log.txt", json_encode($data, JSON_PRETTY_PRINT), FILE_APPEND);

$transaction = $data['TransID'] ?? '';
$phone       = $data['MSISDN'] ?? '';
$amount      = $data['TransAmount'] ?? 0;
$clientName  = $data['FirstName'] ?? '';

// Insert into payments table
$stmt = $conn->prepare("INSERT INTO payments (payment_method, amount, mpesa_code, notes, received_by) VALUES ('mpesa_till', ?, ?, ?, 1)");
$stmt->bind_param("dss", $amount, $transaction, $clientName);
$stmt->execute();

// Response back to Safaricom
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Success"]);
