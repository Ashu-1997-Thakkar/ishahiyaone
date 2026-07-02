<?php
require_once dirname(__DIR__) . '/config/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$product_name = trim($_POST['product_name'] ?? '');
$brand        = trim($_POST['brand'] ?? '');
$price        = floatval($_POST['price'] ?? 0);
$sku_no       = trim($_POST['sku_no'] ?? '');
$description  = trim($_POST['description'] ?? '');
$category_id  = intval($_POST['category_id'] ?? 0);
$quantity     = intval($_POST['quantity'] ?? 0);
$sizes        = json_decode($_POST['size'] ?? '[]', true) ?: [];
$size_json    = json_encode($sizes);

if (!$product_name || !$category_id) {
    echo json_encode(['success' => false, 'message' => 'Product name and category are required.']);
    exit;
}

$uploadDir = __DIR__ . '/../uploads/subshop/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imageNames = ['image1' => '', 'image2' => '', 'image3' => '', 'image4' => ''];

foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
    if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $ext     = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $newName = time() . '_' . rand(1000, 9999) . '.' . $ext;
        $newName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "_", $newName);
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $newName)) {
            $imageNames[$field] = $newName;
        }
    }
}

$stmt = $conn->prepare("
    INSERT INTO subcategories 
        (name, brand, price, sku_no, description, Size, image1, image2, image3, image4, Stock, quantity, category_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssdssssssssii",
    $product_name,
    $brand,
    $price,
    $sku_no,
    $description,
    $size_json,
    $imageNames['image1'],
    $imageNames['image2'],
    $imageNames['image3'],
    $imageNames['image4'],
    $quantity,
    $quantity,
    $category_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Sub collection added successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $stmt->error]);
}
?>
