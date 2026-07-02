<?php
error_reporting(0);
require_once __DIR__ . '/db.php';

echo "=== SUBCATEGORIES NEW ARRIVALS ===<br>\n";
$r = $conn->query("SELECT id, name, Image1 FROM subcategories WHERE is_new_arrival = 1");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $img = trim($row['Image1']);
        echo "ID: " . $row['id'] . " | Name: " . htmlspecialchars($row['name']) . "<br>\n";
        echo "Image Col: '" . htmlspecialchars($img) . "'<br>\n";
        $base = basename($img);
        echo "Exists in shop_admin/uploads/: " . (file_exists("shop_admin/uploads/" . $base) ? "YES" : "NO") . "<br>\n";
        echo "Exists in shop_admin/uploads/subshop/: " . (file_exists("shop_admin/uploads/subshop/" . $base) ? "YES" : "NO") . "<br>\n";
        echo "Exists in image/subcategories/: " . (file_exists("image/subcategories/" . $base) ? "YES" : "NO") . "<br><br>\n";
    }
}

echo "=== ALL CATEGORY NEW ARRIVALS ===<br>\n";
$r2 = $conn->query("SELECT id, name, Image1 FROM all_category WHERE is_new_arrival = 1");
if ($r2) {
    while ($row = $r2->fetch_assoc()) {
        $img = trim($row['Image1']);
        echo "ID: " . $row['id'] . " | Name: " . htmlspecialchars($row['name']) . "<br>\n";
        echo "Image Col: '" . htmlspecialchars($img) . "'<br>\n";
        $base = basename($img);
        echo "Exists in shop_admin/uploads/: " . (file_exists("shop_admin/uploads/" . $base) ? "YES" : "NO") . "<br>\n";
        echo "Exists in shop_admin/uploads/subshop/: " . (file_exists("shop_admin/uploads/subshop/" . $base) ? "YES" : "NO") . "<br>\n";
        echo "Exists in image/subcategories/: " . (file_exists("image/subcategories/" . $base) ? "YES" : "NO") . "<br><br>\n";
    }
}

echo "=== PRODUCTS NEW ARRIVALS ===<br>\n";
$r3 = $conn->query("SELECT product_id, name, image FROM products WHERE is_new_arrival = 1");
if ($r3) {
    while ($row = $r3->fetch_assoc()) {
        $img = trim($row['image']);
        echo "ID: " . $row['product_id'] . " | Name: " . htmlspecialchars($row['name']) . "<br>\n";
        echo "Image Col: '" . htmlspecialchars($img) . "'<br>\n";
        $base = basename($img);
        echo "Exists in shop_admin/uploads/: " . (file_exists("shop_admin/uploads/" . $base) ? "YES" : "NO") . "<br>\n";
        echo "Exists in shop_admin/uploads/subshop/: " . (file_exists("shop_admin/uploads/subshop/" . $base) ? "YES" : "NO") . "<br><br>\n";
    }
}
