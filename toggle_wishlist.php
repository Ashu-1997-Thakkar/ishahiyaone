<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    if (isset($_SESSION['customer_id'])) {
        $_SESSION['user_id'] = (int)$_SESSION['customer_id'];
    } elseif (isset($_SESSION['id'])) {
        $_SESSION['user_id'] = (int)$_SESSION['id'];
    } elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $uSafe = $conn->real_escape_string($_SESSION['username']);
        $q = $conn->query("SELECT id FROM user WHERE email = '$uSafe' LIMIT 1");
        if ($q && $r = $q->fetch_assoc()) {
            $_SESSION['user_id'] = (int)$r['id'];
        }
    }
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to wishlist']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

try {
    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Remove from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $action = 'removed';
        $message = 'Removed from wishlist';
    } else {
        // Add to wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $action = 'added';
        $message = 'Added to wishlist';
    }

    // Get updated count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $countRow = $stmt->get_result()->fetch_assoc();
    $newCount = $countRow['total'];

    echo json_encode(['success' => true, 'action' => $action, 'message' => $message, 'count' => $newCount]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
