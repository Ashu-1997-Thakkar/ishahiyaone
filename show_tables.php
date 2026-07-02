<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SHOW TABLES");
while ($r = $q->fetch_array()) {
    echo $r[0] . "\n";
}
?>
