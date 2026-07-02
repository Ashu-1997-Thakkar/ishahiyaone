<?php
include_once __DIR__ . "/shop_admin/config/dbconnect.php";
$r = $conn->query("SHOW COLUMNS FROM hero_slider");
while($row = $r->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
// Also show 1 sample row
echo "\n--- Sample row ---\n";
$s = $conn->query("SELECT * FROM hero_slider LIMIT 1");
if($row = $s->fetch_assoc()) print_r($row);
?>
