<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "ishahiyaone", "BhaV@1437I", "ishahiyaone");

$tables = ['products', 'all_category', 'subcategories'];
$columns = [
    "ADD COLUMN bumper_title VARCHAR(255) NULL AFTER is_bumper_offer",
    "ADD COLUMN bumper_start_date DATETIME NULL AFTER bumper_title",
    "ADD COLUMN bumper_end_date DATETIME NULL AFTER bumper_start_date",
    "ADD COLUMN bumper_discount INT DEFAULT 0 AFTER bumper_end_date"
];

foreach ($tables as $table) {
    foreach ($columns as $col) {
        $sql = "ALTER TABLE $table $col";
        if ($conn->query($sql)) {
            echo "Added to $table successfully.<br>\n";
        } else {
            echo "Error adding to $table: " . $conn->error . "<br>\n";
        }
    }
}
?>
