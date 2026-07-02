<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get image filenames to delete
    $result = mysqli_query($conn, "SELECT Image1, Image2, Image3, Image4 FROM all_category WHERE id=$id");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        foreach (['Image1', 'Image2', 'Image3', 'Image4'] as $key) {
            $img = $row[$key];
            if (!empty($img)) {
                $filePath = dirname(__DIR__) . "/" . ltrim($img, '/');
                if (file_exists($filePath) && !is_dir($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }

    // Delete the product
    $delete = mysqli_query($conn, "DELETE FROM all_category WHERE id=$id");
    if ($delete) {
        echo "success";
    } else {
        echo "Error deleting record.";
    }
}
?>
