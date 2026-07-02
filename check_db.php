<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */
$r = $conn->query("SELECT name FROM all_category LIMIT 10");
while($row=$r->fetch_assoc()) {
    echo $row['name'] . "\n";
}
?>
