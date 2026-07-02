<?php
include_once __DIR__ . "/config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $collection_id = $_POST['collection_id'];
    $subcategory_name = $_POST['subcategory_name'];
    $category_name = $_POST['category_name']; // This is not stored, used for display only
    $price = $_POST['price'];
    $name = $_POST['name'];

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/subcategories/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $filename = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = str_replace("../", "", $target_file); // Save relative path for DB
        }
    }

    // SQL Insert
    $sql = "INSERT INTO subcategory (collection_id, subcategory_name, image_path, price, name)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issds", $collection_id, $subcategory_name, $image_path, $price, $name);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Subcategory added successfully!'); window.location.href = '../adminView/viewOurCollections.php';</script>";
        } else {
            echo "Error executing statement: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>
