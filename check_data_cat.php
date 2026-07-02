<?php
include_once "shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$result = $conn->query("SELECT id, name, category_id FROM subcategories LIMIT 5");
while($row = $result->fetch_assoc()) {
    echo $row['id'] . " - " . $row['name'] . " - CatID: " . $row['category_id'] . "\n";
}
?>
