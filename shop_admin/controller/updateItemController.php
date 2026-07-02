<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// GET VALUES (keep compatibility)
$product_id = $_POST['product_id'];
$p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
$p_desc = mysqli_real_escape_string($conn, $_POST['p_desc']);
$p_price = $_POST['p_price'];
$category = $_POST['category'];

// 🔥 ADD THESE (missing fields)
$sub_category = $_POST['sub_category'] ?? 0;
$brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
$sku = mysqli_real_escape_string($conn, $_POST['sku'] ?? '');
$stock = $_POST['stock'] ?? 0;

// sizes (multi-select)
$sizes = '';
if (isset($_POST['sizes'])) {
    $sizes = is_array($_POST['sizes']) ? implode(',', $_POST['sizes']) : $_POST['sizes'];
}

// IMAGE UPLOAD
if (isset($_FILES['newImage']) && $_FILES['newImage']['name'] != '') {

    $img = $_FILES['newImage']['name'];
    $tmp = $_FILES['newImage']['tmp_name'];
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');

    if (in_array($ext, $valid_extensions)) {
        $image_name = time() . "_" . $img;
        move_uploaded_file($tmp, "../uploads/" . $image_name);
    } else {
        $image_name = $_POST['existingImage'];
    }

} else {
    $image_name = $_POST['existingImage'];
}

// ✅ FINAL UPDATE (THIS FIXES EVERYTHING)
$updateItem = mysqli_query($conn, "
UPDATE products SET 
    name = '$p_name',
    brand = '$brand',
    description = '$p_desc',
    price = '$p_price',
    category_id = '$category',
    sub_category_id = '$sub_category',
    size_ids = '$sizes',
    stock = '$stock',
    sku = '$sku',
    image = '$image_name'
WHERE product_id = '$product_id'
");

// RESULT
if ($updateItem) {
    echo "true";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>