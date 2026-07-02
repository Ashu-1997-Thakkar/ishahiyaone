<?php
session_start();

// ✅ Load centralized config (Razorpay keys from single source of truth)
require_once __DIR__ . '/config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['amount'])) {
    http_response_code(400);
    exit;
}

$amount  = (int) round($data['amount'] * 100);
$receipt = "RCPT_" . time();

$ch = curl_init("https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ":" . RAZORPAY_SECRET);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "amount"          => $amount,
    "currency"        => "INR",
    "receipt"         => $receipt,
    "payment_capture" => 1
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fixes local WAMP SSL Error

$response = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode([
        "error" => "CURL Error: " . $curl_error,
        "response" => null
    ]);
    exit;
}

$order = json_decode($response, true);

if (!isset($order['id'])) {
    echo json_encode([
        "error" => "Razorpay API Error",
        "response" => $order
    ]);
    exit;
}

echo json_encode([
    "order_id" => $order['id']
]);
