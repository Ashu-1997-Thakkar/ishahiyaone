<?php
include 'shop_admin/config/dbconnect.php';
$conn->query("ALTER TABLE orders MODIFY Contact varchar(20)");
echo "Done";
?>
