<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once dirname(__DIR__) . "/config/dbconnect.php";

    // ✅ Sanitize Inputs
    $main_category_id = intval($_POST['main_category_id']);
    $sub_category_id  = intval($_POST['sub_category_id']);
    $name             = trim($_POST['name']);
    $price            = floatval($_POST['price']);
    $brand            = trim($_POST['brand']);
    $description      = trim($_POST['description'] ?? '');
    $sku_no           = trim($_POST['sku_no'] ?? '');

    // ✅ Fetch readable category names
    $category_name = $conn->query("SELECT main_category_name FROM main_category WHERE id = {$main_category_id}")
        ->fetch_assoc()['main_category_name'] ?? '';

    $subcategory_name = $conn->query("SELECT sub_category_name FROM sub_category WHERE id = {$sub_category_id}")
        ->fetch_assoc()['sub_category_name'] ?? '';

    // ✅ Upload Helper Function
    function uploadImage(string $fieldName) {
        $uploadDir = __DIR__ . "/../uploads/subshop/"; // absolute path on server

        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif', 'svg', 'bmp', 'tiff'];
        if (!in_array($ext, $allowed)) {
            echo "Invalid image type for {$fieldName}. Only JPG, JPEG, PNG, WEBP, AVIF, GIF allowed.";
            exit;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $safeName = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $safeName;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
            return "uploads/subshop/" . $safeName;
        }

        return null;
    }

    // ✅ Handle uploads
    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');
    $image4 = uploadImage('image4');

    // Ensure that image1 is uploaded, otherwise exit
    if (!$image1) {
        echo 'Image 1 is required!';
        exit;
    }

    // ✅ Handle sizes
    $size = isset($_POST['size']) ? trim($_POST['size']) : '[]';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    // ✅ Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO all_category 
        (`category`, `name`, `sub_category_id`, `price`, `main_category_id`, `brand`, `Image1`, `Image2`, `Image3`, `Image4`, `sku_no`, `description`, `quantity`, `size`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssidisssssssis",
        $category_name,
        $name,
        $sub_category_id,
        $price,
        $main_category_id,
        $brand,
        $image1,
        $image2,
        $image3,
        $image4,
        $sku_no,
        $description,
        $quantity,
        $size
    );

    // Execute the statement
    if ($stmt->execute()) {
        // Return success response for AJAX
        echo "success";
    } else {
        // Return error response if there's a problem
        echo 'Database Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid access.';
}
?>
