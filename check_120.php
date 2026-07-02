<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT count(*) as c FROM products WHERE sub_category_id=120");
echo "products in 120: " . $q->fetch_assoc()['c'] . "\n";
$q = $conn->query("SELECT count(*) as c FROM all_category WHERE sub_category_id=120");
echo "all_cat in 120: " . $q->fetch_assoc()['c'] . "\n";
?>
