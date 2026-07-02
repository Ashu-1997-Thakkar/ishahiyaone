<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/dbconnect.php';
try {
    echo "--- all_category ---\n";
    $r = $conn->query("SHOW COLUMNS FROM all_category");
    while($row = $r->fetch_assoc()) echo $row['Field'] . "\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
