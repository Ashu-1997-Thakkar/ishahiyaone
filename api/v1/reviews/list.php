<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($product_id <= 0) {
    echo json_encode(['status' => 'success', 'average_rating' => 0, 'total_reviews' => 0, 'reviews' => []]);
    exit;
}

$res = $conn->query("SELECT * FROM product_reviews WHERE product_id = $product_id ORDER BY id DESC");
$reviews = [];
$total_score = 0;

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $r = (int)$row['rating'];
        $total_score += $r;
        $reviews[] = [
            'id' => (int)$row['id'],
            'user_name' => htmlspecialchars($row['user_name']),
            'rating' => $r,
            'review_text' => htmlspecialchars($row['review_text']),
            'created_at' => date('M d, Y', strtotime($row['created_at']))
        ];
    }
}

$count = count($reviews);
$avg = $count > 0 ? round($total_score / $count, 1) : 0;

echo json_encode([
    'status' => 'success',
    'average_rating' => $avg,
    'total_reviews' => $count,
    'reviews' => $reviews
]);

$conn->close();
