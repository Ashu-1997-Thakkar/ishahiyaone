<?php
require_once __DIR__ . '/db.php';
$sqlNew = "
    SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS img, CAST('all_category' AS CHAR CHARACTER SET utf8mb4) AS source FROM all_category WHERE is_new_arrival = 1
    UNION ALL
    SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS img, CAST('subcategories' AS CHAR CHARACTER SET utf8mb4) AS source FROM subcategories WHERE is_new_arrival = 1
    UNION ALL
    SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(image AS CHAR CHARACTER SET utf8mb4) AS img, CAST('products' AS CHAR CHARACTER SET utf8mb4) AS source FROM products WHERE is_new_arrival = 1
";
$res = $conn->query($sqlNew);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $img = trim($row['img'] ?? '');
        $base = basename($img);
        $p1 = 'shop_admin/uploads/subshop/' . $base;
        $p2 = 'shop_admin/uploads/' . $base;
        $p3 = 'image/subcategories/' . $base;
        $p4 = ltrim($img, '/');
        echo "Product: {$row['name']} (Source: {$row['source']})\n";
        echo "Raw img val: '{$img}' | Base: '{$base}'\n";
        echo "In subshop/: " . (file_exists(__DIR__ . '/' . $p1) ? "YES ($p1)" : 'NO') . "\n";
        echo "In uploads/: " . (file_exists(__DIR__ . '/' . $p2) ? "YES ($p2)" : 'NO') . "\n";
        echo "In subcat/: " . (file_exists(__DIR__ . '/' . $p3) ? "YES ($p3)" : 'NO') . "\n";
        echo "Direct: " . (file_exists(__DIR__ . '/' . $p4) ? "YES ($p4)" : 'NO') . "\n\n";
    }
} else {
    echo "Query error: " . $conn->error;
}
