<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// Ensure we only output JSON
ob_start();

if (isset($_POST['update_subcategory']) || isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $subcategory_name = mysqli_real_escape_string($conn, $_POST['subcategory_name']);
    $collection_id = intval($_POST['collection_id']);
    $brand = mysqli_real_escape_string($conn, rtrim(trim($_POST['brand']), "/"));
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    function uploadImage(string $fieldName) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['name'] != "") {
            $imageName = time() . "_" . basename($_FILES[$fieldName]['name']);
            $tmp = $_FILES[$fieldName]['tmp_name'];
            $target_dir = "../uploads/subcategories/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            
            $folder = $target_dir . $imageName;
            if (move_uploaded_file($tmp, $folder)) {
                return "uploads/subcategories/" . $imageName;
            }
        }
        return null;
    }

    $img1 = uploadImage('image1');
    $img2 = uploadImage('image2');
    $img3 = uploadImage('image3');
    $img4 = uploadImage('image4');

    $set = "
        subcategory_name='$subcategory_name',
        brand='$brand',
        price='$price',
        description='$description',
        collection_id='$collection_id'
    ";

    if ($img1) $set .= ", image1='$img1'";
    if ($img2) $set .= ", image2='$img2'";
    if ($img3) $set .= ", image3='$img3'";
    if ($img4) $set .= ", image4='$img4'";

    $sql = "UPDATE subcategory SET $set WHERE id=$id";

    ob_clean(); // Clear any warnings/notices
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . mysqli_error($conn)]);
    }
} else {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'No data received']);
}
?>
