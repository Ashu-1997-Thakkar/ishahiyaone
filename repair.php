<?php
$lines = file('index.php');
$replacement = [
"        <div class=\"carousel-indicators\">\n",
"          <?php foreach (\$all_sliders as \$index => \$slide): ?>\n",
"            <button type=\"button\" data-bs-target=\"#heroCarousel\" data-bs-slide-to=\"<?= \$index ?>\" class=\"<?= \$index === 0 ? 'active' : '' ?>\"></button>\n",
"          <?php endforeach; ?>\n",
"        </div>\n",
"        <div class=\"carousel-inner\">\n",
"          <?php foreach (\$all_sliders as \$index => \$slide): ?>\n",
"            <div class=\"carousel-item <?= \$index === 0 ? 'active' : '' ?>\">\n",
"              <?php \n",
"              // Convert spaces to %20 to ensure valid URLs\n",
"              \$rawLink = \$slide['link'] ?: 'shop.php';\n",
"              \$slideLink = htmlspecialchars(str_replace(' ', '%20', \$rawLink)); \n",
"              ?>\n",
"              <a href=\"<?= \$slideLink ?>\" style=\"display: block; position: relative;\">\n",
"                <div class=\"hero-overlay\"></div>\n",
"                <img src=\"<?= htmlspecialchars(\$slide['img_path']) ?>\" class=\"hero-image\" alt=\"<?= htmlspecialchars(\$slide['title']) ?>\">\n",
"              </a>\n",
"              <div class=\"carousel-caption\" style=\"pointer-events: auto;\">\n",
"                <?php if (!empty(\$slide['title'])): ?><h5><?= htmlspecialchars(\$slide['title']) ?></h5><?php endif; ?>\n",
"                <?php if (!empty(\$slide['subtitle'])): ?><h2><?= htmlspecialchars(\$slide['subtitle']) ?></h2><?php endif; ?>\n",
"                <a href=\"<?= \$slideLink ?>\" class=\"btn-premium\" style=\"position: relative; z-index: 10; pointer-events: auto !important; cursor: pointer;\"><?= htmlspecialchars(\$slide['btn_text'] ?: 'Shop Now') ?></a>\n",
"              </div>\n",
"            </div>\n",
"          <?php endforeach; ?>\n",
"        </div>\n"
];
array_splice($lines, 660, 4, $replacement);
file_put_contents('index.php', implode('', $lines));
