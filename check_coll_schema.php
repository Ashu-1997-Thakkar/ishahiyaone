<?php
include 'db.php';
$res = mysqli_query($conn, 'DESCRIBE collections');
while($row = mysqli_fetch_assoc($res)) print_r($row);
?>
