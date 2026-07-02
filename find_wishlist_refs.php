<?php
header('Content-Type: text/plain');
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
foreach ($files as $f) {
    if ($f->isFile() && in_array($f->getExtension(), ['php', 'html', 'js'])) {
        if (strpos($f->getPathname(), 'error_log') !== false || strpos($f->getPathname(), '.git') !== false) continue;
        $c = @file_get_contents($f->getPathname());
        if ($c && strpos($c, 'toggle_wishlist.php') !== false) {
            echo $f->getPathname() . "\n";
        }
    }
}
if (file_exists(__FILE__)) @unlink(__FILE__);
