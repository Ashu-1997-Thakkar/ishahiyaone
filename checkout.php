<?php
session_start();

// ✅ Load centralized config (DB + Razorpay keys)
require_once __DIR__ . '/config.php';

// ✅ Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset(DB_CHARSET);

// Remove Item from Cart
if (isset($_POST['remove_item']) && !empty($_POST['remove_item'])) {
    $remove_ids = [(int)$_POST['remove_item']];
    process_removal($conn, $remove_ids);
}

// Remove Selected Items from Cart
if (isset($_POST['remove_selected']) && !empty($_POST['selected_cart_ids'])) {
    $remove_ids = array_map('intval', $_POST['selected_cart_ids']);
    process_removal($conn, $remove_ids);
}

function process_removal(mysqli $conn, array $remove_ids) {
    if (empty($remove_ids)) return;

    // For logged in user
    if (isset($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        $placeholders = implode(',', array_fill(0, count($remove_ids), '?'));
        $stmt = $conn->prepare("DELETE FROM cart WHERE id IN ($placeholders) AND user_id = ?");
        
        $types = str_repeat('i', count($remove_ids)) . 'i';
        $params = $remove_ids;
        $params[] = $user_id;
        
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    } else {
        // For guest user
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if (in_array($item['id'], $remove_ids)) {
                    unset($_SESSION['cart'][$key]);
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
        }
    }
    
    header("Location: checkout.php");
    exit;
}

// Fetch states
$states_result = $conn->query("SELECT * FROM states");

// Cart Initialization
$cart_items = [];
$total_items = 0;
$subtotal = 0;

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if ($user_id > 0) {
    // Fetch cart items for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $qty    = max(1, (int)$row['quantity']);
        $price = (float)$row['price'];

        $imagePath = 'uploads/no-image.png';
        if (!empty($row['images1'])) {
            $img = trim($row['images1']);
            if (file_exists(__DIR__ . '/' . $img)) {
                $imagePath = $img;
            } elseif (file_exists(__DIR__ . '/shop_admin/' . $img)) {
                $imagePath = 'shop_admin/' . $img;
            } elseif (file_exists(__DIR__ . '/shop_admin/uploads/subshop/' . basename($img))) {
                $imagePath = 'shop_admin/uploads/subshop/' . basename($img);
            }
        }

        $cart_items[] = [
            'id'       => $row['id'],        // ✅ Use $row
            'name'     => $row['name'],      // ✅ Use $row
            'images1'  => $imagePath,
            'size'     => $row['size'] ?? "-",
            'quantity' => $qty,
            'price'    => $price,
            'sku_no'   => $row['sku_no'] ?? ''      // ✅ Use $row
        ];

        $total_items += $qty;
        $subtotal    += $qty * $price;
    }
    $stmt->close();
}
else {
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $qty    = max(1, (int)$item['quantity']);
            $price = (float)$item['price'];

            $imagePath = 'uploads/no-image.png';
            if (!empty($item['images1'])) {
                $img = trim($item['images1']);
                if (file_exists(__DIR__ . '/' . $img)) {
                    $imagePath = $img;
                } elseif (file_exists(__DIR__ . '/shop_admin/' . $img)) {
                    $imagePath = 'shop_admin/' . $img;
                } elseif (file_exists(__DIR__ . '/shop_admin/uploads/subshop/' . basename($img))) {
                    $imagePath = 'shop_admin/uploads/subshop/' . basename($img);
                }
            }

            $cart_items[] = [
                'id'       => $item['id'],
                'name'     => $item['name'],
                'images1'  => $imagePath,
                'size'     => $item['size'] ?? "-",
                'quantity' => $qty,
                'price'    => $price,
                'sku_no'   => $item['sku_no'] ?? ''
            ];

            $total_items += $qty;
            $subtotal    += $qty * $price;
        }

    }
}

