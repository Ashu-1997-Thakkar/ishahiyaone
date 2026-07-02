<?php
$targetDir = "e:/wamp64/www/ishahiyaone/uploads/offers/";
echo "Dir exists: " . (file_exists($targetDir) ? 'Yes' : 'No') . "\n";
echo "Dir writable: " . (is_writable($targetDir) ? 'Yes' : 'No') . "\n";
?>
