<?php
$content = file_get_contents('index.php');

$search1 = "    .btn-premium-outline:hover { background: var(--black); color: #fff; }\n      border-radius: 30px; flex: 1;";
$replace1 = "    .btn-premium-outline:hover { background: var(--black); color: #fff; }\n    .newsletter-form .form-control {\n      border-radius: 30px; flex: 1;";
$content = str_replace($search1, $replace1, $content);

$search1_rn = "    .btn-premium-outline:hover { background: var(--black); color: #fff; }\r\n      border-radius: 30px; flex: 1;";
$replace1_rn = "    .btn-premium-outline:hover { background: var(--black); color: #fff; }\r\n    .newsletter-form .form-control {\r\n      border-radius: 30px; flex: 1;";
$content = str_replace($search1_rn, $replace1_rn, $content);

$search2 = "      transform: scale(1.2);\n    }\n      \$promoRes = \$db->query";
$replace2 = "      transform: scale(1.2);\n    }\n  </style>\n\n  <?php\n      \$promoRes = \$db->query";
$content = str_replace($search2, $replace2, $content);

$search2_rn = "      transform: scale(1.2);\r\n    }\r\n      \$promoRes = \$db->query";
$replace2_rn = "      transform: scale(1.2);\r\n    }\r\n  </style>\r\n\r\n  <?php\r\n      \$promoRes = \$db->query";
$content = str_replace($search2_rn, $replace2_rn, $content);

file_put_contents('index.php', $content);
echo "Fixed";
