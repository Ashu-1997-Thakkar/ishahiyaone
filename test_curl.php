<?php
$ch = curl_init('http://localhost/ishahiyaone/shop_admin/controller/bumperOfferController.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['update_offer' => 1, 'offer_id' => 1, 'title' => 'test', 'description' => 'desc', 'start_date' => '2026-06-20', 'end_date' => '2026-07-09']);
$res = curl_exec($ch);
echo "Result:\n" . substr($res, 0, 500) . "\n";
?>
