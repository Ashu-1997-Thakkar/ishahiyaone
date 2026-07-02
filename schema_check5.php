<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SELECT quantity, Stock FROM subcategories WHERE id = 19");
print_r($res->fetch_assoc());
?>
