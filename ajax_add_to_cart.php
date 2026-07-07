<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Database connection
$server   = "localhost";
$user     = "ishahiyaone";
$password = "BhaV@1437I";
$database = "ishahiyaone";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$pid  = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty  = isset($_POST['product_quantity']) ? (int)$_POST['product_quantity'] : 1;
$size = isset($_POST['product_size']) ? trim($_POST['product_size']) : '';

if ($pid <= 0 || $qty <= 0) {
    http_response_code(400);
    exit("error:bad_input");
}

$name  = $_POST['product_name'] ?? $_POST['name'] ?? '';
$price_raw = $_POST['product_price'] ?? $_POST['price'] ?? 0;
if (is_string($price_raw)) {
    $price_raw = preg_replace('/[^\d.]/', '', $price_raw);
}
$price = (float)$price_raw;
$image = $_POST['product_image'] ?? $_POST['image'] ?? $_POST['images1'] ?? '';
$sku   = $_POST['sku_no'] ?? $_POST['sku'] ?? '';

// Fallback to DB lookup across all product tables if details are missing
if (empty($name) || $price <= 0 || empty($image)) {
    try {
        $pStmt = $conn->prepare("
            SELECT name, price, Image1 AS img, sku_no FROM all_category WHERE id = :p1
            UNION ALL
            SELECT name, price, Image1 AS img, sku_no FROM subcategories WHERE id = :p2
            UNION ALL
            SELECT name, price, image AS img, sku AS sku_no FROM products WHERE product_id = :p3
            LIMIT 1
        ");
        $pStmt->execute([':p1' => $pid, ':p2' => $pid, ':p3' => $pid]);
        $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
        if ($pRow) {
            if (empty($name)) $name = $pRow['name'];
            if ($price <= 0) $price = (float)$pRow['price'];
            if (empty($image)) $image = $pRow['img'];
            if (empty($sku)) $sku = $pRow['sku_no'];
        }
    } catch (Exception $ex) {}
}
if (empty($name)) $name = "Product #$pid";

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if ($user_id > 0) {
    try {
        $check = $conn->prepare("SELECT id FROM cart WHERE user_id = :uid AND product_id = :pid AND (size = :size OR :size = '') LIMIT 1");
        $check->execute([':uid' => $user_id, ':pid' => $pid, ':size' => $size]);
        $row = $check->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $upd = $conn->prepare("UPDATE cart SET quantity = quantity + :q, name = COALESCE(NULLIF(:name, ''), name), price = IF(:price > 0, :price, price), images1 = COALESCE(NULLIF(:img, ''), images1), sku_no = COALESCE(NULLIF(:sku, ''), sku_no) WHERE id = :id AND user_id = :uid");
            $upd->execute([':q' => $qty, ':name' => $name, ':price' => $price, ':img' => $image, ':sku' => $sku, ':id' => $row['id'], ':uid' => $user_id]);
        } else {
            $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, name, price, images1, sku_no) VALUES (:uid, :pid, :q, :size, :name, :price, :img, :sku)");
            $ins->execute([':uid' => $user_id, ':pid' => $pid, ':q' => $qty, ':size' => $size, ':name' => $name, ':price' => $price, ':img' => $image, ':sku' => $sku]);
        }

        exit("success");
    } catch (PDOException $e) {
        http_response_code(500);
        exit("error:db_write");
    }
} else {
    // Guest user: manage cart in session
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] += $qty;
        if ($size !== '') $_SESSION['cart'][$pid]['size'] = $size;
        if ($price > 0) $_SESSION['cart'][$pid]['price'] = $price;
        if (!empty($name)) $_SESSION['cart'][$pid]['name'] = $name;
        if (!empty($image)) {
            $_SESSION['cart'][$pid]['image'] = $image;
            $_SESSION['cart'][$pid]['images1'] = $image;
        }
    } else {
        $_SESSION['cart'][$pid] = [
            'id' => $pid,
            'product_id' => $pid,
            'name' => $name,
            'price' => $price,
            'quantity' => $qty,
            'size' => $size,
            'image' => $image,
            'images1' => $image,
            'sku_no' => $sku
        ];
    }
    exit("success");
}
