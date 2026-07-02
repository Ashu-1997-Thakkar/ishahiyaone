<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "UPDATE all_category SET is_new_arrival = 1 WHERE id = $id";


    if (mysqli_query($conn, $sql)) {
        // Redirect back to admin page
     echo "<script>window.location.href = '/shop_admin/index.php#products';</script>";
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
