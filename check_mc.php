<?php
include_once "shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$result = $conn->query("SELECT id, main_category_name FROM main_category");
while($row = $result->fetch_assoc()) {
    echo $row['id'] . " - " . $row['main_category_name'] . "\n";
}
?>
