<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SHOW CREATE TABLE product_size_variation");
$row = $res->fetch_row();
echo $row[1] . "\n\n";
?>
