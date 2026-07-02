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

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if ($user_id > 0) {
    try {
        $check = $conn->prepare("SELECT id FROM cart WHERE user_id = :uid AND product_id = :pid AND (size = :size OR :size = '') LIMIT 1");
        $check->execute([':uid' => $user_id, ':pid' => $pid, ':size' => $size]);
        $row = $check->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $upd = $conn->prepare("UPDATE cart SET quantity = quantity + :q WHERE id = :id AND user_id = :uid");
            $upd->execute([':q' => $qty, ':id' => $row['id'], ':uid' => $user_id]);
        } else {
            $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size) VALUES (:uid, :pid, :q, :size)");
            $ins->execute([':uid' => $user_id, ':pid' => $pid, ':q' => $qty, ':size' => $size]);
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
    
    // Fetch price/details from DB if possible so session cart has complete data
    $price = 0;
    $name = "Product #$pid";
    $image = "";
    try {
        $pStmt = $conn->prepare("SELECT name, price, original_price, img FROM products WHERE id = :pid LIMIT 1");
        $pStmt->execute([':pid' => $pid]);
        $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
        if ($pRow) {
            $name = $pRow['name'];
            $price = (float)$pRow['price'];
            $image = $pRow['img'];
        }
    } catch (Exception $ex) {}

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] += $qty;
        if ($size !== '') $_SESSION['cart'][$pid]['size'] = $size;
    } else {
        $_SESSION['cart'][$pid] = [
            'id' => $pid,
            'product_id' => $pid,
            'name' => $name,
            'price' => $price,
            'quantity' => $qty,
            'size' => $size,
            'image' => $image,
            'images1' => $image
        ];
    }
    exit("success");
}
