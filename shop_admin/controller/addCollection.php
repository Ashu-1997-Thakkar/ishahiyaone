<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_name'], $_POST['category_name']) && isset($_FILES['image'])) {
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        
        // Find category_id from main_category table based on name
        $cat_id_result = $conn->query("SELECT id FROM main_category WHERE main_category_name = '$category_name' LIMIT 1");
        $category_id = ($cat_id_result && $cat_id_result->num_rows > 0) ? $cat_id_result->fetch_assoc()['id'] : 0;

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $target_dir = dirname(__DIR__) . "/uploads/";
            
            // Create uploads directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $sql = "INSERT INTO collections (product_name, category_name, category_id, image) VALUES ('$product_name', '$category_name', '$category_id', '$image_name')";
                if (mysqli_query($conn, $sql)) {
                    // Assuming this was called via AJAX or form submit, redirect back
                    echo "<script>alert('Collection added successfully'); window.location.href = '../index.php#collections';</script>";
                } else {
                    echo "Database Error: " . mysqli_error($conn);
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Error with image upload.";
        }
    } else {
        echo "All fields (product_name, category_name, image) are required.";
    }
} else {
    echo "Invalid request method.";
}
?>
