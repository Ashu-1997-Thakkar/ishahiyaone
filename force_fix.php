<?php
require_once 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$tables = ['all_category', 'subcategories', 'products'];
foreach ($tables as $table) {
    echo "Checking $table: ";
    $conn->query("ALTER TABLE `$table` ADD COLUMN IF NOT EXISTS `is_new_arrival` INT DEFAULT 1");
    $conn->query("UPDATE `$table` SET `is_new_arrival` = 1 WHERE `is_new_arrival` IS NULL OR `is_new_arrival` = 0");
    echo "Done.\n";
}
echo "Check complete.";
?>
