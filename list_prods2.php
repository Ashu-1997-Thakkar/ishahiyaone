<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT id, name, sub_category_id FROM all_category LIMIT 5");
while($r = $q->fetch_assoc()) echo $r['name'] . " (all_cat sub_id: " . $r['sub_category_id'] . ")\n";
$q2 = $conn->query("SELECT id, name, category_id FROM subcategories LIMIT 5");
while($r = $q2->fetch_assoc()) echo $r['name'] . " (subcat cat_id: " . $r['category_id'] . ")\n";
?>
