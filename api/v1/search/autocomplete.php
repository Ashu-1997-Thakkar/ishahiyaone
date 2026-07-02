<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if (strlen($query) < 2) {
    echo json_encode(['status' => 'success', 'suggestions' => []]);
    exit;
}

$safe_q = $conn->real_escape_string($query);

// Search across all 4 catalog tables
$sql = "(SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('all_category' AS CHAR CHARACTER SET utf8mb4) AS source 
         FROM all_category WHERE name LIKE '%$safe_q%' OR brand LIKE '%$safe_q%' LIMIT 4)
        UNION ALL
        (SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('subcategories' AS CHAR CHARACTER SET utf8mb4) AS source 
         FROM subcategories WHERE name LIKE '%$safe_q%' OR brand LIKE '%$safe_q%' LIMIT 4)
        UNION ALL
        (SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(image AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('products' AS CHAR CHARACTER SET utf8mb4) AS source 
         FROM products WHERE name LIKE '%$safe_q%' OR brand LIKE '%$safe_q%' LIMIT 4)
        UNION ALL
        (SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST('Ishahiya' AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('subshop' AS CHAR CHARACTER SET utf8mb4) AS source 
         FROM subshop WHERE name LIKE '%$safe_q%' LIMIT 4)
        LIMIT 8";

$res = $conn->query($sql);
$suggestions = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $img = trim($row['image'] ?? '');
        $basename = basename($img);
        if (empty($img)) {
            $imgPath = 'shop_admin/uploads/no-image.png';
        } else {
            $pathMain = __DIR__ . '/../../../shop_admin/uploads/' . $basename;
            $pathSub = __DIR__ . '/../../../shop_admin/uploads/subshop/' . $basename;
            if (file_exists($pathSub)) {
                $imgPath = 'shop_admin/uploads/subshop/' . $basename;
            } elseif (file_exists($pathMain)) {
                $imgPath = 'shop_admin/uploads/' . $basename;
            } else {
                $imgPath = (strpos($img, 'shop_admin/') !== false) ? ltrim($img, '/') : 'shop_admin/uploads/subshop/' . $basename;
            }
        }

        $suggestions[] = [
            'id' => (int)$row['id'],
            'title' => htmlspecialchars_decode($row['name'] ?? ''),
            'price' => number_format((float)($row['price'] ?? 0), 2),
            'image' => $imgPath,
            'brand' => $row['brand'] ?: 'Ishahiya',
            'url' => 'drt.php?product_id=' . $row['id'] . '&source=' . $row['source'] . '&hide_timer=1'
        ];
    }
}

echo json_encode(['status' => 'success', 'suggestions' => $suggestions]);
$conn->close();
