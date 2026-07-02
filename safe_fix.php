<?php
require_once 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$tables = ['all_category', 'subcategories', 'products'];
foreach ($tables as $table) {
    echo "Table: $table - ";
    try {
        $conn->query("ALTER TABLE `$table` ADD `is_new_arrival` INT DEFAULT 1");
        echo "Column Added. ";
    } catch (Exception $e) {
        echo "Column maybe exists. ";
    }
    $conn->query("UPDATE `$table` SET `is_new_arrival` = 1");
    echo "Updated.\n";
}
echo "Done.";
?>
