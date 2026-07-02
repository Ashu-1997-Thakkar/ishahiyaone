<?php
session_start();
header('Content-Type: text/plain');
require_once 'db.php';

echo "SESSION DUMP:\n";
print_r($_SESSION);

echo "\nWISHLIST TABLE COUNT:\n";
$res = $conn->query("SELECT * FROM wishlist");
echo "Total rows: " . ($res ? $res->num_rows : 0) . "\n";
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        print_r($r);
    }
}
