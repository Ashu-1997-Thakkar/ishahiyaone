<?php
include_once __DIR__ . "/shop_admin/config/dbconnect.php";
$conn->query("ALTER TABLE promo_codes ADD COLUMN offer_name VARCHAR(255) NULL AFTER id");
echo $conn->error;
?>
