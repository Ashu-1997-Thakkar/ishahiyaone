<?php
require_once "e:/wamp64/www/ishahiyaone/shop_admin/config/dbconnect.php";
$result = $conn->query("DESCRIBE all_category");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