// Calculate Dynamic GST based on Category
$gst = 0;
$gst_5_total = 0;
$gst_18_total = 0;
foreach ($cart_items as $item) {
    $sku_esc = $conn->real_escape_string($item['sku_no'] ?? '');
    $name_esc = $conn->real_escape_string($item['name']);
    
    // Default fallback GST rate (e.g. for unknown items)
    $item_gst_rate = 0.18; 

    // Try to find the category name via the main product table (subcategories)
    $sql1 = "SELECT mc.main_category_name FROM subcategories sc 
             LEFT JOIN sub_category sub ON sc.category_id = sub.id 
             LEFT JOIN main_category mc ON sub.main_category_id = mc.id 
             WHERE sc.sku_no = '$sku_esc' OR sc.name = '$name_esc' LIMIT 1";
    $res1 = $conn->query($sql1);
    
    $cat_name = "";
    if ($res1 && $res1->num_rows > 0) {
        $row = $res1->fetch_assoc();
        $cat_name = strtolower($row['main_category_name'] ?? '');
    } else {
        // Fallback to all_category
        $sql2 = "SELECT mc.main_category_name FROM all_category ac 
                 LEFT JOIN sub_category sub ON ac.sub_category_id = sub.id 
                 LEFT JOIN main_category mc ON sub.main_category_id = mc.id 
                 WHERE ac.name = '$name_esc' LIMIT 1";
        $res2 = $conn->query($sql2);
        
        if ($res2 && $res2->num_rows > 0) {
            $row = $res2->fetch_assoc();
            $cat_name = strtolower($row['main_category_name'] ?? '');
        }
    }

    // Apply GST based on the fetched Category Name
    if (strpos($cat_name, 'elect') !== false || strpos($cat_name, 'tech') !== false || strpos($cat_name, 'gadget') !== false) {
        $item_gst_rate = 0.18; // 18% for Electronics
    } else if (strpos($cat_name, 'fash') !== false || strpos($cat_name, 'wear') !== false || strpos($cat_name, 'cloth') !== false) {
        $item_gst_rate = 0.05; // 5% for Fashion
    }


    
    $item_total = $item['price'] * $item['quantity'];
    $item_gst = $item_total * $item_gst_rate;
    
    if ($item_gst_rate == 0.18) {
        $gst_18_total += $item_gst;
    } else {
        $gst_5_total += $item_gst;
    }
    
    $gst += $item_gst;
}

$shipping_text = "As Per Charge";  
$grand_total = $subtotal + $gst;  

// UPI Details
$upi_id = "9974328904@sbi";
$payment_message = "Payment for your order";

$success_message = $error_message = "";

$sku_numbers = array_column($cart_items, 'sku_no');
$sku_no_str = implode(", ", $sku_numbers);

