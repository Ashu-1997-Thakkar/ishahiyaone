<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['upload'])) {

    // ✅ SUPPORT BOTH OLD + NEW FIELD NAMES (NO LOGIC CHANGE)
    $ProductName = mysqli_real_escape_string($conn, $_POST['name'] ?? $_POST['p_name'] ?? '');
    $Brand       = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
    $Description = mysqli_real_escape_string($conn, $_POST['description'] ?? $_POST['p_desc'] ?? '');
    $Price       = $_POST['price'] ?? $_POST['p_price'] ?? 0;

    // ✅ CATEGORY DATA
    $Category    = $_POST['category'] ?? 0;
    $SubCategory = $_POST['sub_category'] ?? 0;

    // ✅ HANDLE SIZES (ARRAY OR STRING SAFE)
    if (isset($_POST['sizes'])) {
        $Sizes = is_array($_POST['sizes']) ? implode(',', $_POST['sizes']) : $_POST['sizes'];
    } else {
        $Sizes = '';
    }

    // ✅ STOCK + SKU
    $Stock = $_POST['stock'] ?? 0;
    $SKU   = mysqli_real_escape_string($conn, $_POST['sku'] ?? '');

    // ✅ IMAGE UPLOAD
    $fileName = "";
    if (!empty($_FILES['file']['name'])) {
        $fileName = time() . "_" . basename($_FILES['file']['name']);
        $temp = $_FILES['file']['tmp_name'];
        $target_path = "../uploads/" . $fileName;

        if (!move_uploaded_file($temp, $target_path)) {
            echo "Error uploading image.";
            exit;
        }
    }

    // ✅ CHECK DUPLICATE PRODUCT
    $checkQuery = "SELECT * FROM products WHERE name = '$ProductName'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Product already exists.";
        exit;
    }

    // ✅ INSERT DATA (FINAL)
    $insertQuery = "
        INSERT INTO products 
        (name, brand, description, price, image, category_id, sub_category_id, size_ids, stock, sku, is_new_arrival, created_at)
        VALUES 
        ('$ProductName', '$Brand', '$Description', '$Price', '$fileName', '$Category', '$SubCategory', '$Sizes', '$Stock', '$SKU', 1, NOW())
    ";

    $insert = mysqli_query($conn, $insertQuery);

    if ($insert) {
        echo "success"; // ✅ better for AJAX than alert
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>