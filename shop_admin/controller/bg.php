<?php
// Debugging On
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(dirname(__DIR__) . "/config/dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid Request Method");
}

// Validate ID
if (!isset($_POST['edit_id']) || !is_numeric($_POST['edit_id'])) {
    die("Invalid product ID.");
}

$id = (int)$_POST['edit_id'];
$name = mysqli_real_escape_string($conn, $_POST['product_name'] ?? '');
$description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
$price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
$category = mysqli_real_escape_string($conn, $_POST['category_name'] ?? '');
$brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
$sku_no = mysqli_real_escape_string($conn, $_POST['sku_no'] ?? '');

// Upload helper
function uploadImage(string $fieldName, string $oldValue = '', string $uploadDir = "../../uploads/") {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return $oldValue;
    }
    $cleanName = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES[$fieldName]['name']));
    $targetPath = $uploadDir . $cleanName;
    if (!is_dir($uploadDir)) {
        // try to create
        if (!mkdir($uploadDir, 0755, true)) {
            die("Failed to create upload directory: $uploadDir");
        }
    }
    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        return "uploads/" . $cleanName;
    } else {
        die("Failed to move uploaded file for $fieldName");
    }
}

$image1_old = $_POST['old_image1'] ?? '';
$image2_old = $_POST['old_image2'] ?? '';
$image3_old = $_POST['old_image3'] ?? '';
$image4_old = $_POST['old_image4'] ?? '';

$image1 = uploadImage('new_image1', $image1_old);
$image2 = uploadImage('new_image2', $image2_old);
$image3 = uploadImage('new_image3', $image3_old);
$image4 = uploadImage('new_image4', $image4_old);

// Build SQL, using correct column names
$sql = "
    UPDATE all_category SET
        name = '$name',
        description = '$description',
        price = '$price',
        category = '$category',
        brand = '$brand',
        `SKU No` = '$sku_no',
        Image1 = '$image1',
        Image2 = '$image2',
        Image3 = '$image3',
        Image4 = '$image4',
        updated_at = NOW()
    WHERE id = $id
";

if (!mysqli_query($conn, $sql)) {
    die("❌ Error updating product: " . mysqli_error($conn));
}

header("Location: ../index.php#editProduct");
exit();
