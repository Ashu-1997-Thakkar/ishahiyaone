<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */ 
$q = "SELECT name, Image1 FROM all_category WHERE is_best=1 LIMIT 5"; 
$r = $conn->query($q); 
while($row = $r->fetch_assoc()){ 
    echo $row['name'] . ' -> ' . $row['Image1'] . "\n"; 
}
?>
