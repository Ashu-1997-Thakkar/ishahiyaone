<?php
$content = file_get_contents('index.php');
$pattern = '/<div style="display:block; background:#0a1128; color:#fff; font-weight:700; padding:8px; border-radius:6px; transition:0.3s; font-size:0.85rem;">\s*<\?= htmlspecialchars\(\$row\[\'cta_text\'\] \?: \'Shop Now\'\) \?>\s*<\/div>/s';
$content = preg_replace($pattern, '', $content);
file_put_contents('index.php', $content);
echo "Done";
