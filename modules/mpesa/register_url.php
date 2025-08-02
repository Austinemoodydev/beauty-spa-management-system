<?php
$shortcode = '600000'; // Use your Paybill or Till Number
$token = 'ACCESS_TOKEN_FROM_above';

$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

$curl_post_data = array(
    'ShortCode' => $shortcode,
    'ResponseType' => 'Completed',
    'ConfirmationURL' => 'https://yourdomain.com/mpesa/confirmation_url.php',
    'ValidationURL' => 'https://yourdomain.com/mpesa/validation_url.php'
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $token"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

$response = curl_exec($ch);
echo $response;
