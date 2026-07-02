<?php
$url = "http://localhost/ishahiyaone/drt.php?product_id=19";
$data = ['prd_id' => 19, 'size' => 'One Size'];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "Result: " . $result;
?>
