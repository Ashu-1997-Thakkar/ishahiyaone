<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SELECT * FROM product_size_variation WHERE product_id = 21");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
