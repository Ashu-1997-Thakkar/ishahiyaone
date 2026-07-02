<?php
session_start();

/* ✅ Load centralized config */
require_once __DIR__ . '/config.php';

/* ✅ DATABASE CONNECTION */
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed");
}
$conn->set_charset(DB_CHARSET);


/* 🔐 Validate order id */
if (!isset($_SESSION['temp_order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = (int) $_SESSION['temp_order_id'];
$billing_id = isset($_SESSION['temp_billing_id']) ? (int) $_SESSION['temp_billing_id'] : $order_id;
$customer_mobile = $_SESSION['temp_customer_mobile'] ?? '';

if ( !empty($customer_mobile) ){
        $api_url = SMS_API_URL;
        $date = date("d/m/Y");

        // CUSTOMER SMS
        $customer_messageq1 = "Order Confirmed Thank you for your order! Order ID: {$billing_id} Payment Mode: Razorpay Date: {$date} Thank you for shopping with IshaHiya We'll notify you once it's shipped. br cattle feed.";

        $sms_customer = [
            "username"   => SMS_USERNAME,
            "apikey"     => SMS_APIKEY,
            "apirequest" => "Text",
            "sender"     => SMS_SENDER,
            "mobile"     => $customer_mobile,
            "message"    => $customer_messageq1,
            "route"      => "TRANS",
            "TemplateID" => "1707176269991549656",
            "format"     => "JSON"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url . "?" . http_build_query($sms_customer));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        // ADMIN SMS
        $admin_message = "New Order Alert! Order ID: {$billing_id} Payment Mode: Razorpay Date: {$date} Thank you for shopping with IshaHiya, br cattle feed.";

        $sms_admin = [
            "username"   => SMS_USERNAME,
            "apikey"     => SMS_APIKEY,
            "apirequest" => "Text",
            "sender"     => SMS_SENDER,
            "mobile"     => ADMIN_MOBILE,
            "message"    => $admin_message,
            "route"      => "TRANS",
            "TemplateID" => "1707176269971388164",
            "format"     => "JSON"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url . "?" . http_build_query($sms_admin));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
}

/* ✅ UPDATE PAYMENT STATUS */
$stmt = $conn->prepare("
    UPDATE billing_details
    SET payment_status = 'SUCCESS'
    WHERE id = ? AND payment_status = 'PENDING'
");
$stmt->bind_param("i", $billing_id);
if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();

    /* ✅ FINALIZING ORDER: Insert Items & Deduct Stock */
    $final_items = [];
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if ($user_id > 0) {
        $cart_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $cart_stmt->bind_param("i", $user_id);
        $cart_stmt->execute();
        $res = $cart_stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $final_items[] = [
                'name' => $row['name'],
                'size' => $row['size'],
                'quantity' => (int)$row['quantity'],
                'price' => (float)$row['price']
            ];
        }
        $cart_stmt->close();
    } else if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $final_items[] = [
                'name' => $item['name'],
                'size' => $item['size'],
                'quantity' => (int)$item['quantity'],
                'price' => (float)$item['price']
            ];
        }
    }

    foreach ($final_items as $item) {
        $name = $item['name'];
        $size = $item['size'];
        $qty = $item['quantity'];
        $price = $item['price'];

        // Insert into order_items
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issid", $order_id, $name, $size, $qty, $price);
        $stmt2->execute();
        $stmt2->close();

        // Deduct stock
        if (!empty($size) && $size !== '-') {
            $stock_stmt = $conn->prepare("
                UPDATE product_size_variation psv
                JOIN sizes s ON psv.size_id = s.size_id
                SET psv.quantity_in_stock = GREATEST(0, psv.quantity_in_stock - ?)
                WHERE s.size_name = ?
                AND psv.quantity_in_stock > 0
                LIMIT 1
            ");
            $stock_stmt->bind_param("is", $qty, $size);
            $stock_stmt->execute();
            $stock_stmt->close();
        }
    }
} else {
    $stmt->close();
}

/* ✅ DELETE ONLY THIS USER'S CART (Fixed: was deleting ALL users' carts!) */
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($user_id > 0) {
    $del_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $del_stmt->bind_param("i", $user_id);
    $del_stmt->execute();
    $del_stmt->close();
}

/* ✅ CLEAR SESSION CART */
$_SESSION['cart'] = [];

/* 🔒 Prevent duplicate execution */
unset($_SESSION['temp_order_id']);
unset($_SESSION['temp_billing_id']);
unset($_SESSION['temp_customer_mobile']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Favicon -->
<link rel="icon" href="image/logo/ishahiya-logo.png" type="image/png">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    display: flex;
    justify-content: center;
    align-items: center;
}

.success-card {
    max-width: 480px;
    width: 100%;
    border-radius: 16px;
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(60px); }
    to { opacity: 1; transform: translateY(0); }
}

.success-icon {
    width: 90px;
    height: 90px;
    background: #28a745;
    color: #fff;
    font-size: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: -55px auto 15px;
    box-shadow: 0 12px 30px rgba(40,167,69,0.6);
}
</style>
</head>

<body>

<div class="card success-card shadow-lg text-center p-4 bg-white">
    <div class="success-icon">
        <i class="fas fa-check"></i>
    </div>

    <h3 class="fw-bold text-success">Payment Successful</h3>
    <p class="text-muted mt-2">
        Thank you for your order!  
        Your payment has been processed successfully.
    </p>

    <div class="alert alert-success fw-semibold mt-3">
        Order ID: <strong>#<?= htmlspecialchars($billing_id) ?></strong>
    </div>

    <div class="d-grid gap-2 mt-4">
        <a href="index.php" class="btn btn-success btn-lg">
            Continue Shopping
        </a>
        <a href="my_orders.php" class="btn btn-outline-success">
            View My Orders
        </a>
    </div>
</div>

</body>
</html>
