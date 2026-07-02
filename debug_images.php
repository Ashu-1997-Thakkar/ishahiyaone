<?php
include_once __DIR__ . "/shop_admin/config/dbconnect.php";

echo "=== HERO SLIDER RECORDS ===\n";
$r = $conn->query("SELECT id, image, title FROM hero_slider ORDER BY id DESC");
while($row = $r->fetch_assoc()) {
    $path = __DIR__ . '/uploads/slider/' . $row['image'];
    echo "ID:{$row['id']} | File: {$row['image']} | Exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
}

echo "\n=== PROMO CODES (Hero/Both) ===\n";
$r2 = $conn->query("SELECT id, offer_name, image, display_location, status, starts_at, ends_at FROM promo_codes ORDER BY id DESC LIMIT 10");
while($row = $r2->fetch_assoc()) {
    $path = __DIR__ . '/uploads/promo/' . $row['image'];
    $active = ($row['status']==1) ? 'Active' : 'Inactive';
    echo "ID:{$row['id']} | {$row['offer_name']} | Loc:{$row['display_location']} | {$active} | {$row['starts_at']} to {$row['ends_at']} | File:{$row['image']} | Exists:" . (file_exists($path) ? 'YES' : 'NO') . "\n";
}
?>
