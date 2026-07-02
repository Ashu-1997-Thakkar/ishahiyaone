<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */
$r = $conn->query("SELECT count(*) as c FROM subcategories WHERE sub_category_id=97");
echo "Count: " . $r->fetch_assoc()['c'] . "\n";
?>
