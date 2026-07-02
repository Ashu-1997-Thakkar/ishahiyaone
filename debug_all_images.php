<?php
include_once __DIR__ . "/shop_admin/config/dbconnect.php";
echo "<h2>Hero Slider Images</h2>";
$r = $conn->query("SELECT image FROM hero_slider");
while($row=$r->fetch_assoc()){
    $path='uploads/slider/'.$row['image'];
    $exists=file_exists(__DIR__.'/'.$path)?'✅':'❌';
    echo "$exists <a href='/$path' target='_blank'>$path</a><br>";
}

echo "<h2>Promo Code Images (active)</h2>";
$r=$conn->query("SELECT offer_name,image,category,display_location FROM promo_codes WHERE status=1");
while($row=$r->fetch_assoc()){
    $path='uploads/promo/'.$row['image'];
    $exists=file_exists(__DIR__.'/'.$path)?'✅':'❌';
    echo "{$exists} <b>{$row['offer_name']}</b> [{$row['category']} / {$row['display_location']}] - <a href='http://localhost/ishahiyaone/$path' target='_blank'>$path</a><br>";
}

echo "<h2>Best Seller Product Images</h2>";
$q="SELECT name,Image1,'all_category' AS src FROM all_category WHERE is_best=1 LIMIT 5
    UNION ALL
    SELECT name,image1,'subcategories' FROM subcategories WHERE is_best=1 LIMIT 5";
$r=$conn->query($q);
if($r) while($row=$r->fetch_assoc()){
    $raw=trim($row['Image1']);
    $base=basename($raw);
    $path=(strpos($raw,'shop_admin/uploads/')!==false)?$raw:'shop_admin/uploads/subshop/'.$base;
    $exists=file_exists(__DIR__.'/'.$path)?'✅':'❌';
    echo "{$exists} <b>{$row['name']}</b> - <a href='http://localhost/ishahiyaone/$path' target='_blank'>$path</a><br>";
}
?>
