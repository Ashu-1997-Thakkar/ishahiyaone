<?php
include_once "shop_admin/config/dbconnect.php";
$conn->query("UPDATE bumper_offers SET sub_category_id = 120 WHERE status = 1");
$conn->query("UPDATE all_category SET is_best = 0");
$conn->query("UPDATE subcategories SET is_best = 0");
echo "Reverted dummy data!";
?>
