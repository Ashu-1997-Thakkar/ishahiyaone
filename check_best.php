<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT count(*) as c FROM all_category WHERE is_best=1");
echo "all_cat is_best: " . $q->fetch_assoc()['c'] . "\n";
$q = $conn->query("SELECT count(*) as c FROM subcategories WHERE is_best=1");
echo "subcat is_best: " . $q->fetch_assoc()['c'] . "\n";
?>
