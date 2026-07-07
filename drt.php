<?php
session_start();
$userLoggedIn = isset($_SESSION['user_id']); // true if user is logged in

// Display errors in development environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Database connection
$server   = "localhost";
$user     = "ishahiyaone";
$password = "BhaV@1437I";
$database = "ishahiyaone";

$conn = new mysqli($server, $user, $password, $database);


// Ensure $conn is available (mysqli)
if (!$conn) {
  die("Database connection not found. Please check shop_admin/config/dbconnect.php");
}
$conn->set_charset("utf8mb4");

// --- Get product_id safely ---
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
$source = isset($_GET['source']) ? trim($_GET['source']) : '';

$main = $_GET['main'] ?? null;
$sub  = $_GET['subcategory'] ?? null;

// If product ID is missing BUT user is accessing category page → allow it
if (!$product_id && !$main && !$sub) {
  die("Invalid or missing product ID.");
}

$product = null;

if ($product_id > 0) {
  // If specific source is requested, try that table first to prevent ID collisions
  if ($source === 'all_category') {
    $sql = "SELECT ac.*, ac.id AS id, ac.Image1 AS image1, 'all_category' AS table_type,
                   sub.sub_category_name, mc.id AS mc_id, mc.main_category_name
            FROM all_category ac
            LEFT JOIN sub_category sub ON ac.sub_category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE ac.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  } else if ($source === 'subcategories' || $source === 'main_shop' || $source === 'sub') {
    $sql = "SELECT sc.*, sub.sub_category_name, mc.id AS mc_id, mc.main_category_name, 'subcategories' AS table_type
            FROM subcategories sc
            LEFT JOIN sub_category sub ON sc.category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE sc.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  } else if ($source === 'subshop') {
    $sql = "SELECT *, id AS product_id, image1 AS Image1, 'subshop' AS table_type FROM subshop WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  } else if ($source === 'products') {
    $sql = "SELECT p.*, p.product_id AS id, p.image AS Image1, p.image AS image1, 'products' AS table_type,
                   sub.sub_category_name, mc.id AS mc_id, mc.main_category_name
            FROM products p
            LEFT JOIN sub_category sub ON p.sub_category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE p.product_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }

  // --- Fallback chain (if not loaded yet or accessed via direct legacy links) ---
  if (!$product) {
    // 1. Try subcategories
    $sql = "SELECT sc.*, sub.sub_category_name, mc.id AS mc_id, mc.main_category_name, 'subcategories' AS table_type
            FROM subcategories sc
            LEFT JOIN sub_category sub ON sc.category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE sc.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }

  if (!$product) {
    // 2. Try all_category
    $sql = "SELECT ac.*, ac.id AS id, ac.Image1 AS image1, 'all_category' AS table_type,
                   sub.sub_category_name, mc.id AS mc_id, mc.main_category_name
            FROM all_category ac
            LEFT JOIN sub_category sub ON ac.sub_category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE ac.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }

  if (!$product) {
    // 3. Try subcategory (singular)
    $sql = "SELECT *, subcategory_name AS name, image1 AS image1, 'admin_coll_item' AS table_type FROM subcategory WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }

  if (!$product) {
    // 4. Try products
    $sql = "SELECT p.*, p.product_id AS id, p.image AS Image1, p.image AS image1, 'products' AS table_type,
                   sub.sub_category_name, mc.id AS mc_id, mc.main_category_name
            FROM products p
            LEFT JOIN sub_category sub ON p.sub_category_id = sub.id
            LEFT JOIN main_category mc ON sub.main_category_id = mc.id
            WHERE p.product_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }
}

// Debugging: If no product is found, print the product_id
if ($product_id && !$product) {
  $safe_pid = htmlspecialchars($product_id);
  echo "Product not found for product_id: " . $safe_pid;
  die();
}


// --- Cart count ---
$cart_count = 0;
if ($userLoggedIn) {
  $user_id = intval($_SESSION['user_id']);
  $q = "SELECT COUNT(*) AS total_quantity FROM cart WHERE user_id=?";
  $st = $conn->prepare($q);
  $st->bind_param("i", $user_id);
  $st->execute();
  $res = $st->get_result();
  $cart_count = $res && ($r = $res->fetch_assoc()) ? intval($r['total_quantity']) : 0;
  $st->close();
} else if (!empty($_SESSION['cart'])) {
  // Just count the unique products
  $cart_count = count($_SESSION['cart']);
}

// --- AJAX: stock check ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prd_id']) && !isset($_POST['apply_coupon'])) {
  $prd_id = intval($_POST['prd_id']);
  $size = isset($_POST['size']) ? trim($_POST['size']) : '';
  $source = isset($_POST['source']) ? trim($_POST['source']) : '';
  
  $actual_stock = 0;
  $row_base = null;
  
  if ($source === 'all_category') {
      $sql_base = "SELECT ac.quantity as Stock, sub.main_category_id FROM all_category ac LEFT JOIN sub_category sub ON ac.sub_category_id = sub.id WHERE ac.id = ? LIMIT 1";
      $stmt_base = $conn->prepare($sql_base);
      $stmt_base->bind_param("i", $prd_id);
      $stmt_base->execute();
      $row_base = $stmt_base->get_result()->fetch_assoc();
  } else if ($source === 'products') {
      $sql_base = "SELECT p.quantity as Stock, sub.main_category_id FROM products p LEFT JOIN sub_category sub ON p.sub_category_id = sub.id WHERE p.product_id = ? LIMIT 1";
      $stmt_base = $conn->prepare($sql_base);
      $stmt_base->bind_param("i", $prd_id);
      $stmt_base->execute();
      $row_base = $stmt_base->get_result()->fetch_assoc();
  } else if ($source === 'admin_coll_item') {
      $sql_base = "SELECT quantity as Stock, main_category_id FROM subcategory WHERE id = ? LIMIT 1";
      $stmt_base = $conn->prepare($sql_base);
      $stmt_base->bind_param("i", $prd_id);
      $stmt_base->execute();
      $row_base = $stmt_base->get_result()->fetch_assoc();
  } else {
      // Default: subcategories
      $sql_base = "SELECT sc.Stock, sub.main_category_id FROM subcategories sc LEFT JOIN sub_category sub ON sc.category_id = sub.id WHERE sc.id = ? LIMIT 1";
      $stmt_base = $conn->prepare($sql_base);
      $stmt_base->bind_param("i", $prd_id);
      $stmt_base->execute();
      $row_base = $stmt_base->get_result()->fetch_assoc();
  }
  
  if ($row_base) {
      $main_cat_id = (int)$row_base['main_category_id'];
      
      // Default fallback is the master Stock column
      $actual_stock = (int)$row_base['Stock'];

      if ($main_cat_id === 7 && !empty($size) && $size !== '-' && $size !== 'One Size') {
          // Fashion product: try to check product_size_variation first
          $sql_stock = "
              SELECT psv.quantity_in_stock 
              FROM product_size_variation psv
              JOIN sizes s ON psv.size_id = s.size_id
              WHERE psv.product_id = ? AND s.size_name = ?
              LIMIT 1
          ";
          $stmt = $conn->prepare($sql_stock);
          $stmt->bind_param("is", $prd_id, $size);
          $stmt->execute();
          $row = $stmt->get_result()->fetch_assoc();
          if ($row && isset($row['quantity_in_stock'])) {
              $actual_stock = (int)$row['quantity_in_stock'];
          }
      }
  }

  echo json_encode([
      "available" => $actual_stock > 0,
      "stock" => $actual_stock
  ]);
  exit;
}
// Add to Cart Logic - POST Method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['product_quantity'];
  $size = $_POST['product_size'] ?? '';  // Size (if applicable)
  $image = $_POST['product_image'];
  $name = $_POST['product_name'];
  $price_raw = $_POST['product_price'] ?? 0;
  if (is_string($price_raw)) {
      $price_raw = preg_replace('/[^\d.]/', '', $price_raw);
  }
  $price = (float)$price_raw;
  $sku_no = $_POST['sku_no'];

  if ($userLoggedIn) {
    // User is logged in, add to the database
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity, images1, name, sku_no, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisisssd", $user_id, $product_id, $size, $quantity, $image, $name, $sku_no, $price);

    if ($stmt->execute()) {
      echo "success";  // Can be used for AJAX response
    } else {
      echo "Failed to add to cart";
    }

    $stmt->close();
  } else {
    // Guest user, add to session
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $name,
        'size' => $size,
        'quantity' => $quantity,
        'image' => $image,
        'price' => $price,
        'sku_no' => $sku_no,
      ];
    }
  }
  // Return success response
  echo "success";
}

