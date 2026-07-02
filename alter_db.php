<?php
require_once "e:/wamp64/www/ishahiyaone/shop_admin/config/dbconnect.php";

$queries = [
    "ALTER TABLE all_category ADD COLUMN sku_no VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE all_category ADD COLUMN quantity INT DEFAULT 0",
    "ALTER TABLE all_category ADD COLUMN size VARCHAR(255) DEFAULT NULL"
];

foreach ($queries as $q) {
    if ($conn->query($q) === TRUE) {
        echo "Successfully executed: $q\n";
    } else {
        echo "Error executing $q: " . $conn->error . "\n";
    }
}
?>
