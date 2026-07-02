<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SHOW CREATE TABLE subcategories");
$row = $res->fetch_row();
echo $row[1] . "\n\n";
?>