// --- Related products ---
$sql_related = "SELECT * FROM subcategories WHERE category_id=? AND id!=? LIMIT 5";
$stmt = $conn->prepare($sql_related);
$catForRel = $product['category_id'] ?? 0;
$stmt->bind_param("ii", $catForRel, $product_id);
$stmt->execute();
$related_products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Parse sizes ---
$sizes = [];
if (!empty($product['Size'])) {
  $decoded = json_decode($product['Size'], true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    $sizes = array_map('trim', $decoded);
  } else {
    $sizes = array_map('trim', explode(',', $product['Size']));
  }
}

?>

<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'includes/seo_master.php'; ?>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/banner.css" />
  <!-- ✅ Favicon -->
  <link rel="icon" href="image/logo/ishahiya-logo.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* ================= SCROLLING TEXT ================= */
    .scrolling-slogan {
      overflow: hidden;
      white-space: nowrap;
      background: none;
      padding: 8px 0;
    }

    .scrolling-text {
      display: inline-block;
      padding-left: 100%;
      animation: slide 40s linear infinite;
      font-size: 0.95rem;
      font-weight: bold;
      color: #ff9800;
    }

    @keyframes slide {
      0% {
        transform: translateX(0%);
      }

      100% {
        transform: translateX(-100%);
      }
    }

    /* ================= HERO SECTION ================= */
    .home {
      padding: 70px 20px;
      background: linear-gradient(to right, #fff, #f9f9f9);
    }

    .home__container {
      max-width: 1200px;
      margin: auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 40px;
    }

    .home__heading {
      font-size: 3rem;
      font-weight: 700;
      line-height: 1.2;
    }

    .home__heading .highlight {
      color: #0a9396;
      font-size: 3.2rem;
    }

    .home__btn {
      display: inline-block;
      padding: 12px 28px;
      background: #0a9396;
      color: #fff;
      border-radius: 6px;
      margin-top: 15px;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .home__btn:hover {
      background: #088588;
    }

    .home__image img {
      width: 100%;
      max-width: 480px;
    }

    /* Responsive Hero */
    @media(max-width:768px) {
      .home__container {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .home__heading {
        font-size: 2.2rem;
      }

      .home__heading .highlight {
        font-size: 2.4rem;
      }

      .home__btn {
        margin-top: 20px;
      }
    }

    /* ================= PRODUCT SECTIONS ================= */
    .categories__wrapper {
      display: flex;
      overflow-x: auto;
      gap: 20px;
      padding: 20px;
      scroll-behavior: smooth;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }

    .category__item {
      flex: 0 0 230px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      text-decoration: none;
      color: inherit;
      transition: transform 0.3s, box-shadow 0.3s;
      scroll-snap-align: start;
    }

    .category__item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .product__banner img {
      width: 100%;
      height: 200px;
      object-fit: contain;
      background: #fff;
    }

    .card-content {
      padding: 12px;
    }

    .brand {
      font-size: 0.9rem;
      color: #777;
    }

    .product-name {
      font-weight: 600;
      font-size: 1rem;
      margin: 5px 0;
    }

    .stars {
      color: #FFD700;
      font-size: 1rem;
    }

    .price {
      color: #0a9396;
      font-size: 1.1rem;
      font-weight: bold;
    }

    /* Section Titles */
    .section-title,
    .section-subtitle,
    #product1 h2,
    #product1 p {
      text-align: center;
    }

    .section-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .section-subtitle {
      font-size: 1rem;
      color: #777;
      margin-bottom: 20px;
    }

    #product1 h2 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    #product1 p {
      font-size: 1rem;
      color: #777;
      margin-bottom: 20px;
    }

    /* Mobile Cards */
    @media(max-width:768px) {
      .categories__wrapper {
        gap: 10px;
        padding: 10px;
      }

      .category__item {
        min-width: 85vw;
        max-width: 90vw;
      }

      .section-title {
        font-size: 1.5rem;
      }

      #product1 h2 {
        font-size: 1.4rem;
      }
    }

    /* ================= MODERN PRODUCT DETAILS UI ================= */
    :root {
      --primary-color: #0a9396;
      --accent-color: #c59d2f;
      --text-dark: #1f2937;
      --text-muted: #6b7280;
      --bg-light: #f8fafc;
    }

    #prodetails {
      display: flex;
      margin-top: 40px;
      gap: 50px;
      padding: 40px 5%;
      background: #fff;
    }

    #prodetails .single-pro-image {
      width: 45%;
      position: sticky;
      top: 100px;
      height: fit-content;
    }

    /* ====== FLIPKART STYLE PRODUCT GALLERY ====== */
    .gallery-wrapper {
      display: flex;
      flex-direction: row-reverse;
      gap: 20px;
      align-items: flex-start;
    }

    .thumbnails-col {
      display: flex;
      flex-direction: column;
      gap: 12px;
      width: 76px;
      flex-shrink: 0;
      align-self: flex-start;
      margin-top: 0;
    }

    .thumb-box {
      width: 76px;
      height: 76px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      overflow: hidden;
      cursor: pointer;
      background: #fff;
      transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .thumb-box::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(10, 147, 150, 0.04);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .thumb-box:hover::after {
      opacity: 1;
    }

    .thumb-box img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 3px;
      transition: transform 0.3s ease;
    }

    .thumb-box:hover img {
      transform: scale(1.05);
    }

    .thumb-box:hover,
    .thumb-box.active {
      border-color: var(--accent-color);
      box-shadow: 0 4px 12px rgba(197, 157, 47, 0.2);
    }

    .thumb-box.active {
      border-width: 2px;
    }

    .main-img-box {
      flex-grow: 1;
      border: 1.5px solid #f1f5f9;
      border-radius: 20px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
      position: relative;
      cursor: zoom-in;
      align-self: flex-start;
      margin-top: 0;
    }

    .main-img-box img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: contain;
      transition: opacity 0.2s ease, transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .main-img-box:hover img {
      transform: scale(1.12);
    }

    /* Tablet & Mobile responsive layout */
    @media(max-width: 992px) {
      #prodetails .single-pro-image {
        position: relative;
        top: 0;
        margin-bottom: 30px;
      }
    }

    @media(max-width: 768px) {
      .gallery-wrapper {
        flex-direction: column-reverse;
        gap: 15px;
      }

      .thumbnails-col {
        flex-direction: row;
        width: 100%;
        overflow-x: auto;
        padding-bottom: 5px;
        gap: 10px;
      }

      .thumb-box {
        width: 65px;
        height: 65px;
        flex-shrink: 0;
      }

      .main-img-box {
        width: 100%;
      }
    }

    #prodetails .single-pro-details {
      width: 50%;
      padding-top: 10px;
    }

    .breadcrumb-custom {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .breadcrumb-custom a {
      color: inherit;
      text-decoration: none;
      transition: color 0.2s;
    }

    .breadcrumb-custom a:hover {
      color: var(--accent-color);
      text-decoration: underline;
    }

    .product-title {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 10px;
      line-height: 1.1;
    }

    .product-price-box {
      display: flex;
      align-items: center;
      gap: 15px;
      margin: 20px 0;
    }

    .current-price {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--primary-color);
    }

    .sku-badge {
      font-size: 0.75rem;
      background: #f1f5f9;
      padding: 4px 12px;
      border-radius: 20px;
      color: var(--text-muted);
      font-weight: 600;
    }

    .selection-title {
      font-size: 0.95rem;
      font-weight: 700;
      margin-bottom: 12px;
      color: var(--text-dark);
    }

    .size-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 25px;
    }

    .size-chip {
      padding: 10px 20px;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.2s ease;
      user-select: none;
    }

    .size-chip:hover {
      border-color: var(--primary-color);
    }

    .size-chip.selected {
      background: var(--primary-color);
      border-color: var(--primary-color);
      color: #fff;
      box-shadow: 0 4px 12px rgba(10, 147, 150, 0.3);
    }

    .qty-cart-wrapper {
      display: flex;
      gap: 20px;
      margin-top: 25px;
      align-items: center;
      flex-wrap: wrap;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      height: 50px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
      overflow: hidden;
    }

    /* ================= MODERN MODAL (OTP) ================= */
    .swal2-container {
      z-index: 100000 !important;
    }

    #otp-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(8px);
      align-items: center;
      justify-content: center;
      z-index: 10000;
      animation: fadeIn 0.3s ease;
    }

    .modern-modal-content {
      background: #fff;
      padding: 40px;
      border-radius: 24px;
      text-align: center;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-close-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(0, 0, 0, 0.05);
      border: none;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #555;
      cursor: pointer;
      transition: 0.3s;
    }
    .modal-close-btn:hover {
      background: #111;
      color: #d4af37;
      transform: scale(1.1);
    }

    .modal-icon-circle {
      width: 70px;
      height: 70px;
      background: #f0fdfa;
      color: var(--primary-color);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin: 0 auto 20px;
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 8px;
    }

    .modal-subtitle {
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-bottom: 25px;
      line-height: 1.5;
    }

    .modern-input {
      width: 100%;
      padding: 14px 20px;
      border: 2px solid #f1f5f9;
      border-radius: 12px;
      font-size: 1rem;
      margin-bottom: 20px;
      transition: all 0.2s;
      outline: none;
      text-align: center;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .modern-input:focus {
      border-color: var(--primary-color);
      background: #fff;
    }

    .modal-primary-btn {
      width: 100%;
      padding: 15px;
      background: var(--primary-color);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s;
      margin-bottom: 15px;
    }

    .modal-primary-btn:hover {
      background: #088588;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(10, 147, 150, 0.2);
    }

    .modal-cancel-link {
      color: var(--text-muted);
      font-size: 0.9rem;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: color 0.2s;
    }

    .modal-cancel-link:hover {
      color: #ef4444;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }


    .quantity-control button {
      width: 40px;
      height: 100%;
      border: none;
      background: #f9fafb;
      cursor: pointer;
      font-size: 1.2rem;
      color: #374151;
      transition: all 0.2s;
    }

    .quantity-control button:hover {
      background: #e5e7eb;
      color: #000;
    }

    .quantity-control input {
      width: 50px;
      text-align: center;
      border: none;
      border-left: 1px solid #d1d5db;
      border-right: 1px solid #d1d5db;
      background: transparent;
      font-weight: 700;
      font-size: 1.1rem;
      height: 100%;
      color: #111;
    }

    .add-to-cart-btn {
      padding: 0 40px;
      height: 50px;
      background: #111;
      color: #d4af37;
      border: 2px solid #111;
      border-radius: 8px;
      font-size: 1.05rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .add-to-cart-btn:hover {
      background: #d4af37;
      color: #111;
      border-color: #d4af37;
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(212, 175, 55, 0.3);
    }

    .stock-status-box {
      margin-top: 12px;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .product-meta-sections {
      margin-top: 40px;
      border-top: 1px solid #f1f5f9;
      padding-top: 30px;
    }

    .meta-section {
      margin-bottom: 25px;
    }

    .meta-section h4 {
      font-size: 1.1rem;
      margin-bottom: 10px;
      color: var(--text-dark);
    }

    .meta-section p {
      color: var(--text-muted);
      line-height: 1.6;
    }



    @media(max-width: 992px) {
      #prodetails {
        flex-direction: column;
        padding: 20px 5%;
      }

      #prodetails .single-pro-image,
      #prodetails .single-pro-details {
        width: 100%;
      }

      .product-title {
        font-size: 2rem;
      }
    }
  </style>
</head>

<body>
  <!-- Header & Navigation -->
  <?php include 'includes/header_nav.php'; ?>
  <?php include 'includes/category_nav.php'; ?>

  <?php
  // Include database connection


  // Get the product_id from the URL
  if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Use the product data already securely fetched (with fallbacks) at the top of this file
    $row = $product;

    if ($row) {
      $product_name = $row['name'] ?? 'N/A';
      $brand = $row['brand'] ?? 'N/A';
      $original_price = $row['price'] ?? 0;
      $price = $original_price;
      $bumper_discount = !empty($row['bumper_discount']) ? (int)$row['bumper_discount'] : 0;
      $is_active_bumper = (!empty($row['is_bumper_offer']) && $row['is_bumper_offer'] == 1 && $bumper_discount > 0);
      if ($is_active_bumper) {
          $price = round($original_price * (1 - $bumper_discount / 100), 2);
      }
      $image = $row['Image1'] ?? '';
      $image2 = $row['Image2'] ?? '';
      $image3 = $row['Image3'] ?? '';
      $image4 = $row['Image4'] ?? '';
      $category = !empty($row['main_category_name']) ? $row['main_category_name'] : (!empty($row['sub_category_name']) ? $row['sub_category_name'] : (!empty($row['category']) ? $row['category'] : 'N/A'));
      $main_cat_id = intval($row['mc_id'] ?? 0);
      $is_fashion = ($main_cat_id === 7); // 7 = AllGen Fashion Wear
      
      // Parse sizes to verify if sizes actually exist
      $parsed_sizes = [];
      $raw_size = $row['Size'] ?? ($row['size'] ?? '');
      if (!empty($raw_size)) {
        $decoded = json_decode($raw_size, true);
        $parsed_sizes = is_array($decoded) ? array_map(function ($s) {
          return trim($s, " \t\n\r\0\x0B\"[]");
        }, $decoded) : array_map('trim', explode(",", $raw_size));
        $parsed_sizes = array_filter($parsed_sizes, function($s) { return $s !== ''; });
      }
      if (empty($parsed_sizes)) {
          $is_fashion = false; // No valid sizes found, bypass size selection
      }
      $description = $row['description'] ?? '';
      $quantity = $row['Stock'] ?? 0;
      $pr_id = $row['id'];
      $sku_no = $row['sku_no'] ?? 'N/A';

      $couponMsg = "";



      // Fetch related products (with robust fallback so the section is never empty!)
      $related_products = [];
      $main_cat_id = intval($row['mc_id'] ?? 0);

      if ($main_cat_id > 0) {
        $sql_related = "SELECT id, name, brand, price, Image1, 'all_category' AS source 
                        FROM all_category 
                        WHERE main_category_id = ? AND NOT (id = ? AND ? = 'all_category')
                        UNION ALL
                        SELECT sc.id, sc.name, sc.brand, sc.price, sc.image1 AS Image1, 'subcategories' AS source 
                        FROM subcategories sc
                        LEFT JOIN sub_category sub ON sc.category_id = sub.id
                        WHERE sub.main_category_id = ? AND NOT (sc.id = ? AND ? = 'subcategories')
                        LIMIT 5";
        $related_stmt = $conn->prepare($sql_related);
        $source_param1 = $row['table_type'] ?? '';
        $source_param2 = $row['table_type'] ?? '';
        $related_stmt->bind_param("iisiis", $main_cat_id, $product_id, $source_param1, $main_cat_id, $product_id, $source_param2);
        $related_stmt->execute();
        $related_products = $related_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $related_stmt->close();
      }

      // Fallback: If no related products found in the same main category, fetch other products
      if (empty($related_products)) {
        $sql_fallback = "SELECT id, name, brand, price, Image1, 'all_category' AS source 
                         FROM all_category 
                         WHERE NOT (id = ? AND ? = 'all_category')
                         UNION ALL
                         SELECT id, name, brand, price, image1 AS Image1, 'subcategories' AS source 
                         FROM subcategories 
                         WHERE NOT (id = ? AND ? = 'subcategories')
                         LIMIT 5";
        $fallback_stmt = $conn->prepare($sql_fallback);
        $source_param1 = $row['table_type'] ?? '';
        $source_param2 = $row['table_type'] ?? '';
        $fallback_stmt->bind_param("isis", $product_id, $source_param1, $product_id, $source_param2);
        $fallback_stmt->execute();
        $related_products = $fallback_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $fallback_stmt->close();
      }
    } else {
      echo "<p>Product not found!</p>";
      exit;
    }
  } else {
    echo "<p>No product selected!</p>";
    exit;
  }




  // Function to get the correct full image path
  function getProductImgPath(?string $imgName): string
  {
    if (empty($imgName)) return 'shop_admin/assets/no-image.png';
    if (strpos($imgName, 'uploads/') !== false) {
      return 'shop_admin/' . $imgName;
    }
    return 'shop_admin/uploads/subshop/' . $imgName;
  }
  ?>
  <!-- 👇 Product Details Section -->
  <section id="prodetails" class="section-p1">
    <div class="single-pro-image">
      <div class="gallery-wrapper">
        <!-- Vertical Thumbnails on the left -->
        <div class="thumbnails-col">
          <?php 
            $t_imgs = [];
            if (!empty($image)) $t_imgs[] = getProductImgPath($image);
            if (!empty($image2)) $t_imgs[] = getProductImgPath($image2);
            if (!empty($image3)) $t_imgs[] = getProductImgPath($image3);
            if (!empty($image4)) $t_imgs[] = getProductImgPath($image4);
            
            // If less than 4 images, repeat the first one to fill up space
            while (count($t_imgs) > 0 && count($t_imgs) < 4) {
               $t_imgs[] = $t_imgs[0];
            }
            
            foreach ($t_imgs as $idx => $t_img) {
          ?>
            <div class="thumb-box <?php echo $idx === 0 ? 'active' : ''; ?>" onmouseover="changeImage('<?php echo $t_img; ?>', this)" onclick="changeImage('<?php echo $t_img; ?>', this)">
              <img src="<?php echo $t_img; ?>" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>
          <?php } ?>
        </div>

        <!-- Main Product Image on the right -->
        <div class="main-img-box">
          <img src="<?php echo getProductImgPath($image); ?>" id="MainImg" alt="<?php echo htmlspecialchars($product_name); ?>" />
        </div>
      </div>
    </div>

    <script>
      function changeImage(imageSrc, thumbElement) {
        const mainImg = document.getElementById("MainImg");
        if (mainImg.src === imageSrc) return; // Skip if already active

        // Smooth transition
        mainImg.style.opacity = '0.4';
        setTimeout(() => {
          mainImg.src = imageSrc;
          mainImg.style.opacity = '1';
        }, 80);

        // Update active state in thumbnail elements
        document.querySelectorAll('.thumb-box').forEach(box => box.classList.remove('active'));
        if (thumbElement) {
          thumbElement.classList.add('active');
        }
      }
    </script>

    <!-- OTP Popup HTML -->
    <div id="otp-popup">
      <div class="modern-modal-content">
        <button class="modal-close-btn" onclick="document.getElementById('otp-popup').style.display='none'" title="Close">
          <i class="fas fa-times"></i>
        </button>
        <div class="modal-icon-circle" style="background:#111; color:#d4af37;">
          <i class="fas fa-mobile-alt"></i>
        </div>

        <div id="phone-entry-section">
          <h3 class="modal-title">Verification Required</h3>
          <p class="modal-subtitle">To ensure a secure checkout, please enter your phone number to receive a one-time password (OTP).</p>

          <form id="otp-form" onsubmit="event.preventDefault(); sendOtp();">
            <input type="tel" id="phone-number" class="modern-input" placeholder="Enter Phone Number" required maxlength="10" pattern="[0-9]{10}" />
            <button type="submit" id="btn-get-otp" class="modal-primary-btn" style="background:#111; color:#d4af37; border:2px solid #111;">
              <i class="fas fa-paper-plane" style="margin-right: 8px;"></i> <span>Get OTP</span>
            </button>
          </form>
        </div>

        <div id="otp-verification-section" style="display:none;">
          <h3 class="modal-title">Enter OTP</h3>
          <p class="modal-subtitle">We've sent a 6-digit code to <span id="display-phone-number" style="font-weight:700; color:#111;"></span>. <a href="#" onclick="editPhoneNumber()" style="color:#d4af37; font-weight:600; text-decoration:none; margin-left:5px;" title="Edit Phone Number"><i class="fas fa-edit"></i></a></p>

          <form onsubmit="event.preventDefault(); verifyOtp();">
            <input type="tel" id="otp-input" class="modern-input" placeholder="######" required maxlength="6" style="letter-spacing: 10px; font-size: 1.5rem; text-align:center; font-weight:800;" />
            
            <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px; font-size:0.95rem;">
                <span id="otp-timer-text" style="color:#666; font-weight:500;">Resend OTP in <span id="otp-countdown" style="font-weight:700; color:#ef4444;">30</span>s</span>
                <a href="#" id="resend-otp-link" onclick="resendOtp()" style="display:none; color:#d4af37; font-weight:700; text-decoration:none;"><i class="fas fa-sync-alt" style="margin-right:5px;"></i> Resend OTP</a>
            </div>

            <button type="submit" id="btn-verify-otp" class="modal-primary-btn" style="background:#111; color:#d4af37; border:2px solid #111;">
              <i class="fas fa-check-circle" style="margin-right: 8px;"></i> <span>Verify & Add to Cart</span>
            </button>
          </form>
        </div>

        <a onclick="closePopup()" class="modal-cancel-link" style="display:inline-block; margin-top:15px;">Not now, cancel</a>
      </div>
    </div>

    <div class="single-pro-details">
      <div class="breadcrumb-custom">
        <a href="index.php"><?php echo htmlspecialchars($brand); ?></a> / <?php echo htmlspecialchars($category); ?>
      </div>

      <h1 class="product-title"><?php echo htmlspecialchars($product_name); ?></h1>

      <?php 
          $hide_timer = isset($_GET['hide_timer']) ? (int)$_GET['hide_timer'] : 0;
          if (!empty($row['is_bumper_offer']) && !empty($row['bumper_end_date']) && !$hide_timer): 
          $target_time = $row['bumper_end_date'];
          $bumper_title = !empty($row['bumper_title']) ? $row['bumper_title'] : "Bumper Offer";
          $bumper_discount = !empty($row['bumper_discount']) ? (int)$row['bumper_discount'] : 0;
      ?>
      <div class="bumper-timer-container mt-3 mb-4" style="background: linear-gradient(135deg, #111111, #000000); border: 1px solid #d4af37; border-radius: 12px; padding: 15px 20px; display: inline-block; position: relative; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.15);">
          <div style="color: #d4af37; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;"><i class="fas fa-bolt mr-1"></i> <?= htmlspecialchars($bumper_title) ?> Ends In:</div>
          <div id="drt-countdown" style="display: flex; gap: 15px;">
              <div class="time-box" style="text-align: center;"><span id="cd-days" style="font-size: 1.5rem; font-weight: 800; color: #fff; display: block; line-height: 1;">00</span><small style="font-size: 0.7rem; color: #aaa; text-transform: uppercase; font-weight: 700;">Days</small></div>
              <div class="time-box" style="text-align: center;"><span id="cd-hours" style="font-size: 1.5rem; font-weight: 800; color: #fff; display: block; line-height: 1;">00</span><small style="font-size: 0.7rem; color: #aaa; text-transform: uppercase; font-weight: 700;">Hrs</small></div>
              <div class="time-box" style="text-align: center;"><span id="cd-mins" style="font-size: 1.5rem; font-weight: 800; color: #fff; display: block; line-height: 1;">00</span><small style="font-size: 0.7rem; color: #aaa; text-transform: uppercase; font-weight: 700;">Mins</small></div>
              <div class="time-box" style="text-align: center;"><span id="cd-secs" style="font-size: 1.5rem; font-weight: 800; color: #d4af37; display: block; line-height: 1;">00</span><small style="font-size: 0.7rem; color: #d4af37; text-transform: uppercase; font-weight: 700;">Secs</small></div>
          </div>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
            var targetDate = new Date("<?php echo $target_time; ?>").getTime();
            var timer = setInterval(function() {
                var now = new Date().getTime();
                var distance = targetDate - now;
                if (distance < 0) {
                    clearInterval(timer);
                    document.getElementById("drt-countdown").innerHTML = "<div style='color:#d4af37; font-weight:700; font-size:1.1rem;'>OFFER EXPIRED</div>";
                    return;
                }
                document.getElementById("cd-days").innerText = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                document.getElementById("cd-hours").innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                document.getElementById("cd-mins").innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                document.getElementById("cd-secs").innerText = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
            }, 1000);
        });
      </script>
      <?php endif; ?>

      <div class="product-price-box">
        <?php if (!empty($is_active_bumper)): ?>
          <del style="color: #888; font-size: 1.2rem; margin-right: 10px; font-weight: 600;">₹<?php echo number_format((float)$original_price, 2); ?></del>
          <span class="current-price text-danger">₹<?php echo number_format((float)$price, 2); ?></span>
          <span class="badge badge-danger ml-2" style="background: #ff4757; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; vertical-align: middle;"><?php echo $bumper_discount; ?>% OFF</span>
        <?php else: ?>
          <span class="current-price">₹<?php echo number_format((float)$price, 2); ?></span>
        <?php endif; ?>
        <span class="sku-badge">SKU: <?php echo htmlspecialchars($sku_no); ?></span>
      </div>

      <form id="addToCartForm">
        <input type="hidden" name="prd_id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="prd_source" id="prd_source" value="<?php echo htmlspecialchars($product['table_type'] ?? ''); ?>">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
        <input type="hidden" name="product_price" value="<?php echo $price; ?>">
        <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($image); ?>">
        <input type="hidden" name="sku_no" value="<?php echo htmlspecialchars($sku_no); ?>">
        <input type="hidden" name="add_to_cart" value="1" />
        <input type="hidden" name="product_size" id="selected_size" value="<?php echo $is_fashion ? '' : 'One Size'; ?>" required>

        <?php if ($is_fashion): ?>
        <div class="selection-title">SELECT SIZE</div>
        <div class="size-chips" id="sizeSelector">
          <?php
          $sizes = $parsed_sizes;
          foreach ($sizes as $size): ?>
            <div class="size-chip" onclick="selectSize(this, '<?php echo htmlspecialchars($size); ?>')">
              <?php echo htmlspecialchars($size); ?>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="stock-status-box">
          <span style="color: var(--text-muted)">Availability: </span>
          <span id="stockStatusLabel" style="color: var(--accent-color)"><?php echo $is_fashion ? 'Please select a size' : 'Checking stock...'; ?></span>
        </div>

        <div class="qty-cart-wrapper">
          <div class="quantity-control">
            <button type="button" id="qty-minus-btn" onclick="changeQty(-1)">-</button>
            <input type="number" name="product_quantity" id="quantity" value="1" min="1" readonly>
            <button type="button" id="qty-plus-btn" onclick="changeQty(1)">+</button>
          </div>

          <button type="button" class="add-to-cart-btn" onclick="handleAddToCart()">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </form>



      <div class="product-meta-sections">
        <div class="meta-section">
          <h4><i class="fas fa-align-left" style="color: var(--primary-color); margin-right: 8px;"></i> Product Description</h4>
          <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
        </div>
        <div class="meta-section">
          <h4><i class="fas fa-shipping-fast" style="color: var(--primary-color); margin-right: 8px;"></i> Shipping Info</h4>
          <p>Standard delivery in 3-5 business days. Free shipping on orders above ₹999.</p>
        </div>
      </div>
    </div>

    <style>
      .premium-error-toast {
        border: 1px solid rgba(255, 71, 87, 0.4) !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 25px rgba(255, 71, 87, 0.2) !important;
        font-family: 'Inter', sans-serif !important;
        padding: 12px 20px !important;
      }
    </style>
    <script>
      window.maxAvailableStock = 0;

      function selectSize(element, size) {
        // Remove selected class from all chips
        document.querySelectorAll('.size-chip').forEach(chip => chip.classList.remove('selected'));
        // Add to current
        element.classList.add('selected');
        // Update hidden input
        document.getElementById('selected_size').value = size;
        // Trigger stock check
        updateStockStatus();
      }

      function updateQtyButtons() {
        const qtyInput = document.getElementById('quantity');
        const currentVal = parseInt(qtyInput.value) || 1;
        const plusBtn = document.getElementById('qty-plus-btn');
        const minusBtn = document.getElementById('qty-minus-btn');

        if (window.maxAvailableStock > 0 && currentVal >= window.maxAvailableStock) {
            plusBtn.disabled = true;
            plusBtn.style.opacity = '0.4';
            plusBtn.style.cursor = 'not-allowed';
        } else {
            plusBtn.disabled = false;
            plusBtn.style.opacity = '1';
            plusBtn.style.cursor = 'pointer';
        }

        if (currentVal <= 1) {
            minusBtn.disabled = true;
            minusBtn.style.opacity = '0.4';
            minusBtn.style.cursor = 'not-allowed';
        } else {
            minusBtn.disabled = false;
            minusBtn.style.opacity = '1';
            minusBtn.style.cursor = 'pointer';
        }
      }

      function changeQty(delta) {
        const qtyInput = document.getElementById('quantity');
        let newVal = parseInt(qtyInput.value) + delta;
        
        if (newVal >= 1) {
            // Check if attempting to exceed stock
            if (window.maxAvailableStock > 0 && newVal > window.maxAvailableStock) {
                const itemText = window.maxAvailableStock === 1 ? ' item' : ' items';
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Stock Limit Reached! Only ' + window.maxAvailableStock + itemText + ' available.',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timerProgressBar: true,
                    timer: 4000,
                    background: '#1a1a1a',
                    color: '#fff',
                    iconColor: '#ff4757',
                    customClass: {
                        popup: 'premium-error-toast'
                    }
                });
                return; // Stop the increment
            }
            qtyInput.value = newVal;
            updateQtyButtons();
        }
      }

      function handleAddToCart() {
        const size = document.getElementById('selected_size').value;
        const isFashion = <?php echo $is_fashion ? 'true' : 'false'; ?>;
        
        if (isFashion && (!size || size === '-')) {
          Swal.fire({
            icon: 'warning',
            title: 'Please select a size',
            text: 'You must choose a size before adding to cart.'
          });
          return;
        }

        const qty = parseInt(document.getElementById('quantity').value);
        if (window.maxAvailableStock > 0 && qty > window.maxAvailableStock) {
             const itemText = window.maxAvailableStock === 1 ? ' item' : ' items';
             Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Stock Limit Reached! Only ' + window.maxAvailableStock + itemText + ' available.',
                showConfirmButton: false,
                showCloseButton: true,
                timerProgressBar: true,
                timer: 4000,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: '#ff4757',
                customClass: {
                    popup: 'premium-error-toast'
                }
            });
            return;
        }

        // Call the existing OTP logic
        document.getElementById('otp-popup').style.display = 'flex';
      }

      function updateStockStatus() {
        const productId = document.querySelector('input[name="prd_id"]').value;
        const size = document.getElementById('selected_size').value;
        const label = document.getElementById("stockStatusLabel");
        const isFashion = <?php echo $is_fashion ? 'true' : 'false'; ?>;

        if (isFashion && (!size || size === '-')) return;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true); // Send request to the current page strictly (ignores <base> tag)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
          try {
              const response = JSON.parse(xhr.responseText.trim());
              window.maxAvailableStock = response.stock;
              
              // Automatically clamp quantity if it exceeds new max stock
              const qtyInput = document.getElementById('quantity');
              if (parseInt(qtyInput.value) > window.maxAvailableStock) {
                  qtyInput.value = window.maxAvailableStock || 1;
              }
              // Immediately update buttons based on new stock
              updateQtyButtons();

              if (response.available) {
                label.textContent = "Available in Stock (" + response.stock + " items)";
                label.style.color = "#2a8105"; // Green color
              } else {
                label.textContent = "Out of Stock";
                label.style.color = "red"; // Red color
              }
          } catch(e) {
              // Fallback if not JSON
              console.log("Error parsing stock: " + xhr.responseText);
              label.textContent = "Error checking stock";
              label.style.color = "gray";
          }
        };

        const source = document.getElementById('prd_source') ? document.getElementById('prd_source').value : '';

        xhr.send("prd_id=" + encodeURIComponent(productId) + "&size=" + encodeURIComponent(size || '') + "&source=" + encodeURIComponent(source));
      }

      window.addEventListener('DOMContentLoaded', (event) => {
        <?php if (!$is_fashion): ?>
          updateStockStatus();
        <?php endif; ?>
      });
    </script>

  </section>
  <style>
    .product-details-box {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 25px;
      margin-top: 25px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
      font-family: 'Segoe UI', sans-serif;
    }

    .product-details-box h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #2c2c2c;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
    }

    .product-code {
      font-weight: 600;
      color: #444;
      margin-bottom: 15px;
    }

    .product-intro {
      font-size: 15px;
      color: #444;
      line-height: 1.8;
      margin-bottom: 25px;
    }

    .product-spec-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .product-spec-list li {
      margin-bottom: 10px;
      font-size: 15px;
      color: #333;
    }

    .product-spec-list li .label {
      font-weight: 600;
      color: #000;
    }

    .product-spec-list li.section-header {
      font-weight: 600;
      font-size: 16px;
      margin-top: 18px;
      color: #6c2bd9;
      border-bottom: 1px solid #ddd;
      padding-bottom: 3px;
    }
  </style>
  <!-- 👇 Related Products Section -->
  <?php if (!empty($related_products)): ?>
    <section class="related-section">
      <h2>Related Products</h2>
      <div class="related-products-grid">
        <?php foreach ($related_products as $product): ?>
          <?php
          $rel_name = $product['name'] ?? '';
          $rel_price = $product['price'] ?? 0;
          $rel_img = $product['Image1'] ?? $product['image1'] ?? '';
          $rel_img_path = getProductImgPath($rel_img);
          $rel_brand = $product['brand'] ?? 'IshaHiya';
          ?>
          <div class="related-product-card">
            <div class="rel-img-box">
              <img src="<?php echo htmlspecialchars($rel_img_path); ?>"
                alt="<?php echo htmlspecialchars($rel_name); ?>"
                onerror="this.onerror=null;this.src='shop_admin/uploads/no-image.png';" />
            </div>
            <div class="rel-info-box">
              <span class="rel-brand"><?php echo htmlspecialchars($rel_brand); ?></span>
              <h4 class="rel-name"><?php echo htmlspecialchars($rel_name); ?></h4>
              <div class="rel-price">₹<?php echo number_format($rel_price, 2); ?></div>
              <a href="drt.php?product_id=<?php echo htmlspecialchars($product['id']); ?>&source=subcategories" class="rel-btn">View Details</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <style>
    /* ====== RELATED PRODUCTS PREMIUM SECTION ====== */
    .related-section {
      padding: 60px 5%;
      max-width: 1200px;
      margin: 0 auto;
    }

    .related-section h2 {
      font-size: 2.2rem;
      font-weight: 800;
      text-align: center;
      color: #1a1a1a;
      margin-bottom: 40px;
      position: relative;
    }

    .related-section h2::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background: var(--accent-color);
      margin: 15px auto 0;
      border-radius: 2px;
    }

    .related-products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
    }

    /* Premium Related Card Styling */
    .related-product-card {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      border: 1px solid rgba(0, 0, 0, 0.03);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .related-product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-color: rgba(197, 157, 47, 0.2);
    }

    .rel-img-box {
      position: relative;
      aspect-ratio: 1/1;
      overflow: hidden;
      background: #fdfdfd;
      display: flex;
      align-items: center;
      justify-content: center;
      border-bottom: 1px solid #f2e9e1;
    }

    .rel-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.8s ease;
    }

    .related-product-card:hover .rel-img-box img {
      transform: scale(1.06);
    }

    .rel-info-box {
      padding: 18px;
      text-align: center;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .rel-brand {
      font-size: 10px;
      color: #888;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      font-weight: 600;
      margin-bottom: 5px;
      display: block;
    }

    .rel-name {
      font-size: 14px;
      color: #1a1a1a;
      font-weight: 700;
      margin: 0 0 8px;
      line-height: 1.4;
      height: 38px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .rel-price {
      font-size: 16px;
      font-weight: 800;
      color: #ff6600;
      margin-bottom: 12px;
    }

    .rel-btn {
      display: inline-block;
      margin-top: auto;
      padding: 8px 18px;
      background: #000;
      color: #d4af37 !important;
      border: 1px solid #d4af37;
      border-radius: 20px;
      text-decoration: none !important;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .rel-btn:hover {
      background: #d4af37;
      color: #000 !important;
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
    }

    @media(max-width: 768px) {
      .related-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 0 10px;
      }

      .rel-info-box {
        padding: 12px;
      }

      .rel-name {
        font-size: 12px;
        height: 34px;
      }

      .rel-price {
        font-size: 14px;
      }

      .rel-btn {
        padding: 5px 12px;
        font-size: 10px;
      }
    }
  </style>

  

  <style>
    /* ===== Newsletter Section ===== */
    #newsletter {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      align-items: center;
      background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
      background-image: url(img/b14.png);
      background-repeat: no-repeat;
      background-position: 20% 30%;
      background-blend-mode: overlay;
      padding: 40px 50px;
      gap: 20px;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
    }

    #newsletter h4 {
      font-size: 22px;
      font-weight: 700;
      color: #d4af37;
      margin-bottom: 10px;
      text-shadow: 0 2px 8px rgba(212, 175, 55, 0.5);
    }

    #newsletter p {
      font-size: 14px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.7);
    }

    #newsletter p span {
      color: #d4af37;
      font-weight: 700;
    }

    #newsletter .form {
      display: flex;
      width: 40%;
      min-width: 280px;
    }

    #newsletter input {
      height: 3.125rem;
      padding: 0 1.25em;
      font-size: 14px;
      width: 75%;
      border: 1px solid #d4af37;
      border-radius: 4px;
      outline: none;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      transition: all 0.3s ease;
    }

    #newsletter input:focus {
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
      border-color: #d4af37;
    }

    #newsletter input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    #newsletter button {
      background: #d4af37;
      color: #000;
      white-space: nowrap;
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      height: 3.125rem;
      padding: 0 20px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(212, 175, 55, 0.4);
    }

    #newsletter button:hover {
      background: #c49a2e;
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.8);
    }

    /* ===== Footer Section ===== */
    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      padding: 40px 20px;
      background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
      color: #fff;
      font-family: Arial, sans-serif;
      border-top: 3px solid #d4af37;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.5);
    }

    .col {
      flex: 1 1 220px;
      margin-bottom: 25px;
      text-align: left;
    }

    .nav__logo-img {
      max-width: 200px;
      height: auto;
      object-fit: contain;
      margin-bottom: 15px;
      display: block;
      transition: transform 0.3s ease, filter 0.3s ease, box-shadow 0.3s ease;
      filter: drop-shadow(0 4px 10px rgba(212, 175, 55, 0.4));
      border: 2px solid #d4af37;
      padding: 8px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(212, 175, 55, 0.3),
        0 0 40px rgba(212, 175, 55, 0.2),
        inset 0 0 10px rgba(212, 175, 55, 0.1);
    }

    .nav__logo-img:hover {
      transform: scale(1.08);
      filter: drop-shadow(0 6px 15px rgba(212, 175, 55, 0.7));
      box-shadow: 0 0 30px rgba(212, 175, 55, 0.5),
        0 0 60px rgba(212, 175, 55, 0.3),
        inset 0 0 15px rgba(212, 175, 55, 0.2);
    }

    .col h4 {
      font-size: 16px;
      margin-bottom: 12px;
      color: #d4af37;
      text-transform: uppercase;
      font-weight: 700;
      text-shadow: 0 2px 8px rgba(212, 175, 55, 0.5);
      position: relative;
      padding-bottom: 8px;
    }

    .col h4::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background: #d4af37;
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }

    .col p,
    .col a {
      display: block;
      margin: 5px 0;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-size: 14px;
      line-height: 1.6;
      transition: all 0.3s ease;
      padding: 5px 0;
      position: relative;
    }

    .col a::before {
      content: "";
      position: absolute;
      bottom: 3px;
      left: 0;
      width: 0;
      height: 2px;
      background: #d4af37;
      transition: width 0.4s ease;
    }

    .col a:hover {
      color: #d4af37;
      padding-left: 8px;
      text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }

    .col a:hover::before {
      width: 100%;
    }

    .follow .icon {
      display: flex;
      gap: 12px;
      margin-top: 10px;
    }

    .follow .icon a {
      font-size: 20px;
      transition: all 0.3s ease;
      padding: 8px 12px;
      border-radius: 6px;
      background: rgba(212, 175, 55, 0.1);
      border: 1px solid rgba(212, 175, 55, 0.3);
      filter: drop-shadow(0 2px 6px rgba(212, 175, 55, 0.4));
    }

    .follow .icon a:hover {
      transform: scale(1.15);
      filter: drop-shadow(0 4px 12px rgba(212, 175, 55, 0.8));
      background: rgba(212, 175, 55, 0.2);
      border-color: #d4af37;
    }

    .payment .payment-icons {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-top: 10px;
    }

    .payment .payment-icons img {
      max-width: 50px;
      height: auto;
      transition: all 0.3s ease;
      border: 2px solid rgba(212, 175, 55, 0.3);
      border-radius: 8px;
      padding: 8px;
      background: rgba(255, 255, 255, 0.05);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .payment .payment-icons img:hover {
      transform: scale(1.1);
      border-color: #d4af37;
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.5);
    }

    .copyright {
      width: 100%;
      text-align: center;
      padding: 20px 15px;
      color: rgba(255, 255, 255, 0.7);
      font-size: 13px;
      border-top: 2px solid #d4af37;
      margin-top: 30px;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(26, 26, 26, 0.5) 100%);
      border-radius: 8px;
      box-shadow: 0 -2px 15px rgba(212, 175, 55, 0.2),
        inset 0 2px 10px rgba(0, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }

    .copyright::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, transparent, #d4af37, transparent);
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% {
        left: -100%;
      }

      100% {
        left: 100%;
      }
    }

    .copyright p {
      margin: 0;
      line-height: 1.6;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
      letter-spacing: 0.5px;
    }


    /* ===== Responsive Design ===== */
    @media (max-width: 768px) {
      #newsletter {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
      }

      #newsletter .form {
        width: 100%;
      }

      .footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .col h4::after {
        left: 50%;
        transform: translateX(-50%);
      }

      .col a:hover {
        padding-left: 0;
      }

      .col {
        flex: 1 1 100%;
        margin-bottom: 25px;
      }

      .follow .icon,
      .payment .payment-icons {
        justify-content: center;
      }

      .nav__logo-img {
        margin: 0 auto 20px auto;
      }
    }

    /* Tablet view */
    @media (max-width: 992px) {
      .nav__logo-img {
        width: 120px;
      }
    }

    /* Mobile view */
    @media (max-width: 768px) {
      .nav__logo-img {
        width: 100px;
        margin: 0 auto 20px auto;
      }

      .nav__logo-img {
        width: 90px;
      }
    }

    /* Small mobile view */
    @media (max-width: 480px) {
      .nav__logo-img {
        width: 80px;
      }
    }
  </style>

  <?php include 'includes/footer.php'; ?>

  <a href="https://wa.me/917201808176?text=Hello,%20I%E2%80%99m%20interested%20in%20your%20products.%20Please%20share%20catalog/price%20list%20on%20WhatsApp."
    class="whatsapp-float" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg"
      alt="WhatsApp" width="50" height="50">
  </a>

  <style>
    /* ===== WhatsApp Floating Button ===== */
    .whatsapp-float {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #25D366;
      border-radius: 50%;
      padding: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      z-index: 999;
      transition: transform 0.3s ease;
    }

    .whatsapp-float:hover {
      transform: scale(1.1);
    }

    .whatsapp-float img {
      width: 40px;
      height: 40px;
    }
  </style>
  <script>
    // Function to show OTP popup
    function showOtpPopup() {
      <?php if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true): ?>
        addToCart();
      <?php else: ?>
        document.getElementById('otp-popup').style.display = 'flex';
      <?php endif; ?>
    }

    // Close OTP Popup
    function closePopup() {
      document.getElementById('otp-popup').style.display = 'none';
      document.getElementById('add-to-cart-btn').disabled = false; // Enable Add to Cart button when popup is closed
    }

    let otpTimerInterval;

    function startOtpTimer(durationSeconds) {
      clearInterval(otpTimerInterval);
      const timerText = document.getElementById('otp-timer-text');
      const countdown = document.getElementById('otp-countdown');
      const resendLink = document.getElementById('resend-otp-link');
      
      timerText.style.display = 'inline';
      resendLink.style.display = 'none';
      let remaining = durationSeconds;
      countdown.textContent = remaining;

      otpTimerInterval = setInterval(() => {
        remaining--;
        countdown.textContent = remaining;
        if (remaining <= 0) {
          clearInterval(otpTimerInterval);
          timerText.style.display = 'none';
          resendLink.style.display = 'inline';
        }
      }, 1000);
    }

    function editPhoneNumber() {
      document.getElementById('otp-verification-section').style.display = 'none';
      document.getElementById('phone-entry-section').style.display = 'block';
      clearInterval(otpTimerInterval);
    }

    function resendOtp() {
      sendOtp(true);
    }

    function sendOtp(isResend = false) {
      var phoneNumber = document.getElementById('phone-number').value;
      if (phoneNumber && phoneNumber.length >= 10) {
        var btn = isResend ? document.getElementById('resend-otp-link') : document.getElementById('btn-get-otp');
        var originalText = btn.innerHTML;
        
        if (!isResend) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>';
        } else {
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resending...';
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "send-otp.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (!isResend) {
              btn.disabled = false;
              btn.innerHTML = originalText;
          } else {
              btn.style.pointerEvents = 'auto';
              btn.innerHTML = originalText;
          }

          try {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'OTP Sent!',
                text: 'Please check your phone for the code.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: '#2a8105'
              });
              document.getElementById('phone-entry-section').style.display = 'none';
              document.getElementById('otp-verification-section').style.display = 'block';
              document.getElementById('display-phone-number').textContent = '+91 ' + phoneNumber;
              startOtpTimer(30);
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to send OTP. Please try again.'
              });
            }
          } catch (e) {
            Swal.fire({
              icon: 'error',
              title: 'Server Error',
              text: 'Invalid response from server.'
            });
          }
        };
        xhr.send("phone=" + phoneNumber);
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Number',
          text: 'Please enter a valid 10-digit phone number.'
        });
      }
    }

    function verifyOtp() {
      var otp = document.getElementById('otp-input').value;
      var phoneNumber = document.getElementById('phone-number').value;

      if (otp && phoneNumber) {
        var btn = document.getElementById('btn-verify-otp');
        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Verifying...</span>';

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "verify-otp.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          btn.disabled = false;
          btn.innerHTML = originalText;

          try {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "verified") {
              addToCart();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Verification Failed',
                text: response.message || 'The OTP you entered is incorrect.'
              });
            }
          } catch (e) {
            Swal.fire({
              icon: 'error',
              title: 'Server Error',
              text: 'Invalid response from server.'
            });
          }
        };
        xhr.send("otp=" + otp + "&phone=" + phoneNumber);
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Incomplete Info',
          text: 'Please enter both the OTP and your phone number.'
        });
      }
    }

    // Function to add product to cart after OTP verification
    function addToCart() {
      const form = document.getElementById('addToCartForm');
      const formData = new FormData(form);

      fetch('cart.php', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest' // needed for PHP to detect Ajax
          }
        })
        .then(response => response.text())
        .then(responseText => {
          const response = responseText.trim();
          console.log(response)
          if (response === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Added to Cart',
              text: 'The product has been added to your cart.',
              showConfirmButton: false,
              timer: 1500
            });
            // Optionally update cart count
            const cartCountElem = document.getElementById("cart-count");
            if (cartCountElem) {
              cartCountElem.innerText = parseInt(cartCountElem.innerText) + 1;
            }
            // Redirect to cart page after success
            window.location.href = "cart.php";
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Failed to add product to cart: ' + response,
            });
          }
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Request Failed',
            text: 'Something went wrong with the server request.',
          });
          console.error('Fetch error:', error);
        });
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="script.js"></script>

</body>

</html>