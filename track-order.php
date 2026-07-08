<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Database connection
$server   = "localhost";
$user     = "ishahiyaone";
$password = "BhaV@1437I";
$database = "ishahiyaone";

$conn = new mysqli($server, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$order_data = null;
$error_msg = '';

// Check if tracking form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_order'])) {
    $order_id = trim($_POST['order_id'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!empty($order_id) && !empty($phone)) {
        // Remove '#' if user typed it
        $order_id_clean = trim(str_replace('#', '', $order_id));
        $phone_clean = trim(preg_replace('/[^\d]/', '', $phone));
        if (empty($phone_clean)) $phone_clean = trim($phone);
        
        // 1. Try checking billing_details first (since Order ID sent in SMS / displayed on admin payment table is billing_details.id)
        $b_row = null;
        $b_stmt = $conn->prepare("SELECT * FROM billing_details WHERE (id = ? OR RefNo = ? OR TXNID = ?) AND (mobile = ? OR alt_mobile = ? OR mobile LIKE CONCAT('%', ?, '%') OR alt_mobile LIKE CONCAT('%', ?, '%')) LIMIT 1");
        if ($b_stmt) {
            $ord_int = (int)$order_id_clean;
            $b_stmt->bind_param("issssss", $ord_int, $order_id_clean, $order_id_clean, $phone_clean, $phone_clean, $phone_clean, $phone_clean);
            $b_stmt->execute();
            $b_res = $b_stmt->get_result();
            if ($row_b = $b_res->fetch_assoc()) {
                $b_row = $row_b;
            }
            $b_stmt->close();
        }
        
        // 2. Look up orders table record
        $o_row = null;
        if ($b_row) {
            $o_stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
            if ($o_stmt) {
                $ord_id_val = (int)$b_row['id'];
                $o_stmt->bind_param("i", $ord_id_val);
                $o_stmt->execute();
                if ($row_o = $o_stmt->get_result()->fetch_assoc()) {
                    $o_row = $row_o;
                }
                $o_stmt->close();
            }
        }
        if (!$o_row) {
            $ord_int = (int)$order_id_clean;
            $o_stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND (Contact = ? OR Contact LIKE CONCAT('%', ?, '%')) LIMIT 1");
            if ($o_stmt) {
                $o_stmt->bind_param("iss", $ord_int, $phone_clean, $phone_clean);
                $o_stmt->execute();
                if ($row_o = $o_stmt->get_result()->fetch_assoc()) {
                    $o_row = $row_o;
                }
                $o_stmt->close();
            }
        }
        if (!$o_row && $b_row) {
            // Try fuzzy linking by total price and user/contact
            $u_id = (int)($b_row['user_id'] ?? 0);
            $tot = (float)($b_row['total_amount'] ?? 0);
            $b_time = $b_row['created_at'] ?? '';
            $o_stmt = $conn->prepare("SELECT * FROM orders WHERE (user_id = ? OR Contact = ? OR Contact LIKE CONCAT('%', ?, '%')) AND ABS(total_price - ?) < 5 ORDER BY ABS(TIMESTAMPDIFF(SECOND, order_date, ?)) ASC LIMIT 1");
            if ($o_stmt) {
                $o_stmt->bind_param("issds", $u_id, $phone_clean, $phone_clean, $tot, $b_time);
                $o_stmt->execute();
                if ($row_o = $o_stmt->get_result()->fetch_assoc()) {
                    $o_row = $row_o;
                }
                $o_stmt->close();
            }
        }
        
        if ($o_row) {
            $order_data = $o_row;
            if ($b_row) {
                $order_data['billing_details'] = $b_row;
                $order_data['display_order_id'] = $b_row['id'];
            } else {
                $u_id = (int)($order_data['user_id'] ?? 0);
                $tot = (float)($order_data['total_price'] ?? 0);
                $ord_time = $order_data['order_date'] ?? '';
                $b_stmt = $conn->prepare("SELECT * FROM billing_details WHERE (user_id = ? OR mobile = ? OR alt_mobile = ? OR mobile LIKE CONCAT('%', ?, '%') OR alt_mobile LIKE CONCAT('%', ?, '%')) AND ABS(total_amount - ?) < 5 ORDER BY ABS(TIMESTAMPDIFF(SECOND, created_at, ?)) ASC LIMIT 1");
                if ($b_stmt) {
                    $b_stmt->bind_param("issssds", $u_id, $phone_clean, $phone_clean, $phone_clean, $phone_clean, $tot, $ord_time);
                    $b_stmt->execute();
                    if ($b_row_found = $b_stmt->get_result()->fetch_assoc()) {
                        $order_data['billing_details'] = $b_row_found;
                        $order_data['display_order_id'] = $b_row_found['id'];
                    }
                    $b_stmt->close();
                }
            }
            if (empty($order_data['display_order_id'])) {
                $order_data['display_order_id'] = $order_data['id'];
            }
        } elseif ($b_row) {
            // Construct order_data directly from billing_details if orders table row missing/unlinked
            $order_data = [
                'id' => (int)$b_row['id'],
                'display_order_id' => $b_row['id'],
                'user_id' => $b_row['user_id'],
                'customer_name' => $b_row['fullname'],
                'address' => $b_row['address'] . (!empty($b_row['city']) ? ', ' . $b_row['city'] : '') . (!empty($b_row['state']) ? ', ' . $b_row['state'] : '') . (!empty($b_row['pincode']) ? ' - ' . $b_row['pincode'] : ''),
                'Contact' => !empty($b_row['mobile']) ? $b_row['mobile'] : $b_row['alt_mobile'],
                'total_price' => $b_row['total_amount'],
                'payment_mode' => $b_row['Mode'],
                'order_status' => (strtoupper($b_row['payment_status'] ?? '') === 'SUCCESS') ? 1 : 0,
                'order_date' => $b_row['created_at'],
                'billing_details' => $b_row
            ];
        } else {
            $error_msg = "No order found with the provided Order ID and Phone Number.";
        }
    } else {
        $error_msg = "Please enter both Order ID and Phone Number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once 'includes/seo_master.php'; ?>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="shop.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    :root {
      --gold: #d4af37;
      --dark-bg: #000000;
      --card-bg: #111111;
      --border-color: rgba(212, 175, 55, 0.2);
    }
    body { background-color: var(--dark-bg); color: #fff; font-family: 'Inter', sans-serif; }
    
    .hero-section {
      background: #000;
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
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

    /* === Form Styling === */
    .track-form-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 40px;
      max-width: 600px;
      margin: 0 auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .form-control {
      background-color: #222 !important;
      border: 1px solid #333 !important;
      color: #fff !important;
      padding: 12px 15px;
    }
    .form-control:focus {
      border-color: var(--gold) !important;
      box-shadow: 0 0 5px rgba(212, 175, 55, 0.5) !important;
    }
    .form-label { color: #ccc; font-weight: 500; }
    .btn-gold {
      background-color: var(--gold);
      color: #000;
      font-weight: 700;
      text-transform: uppercase;
      border: none;
      padding: 12px 25px;
      transition: 0.3s;
      width: 100%;
    }
    .btn-gold:hover { background-color: #fff; color: #000; transform: translateY(-2px); }

    /* === Tracking Stepper === */
    .tracking-wrapper {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 40px 20px;
      margin-bottom: 30px;
    }
    .tracking-header { text-align: center; margin-bottom: 40px; }
    .tracking-header h3 { color: var(--gold); font-weight: 700; }
    
    .stepper {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      max-width: 800px;
      margin: 0 auto;
    }
    .stepper::before {
      content: '';
      position: absolute;
      top: 25px;
      left: 12.5%;
      right: 12.5%;
      height: 4px;
      background: #333;
      z-index: 1;
    }
    .step {
      position: relative;
      z-index: 2;
      text-align: center;
      width: 25%;
    }
    .step-icon {
      width: 50px;
      height: 50px;
      background: #222;
      border: 4px solid #333;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
      font-size: 1.2rem;
      color: #888;
      transition: 0.4s;
    }
    .step-label { color: #888; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
    
    .step.completed .step-icon {
      background: var(--gold);
      border-color: #fff;
      color: #000;
      box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
    }
    .step.completed .step-label { color: var(--gold); }
    
    .step.active .step-icon {
      background: #fff;
      border-color: var(--gold);
      color: #000;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
      animation: pulse 2s infinite;
    }
    .step.active .step-label { color: #fff; }

    .step.cancelled .step-icon {
      background: #ff4757;
      border-color: #fff;
      color: #fff;
      box-shadow: 0 0 15px rgba(255, 71, 87, 0.5);
    }
    .step.cancelled .step-label { color: #ff4757; }

    /* Progress bar overlay */
    .progress-line {
      position: absolute;
      top: 25px;
      left: 12.5%;
      height: 4px;
      background: var(--gold);
      z-index: 1;
      transition: 0.5s ease;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    /* === Order Details UI (Matching my_orders.php) === */
    .order-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      overflow: hidden;
    }
    .order-header {
      background: rgba(212, 175, 55, 0.1);
      padding: 15px 20px;
      border-bottom: 1px solid var(--border-color);
    }
    .order-body { padding: 20px; }
    .item-row { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .item-row:last-child { border-bottom: none; }
    .item-image { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); margin-right: 20px; }
    .item-details { flex-grow: 1; }
    .item-title { font-weight: 600; font-size: 1rem; margin-bottom: 5px; }
    .item-meta { font-size: 0.85rem; color: #888; }
    .item-price { font-weight: 700; color: var(--gold); text-align: right; }

    @media (max-width: 768px) {
      .stepper { flex-direction: column; gap: 30px; }
      .stepper::before { display: none; }
      .progress-line { display: none; }
      .step { width: 100%; display: flex; align-items: center; gap: 20px; text-align: left; }
      .step-icon { margin: 0; }
      .item-row { flex-direction: column; align-items: flex-start; }
      .item-image { margin-bottom: 15px; }
      .item-price { text-align: left; margin-top: 10px; }
    }
  </style>
</head>
<body>
<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>

<section class="hero-section">
    <div class="container">
        <h1>Track Your Order</h1>
        <p>Enter your details below to check your shipment status</p>
    </div>
</section>

<div class="container pb-5">
    <?php if (!$order_data): ?>
        <div class="track-form-card">
            <?php if ($error_msg): ?>
                <div class="alert alert-danger" style="background: rgba(255,71,87,0.1); border: 1px solid #ff4757; color: #ff4757;">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-4">
                    <label class="form-label">Order ID</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: #333; border: 1px solid #333; color: var(--gold);">#</span>
                        <input type="text" name="order_id" class="form-control" placeholder="e.g. 29" required value="<?= htmlspecialchars($_POST['order_id'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Registered Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="e.g. 9876543210" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <button type="submit" name="track_order" class="btn btn-gold mt-2">Track Now <i class="fas fa-arrow-right ms-2"></i></button>
            </form>
        </div>
    <?php else: 
        $status = (int)$order_data['order_status'];
        // Determine progress width for desktop
        $progress_width = "0%";
        if ($status === 1) $progress_width = "25%";
        if ($status === 2) $progress_width = "50%";
        if ($status === 3) $progress_width = "75%";
    ?>
        <!-- Tracker UI -->
        <div class="tracking-wrapper">
            <div class="tracking-header">
                <h3>Order #<?= htmlspecialchars($order_data['display_order_id'] ?? $order_data['id']) ?></h3>
                <p class="text-muted">Placed on <?= date('d M Y, h:i A', strtotime($order_data['order_date'])) ?></p>
            </div>
            
            <?php if ($status === 4): // Cancelled ?>
                <div class="stepper">
                    <div class="step cancelled w-100">
                        <div class="step-icon"><i class="fas fa-times-circle"></i></div>
                        <div class="step-label">Order Cancelled</div>
                    </div>
                </div>
                <p class="text-center mt-4 text-danger">This order has been cancelled.</p>
            <?php else: ?>
                <div class="stepper">
                    <div class="progress-line" style="width: <?= $progress_width ?>;"></div>
                    
                    <div class="step <?= $status >= 0 ? 'completed' : '' ?> <?= $status === 0 ? 'active' : '' ?>">
                        <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                        <div class="step-label">Pending</div>
                    </div>
                    
                    <div class="step <?= $status >= 1 ? 'completed' : '' ?> <?= $status === 1 ? 'active' : '' ?>">
                        <div class="step-icon"><i class="fas fa-box-open"></i></div>
                        <div class="step-label">Processing</div>
                    </div>
                    
                    <div class="step <?= $status >= 2 ? 'completed' : '' ?> <?= $status === 2 ? 'active' : '' ?>">
                        <div class="step-icon"><i class="fas fa-truck-fast"></i></div>
                        <div class="step-label">Shipped</div>
                    </div>
                    
                    <div class="step <?= $status >= 3 ? 'completed' : '' ?> <?= $status === 3 ? 'active' : '' ?>">
                        <div class="step-icon"><i class="fas fa-house-circle-check"></i></div>
                        <div class="step-label">Delivered</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Items -->
        <h4 class="mb-3" style="color: var(--gold);">Items in this Order</h4>
        <div class="order-card">
            <div class="order-body">
                <?php
                $ord_id_1 = (int)$order_data['id'];
                $ord_id_2 = (int)($order_data['billing_details']['id'] ?? $order_data['id']);
                $items_stmt = $conn->prepare("
                    SELECT oi.*, 
                           COALESCE(NULLIF(oi.image, ''), sc.Image1, ac.Image1, p.image, bo.banner_image) AS Image1 
                    FROM order_items oi 
                    LEFT JOIN subcategories sc ON (oi.product_name COLLATE utf8mb4_general_ci = sc.name COLLATE utf8mb4_general_ci OR sc.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', sc.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN all_category ac ON (oi.product_name COLLATE utf8mb4_general_ci = ac.name COLLATE utf8mb4_general_ci OR ac.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', ac.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN products p ON (oi.product_name COLLATE utf8mb4_general_ci = p.name COLLATE utf8mb4_general_ci OR p.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', p.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN bumper_offers bo ON (oi.product_name COLLATE utf8mb4_general_ci = bo.title COLLATE utf8mb4_general_ci OR bo.title COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', bo.title COLLATE utf8mb4_general_ci, '%'))
                    WHERE oi.order_id IN (?, ?)
                    GROUP BY oi.id
                ");
                $items_stmt->bind_param("ii", $ord_id_1, $ord_id_2);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                
                // Self-healing fallback if order_items is empty (e.g. historical Razorpay orders)
                if ($items_result->num_rows === 0 && !empty($order_data['billing_details']['product_name'])) {
                    $prod_names = explode(',', $order_data['billing_details']['product_name']);
                    $ord_id_val = (int)$order_data['id'];
                    foreach ($prod_names as $p_name) {
                        $p_name = trim($p_name);
                        if (empty($p_name)) continue;
                        $lookup = $conn->prepare("
                            SELECT name, price, COALESCE(NULLIF(Image1, ''), 'uploads/no-image.png') AS img, size FROM subcategories WHERE name LIKE CONCAT('%', ?, '%')
                            UNION ALL
                            SELECT name, price, COALESCE(NULLIF(Image1, ''), 'uploads/no-image.png') AS img, '' AS size FROM all_category WHERE name LIKE CONCAT('%', ?, '%')
                            UNION ALL
                            SELECT name, price, COALESCE(NULLIF(image, ''), 'uploads/no-image.png') AS img, '' AS size FROM products WHERE name LIKE CONCAT('%', ?, '%')
                            UNION ALL
                            SELECT title AS name, 0 AS price, COALESCE(NULLIF(banner_image, ''), 'uploads/no-image.png') AS img, '' AS size FROM bumper_offers WHERE title LIKE CONCAT('%', ?, '%')
                            LIMIT 1
                        ");
                        $lookup->bind_param("ssss", $p_name, $p_name, $p_name, $p_name);
                        $lookup->execute();
                        $l_res = $lookup->get_result()->fetch_assoc();
                        $lookup->close();
                        
                        $ins_name = $l_res['name'] ?? $p_name;
                        $ins_price = (float)($l_res['price'] ?? 0);
                        if ($ins_price == 0 && !empty($order_data['total_price'])) {
                            $ins_price = (float)$order_data['total_price'] / max(1, count($prod_names));
                        }
                        $ins_img = $l_res['img'] ?? '';
                        $ins_size = $l_res['size'] ?? '';
                        
                        $ins_oi = $conn->prepare("INSERT INTO order_items (order_id, product_name, size, quantity, price, image) VALUES (?, ?, ?, 1, ?, ?)");
                        $ins_oi->bind_param("issds", $ord_id_val, $ins_name, $ins_size, $ins_price, $ins_img);
                        $ins_oi->execute();
                        $ins_oi->close();
                    }
                    // Re-query order_items after self-healing
                    $items_stmt->execute();
                    $items_result = $items_stmt->get_result();
                }
                
                while ($item = $items_result->fetch_assoc()):
                    $img = !empty($item['Image1']) ? $item['Image1'] : 'uploads/no-image.png';
                    $imagePath = 'uploads/no-image.png';
                    if (!empty($img) && $img !== 'uploads/no-image.png') {
                        if (file_exists(__DIR__ . '/' . $img)) {
                            $imagePath = $img;
                        } elseif (file_exists(__DIR__ . '/shop_admin/' . $img)) {
                            $imagePath = 'shop_admin/' . $img;
                        } elseif (file_exists(__DIR__ . '/shop_admin/uploads/subshop/' . basename($img))) {
                            $imagePath = 'shop_admin/uploads/subshop/' . basename($img);
                        } elseif (file_exists(__DIR__ . '/shop_admin/uploads/' . basename($img))) {
                            $imagePath = 'shop_admin/uploads/' . basename($img);
                        } else {
                            // Web URL fallback for Linux hosting environments
                            $imagePath = (strpos($img, 'shop_admin/') !== false || strpos($img, 'uploads/') !== false) ? $img : 'shop_admin/uploads/subshop/' . basename($img);
                        }
                    }
                ?>
                <div class="item-row">
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="item-image">
                    <div class="item-details">
                        <div class="item-title"><?= htmlspecialchars($item['product_name']) ?></div>
                        <div class="item-meta">
                            <?php if (!empty($item['size']) && $item['size'] !== '-'): ?>
                                Size: <?= htmlspecialchars($item['size']) ?> &bull; 
                            <?php endif; ?>
                            Qty: <?= $item['quantity'] ?>
                        </div>
                    </div>
                    <div class="item-price">
                        ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </div>
                </div>
                <?php endwhile; $items_stmt->close(); ?>
            </div>
            
            <div class="order-header" style="border-top: 1px solid var(--border-color); border-bottom: none; background: #0a0a0a;">
                <div style="font-size: 0.9rem; color: #ccc;">
                    <?php
                    $pay_mode = $order_data['billing_details']['Mode'] ?? $order_data['payment_mode'] ?? 'COD';
                    if ($pay_mode === '0' || $pay_mode === 0 || empty($pay_mode)) $pay_mode = 'COD';
                    elseif ($pay_mode === '1' || $pay_mode === 1) $pay_mode = 'Online Pay (Razorpay)';
                    
                    $addr = $order_data['billing_details']['address'] ?? $order_data['address'] ?? $order_data['Address'] ?? '';
                    $city = $order_data['billing_details']['city'] ?? $order_data['City'] ?? '';
                    $state = $order_data['billing_details']['state'] ?? '';
                    $pincode = $order_data['billing_details']['pincode'] ?? '';
                    
                    $full_addr = trim(implode(', ', array_filter([$addr, $city, $state, $pincode])));
                    if (empty($full_addr)) $full_addr = 'Address not provided';
                    ?>
                    <strong>Payment Mode:</strong> <?= htmlspecialchars($pay_mode) ?><br>
                    <strong>Shipping Address:</strong> <?= htmlspecialchars($full_addr) ?><br>
                </div>
                <div style="font-size: 1.2rem; font-weight: 700; color: var(--gold);">
                    Total: ₹<?= number_format($order_data['total_price'], 2) ?>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="track-order.php" class="btn btn-outline-warning">Track Another Order</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
