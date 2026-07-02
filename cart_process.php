<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['product_id']) && isset($_GET['size'])) {
    $productId = $_GET['product_id'];
    $productSize = $_GET['size'];
    $uniqueKey = $productId . '_' . $productSize;

    if (isset($_SESSION['cart'][$uniqueKey])) {
        unset($_SESSION['cart'][$uniqueKey]);
    }
}

header("Location: cart.php");
exit;
?>
