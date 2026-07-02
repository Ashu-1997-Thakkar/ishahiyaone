// <?php
// header("Content-Type: text/plain");

// // Database connection
// include(dirname(__DIR__) . "/config/dbconnect.php");

// // Get and sanitize
// $product_id = isset($_POST['prd_id']) ? (int)$_POST['prd_id'] : 0;
// $size = isset($_POST['size']) ? mysqli_real_escape_string($conn, $_POST['size']) : '';

// // Validate inputs
// if (!$product_id || !$size) {
//     echo "0";
//     exit;
// }

// // Example table: subcategories
// $sql = "SELECT Stock FROM subcategories WHERE id = $product_id AND size = '$size' LIMIT 1";
// $result = mysqli_query($conn, $sql);

// // Return stock
// if ($result && mysqli_num_rows($result) > 0) {
//     $row = mysqli_fetch_assoc($result);
//     echo $row['Stock']; // case-sensitive!
// } else {
//     echo "0";
// }
// ?>


<?php
header("Content-Type: text/plain");
include(dirname(__DIR__) . "/config/dbconnect.php");

$product_id = isset($_POST['prd_id']) ? (int)$_POST['prd_id'] : 0;

if (!$product_id) {
    echo "0"; // invalid input
    exit;
}

// Fetch stock for this product
$sql = "SELECT Stock FROM subcategories WHERE id = $product_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stock = (int)$row['Stock'];

    if ($stock > 0) {
        echo "1"; // ✅ Available
    } else {
        echo "0"; // ❌ Out of Stock
    }
} else {
    echo "0"; // product not found
}
?>
