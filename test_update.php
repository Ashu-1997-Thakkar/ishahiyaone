<?php
$ch = curl_init('http://localhost/ishahiyaone/shop_admin/controller/bumperOfferController.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'update_offer' => '1',
    'offer_id' => '1',
    'title' => 'mega Offer',
    'description' => 'Test update',
    'discount_percent' => '25',
    'promo_code' => 'TEST25',
    'start_date' => '2026-06-19',
    'end_date' => '2026-07-03',
    'status' => '1',
    'sub_category_id' => '123'
]);
$res = curl_exec($ch);
curl_close($ch);
echo "Result: " . $res . "\n";
?>
