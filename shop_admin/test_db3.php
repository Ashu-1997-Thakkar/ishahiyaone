<?php
include_once "config/dbconnect.php";
/** @var mysqli $conn */
$tables = ['products', 'all_category', 'subcategories'];
foreach($tables as $t) {
    echo "TABLE: $t\n";
    $res = $conn->query("DESCRIBE $t");
    while($r = $res->fetch_assoc()){
        echo $r['Field'].", ";
    }
    echo "\n\n";
}
?>
