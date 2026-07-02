<?php
include 'db.php';
session_start();

$user_id = 1; // Replace this with: $_SESSION['user_id'] once login system is implemented

if (isset($_GET['id'])) {
    $cart_id = $_GET['id'];

    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: cart.php");
exit();
