<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT * FROM subcategories WHERE sub_category LIKE '%Laptop%' OR category_id = 120");
while($r = $q->fetch_assoc()) {
    echo "ID: " . $r['category_id'] . " Name: " . $r['sub_category'] . "\n";
}
?>
