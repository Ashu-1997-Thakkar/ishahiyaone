<?php
include_once "config/dbconnect.php";
/** @var mysqli $conn */
$res = $conn->query("SELECT id, name, category, sub_category_id, category_id, Stock FROM subcategories");
while ($r = $res->fetch_assoc()) {
    echo print_r($r, true) . "\n";
}
?>
