<?php
include "e:/wamp64/www/ishahiyaone/shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
$r = mysqli_query($conn, "SELECT id, main_category_name, icon_class FROM main_category");
echo "ID | NAME | ICON\n";
echo "-------------------\n";
while($row = mysqli_fetch_assoc($r)) {
    echo $row['id'] . " | " . $row['main_category_name'] . " | " . ($row['icon_class'] ?: 'EMPTY') . "\n";
}
?>
