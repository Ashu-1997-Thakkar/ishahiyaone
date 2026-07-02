<?php
$content = file_get_contents('index.php');
$search = "  <?php\n      \$promoRes";
$search_rn = "  <?php\r\n      \$promoRes";

$replace = "  <?php\n      \$sliderRes = \$db->query(\"SELECT title, subtitle, image, link, btn_text FROM hero_slider ORDER BY id DESC\");\n      \$sliders = \$sliderRes->fetchAll(PDO::FETCH_ASSOC);\n      foreach(\$sliders as &\$s) { \$s['img_path'] = 'shop_admin/uploads/' . \$s['image']; }\n\n      \$promoRes";
$replace_rn = "  <?php\r\n      \$sliderRes = \$db->query(\"SELECT title, subtitle, image, link, btn_text FROM hero_slider ORDER BY id DESC\");\r\n      \$sliders = \$sliderRes->fetchAll(PDO::FETCH_ASSOC);\r\n      foreach(\$sliders as &\$s) { \$s['img_path'] = 'shop_admin/uploads/' . \$s['image']; }\r\n\r\n      \$promoRes";

$content = str_replace($search, $replace, $content);
$content = str_replace($search_rn, $replace_rn, $content);

file_put_contents('index.php', $content);
echo "Fixed sliders variable";
