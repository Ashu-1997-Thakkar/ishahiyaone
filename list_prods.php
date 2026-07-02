<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT * FROM products LIMIT 5");
while($r = $q->fetch_assoc()) echo $r['name'] . " (" . $r['sub_category_id'] . ")\n";
?>
