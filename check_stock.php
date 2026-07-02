<?php
include_once "./shop_admin/config/dbconnect.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prd_id'], $_POST['size'])) {
    $product_id = (int) $_POST['prd_id'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);

    // Query subcategories table (adjust if your size column is different)
    $sql = "SELECT Stock 
            FROM subcategories 
            WHERE id = $product_id 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $stock = (int)$row['Stock'];

        if ($stock > 0) {
            echo json_encode(["success" => true, "Stock" => $stock, "status" => "Available in Stock"]);
        } else {
            echo json_encode(["success" => true, "Stock" => 0, "status" => "Out of Stock"]);
        }
    } else {
        echo json_encode(["success" => true, "Stock" => 0, "status" => "Out of Stock"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid Request"]);
