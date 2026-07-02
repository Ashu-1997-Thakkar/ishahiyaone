<?php
include_once "shop_admin/config/dbconnect.php";
$conn->query("UPDATE all_category SET is_best=1 LIMIT 5");
$conn->query("UPDATE subcategories SET is_best=1 LIMIT 5");
echo "Updated!";
?>
