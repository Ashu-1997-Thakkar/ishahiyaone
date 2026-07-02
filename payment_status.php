<?php
session_start();

if (!isset($_SESSION['success_order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = $_SESSION['success_order_id'];
$payment_mode = $_SESSION['success_payment_mode'];

// Optional: unset to prevent refresh issue
unset($_SESSION['success_order_id'], $_SESSION['success_payment_mode']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow text-center p-5">
        <h1 class="text-success mb-3">🎉 Payment Successful</h1>
        <p class="fs-5">Thank you for your order!</p>

        <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment_mode) ?></p>

        <hr>

        <p class="text-muted">
            Your order has been placed successfully.  
            A confirmation SMS has been sent to your mobile number.
        </p>

        <a href="index.php" class="btn btn-dark mt-3">Continue Shopping</a>
        <a href="accounts.php" class="btn btn-outline-secondary mt-3">My Orders</a>
    </div>
</div>

</body>
</html>
