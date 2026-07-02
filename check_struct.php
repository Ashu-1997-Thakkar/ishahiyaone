<?php
include_once "shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$result = $conn->query("DESCRIBE subcategories");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
