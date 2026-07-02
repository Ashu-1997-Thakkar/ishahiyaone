<?php
include 'db.php';
echo "--- subcategory ---\n";
$res = mysqli_query($conn, 'DESCRIBE subcategory');
while($row = mysqli_fetch_assoc($res)) print_r($row);
echo "\n--- subcategories ---\n";
$res = mysqli_query($conn, 'DESCRIBE subcategories');
if($res) while($row = mysqli_fetch_assoc($res)) print_r($row);
?>
