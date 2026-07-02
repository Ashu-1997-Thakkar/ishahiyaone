<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/config/dbconnect.php';

// Start processing form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1️⃣ Get form data
    $id = intval($_POST['edit_id']);
    $product_name = trim($_POST['edit_product_name']);
    $price = floatval($_POST['price']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $sku_no = trim($_POST['sku_no']);
    // $main_category_id = intval($_POST['main_category_id']);
    $category_id = intval($_POST['category_id']);
    $sizes = json_decode($_POST['size'], true) ?: [];
    $size_json = json_encode($sizes);
    $Quanitiy = trim($_POST['quantity']);

    // 2️⃣ Handle images
    $uploadDir = __DIR__ . '/../uploads/subshop/';  // Directory where images will be uploaded
    $imageFields = ['image1', 'image2', 'image3', 'image4'];  // Image fields to process
    $imageNames = [];

    foreach ($imageFields as $field) {
        $newImageField = 'new_' . $field;  // Field name for new images
        $oldImage = $_POST['old_' . $field] ?? '';  // Old image to keep if no new image is uploaded

        // Check if there's a new image uploaded
        if (!empty($_FILES[$newImageField]['name'])) {
            // Get file extension
            $ext = pathinfo($_FILES[$newImageField]['name'], PATHINFO_EXTENSION);
            // Generate new unique name for the image
            $newName = basename(time() . '_' . rand(1000,9999) . '.' . $ext);
            // Sanitize filename to avoid special characters
            $newName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "_", $newName);
            $targetPath = $uploadDir . $newName;

            // Try uploading the file
            if (move_uploaded_file($_FILES[$newImageField]['tmp_name'], $targetPath)) {
                $imageNames[$field] = $newName;  // If successful, save new image name
            } else {
                error_log("Failed to upload image: " . $_FILES[$newImageField]['name']);
                $imageNames[$field] = $oldImage;  // If failed, keep the old image
            }
        } else {
            // If no new image uploaded, keep the old image
            $imageNames[$field] = $oldImage;
        }
    }

    // 3️⃣ Update database
    if ($id > 0) {
        $stmt = $conn->prepare("
            UPDATE subcategories SET
                name = ?,
                brand = ?,
                price = ?,
                sku_no = ?,
                description = ?,
                Size = ?,
                image1 = ?,
                image2 = ?,
                image3 = ?,
                image4 = ?,
                Stock = ?,
                quantity = ?,
                category_id = ?
            WHERE id = ?
        ");

        // Bind the form data and image paths to the prepared statement
        $stmt->bind_param(
            "ssdssssssssiii",
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
            $Quanitiy,
            $Quanitiy,
            $category_id,
            $id
        );

        // Execute the update query
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
    } else {
        echo "Invalid Product ID";
    }

} else {
    echo "Invalid request method.";
}
?>
