<?php
include_once __DIR__ . '/../shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

echo "--- all_category columns ---\n";
$res = $conn->query("SHOW COLUMNS FROM all_category");
while($r = $res->fetch_assoc()) echo $r['Field']." ";

echo "\n\n--- sample rows in all_category for MEN Wear (sub_category_id=123) ---\n";
$res = $conn->query("SELECT id, sub_category_id, main_category_id, name FROM all_category WHERE sub_category_id=123");
while($r = $res->fetch_assoc()) print_r($r);

echo "\n--- sample rows in subcategories for MEN Wear (category_id=123) ---\n";
$res = $conn->query("SELECT id, category_id, name FROM subcategories WHERE category_id=123");
while($r = $res->fetch_assoc()) print_r($r);
?>
