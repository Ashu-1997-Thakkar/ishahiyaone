<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $main_category_id = intval($_POST['main_category_id']);
    $sub_category_id = intval($_POST['sub_category_id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $price = floatval($_POST['price']);
    $brand = mysqli_real_escape_string($conn, trim($_POST['brand']));
    $sku_no = mysqli_real_escape_string($conn, trim($_POST['sku_no'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $size = mysqli_real_escape_string($conn, trim($_POST['size'] ?? '[]'));

    $category_name = $conn->query("SELECT main_category_name FROM main_category WHERE id = {$main_category_id}")->fetch_assoc()['main_category_name'] ?? '';
    $category_name = mysqli_real_escape_string($conn, $category_name);

    $imageSql = "";
    $uploadDir = dirname(__DIR__) . "/uploads/subshop/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    for ($i = 1; $i <= 4; $i++) {
        $fileKey = 'image' . $i;
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
            $safeName = time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $safeName)) {
                $imagePath = "uploads/subshop/" . $safeName;
                $imageSql .= ", Image$i = '$imagePath'";
            }
        }
    }

    $sql = "UPDATE all_category SET 
            main_category_id = $main_category_id, 
            sub_category_id = $sub_category_id, 
            category = '$category_name',
            name = '$name', 
            price = $price, 
            brand = '$brand',
            sku_no = '$sku_no',
            description = '$description',
            quantity = $quantity,
            size = '$size'
            $imageSql 
            WHERE id = $product_id";

    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "Database error: " . $conn->error;
    }
}
?>
