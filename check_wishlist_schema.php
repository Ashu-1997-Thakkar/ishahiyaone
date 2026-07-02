<?php
include_once "shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$res = $conn->query("DESCRIBE wishlist");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
