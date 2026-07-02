<?php
$_POST['update_offer'] = 1;
$_POST['offer_id'] = 1;
$_POST['title'] = "test";
$_POST['description'] = "test";
$_POST['discount_percent'] = 20;
$_POST['promo_code'] = "test";
$_POST['start_date'] = "2026-06-19";
$_POST['end_date'] = "2026-07-03";
$_POST['status'] = 1;
$_POST['sub_category_id'] = 123;
$_FILES['image'] = [
    'name' => 'test.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => __DIR__ . '/test.jpg',
    'error' => 0,
    'size' => 100
];
file_put_contents(__DIR__ . '/test.jpg', 'fake image');

ob_start();
include "shop_admin/controller/bumperOfferController.php";
$out = ob_get_clean();
echo "Controller output: " . $out . "\n";
unlink(__DIR__ . '/test.jpg');
?>
