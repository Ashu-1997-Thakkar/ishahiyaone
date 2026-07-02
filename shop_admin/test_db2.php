<?php
include_once "config/dbconnect.php";
/** @var mysqli $conn */
$res = $conn->query("SELECT id, name FROM subcategories");
echo "Subcategories:\n";
while ($r = $res->fetch_assoc()) {
    echo $r['id'] . " - " . $r['name'] . "\n";
}

$res2 = $conn->query("SELECT product_id, name FROM products");
echo "Products:\n";
while ($r = $res2->fetch_assoc()) {
    echo $r['product_id'] . " - " . $r['name'] . "\n";
}

$res3 = $conn->query("SELECT id, name FROM all_category");
echo "all_category:\n";
while ($r = $res3->fetch_assoc()) {
    echo $r['id'] . " - " . $r['name'] . "\n";
}
?>
