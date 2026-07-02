<?php
include('db.php');
$res = $conn->query("SHOW COLUMNS FROM products");
while($r = $res->fetch_assoc()) {
    echo $r['Field'] . ' ';
}
echo "\n";
$res2 = $conn->query("SHOW COLUMNS FROM all_category");
while($r = $res2->fetch_assoc()) {
    echo $r['Field'] . ' ';
}
