<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid HTTP method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;
$user_name = isset($input['user_name']) ? trim($input['user_name']) : '';
$rating = isset($input['rating']) ? (int)$input['rating'] : 5;
$review_text = isset($input['review_text']) ? trim($input['review_text']) : '';

if ($product_id <= 0 || empty($user_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID and Name are required']);
    exit;
}

if ($rating < 1 || $rating > 5) $rating = 5;

$stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_name, rating, review_text) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $product_id, $user_name, $rating, $review_text);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
}

$stmt->close();
$conn->close();
