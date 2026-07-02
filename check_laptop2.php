<?php
include_once "shop_admin/config/dbconnect.php";
$subcatRes = $conn->query("SELECT * FROM sub_category WHERE sub_category_name LIKE '%Laptop%'");
$sc = $subcatRes->fetch_assoc();
if ($sc) {
    $subId = $sc['id'];
    echo "Laptop subcategory ID: " . $subId . "\n";
    $q1 = $conn->query("SELECT * FROM all_category WHERE sub_category_id=$subId");
    echo "all_category: " . ($q1 ? $q1->num_rows : 'Error: '.$conn->error) . "\n";
    $q2 = $conn->query("SELECT * FROM subcategories WHERE category_id=$subId");
    echo "subcategories: " . ($q2 ? $q2->num_rows : 'Error: '.$conn->error) . "\n";
    $q3 = $conn->query("SELECT * FROM products WHERE sub_category_id=$subId");
    echo "products: " . ($q3 ? $q3->num_rows : 'Error: '.$conn->error) . "\n";
}
?>
