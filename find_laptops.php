<?php
include_once "shop_admin/config/dbconnect.php";
$tables = ['all_category', 'subcategories', 'products'];
foreach($tables as $t) {
    echo "Table $t:\n";
    $q = $conn->query("SELECT * FROM $t WHERE name LIKE '%Laptop%' LIMIT 5");
    if($q) {
        while($r = $q->fetch_assoc()) {
            echo " - " . $r['name'] . " (subcat_id: " . ($r['sub_category_id'] ?? $r['category_id'] ?? 'none') . ")\n";
        }
    }
}
?>
