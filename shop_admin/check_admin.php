<?php
require __DIR__ . '/config/dbconnect.php';

$res = $conn->query("DESCRIBE admin");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
