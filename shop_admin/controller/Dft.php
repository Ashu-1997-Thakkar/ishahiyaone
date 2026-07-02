<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $collection_id = $_POST['collection_id'];
    $subcategory_name = $_POST['subcategory_name'];
    $price = $_POST['price'];
    $name = $_POST['name']; // Brand
    $des = $_POST['description'];

    // Handle image uploads (4 images)
    $image_paths = [];
    $target_dir = "../uploads/subcategories/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Loop through each image field and handle the upload
    for ($i = 1; $i <= 4; $i++) {
        $image_field = "image" . $i; // image1, image2, image3, image4
        if (isset($_FILES[$image_field]) && $_FILES[$image_field]['error'] == 0) {
            $filename = basename($_FILES[$image_field]["name"]);
            $target_file = $target_dir . time() . "_" . $filename;

            if (move_uploaded_file($_FILES[$image_field]["tmp_name"], $target_file)) {
                $image_paths[] = str_replace("../", "", $target_file); // Save relative path for DB
            }
        }
    }

    // SQL Insert - Saving image paths (only store those that were uploaded)
    $sql = "INSERT INTO subcategory (collection_id, subcategory_name, price, brand, description, image1, image2, image3, image4)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    // Use null for any empty image fields
    $image1 = isset($image_paths[0]) ? $image_paths[0] : null;
    $image2 = isset($image_paths[1]) ? $image_paths[1] : null;
    $image3 = isset($image_paths[2]) ? $image_paths[2] : null;
    $image4 = isset($image_paths[3]) ? $image_paths[3] : null;

    if ($stmt) {
        // Correct the parameter binding to match the query structure
        mysqli_stmt_bind_param($stmt, "issssssss", $collection_id, $subcategory_name, $price, $name, $des, $image1, $image2, $image3, $image4);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Subcategory added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_stmt_error($stmt)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'SQL Error: ' . mysqli_error($conn)]);
    }
}
?>
