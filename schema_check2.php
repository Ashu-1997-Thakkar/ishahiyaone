<?php
include 'shop_admin/config/dbconnect.php';
$res = $conn->query("SHOW CREATE TABLE Customer");
if ($res) {
    $row = $res->fetch_row();
    echo $row[1] . "\n\n";
} else {
    echo "Error Customer: " . $conn->error . "\n";
    $res2 = $conn->query("SHOW CREATE TABLE customer");
    if ($res2) {
        $row2 = $res2->fetch_row();
        echo $row2[1] . "\n\n";
    } else {
        echo "Error customer: " . $conn->error . "\n";
    }
}
?>
