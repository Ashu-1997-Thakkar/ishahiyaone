<?php
include 'db.php';
$r1 = $conn->query("DESCRIBE all_category");
if ($r1) print_r($r1->fetch_all(MYSQLI_ASSOC));
$r2 = $conn->query("DESCRIBE subcategories");
if ($r2) print_r($r2->fetch_all(MYSQLI_ASSOC));
$r3 = $conn->query("DESCRIBE products");
if ($r3) print_r($r3->fetch_all(MYSQLI_ASSOC));
?>
