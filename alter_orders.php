<?php
include 'shop_admin/config/dbconnect.php';
$conn->query("ALTER TABLE orders ADD COLUMN user_id INT DEFAULT 0 AFTER id");
$conn->query("ALTER TABLE orders ADD COLUMN payment_mode VARCHAR(50) DEFAULT 'COD' AFTER total_price");
$conn->query("ALTER TABLE orders ADD COLUMN gst_amount DECIMAL(10,2) DEFAULT 0 AFTER payment_mode");
$conn->query("ALTER TABLE orders ADD COLUMN shipping_amount DECIMAL(10,2) DEFAULT 0 AFTER gst_amount");
echo "Done";
?>
