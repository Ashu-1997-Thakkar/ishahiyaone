<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */
$res = $conn->query("DESCRIBE billing_details");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
