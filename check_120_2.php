<?php
include_once "shop_admin/config/dbconnect.php";
$q = $conn->query("SELECT * FROM sub_category WHERE id = 120");
if ($r = $q->fetch_assoc()) {
    print_r($r);
} else {
    echo "No sub_category with id 120\n";
}

$q2 = $conn->query("SELECT * FROM products WHERE sub_category_id = 120 OR category_id = 120 LIMIT 5");
echo "Products in sub_category_id 120 or category_id 120:\n";
while($r = $q2->fetch_assoc()) {
    echo $r['name'] . "\n";
}
?>
