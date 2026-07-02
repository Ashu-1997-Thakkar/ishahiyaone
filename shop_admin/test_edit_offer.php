<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/ishahiyaone/shop_admin/controller/offerController.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'update_offer' => 1,
    'offer_id' => 1,
    'offer_title' => 'Test Edit',
    'timer_text' => 'Test Timer',
    'start_date' => '2026-05-15',
    'end_date' => '2026-06-15',
    'sub_category_id' => 1,
    'active' => 1
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response: " . $response;
?>
