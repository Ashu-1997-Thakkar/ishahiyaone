<?php
include 'shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

echo "Checking products table structure...\n";

$queries = [
    "ALTER TABLE products ADD COLUMN sub_category_id INT DEFAULT 0 AFTER category_id",
    "ALTER TABLE products ADD COLUMN size_ids VARCHAR(255) DEFAULT '' AFTER sub_category_id",
    "ALTER TABLE products ADD COLUMN stock INT DEFAULT 0 AFTER size_ids",
    "ALTER TABLE products ADD COLUMN sku VARCHAR(100) DEFAULT '' AFTER stock"
];

foreach ($queries as $query) {
    try {
        if ($conn->query($query)) {
            echo "Success: $query\n";
        }
    } catch (Exception $e) {
        echo "Skipping/Error: " . $e->getMessage() . "\n";
    }
}

echo "Database fix complete.\n";
?>
