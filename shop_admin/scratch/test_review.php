<?php
require_once '../config/dbconnect.php';
$res = $conn->query("SELECT * FROM product_reviews");
while($r = $res->fetch_assoc()) {
    echo json_encode($r) . "<br>";
}
