<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT * FROM all_category WHERE is_best = 1");
echo "all_category is_best=1: " . ($q ? $q->num_rows : 'Error: '.$conn->error) . "\n";
$q2 = $conn->query("SELECT * FROM subcategories WHERE is_best = 1");
echo "subcategories is_best=1: " . ($q2 ? $q2->num_rows : 'Error: '.$conn->error) . "\n";
?>
