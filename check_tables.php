<?php
$conn = new mysqli("localhost", "ishahiyaone", "BhaV@1437I", "ishahiyaone");
$res = $conn->query("SHOW TABLES LIKE '%bumper%'");
while($row = $res->fetch_array()) {
    echo $row[0] . "\n";
}
$res = $conn->query("DESCRIBE bumper_offers");
echo "DESCRIBE bumper_offers:\n";
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
