<?php
require_once __DIR__ . '/db.php';
header('Content-Type: text/plain; charset=utf-8');

function checkTable($conn, $table, $col) {
    echo "=== Checking Table: $table ===\n";
    $res = $conn->query("SELECT id, name, $col AS img FROM $table WHERE is_new_arrival = 1");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo "ID: {$row['id']} | Name: {$row['name']}\n";
            echo "Img Col Val: '{$row['img']}'\n";
            
            // test file existence
            $base = basename(trim($row['img']));
            $p1 = __DIR__ . '/shop_admin/uploads/subshop/' . $base;
            $p2 = __DIR__ . '/shop_admin/uploads/' . $base;
            $p3 = __DIR__ . '/image/subcategories/' . $base;
            $p4 = __DIR__ . '/' . ltrim($row['img'], '/');
            echo "subshop/: " . (file_exists($p1) ? "FOUND ($p1)" : "missing") . "\n";
            echo "uploads/: " . (file_exists($p2) ? "FOUND ($p2)" : "missing") . "\n";
            echo "subcategories/: " . (file_exists($p3) ? "FOUND ($p3)" : "missing") . "\n";
            echo "direct: " . (file_exists($p4) ? "FOUND ($p4)" : "missing") . "\n";
            echo "----------------------------------------\n";
        }
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

checkTable($conn, 'all_category', 'Image1');
checkTable($conn, 'subcategories', 'Image1');
checkTable($conn, 'products', 'image');
