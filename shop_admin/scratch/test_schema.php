<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/dbconnect.php';
$tables = ['subcategories', 'all_category', 'products', 'admin_coll_item'];
foreach($tables as $t) {
    echo "<b>$t</b><br>";
    $res = $conn->query("SHOW COLUMNS FROM $t");
    if (!$res) { echo $conn->error . "<br>"; continue; }
    while($r = $res->fetch_assoc()) {
        if(stripos($r['Field'], 'image') !== false || stripos($r['Field'], 'name') !== false) {
            echo $r['Field'] . " - " . $r['Type'] . "<br>";
        }
    }
    echo "<hr>";
}
