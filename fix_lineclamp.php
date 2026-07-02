<?php
$content = file_get_contents('index.php');

// Fix line 394 area
$content = str_replace('-webkit-line-clamp: 2; -webkit-box-orient: vertical;', '-webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;', $content);

file_put_contents('index.php', $content);
echo "Fixed line-clamp";
