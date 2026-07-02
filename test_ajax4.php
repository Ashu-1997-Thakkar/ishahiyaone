<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SELECT id, main_category_id, category_id, name FROM subcategories WHERE category_id IN (SELECT id FROM sub_category WHERE main_category_id = 7)");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
