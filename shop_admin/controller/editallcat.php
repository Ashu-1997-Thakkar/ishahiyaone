<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate ID
    if (!isset($_POST['edit_id']) || !is_numeric($_POST['edit_id'])) {
        die("Invalid product ID.");
    }

    $id = (int)$_POST['edit_id'];

    // Sanitize text inputs
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category_name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);

    // Image handling
    // Image handling
$uploadDir = 'ishahiyaone-image/'; // Must be accessible from the browser

$imageFields = ['image1', 'image2', 'image3', 'image4'];
$imageValues = [];

foreach ($imageFields as $field) {
    $oldKey = 'old_' . $field;
    $newKey = 'new_' . $field;

    if (
        isset($_FILES[$newKey]) &&
        $_FILES[$newKey]['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES[$newKey]['tmp_name'])
    ) {
        $originalName = preg_replace('/\s+/', '_', $_FILES[$newKey]['name']);
        $newImageName = time() . '_' . basename($originalName);
        $uploadPath = $uploadDir . $newImageName;

        if (move_uploaded_file($_FILES[$newKey]['tmp_name'], $uploadPath)) {
            $imageValues[$field] = $newImageName; // Only the file name
        } else {
            $imageValues[$field] = $_POST[$oldKey]; // fallback
        }
    } else {
        $imageValues[$field] = $_POST[$oldKey]; // fallback
    }
}



    // Update query
    $sql = "UPDATE all_category SET 
                name = '$name',
                description = '$description',
                price = '$price',
                category = '$category',
                brand = '$brand',
                image1 = '{$imageValues['image1']}',
                image2 = '{$imageValues['image2']}',
                image3 = '{$imageValues['image3']}',
                image4 = '{$imageValues['image4']}',
                updated_at = NOW()
            WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    header("Location: ../index.php#editProduct");
    exit();
}
 else {
        echo "Error updating: " . mysqli_error($conn);
    }
} else {
    echo "Invalid Request Method";
}
?>
