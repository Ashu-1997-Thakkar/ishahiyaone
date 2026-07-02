<?php
$host   = "localhost";
$user   = "ishahiyaone";
$pass   = "BhaV@1437I";
$dbname = "ishahiyaone";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = ['all_category', 'subcategories', 'products'];

foreach ($tables as $table) {
    echo "Fixing $table... ";
    $res = $conn->query("SHOW COLUMNS FROM $table LIKE 'is_new_arrival'");
    if ($res->num_rows == 0) {
        if ($conn->query("ALTER TABLE $table ADD COLUMN is_new_arrival INT DEFAULT 1")) {
            echo "Added column. ";
        } else {
            echo "Failed to add column: " . $conn->error . " ";
        }
    } else {
        echo "Column exists. ";
    }
    
    $conn->query("UPDATE $table SET is_new_arrival = 1");
    echo "Updated.\n";
}

echo "Database Fix Complete.";
?>
