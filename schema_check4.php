<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SHOW CREATE TABLE cart");
$row = $res->fetch_row();
echo $row[1] . "\n\n";
?>
