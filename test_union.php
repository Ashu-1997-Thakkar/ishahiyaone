<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */ 
$q = "SELECT id,name,brand,price,Image1,'all_category' AS source FROM all_category UNION ALL SELECT id,name,brand,price,image1,'subcategories' AS source FROM subcategories ORDER BY id DESC LIMIT 12"; 
$r = $conn->query($q);
if (!$r) {
    echo "SQL ERROR: " . $conn->error . "\n";
} else {
    echo "SUCCESS, row count: " . $r->num_rows . "\n";
}
?>
