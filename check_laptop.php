<?php
include_once "shop_admin/config/dbconnect.php";
$subcatRes = $conn->query("SELECT * FROM sub_category WHERE sub_category_name LIKE '%Laptop%'");
$sc = $subcatRes->fetch_assoc();
if ($sc) {
    echo "Laptop subcategory ID: " . $sc['id'] . "\n";
    $prodRes = $conn->query("SELECT * FROM product WHERE sub_category_id = " . $sc['id']);
    echo "Products found: " . $prodRes->num_rows . "\n";
    while ($p = $prodRes->fetch_assoc()) {
        echo "- " . $p['product_name'] . "\n";
    }
} else {
    echo "No laptop subcategory found.\n";
}
?>
