<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT * FROM sub_category WHERE id = 123");
if($r = $q->fetch_assoc()) echo $r['sub_category_name'];
?>
