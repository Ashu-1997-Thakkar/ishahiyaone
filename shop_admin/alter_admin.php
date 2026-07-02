<?php
require __DIR__ . '/config/dbconnect.php';

$sql1 = "ALTER TABLE admin ADD COLUMN full_name VARCHAR(100) NULL AFTER id";
$sql2 = "ALTER TABLE admin ADD COLUMN email VARCHAR(100) NULL AFTER full_name";

if($conn->query($sql1)) echo "Added full_name\n";
else echo "Failed full_name: " . $conn->error . "\n";

if($conn->query($sql2)) echo "Added email\n";
else echo "Failed email: " . $conn->error . "\n";

echo "Done.";
?>
