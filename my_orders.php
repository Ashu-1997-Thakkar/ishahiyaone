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

if (!isset($_SESSION['user_id'])) {
    if (isset($_SESSION['customer_id'])) { $_SESSION['user_id'] = (int)$_SESSION['customer_id']; }
    elseif (isset($_SESSION['id'])) { $_SESSION['user_id'] = (int)$_SESSION['id']; }
    elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $uSafe = $conn->real_escape_string($_SESSION['username']);
        $q = $conn->query("SELECT id FROM user WHERE email = '$uSafe' LIMIT 1");
        if ($q && $r = $q->fetch_assoc()) { $_SESSION['user_id'] = (int)$r['id']; }
    }
}

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_mobile = isset($_SESSION['mobile']) ? $_SESSION['mobile'] : '';

// Redirect to login if guest
if ($user_id === 0) {
    header("Location: log.php");
    exit();
}

// Fetch Orders
$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? OR Contact = ? ORDER BY order_date DESC");
$stmt->bind_param("is", $user_id, $user_mobile);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $u_id = (int)($row['user_id'] ?? 0);
    $tot = (float)($row['total_price'] ?? 0);
    $ord_time = $row['order_date'] ?? '';
    $b_stmt = $conn->prepare("SELECT * FROM billing_details WHERE (user_id = ? OR mobile = ? OR alt_mobile = ?) AND ABS(total_amount - ?) < 5 ORDER BY ABS(TIMESTAMPDIFF(SECOND, created_at, ?)) ASC LIMIT 1");
    if ($b_stmt) {
        $b_stmt->bind_param("issds", $u_id, $user_mobile, $user_mobile, $tot, $ord_time);
        $b_stmt->execute();
        $b_res = $b_stmt->get_result();
        if ($b_row = $b_res->fetch_assoc()) {
            $row['billing_details'] = $b_row;
        }
        $b_stmt->close();
    }
    $orders[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Ishahiya</title>
  <!-- Standard Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="image/logo/ishahiya-logo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="image/logo/ishahiya-logo.png">
  <link rel="apple-touch-icon" sizes="180x180" href="image/logo/ishahiya-logo.png">
  <meta name="robots" content="noindex, nofollow">
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
    body { background-color: var(--dark-bg); color: #fff; }
    
    .hero-section {
      background: #000;
      color: #fff;
      padding: 35px 20px 20px;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
      margin-bottom: 20px;
    }
    .hero-section h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    .order-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      margin-bottom: 20px;
      overflow: hidden;
    }
    .order-header {
      background: rgba(212, 175, 55, 0.1);
      padding: 15px 20px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
    }
    .order-header h5 {
      color: var(--gold);
      margin: 0;
      font-size: 1.1rem;
      font-weight: 700;
    }
    .order-meta {
      font-size: 0.9rem;
      color: #aaa;
    }
    .order-body {
      padding: 20px;
    }
    .item-row {
      display: flex;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .item-row:last-child {
      border-bottom: none;
    }
    .item-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid var(--border-color);
      margin-right: 20px;
    }
    .item-details {
      flex-grow: 1;
    }
    .item-title {
      font-weight: 600;
      font-size: 1rem;
      margin-bottom: 5px;
    }
    .item-meta {
      font-size: 0.85rem;
      color: #888;
    }
    .item-price {
      font-weight: 700;
      color: var(--gold);
      text-align: right;
    }
    .order-footer {
      background: #0a0a0a;
      padding: 15px 20px;
      border-top: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    .order-summary {
      font-size: 0.9rem;
      color: #ccc;
    }
    .order-total {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--gold);
    }
    
    .badge-status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .status-0 { background: rgba(255,193,7,0.2); color: #ffc107; border: 1px solid #ffc107; } /* Pending */
    .status-1 { background: rgba(23,162,184,0.2); color: #17a2b8; border: 1px solid #17a2b8; } /* Processing */
    .status-2 { background: rgba(0,123,255,0.2); color: #007bff; border: 1px solid #007bff; } /* Shipped */
    .status-3 { background: rgba(40,167,69,0.2); color: #28a745; border: 1px solid #28a745; } /* Delivered */
    .status-4 { background: rgba(220,53,69,0.2); color: #dc3545; border: 1px solid #dc3545; } /* Cancelled */

    .empty-orders {
      text-align: center;
      padding: 60px 20px;
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
    }
    
    @media (max-width: 768px) {
      .item-row { flex-direction: column; align-items: flex-start; text-align: left; }
      .item-image { margin-bottom: 15px; }
      .item-price { text-align: left; margin-top: 10px; }
      .order-footer { flex-direction: column; align-items: flex-start; gap: 10px; }
    }
  </style>
</head>
<body>
<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>

<section class="hero-section">
    <div class="container">
        <h1>My Orders</h1>
        <p>View and track your order history</p>
    </div>
</section>

<div class="container pb-5">
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fa-solid fa-box-open" style="font-size: 4rem; color: var(--gold); margin-bottom: 20px; opacity: 0.5;"></i>
            <h4>You haven't placed any orders yet.</h4>
            <p class="text-muted mb-4">Discover our premium collections and start shopping today.</p>
            <a href="shop.php" class="btn btn-warning" style="background-color: var(--gold); border: none; font-weight: 600; padding: 10px 30px;">Browse Products</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): 
            $order_id = $order['id'];
            $status = (int)$order['order_status'];
            $status_text = "Pending";
            if ($status === 1) $status_text = "Processing";
            if ($status === 2) $status_text = "Shipped";
            if ($status === 3) $status_text = "Delivered";
            if ($status === 4) $status_text = "Cancelled";
        ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <h5>Order #<?= $order_id ?></h5>
                    <div class="order-meta">Placed on <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></div>
                </div>
                <div>
                    <span class="badge-status status-<?= $status ?>"><?= $status_text ?></span>
                </div>
            </div>
            
            <div class="order-body">
                <?php
                // Fetch order items
                $items_stmt = $conn->prepare("
                    SELECT oi.*, 
                           COALESCE(NULLIF(oi.image, ''), sc.Image1, ac.Image1, p.image, bo.banner_image) AS Image1 
                    FROM order_items oi 
                    LEFT JOIN subcategories sc ON (oi.product_name COLLATE utf8mb4_general_ci = sc.name COLLATE utf8mb4_general_ci OR sc.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', sc.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN all_category ac ON (oi.product_name COLLATE utf8mb4_general_ci = ac.name COLLATE utf8mb4_general_ci OR ac.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', ac.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN products p ON (oi.product_name COLLATE utf8mb4_general_ci = p.name COLLATE utf8mb4_general_ci OR p.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', p.name COLLATE utf8mb4_general_ci, '%'))
                    LEFT JOIN bumper_offers bo ON (oi.product_name COLLATE utf8mb4_general_ci = bo.title COLLATE utf8mb4_general_ci OR bo.title COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', bo.title COLLATE utf8mb4_general_ci, '%'))
                    WHERE oi.order_id = ?
                    GROUP BY oi.id
                ");
                $items_stmt->bind_param("i", $order_id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                
                // Self-healing fallback if order_items is empty (e.g. historical Razorpay orders)
                if ($items_result->num_rows === 0 && !empty($order['billing_details']['product_name'])) {
                    $prod_names = explode(',', $order['billing_details']['product_name']);
                    $ord_id_val = (int)$order_id;
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
                        if ($ins_price == 0 && !empty($order['total_price'])) {
                            $ins_price = (float)$order['total_price'] / max(1, count($prod_names));
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
                    // Determine path
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
            
            <div class="order-footer">
                <div class="order-summary">
                    <?php
                    $pay_mode = $order['billing_details']['Mode'] ?? $order['payment_mode'] ?? 'COD';
                    if ($pay_mode === '0' || $pay_mode === 0 || empty($pay_mode)) $pay_mode = 'COD';
                    elseif ($pay_mode === '1' || $pay_mode === 1) $pay_mode = 'Online Pay (Razorpay)';
                    ?>
                    <strong>Payment:</strong> <?= htmlspecialchars($pay_mode) ?><br>
                    <?php if (isset($order['gst_amount']) && $order['gst_amount'] > 0): ?>
                    <strong>GST:</strong> ₹<?= number_format($order['gst_amount'], 2) ?><br>
                    <?php endif; ?>
                    <?php if (isset($order['shipping_amount']) && $order['shipping_amount'] > 0): ?>
                    <strong>Shipping:</strong> ₹<?= number_format($order['shipping_amount'], 2) ?>
                    <?php else: ?>
                    <strong>Shipping:</strong> Free Delivery
                    <?php endif; ?>
                </div>
                <div class="order-total">
                    Total: ₹<?= number_format($order['total_price'], 2) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
