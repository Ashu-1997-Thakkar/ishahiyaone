<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    $sql = "UPDATE products SET is_best = 0 WHERE product_id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "Product removed from Best Products.";
    } else {
        echo "Failed to update.";
    }
}
?>
