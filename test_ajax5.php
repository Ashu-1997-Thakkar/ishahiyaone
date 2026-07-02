<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SELECT * FROM main_category");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
