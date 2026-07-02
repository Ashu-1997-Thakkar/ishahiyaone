<?php
include_once "shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$result = $conn->query("SELECT id, sub_category_name FROM sub_category WHERE main_category_id = 6");
while($row = $result->fetch_assoc()) {
    echo $row['id'] . " - " . $row['sub_category_name'] . "\n";
}
?>
