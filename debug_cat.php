<?php
include_once __DIR__ . "/shop_admin/config/dbconnect.php";
$r = $conn->query("SELECT id, offer_name, category, image, display_location FROM promo_codes");
while($row = $r->fetch_assoc()) { echo json_encode($row) . "\n"; }
?>
