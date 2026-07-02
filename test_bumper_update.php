<?php
$url = 'http://localhost/ishahiyaone/shop_admin/controller/updateBumperOfferStatus.php';
$data = ['product_id' => 1, 'is_bumper_offer' => 1];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "Result: " . $result;
?>
