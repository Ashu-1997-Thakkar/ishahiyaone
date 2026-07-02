<?php
include 'shop_admin/config/dbconnect.php';

$res = $conn->query("SHOW CREATE TABLE order_items");
$row = $res->fetch_row();
echo $row[1] . "\n\n";

$res = $conn->query("SHOW CREATE TABLE billing_details");
$row = $res->fetch_row();
echo $row[1] . "\n\n";

$res = $conn->query("SHOW TABLES LIKE 'orders'");
if($res->num_rows > 0) {
    $res = $conn->query("SHOW CREATE TABLE orders");
    $row = $res->fetch_row();
    echo $row[1] . "\n\n";
} else {
    echo "orders table does not exist.\n";
}
?>
