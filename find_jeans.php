<?php
require_once __DIR__ . '/db.php';

// Check if any product or subcategory has a denim/jeans image
$res = $conn->query("SELECT product_id, name, image FROM products WHERE image != ''");
while ($r = $res->fetch_assoc()) {
    echo "Product {$r['product_id']}: {$r['name']} -> {$r['image']}\n";
}

$res2 = $conn->query("SELECT id, name, Image1 FROM all_category WHERE Image1 != ''");
while ($r = $res2->fetch_assoc()) {
    echo "AllCat {$r['id']}: {$r['name']} -> {$r['Image1']}\n";
}

// Also check disk files in subshop/
$files = glob("shop_admin/uploads/subshop/*.*");
echo "\nFiles in subshop/:\n";
foreach ($files as $f) {
    echo $f . "\n";
}