// Order Placement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $subtotal > 0) {
    if (
    isset($_POST['payment_status']) &&
    $_POST['payment_status'] === 'Razorpay' &&
    empty($_POST['razorpay_payment_id'])
    ) {
    
        $txn_id = "ISHAHIYA-" . strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 12));
    
        $payment_status = 'PENDING';
        $Mode = 'Razorpay';
        
        // 🔥 REQUIRED FIELDS (FIX NULL ISSUE)
        $fullname   = trim($_POST['fullname'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $mobile     = trim($_POST['mobile'] ?? '');
        $alt_mobile = trim($_POST['alt_mobile'] ?? '');
        $address    = trim($_POST['address'] ?? '');
        $landmark   = trim($_POST['landmark'] ?? '');
        $city       = trim($_POST['city'] ?? '');
        $state      = trim($_POST['state'] ?? '');
        $pincode    = trim($_POST['pincode'] ?? '');
        $ref_num    = $_POST['ref_num'] ?? null;
        
        // Product names
        $product_names = array_column($cart_items, 'name');
        $product_names_str = implode(", ", $product_names);

    
        $stmt = $conn->prepare("
            INSERT INTO billing_details
            (user_id, fullname, email, mobile, alt_mobile, address, landmark, city, state, pincode,
             Mode, RefNo, product_name, TXNID, sku_no, total_amount, payment_status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
    
        $stmt->bind_param(
            "issssssssssssssds",
            $user_id,
            $fullname,
            $email,
            $mobile,
            $alt_mobile,
            $address,
            $landmark,
            $city,
            $state,
            $pincode,
            $Mode,
            $ref_num,
            $product_names_str,
            $txn_id,
            $sku_no_str,
            $grand_total,
            $payment_status
        );

    
        $stmt->execute();
        $billing_id = $stmt->insert_id; // Keeping this if needed later
        $stmt->close();
        
        // Also insert into the main orders table
        $total_gst = $gst_5_total + $gst_18_total;
        $shipping = ($subtotal > 0 && $subtotal < 350) ? 60 : 0;
        $stmt_ord = $conn->prepare("INSERT INTO orders (user_id, customer_name, address, Contact, total_price, payment_mode, gst_amount, shipping_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_ord->bind_param("isssdidd", $user_id, $fullname, $address, $mobile, $grand_total, $Mode, $total_gst, $shipping);
        $stmt_ord->execute();
        $order_id = $stmt_ord->insert_id; // Get the valid order_id from orders table
        $stmt_ord->close();
        
        $_SESSION['temp_order_id'] = $order_id;
        $_SESSION['temp_billing_id'] = $billing_id;
        $_SESSION['temp_customer_mobile'] = $mobile;
    
        echo json_encode([
            "status" => true,
            "order_id" => $order_id,
            "amount" => round($grand_total)
        ]);
        exit;
    }

    $payment_status = $_POST['payment_status'] ?? 'COD';
    if ($payment_status === 'COD' || $payment_status == 'QR Code/Bank') {
        $txn_id = "ISHAHIYA-" . strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 12));
        $fullname        = trim($_POST['fullname']);
        $email           = trim($_POST['email']);
        $mobile          = trim($_POST['mobile']);
        $alt_mobile      = trim($_POST['alt_mobile']);
        $address         = trim($_POST['address']);
        $landmark        = trim($_POST['landmark']);
        $city            = trim($_POST['city']);
        $state           = trim($_POST['state']);
        $pincode         = trim($_POST['pincode']);
        // Note: The logic for payment_status and Mode is slightly redundant but preserved
        $payment_status = $_POST['payment_status'] ?? 'COD';
        $Mode = $payment_status;
        $ref_num = isset($_POST['ref_num']) ? trim($_POST['ref_num']) : null;
        $product_names = array_column($cart_items, 'name');
        $product_names_str = implode(", ", $product_names);
        
        $api_url = "http://sms2.brinfo.in/sms-panel/api/http/index.php";
    
    // Dynamic values
        $order_id = $txn_id;      // Generated order ID
        $payment  = $Mode;         // Payment Mode (COD / QR Code/Bank / Razorpay)
        $date     = date("d/m/Y");    // Current date
        
// ------------------ 1. Customer Message ------------------ //
    $customer_message = "Order Confirmed\n"
                      . "Thank you for your order!\n"
                      . "Order ID: {#order_id#}\n"
                      . "Payment Mode: {#payment#}\n"
                      . "Date: {#date#}\n"
                      . "Thank you for shopping with IshaHiya\n"
                      . "We'll notify you once it's shipped.\n"
                      . "BR CATTLE FEED.";
    
    // Replace placeholders
        $customer_message = str_replace(
            ["{#order_id#}", "{#payment#}", "{#date#}"],
            [$order_id, $payment, $date],
            $customer_message
        );
        
        // API payload for customer
        $sms_params_customer = [
            "username"   => SMS_USERNAME,
            "apikey"     => SMS_APIKEY,
            "apirequest" => "Text",
            "sender"     => SMS_SENDER,
            "mobile"     => $mobile,   
            "message"    => $customer_message,
            "route"      => "TRANS",
            "TemplateID" => "1707176269991549656",
            "format"     => "JSON"
        ];
        
        // Send SMS to customer (using curl)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SMS_API_URL . "?" . http_build_query($sms_params_customer));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $sms_response_customer = curl_exec($ch);
        curl_close($ch);
        
        
// ------------------ 2. Admin/Fixed Number Alert ------------------ //
$admin_message = "New Order Alert!\n"
               . "Order ID: {$order_id}\n"
               . "Payment Mode: {$payment}\n"
               . "Date: {$date}\n"
               . "Thank you for shopping with IshaHiya,\n"
               . "BR CATTLE FEED.";

        
        // API payload for admin (fixed number)
        $sms_params_admin = [
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
        
        // Send SMS to admin (using curl)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SMS_API_URL . "?" . http_build_query($sms_params_admin));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $sms_response_admin = curl_exec($ch);
        curl_close($ch);
    
    
        $stmt = $conn->prepare("INSERT INTO billing_details 
        (user_id, fullname, email, mobile, alt_mobile, address, landmark, city, state, pincode, Mode, RefNo, product_name, TXNID, sku_no, total_amount, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $stmt->bind_param("issssssssssssssd", 
            $user_id, $fullname, $email, $mobile, $alt_mobile, $address, $landmark, 
            $city, $state, $pincode, $Mode, $ref_num, $product_names_str, $txn_id, $sku_no_str, $grand_total
        );
    
        if ($stmt->execute()) {
            $billing_id = $stmt->insert_id;
            // Also insert into the main orders table
            $total_gst = $gst_5_total + $gst_18_total;
            $shipping = ($subtotal > 0 && $subtotal < 350) ? 60 : 0;
            $stmt_ord = $conn->prepare("INSERT INTO orders (user_id, customer_name, address, Contact, total_price, payment_mode, gst_amount, shipping_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_ord->bind_param("isssdidd", $user_id, $fullname, $address, $mobile, $grand_total, $Mode, $total_gst, $shipping);
            $stmt_ord->execute();
            $order_id = $stmt_ord->insert_id; // Get the valid order_id from orders table
            $stmt_ord->close();

            $_SESSION['order_id'] = $order_id;
    
            foreach ($cart_items as $item) {
                $qty    = $item['quantity'];
                $price_raw = $item['price'] ?? 0;
                if (is_string($price_raw)) {
                    $price_raw = preg_replace('/[^\d.]/', '', $price_raw);
                }
                $price = (float)$price_raw;
                $name  = $item['name'];
                $size  = $item['size'];
                $img   = $item['images1'] ?? '';
    
                $stmt2 = $conn->prepare("INSERT INTO order_items 
                    (order_id, product_name, size, quantity, price, image) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param("issids", $order_id, $name, $size, $qty, $price, $img);
                $stmt2->execute();
                $stmt2->close();

                // ✅ STOCK DEDUCTION
                $sku = $item['sku_no'];
                if (!empty($sku)) {
                    // Unconditionally update overall Stock in subcategories
                    $stock_stmt_main = $conn->prepare("UPDATE subcategories SET Stock = GREATEST(0, Stock - ?) WHERE sku_no = ?");
                    $stock_stmt_main->bind_param("is", $qty, $sku);
                    $stock_stmt_main->execute();
                    $stock_stmt_main->close();

                    // Optional: Update size variation if a specific size is tracked
                    if (!empty($size) && $size !== '-') {
                        $stock_stmt = $conn->prepare("
                            UPDATE product_size_variation psv
                            JOIN sizes s ON psv.size_id = s.size_id
                            JOIN subcategories p ON psv.product_id = p.id
                            SET psv.quantity_in_stock = GREATEST(0, psv.quantity_in_stock - ?)
                            WHERE s.size_name = ?
                            AND p.sku_no = ?
                            AND psv.quantity_in_stock > 0
                        ");
                        $stock_stmt->bind_param("iss", $qty, $size, $sku);
                        $stock_stmt->execute();
                        $stock_stmt->close();
                    }
                }
            } // end foreach $cart_items
    
            if ($user_id > 0) {
                $conn->query("DELETE FROM cart WHERE user_id = $user_id");
            } else {
                $_SESSION['cart'] = [];
            }
    
            $_SESSION['success_order_id'] = $billing_id;
            $_SESSION['success_payment_mode'] = $payment_status;
            
            header("Location: payment_status.php");
            exit;

    
        } else {
            $error_message = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Checkout | Ishahiya</title>
  <meta name="description" content="Complete your purchase securely at IshahiyaOne. Pay via Razorpay, UPI or cash on delivery. Fast delivery across India.">
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="shop.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ✅ Favicon -->
  <link rel="icon" href="image/logo/ishahiya-logo.png" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <style>
    :root {
      --gold: #d4af37;
      --dark-bg: #000000;
      --card-bg: #111111;
    }
    body { background-color: var(--dark-bg); color: #fff; }
    
    .hero-section {
      background: #000;
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      border-bottom: 1px solid rgba(212, 175, 55, 0.2);
      margin-bottom: 40px;
    }
    .hero-section h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    .hero-section p {
      font-size: 1.2rem;
      color: #ffffff;
      font-style: italic;
      opacity: 0.9;
    }

    .cart-table { border: 1px solid #333; color: #fff !important; }
    .cart-table thead { background-color: var(--gold) !important; color: #000 !important; }
    .cart-table th { background-color: var(--gold) !important; color: #000 !important; font-weight: bold; border-color: #333; }
    .cart-table td { background-color: #111 !important; color: #fff !important; border-bottom: 1px solid #222; border-color: #333; }
    .cart-image { width: 60px; height: auto; border-radius: 4px; border: 1px solid #333; }

    .checkout-box { background: #111; padding: 30px; border-radius: 12px; border: 1px solid #333; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
    .section-title { font-size: 1.5rem; font-weight: 700; color: var(--gold); margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; }
    
    .btn-gold { background-color: var(--gold) !important; color: #000 !important; font-weight: 700; text-transform: uppercase; border: none; padding: 12px 25px; transition: 0.3s; }
    .btn-gold:hover { background-color: #fff !important; transform: translateY(-2px); }
    
    .form-control, .form-select, textarea { background-color: #222 !important; border: 1px solid #333 !important; color: #fff !important; }
    .form-control:focus, .form-select:focus, textarea:focus { border-color: var(--gold) !important; box-shadow: none !important; }
    .form-control::placeholder { color: #888; }
    
    .qr-code-container img { max-width: 200px; height: auto; border: 2px solid var(--gold); border-radius: 8px; padding: 5px; background: #fff;}
    
    .payment-options label { margin-left: 8px; cursor: pointer; }
    .payment-options input[type="radio"] { accent-color: var(--gold); transform: scale(1.2); }
  </style>
</head>
<body>

    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>
    <section class="hero-section">
        <div class="container">
            <h1>Secure Checkout</h1>
            <p>Finalize your order and make payment securely</p>
        </div>
    </section>

<div class="container my-5">
    <?php if (empty($cart_items)): ?>
        <div class="text-center" style="padding: 100px 0;">
            <i class="fa-solid fa-shopping-basket" style="font-size: 4rem; color: var(--gold); margin-bottom: 20px; opacity: 0.5;"></i>
            <h4>Your cart is empty.</h4>
            <a href="index.php" class="btn btn-gold mt-3">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive mb-5 shadow-lg rounded">
            <form method="post" id="cartForm">
                <table class="table cart-table text-center align-middle m-0">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAllCart" class="form-check-input" style="accent-color: var(--gold); cursor: pointer;">
                        </th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>
                            <button type="submit" name="remove_selected" class="btn btn-sm btn-danger px-2 py-1" onclick="return confirm('Are you sure you want to remove selected items?');">
                                <i class="fas fa-trash"></i> Remove Selected
                            </button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_cart_ids[]" value="<?= htmlspecialchars($item['id']) ?>" class="form-check-input cart-item-checkbox" style="accent-color: var(--gold); cursor: pointer;">
                            </td>
                            <td><img src="<?= htmlspecialchars($item['images1'] ?? 'uploads/no-image.png') ?>" class="cart-image">
                              <span><?= htmlspecialchars($item['sku_no'] ?? '') ?></span></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?= htmlspecialchars($item['id']) ?>" class="btn btn-sm btn-danger px-2 py-1">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <?php if ($gst_5_total > 0 || ($gst_5_total == 0 && $gst_18_total == 0)): ?>
                        <tr>
                            <td colspan="6" class="text-end"><strong>GST 5% :</strong></td>
                            <td>₹<?= number_format($gst_5_total, 2) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($gst_18_total > 0): ?>
                        <tr>
                            <td colspan="6" class="text-end"><strong>GST 18% :</strong></td>
                            <td>₹<?= number_format($gst_18_total, 2) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Shipping Charge :</strong></td>
                            <td><?= $shipping_text ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total:</strong></td>
                            <td><strong>₹<?= number_format($grand_total, 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                const selectAll = document.getElementById('selectAllCart');
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                    });
                }
            });
            </script>
        </div>

        <div class="checkout-box mb-5">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success mt-3">
                    <?= $success_message; ?>
                </div>
                <script>
                    alert("<?= addslashes($success_message); ?>");
                </script>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger mt-3">
                    <?= $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" id="checkoutForm">
                <div class="row">
                    <div class="col-md-7">
                        <div class="section-title mb-3">Billing And Shipping Information</div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-select" disabled>
                                    <option selected>India (+91)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="mobile" placeholder="10 Digit Mobile*" maxlength="10" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                        <div class="mb-3"><input type="text" class="form-control" name="alt_mobile" placeholder="10 Digit Mobile Alternative" maxlength="10" pattern="[0-9]{10}"></div>
                        <input type="hidden" name="is_razorpay_success" id="is_razorpay_success" value="0">
                        <div class="mb-3"><input type="text" class="form-control" name="fullname" placeholder="Full Name*" required></div>
                        <div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email*" required></div>
                        <div class="mb-3"><textarea class="form-control" name="address" placeholder="Address*" rows="3" required></textarea></div>
                        <div class="mb-3"><input type="text" class="form-control" name="landmark" placeholder="Landmark"></div>
                        <div class="row mb-3">
                            <div class="col-md-6"><input type="text" class="form-control" name="city" placeholder="City*" required></div>
                            <div class="col-md-6">
                                <select name="state" required class="form-control">
                                    <option value="">Select State</option>
                                    <?php  
                                    // Reset state result pointer to reuse the result set
                                    $states_result->data_seek(0);  
                                    while ($row = $states_result->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['state_name']); ?>">
                                            <?= htmlspecialchars($row['state_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3"><input type="text" class="form-control" name="pincode" placeholder="Pincode*" maxlength="6" pattern="[0-9]{6}" required></div>
                    </div>

                    <div class="col-md-5">
                        <div class="section-title mb-3">Payment Method</div>
                        <div class="payment-options mb-3">
                            <input type="radio" name="payment_option" value="COD" id="COD" checked>
                            <label for="COD">Cash on Delivery</label><br>
                            <input type="hidden" name="payment_option" value="QR Code/Bank" id="qr-code-bank">
                            <!--<label for="qr-code-bank">QR Code / Bank</label><br>-->
                            <input type="radio" name="payment_option" value="Razorpay" id="razorpay-online-pay">
                            <label for="razorpay-online-pay">Online Pay</label>
                        </div>
                        <div class="qr-code-container" id="qr-code-container" style="display:none;">
                            <h6 class="mb-2">Scan & Pay</h6>
                            <div><strong>Total Amount: </strong><span id="total-amount-display">₹<?= number_format($grand_total, 2) ?></span></div>
                            <img id="qr-code-img" src="image/qr-code.png" alt="UPI QR Code" class="img-fluid" style="max-width:200px;">
                            
                            <div class="mt-3">
                                <label for="ref_num">Enter UPI Transcation ID / Reference Number *</label>
                                <input type="text" class="form-control" name="ref_num" id="ref_num" placeholder="Enter Transaction Ref Number">
                            </div>
                        </div>
                        
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                        <input type="hidden" name="payment_status" id="payment_status_hidden" value="COD">
                        
                        <div class="terms-and-conditions mt-3">
                            <input type="checkbox" id="agree-terms" required>
                            <label for="agree-terms">
                                I have read and agree to the 
                                <a href="terms-and-conditions.php" target="_blank" style="color: var(--gold);">Terms & Conditions</a>
                            </label>
                        </div>
                        <button type="submit" name="place_order" class="btn btn-gold w-100 mt-4" <?= ($subtotal == 0) ? "disabled" : "" ?>>SUBMIT ORDER</button>
                        <p class="for-cash-on-delivery mt-3 text-center" style="color: #ccc; font-size: 0.9rem;">For Cash On Delivery Call Us - +91 9974328904</p>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('checkoutForm');
    const paymentRadios = document.querySelectorAll('input[name="payment_option"]');
    const qrContainer = document.getElementById('qr-code-container');
    const refNumInput = document.getElementById('ref_num');
    const hiddenPaymentInput = document.getElementById("payment_status_hidden");

    const totalAmount = <?= json_encode(round($grand_total)) ?>;
    const userName   = <?= json_encode($_SESSION['username'] ?? '') ?>;
    const userEmail  = <?= json_encode($_SESSION['email'] ?? '') ?>;
    const userMobile = <?= json_encode($_SESSION['mobile'] ?? '') ?>;

    /* ---------------- TOGGLE PAYMENT UI ---------------- */
    function togglePaymentUI() {
        if (document.getElementById('qr-code-bank').checked) {
            qrContainer.style.display = 'block';
            refNumInput.required = true;
            hiddenPaymentInput.value = "QR Code/Bank";
        } else if (document.getElementById('razorpay-online-pay').checked) {
            qrContainer.style.display = 'none';
            refNumInput.required = false;
            hiddenPaymentInput.value = "Razorpay";
        } else {
            qrContainer.style.display = 'none';
            refNumInput.required = false;
            hiddenPaymentInput.value = "COD";
        }
    }

    paymentRadios.forEach(r => r.addEventListener('change', togglePaymentUI));
    togglePaymentUI();

    /* ---------------- FORM SUBMIT ---------------- */
    form.addEventListener("submit", function (e) {

        const selected = hiddenPaymentInput.value;
        const submitBtn = form.querySelector('button[type="submit"]');

        /* ========== RAZORPAY FLOW ========== */
        if (selected === "Razorpay") {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            if (totalAmount > 500000) {
                alert("❌ Error: Maximum allowed transaction limit on standard Razorpay accounts is ₹5,00,000.\nYour cart total is ₹" + totalAmount + ".\nPlease remove the test item from your cart or lower its price before checking out.");
                return;
            }
            
            // Prevent double-clicks
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

            const formData = new FormData(form);

            /* STEP 1: SAVE ORDER (PENDING) */
            fetch(window.location.href, {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(db => {

                if (!db.status) {
                    alert("❌ Failed to create order");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'SUBMIT ORDER';
                    return;
                }

                const dbOrderId = db.order_id;

                /* STEP 2: CREATE RAZORPAY ORDER */
                fetch("create_razorpay_order.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ amount: db.amount })
                })
                .then(res => res.json())
                .then(rzp => {

                    if (!rzp.order_id) {
                        alert("❌ Razorpay order creation failed: " + (rzp.error ? JSON.stringify(rzp.response) : "Unknown error"));
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'SUBMIT ORDER';
                        return;
                    }

                    /* STEP 3: OPEN RAZORPAY */
                    const options = {
                        key: "rzp_live_RvW7WZFs8WsJeG",
                        amount: db.amount * 100,
                        currency: "INR",
                        order_id: rzp.order_id,
                        name: "IshaHiya",
                        description: "Order Payment",
                        image: "image/logo/logo.png",

                        handler: function (response) {
                            window.location.href = "payment_success.php";
                        },

                        prefill: {
                            name: userName,
                            email: userEmail,
                            contact: userMobile
                        },

                        theme: { color: "#d4af37" } // Changed to gold for consistency
                    };

                    const rzpInstance = new Razorpay(options);
                    rzpInstance.open();

                    rzpInstance.on('payment.failed', function (response) {
                        alert("❌ Payment failed: " + response.error.description);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'SUBMIT ORDER';
                    });
                    
                    // Re-enable if modal is closed without paying
                    rzpInstance.on('modal.closed', function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'SUBMIT ORDER';
                    });

                });
            })
            .catch(err => {
                console.error(err);
                alert("❌ Something went wrong. Try again.");
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'SUBMIT ORDER';
            });

            return;
        }

        /* ========== COD / QR FLOW ========== */
        hiddenPaymentInput.value = selected;
        // Also disable button for standard form submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
