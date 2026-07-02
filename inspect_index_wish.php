<?php
header('Content-Type: text/plain');
$c = file_get_contents('index.php');
$pos = strpos($c, 'toggle_wishlist.php');
if ($pos !== false) {
    echo substr($c, max(0, $pos - 200), 1000);
}
