<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../shop_admin/config/dbconnect.php';
/** @var mysqli $conn */

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 100000;
$brand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';

// Base queries across tables
$wheres = [];
if ($category_id > 0) {
    $wheres[] = "(sc.category_id = $category_id OR sc.main_category_id = $category_id)";
}
if ($min_price > 0 || $max_price < 100000) {
    $wheres[] = "sc.price BETWEEN $min_price AND $max_price";
}
if ($brand !== '') {
    $safe_b = $conn->real_escape_string($brand);
    $wheres[] = "sc.brand = '$safe_b'";
}

$where_sql = !empty($wheres) ? "WHERE " . implode(' AND ', $wheres) : "";

$sort_sql = "ORDER BY sc.id DESC";
if ($sort === 'price_asc') $sort_sql = "ORDER BY sc.price ASC";
if ($sort === 'price_desc') $sort_sql = "ORDER BY sc.price DESC";

$sql = "SELECT sc.id, sc.name, sc.price, sc.brand, sc.Image1 as image, 'subcategories' as source 
        FROM subcategories sc $where_sql $sort_sql";

$res = $conn->query($sql);
$products = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $img = trim($row['image']);
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

        $products[] = [
            'id' => (int)$row['id'],
            'name' => htmlspecialchars_decode($row['name']),
            'price' => number_format((float)($row['price'] ?? 0), 2),
            'brand' => $row['brand'] ?: 'Ishahiya',
            'image' => $imgPath,
            'url' => 'drt.php?product_id=' . $row['id'] . '&source=' . $row['source'] . '&hide_timer=1'
        ];
    }
}

echo json_encode(['status' => 'success', 'count' => count($products), 'products' => $products]);
$conn->close();
