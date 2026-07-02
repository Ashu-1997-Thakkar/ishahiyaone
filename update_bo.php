<?php
include_once "shop_admin/config/dbconnect.php";
$conn->query("UPDATE bumper_offers SET sub_category_id = 0 WHERE status = 1");
echo "Updated!";
?>
