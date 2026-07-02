<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

// Get images to delete
$q = $conn->prepare("SELECT image1, image2, image3, image4 FROM subcategories WHERE id = ?");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result()->fetch_assoc();
$q->close();

if (!$res) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Delete from database
$d = $conn->prepare("DELETE FROM subcategories WHERE id = ?");
$d->bind_param("i", $id);
$deleted = $d->execute();
$d->close();

if ($deleted) {
    // Delete images if exist
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/shop_admin/uploads/subshop/';
    foreach (['image1','image2','image3','image4'] as $key) {
        if (!empty($res[$key])) {
            $path = $uploadDir . $res[$key];
            if (file_exists($path)) @unlink($path);
        }
    }
    echo json_encode(['success' => true, 'message' => 'Product deleted']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database delete failed']);
}

?>
