<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("DESCRIBE products");
while ($r = $q->fetch_assoc()) print_r($r);
?>
