<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ Same DB config as cart.php
$host   = "localhost";
$user   = "ishahiyaone";
$pass   = "BhaV@1437I";
$dbname = "ishahiyaone";

try {
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $db = new PDO($dsn, $user, $pass);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ---- CART COUNT ----
  $cart_count = 0;

  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT COUNT(*) AS total_quantity FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $cart_count = $row && $row['total_quantity'] ? (int)$row['total_quantity'] : 0;
  } else {
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    $cart_count = count($_SESSION['cart']);
  }
  // ---- WISHLIST ----
  $wishlist_ids = [];
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT product_id FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $wishlist_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
} catch (PDOException $e) {
  error_log("DB Connection failed: " . $e->getMessage());
  http_response_code(500);
  exit("Database connection error.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'includes/seo_master.php'; ?>

  <!-- ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ Favicon -->
  <!--  <link rel="icon" href="image/logo/logo.png" type="image/png">-->
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">-->
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <!-- ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ External CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/banner.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ Schema Markup -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Ishahiya",
      "url": "https://ishahiya.com",
      "logo": "https://ishahiya.com/image/logo/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+91-9974328904",
        "contactType": "Customer Service"
      },
      "sameAs": [
        "https://www.facebook.com/share/1NmMBy5VP4/",
        "https://www.instagram.com/ishahiyaone"
      ]
    }
  </script>
</head>

<body>
  <h1 style="display:none;">IshahiyaOne - Premium Online Fashion Store | Navratri & Festival Collections</h1>
  <?php include 'includes/header_nav.php'; ?>
  <?php include 'includes/category_nav.php'; ?>

  <?php
  // ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ Database connection include (MUST BE AT TOP)
  include_once "shop_admin/config/dbconnect.php";
  /** @var mysqli $conn */
  ?>



  <style>
    /* =====================================================
       ISHAHIYAONE ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â FINAL RESPONSIVE ECOMMERCE CSS v10
       ===================================================== */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    :root {
      --gold: #d4af37;
      --gold-dark: #b8860b;
      --black: #111;
      --border: #e8e8e8;
      --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.14);
      --radius: 10px;
      --orange: #ff5722;
      --orange2: #ff7043;
      --green: #2e7d32;
      --transition: all 0.3s ease;
    }

    html {
      scroll-behavior: smooth;
      overflow-x: clip;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: #fff;
      color: #1a1a1a;
      line-height: 1.5;
      -webkit-text-size-adjust: 100%;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    section img,
    .premium-grid img,
    .coll-slider img,
    .hero-section img,
    .promo-banner img {
      display: block;
    }

    a {
      -webkit-tap-highlight-color: transparent;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Category nav ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .category-menu-container {
      height: 40px !important;
      box-shadow: none !important;
      border-bottom: 1px solid #f0f0f0 !important;
      background: #fff !important;
      overflow: visible !important;
      scrollbar-width: none !important;
      -ms-overflow-style: none !important;
      position: sticky !important;
      top: 71px !important;
      z-index: 1000 !important;
    }

    .category-menu-container ::-webkit-scrollbar {
      display: none !important;
    }

    .category-menu-container .swiper-scrollbar {
      display: none !important;
    }

    .category-menu-wrapper {
      padding: 0 12px !important;
      white-space: nowrap;
    }

    .cat-link {
      font-size: 0.78rem !important;
      padding: 0 10px !important;
      font-weight: 500 !important;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Scrolling slogan ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .scrolling-slogan {
      background: #fff9eb;
      padding: 5px 0;
      border-bottom: 1px solid rgba(212, 175, 55, 0.12);
      overflow: hidden;
      white-space: nowrap;
    }

    .scrolling-text {
      display: inline-block;
      padding-left: 100%;
      animation: ticker 60s linear infinite;
      color: #ff6600;
      font-size: 0.85rem;
      font-weight: 700;
      white-space: nowrap;
    }

    @keyframes ticker {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-100%);
      }
    }

    .scrolling-slogan:hover .scrolling-text {
      animation-play-state: paused;
    }


    /* ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â Hero ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â */
    .hero-section {
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .hero-image {
      width: 100%;
      height: 450px;
      object-fit: contain;
      object-position: center;
      display: block;
      background: #eaeaea;
    }

    /* ── dual image side-by-side wrapper ── */
    .hero-dual-wrap {
      display: flex;
      width: 100%;
      height: 450px;
      background: #eaeaea;
      overflow: hidden;
    }

    .hero-dual-wrap .half {
      flex: 1;
      min-width: 0;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #eaeaea;
      overflow: hidden;
    }

    .hero-dual-wrap .half img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      object-position: center;
      display: block;
    }

    .hero-overlay {
      display: none;
    }

    .carousel-caption {
      display: none;
      pointer-events: none;
    }

    .carousel-caption h5 {
      color: var(--gold);
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 6px;
    }

    .carousel-caption h2 {
      font-size: 2.4rem;
      font-weight: 900;
      color: #fff;
      line-height: 1.1;
      margin-bottom: 12px;
    }

    .carousel-caption p {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.92);
      margin-bottom: 16px;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Sections ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .section-p {
      padding: 40px 0;
    }

    .section-p-compact {
      padding: 24px 0;
    }

    .bg-light-alt {
      background: #f8f9fa;
    }

    .section-title-wrapper {
      text-align: center;
      margin-bottom: 24px;
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--black);
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      display: inline-block;
      padding-bottom: 8px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 36px;
      height: 3px;
      background: var(--gold);
      border-radius: 2px;
    }

    .section-subtitle {
      font-size: 0.7rem;
      color: #888;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 6px;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Product Grid ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â EXPLICIT columns ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .premium-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 16px;
    }
    .premium-grid .product-card-premium {
      flex: 0 0 calc(20% - 13px);
      min-width: 210px;
      max-width: 250px;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Product Card ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .product-card-premium {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      position: relative;
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
    }

    .product-card-premium:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-hover);
      border-color: var(--gold);
    }

    .product-img-wrapper {
      position: relative;
      width: 100%;
      aspect-ratio: 1 / 1;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px;
      overflow: hidden;
    }

    .product-img-main {
      max-width: 100%;
      max-height: 100%;
      width: auto;
      height: auto;
      object-fit: contain;
      transition: transform 0.45s ease;
    }


    .product-card-premium:hover .product-img-main {
      transform: scale(1.07);
    }

    .product-actions {
      position: absolute;
      top: 8px;
      right: 8px;
      z-index: 5;
      display: flex;
      flex-direction: column;
      gap: 5px;
      opacity: 0;
      transform: translateX(8px);
      transition: 0.3s;
    }

    .product-card-premium:hover .product-actions {
      opacity: 1;
      transform: translateX(0);
    }

    .action-btn {
      width: 30px;
      height: 30px;
      background: #fff;
      border: none;
      border-radius: 50%;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.78rem;
      color: var(--black);
      cursor: pointer;
      transition: 0.2s;
      touch-action: manipulation;
    }

    .action-btn:hover {
      background: var(--gold);
      color: #fff;
    }

    .action-btn.active {
      color: #e53e3e;
    }

    .product-details {
      padding: 8px 10px 12px;
      text-align: center;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-top: 1px solid #f0f0f0;
    }

    .product-brand {
      font-size: 0.64rem;
      color: #e76b35;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .product-title {
      font-size: 0.8rem;
      font-weight: 700;
      color: #1a1a1a;
      line-height: 1.4;
      margin: 4px 0;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      min-height: 2.8em;
    }

    .product-price {
      font-size: 0.92rem;
      font-weight: 800;
      color: var(--green);
      display: block;
      margin: 2px 0 6px;
    }

    .btn-shop-now {
      display: block;
      background: linear-gradient(135deg, var(--orange2), var(--orange));
      color: #fff;
      padding: 7px 0;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 700;
      border: none;
      width: 100%;
      cursor: pointer;
      text-decoration: none;
      transition: var(--transition);
      touch-action: manipulation;
    }

    .btn-shop-now:hover {
      background: linear-gradient(135deg, #e64a19, #bf360c);
      color: #fff;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Collection-specific detail reduction ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    /* ======================================
       COLLECTIONS - Premium Redesign
    ====================================== */
    .coll-section-new {
      background: #fafafa;
      padding: 50px 0;
    }

    .coll-header-row {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .coll-main-title {
      font-size: clamp(1.6rem, 3vw, 2.2rem);
      font-weight: 900;
      color: #111;
      margin: 0 0 4px;
      line-height: 1.2;
      font-family: 'Outfit', sans-serif;
    }

    .coll-main-title span {
      color: #d4af37;
    }

    .coll-main-sub {
      color: #777;
      font-size: 0.9rem;
      margin: 0;
    }

    /* ── dual image side-by-side wrapper ── */
    .hero-dual-wrap-unused {
      display: flex;
      width: 100%;
      height: 450px;
      background: #111;
      overflow: hidden;
    }

    .hero-dual-wrap-unused .half {
      flex: 0 0 50%;
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #111;
      overflow: hidden;
    }

    .hero-dual-wrap-unused .half img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      background: #eaeaea;
      overflow: hidden;
    }

    .hero-dual-wrap .half {
      flex: 1;
      min-width: 0;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #eaeaea;
      overflow: hidden;
    }

    .hero-dual-wrap .half img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      object-position: center;
      display: block;
    }

    .coll-view-all {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #111;
      color: #d4af37;
      font-weight: 700;
      font-size: 0.82rem;
      text-decoration: none;
      letter-spacing: 0.5px;
      padding: 10px 22px;
      border-radius: 30px;
      border: 2px solid #111;
      transition: 0.3s;
      white-space: nowrap;
    }

    .coll-view-all:hover {
      background: #d4af37;
      border-color: #d4af37;
      color: #fff;
    }

    .coll-swiper-outer {
      position: relative;
    }

    .coll-swiper-new {
      padding-bottom: 36px !important;
    }

    .coll-slide-new {
      width: 250px !important;
      height: auto;
    }

    /* The card itself */
    .coll-card-new {
      display: block;
      text-decoration: none;
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.10);
      transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .coll-card-new:hover {
      transform: translateY(-8px);
      box-shadow: 0 14px 36px rgba(0, 0, 0, 0.18);
    }

    /* Image wrapper Ã¢â‚¬â€ tall portrait */
    .coll-card-img {
      position: relative;
      aspect-ratio: 1 / 1;
      overflow: hidden;
      background: #fff;
      padding: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .coll-card-img img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      object-position: center;
      transition: transform 0.5s ease;
      display: block;
    }

    .coll-card-new:hover .coll-card-img img {
      transform: scale(1.08);
    }

    /* Info block below image */
    .coll-card-info {
      padding: 12px 10px 16px;
      text-align: center;
      background: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      border-top: 1px solid #f0f0f0;
    }

    .coll-card-badge {
      font-size: 0.65rem;
      font-weight: 800;
      text-transform: uppercase;
      color: #e76b35;
      letter-spacing: 0.5px;
    }

    .coll-card-name {
      font-size: 0.85rem;
      font-weight: 700;
      color: #111;
      line-height: 1.3;
      max-width: 100%;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    /* Swiper nav buttons */
    .coll-nav-btn {
      color: #111 !important;
      background: #fff !important;
      border-radius: 50% !important;
      width: 40px !important;
      height: 40px !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12) !important;
      top: 42% !important;
    }

    .coll-nav-btn::after {
      font-size: 0.85rem !important;
      font-weight: 900 !important;
    }

    .coll-nav-btn:hover {
      background: #d4af37 !important;
      color: #fff !important;
    }

    .coll-scrollbar-new {
      background: #e0e0e0 !important;
      height: 3px !important;
      border-radius: 3px;
    }

    .coll-scrollbar-new .swiper-scrollbar-drag {
      background: #d4af37 !important;
      border-radius: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .coll-slide-new {
        width: 160px !important;
      }

      .coll-header-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }
    }

    @media (max-width: 480px) {
      .coll-slide-new {
        width: 140px !important;
      }
    }

    /* Cleanup old overrides no longer needed */
    .coll-card-override,
    .coll-img-override,
    .coll-img-main-override,
    .coll-details-override,
    .coll-brand-override,
    .coll-title-override {
      all: unset;
    }




    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Best Sellers Slider (Swiper) ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .best-product-slider.swiper {
      padding: 10px 0 40px;
    }

    .best-product-item.swiper-slide {
      width: 220px;
      height: auto;
    }


    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Promo Banner ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .promo-banner {
      position: relative;
      height: 280px;
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      align-items: center;
      padding: 0 8%;
      box-shadow: var(--shadow);
      background-color: #fdfdfd;
    }

    .promo-overlay-light {
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.25) 100%);
      z-index: 1;
    }

    .promo-content {
      position: relative;
      z-index: 2;
      max-width: 340px;
    }

    .promo-content h4 {
      color: var(--gold-dark);
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      margin-bottom: 6px;
    }

    .promo-content h2 {
      font-size: 1.7rem;
      font-weight: 900;
      color: var(--black);
      line-height: 1.2;
      margin-bottom: 8px;
    }

    .promo-content p {
      font-size: 0.82rem;
      color: #666;
      margin-bottom: 14px;
      line-height: 1.4;
    }

    /* ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ Buttons ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ */
    .hero-image-half {
      width: 100%;
      height: 450px;
      object-fit: cover;
      object-position: top center;
      display: block;
      background: #000;
    }

    @media (max-width: 992px) {
      .hero-image-half {
        height: 380px;
        object-position: top center;
      }
    }

    @media (max-width: 768px) {
      .hero-image-half {
        height: 300px;
        object-position: top center;
      }
    }

    @media (max-width: 600px) {
      .hero-image-half {
        height: 240px;
        object-position: top center;
      }
    }

    .btn-premium {
      background: var(--black);
      color: #fff;
      padding: 10px 24px;
      border-radius: 30px;
      font-weight: 700;
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border: 1px solid var(--black);
      transition: 0.3s;
      display: inline-block;
      text-decoration: none;
      touch-action: manipulation;
    }

    .btn-premium:hover {
      background: var(--gold);
      border-color: var(--gold);
      color: #fff;
    }

    .btn-premium-outline {
      background: #fff;
      color: var(--black);
      padding: 8px 22px;
      border-radius: 30px;
      font-weight: 700;
      font-size: 0.7rem;
      text-transform: uppercase;
      border: 1.5px solid var(--black);
      transition: 0.3s;
      display: inline-block;
      text-decoration: none;
    }

    .btn-premium-outline:hover {
      background: var(--black);
      color: #fff;
    }

    .newsletter-form .form-control {
      border-radius: 30px;
      flex: 1;
      padding: 10px 20px;
      border: 1px solid var(--border);
      font-size: 0.9rem;
      outline: none;
    }

    .newsletter-form .form-control:focus {
      border-color: var(--gold);
      box-shadow: none;
    }

    /* ============================================
       RESPONSIVE BREAKPOINTS ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â explicit & clean
       ============================================ */

    /* Laptop (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤1400px) ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â 5 col still fine */
    @media (max-width: 1400px) {
      .premium-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
      }
    }

    /* Small laptop / large tablet landscape (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤1200px) */
    @media (max-width: 1200px) {
      .premium-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
      }

      .best-product-item {
        flex: 0 0 200px;
        width: 200px;
      }

      .coll-item {
        flex: 0 0 145px !important;
        width: 145px !important;
      }



      .hero-image {
        width: 100%;
        height: 450px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #eaeaea;
      }

      .hero-dual-wrap {
        height: 450px;
      }
    }

    /* Tablet landscape (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤992px) */
    @media (max-width: 992px) {
      .premium-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
      }

      .offer-grid {
        grid-template-columns: repeat(3, 1fr);
      }

      .hero-image {
        width: 100%;
        height: 380px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #eaeaea;
      }

      .hero-dual-wrap {
        height: 380px;
      }

      .section-title {
        font-size: 1.3rem;
      }

      .section-p {
        padding: 32px 0;
      }

      .best-product-item {
        flex: 0 0 195px;
        width: 195px;
      }

      .coll-item {
        flex: 0 0 170px;
        width: 170px;
      }

      .carousel-caption h2 {
        font-size: 1.9rem;
      }
    }

    /* Tablet portrait / large phone (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤768px) */
    @media (max-width: 768px) {
      .premium-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }

      .offer-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }

      .container {
        padding-left: 14px !important;
        padding-right: 14px !important;
      }

      .hero-image {
        width: 100%;
        height: 300px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #eaeaea;
      }

      .hero-dual-wrap {
        height: 300px;
      }

      .carousel-caption {
        display: none;
        pointer-events: none;
      }

      .carousel-caption h2 {
        font-size: 1.4rem;
      }

      .carousel-caption p {
        display: none;
      }

      .section-p {
        padding: 26px 0;
      }

      .section-title {
        font-size: 1.1rem;
      }

      .section-title-wrapper {
        margin-bottom: 16px;
      }

      .coll-slider-nav {
        display: none;
      }

      .coll-item {
        flex: 0 0 155px;
        width: 155px;
      }

      .best-product-item {
        flex: 0 0 175px;
        width: 175px;
      }

      .promo-banner {
        height: 230px;
        padding: 0 6%;
      }

      .promo-content h2 {
        font-size: 1.3rem;
      }

      .newsletter-card {
        padding: 24px 20px;
      }

      .newsletter-form {
        flex-direction: column;
      }

      .product-img-wrapper {
        height: 150px;
      }

      .category-menu-container {
        height: 36px !important;
        top: 58px !important;
      }
    }

    /* Phone (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤600px) */
    @media (max-width: 600px) {
      .premium-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
      }

      .container {
        padding-left: 10px !important;
        padding-right: 10px !important;
      }

      .carousel-caption {
        display: none;
        pointer-events: none;
      }

      .hero-image {
        width: 100%;
        height: 240px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #eaeaea;
      }

      .hero-dual-wrap {
        height: 240px;
      }

      .section-p {
        padding: 20px 0;
      }

      .section-title {
        font-size: 1rem;
        letter-spacing: 0.5px;
      }

      .product-img-wrapper {
        height: 130px;
        padding: 7px;
      }

      .product-details {
        padding: 7px 9px 11px;
        gap: 2px;
      }

      .product-brand {
        font-size: 0.62rem;
      }

      .product-title {
        font-size: 0.74rem;
        min-height: 2em;
      }

      .product-price {
        font-size: 0.85rem;
      }

      .btn-shop-now {
        padding: 6px 0;
        font-size: 0.66rem;
        border-radius: 16px;
      }

      .coll-item {
        flex: 0 0 135px;
        width: 135px;
      }

      .best-product-item {
        flex: 0 0 155px;
        width: 155px;
      }

      .promo-banner {
        height: 190px;
        padding: 0 5%;
      }

      .promo-content h2 {
        font-size: 1.1rem;
      }

      .promo-content p {
        font-size: 0.76rem;
      }

      .scrolling-text {
        display: block;
        color: #ff6600;
        font-size: 0.76rem;
        font-weight: 600;
        text-align: center;
        padding: 5px 20px;
        white-space: normal;
      }

      .offer-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
      }
    }

    /* Small phone / iPhone SE (ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤480px) */
    @media (max-width: 480px) {
      .container {
        padding-left: 8px !important;
        padding-right: 8px !important;
      }

      .premium-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
      }

      .product-img-wrapper {
        height: 115px;
        padding: 6px;
      }

      .coll-item {
        flex: 0 0 120px;
        width: 120px;
      }

      .best-product-item {
        flex: 0 0 140px;
        width: 140px;
      }

      .hero-image {
        width: 100%;
        height: 180px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #000;
      }

      .offer-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
      }
    }

    /* Touch devices ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â wishlist always visible, no hover */
    @media (hover: none) {
      .product-actions {
        opacity: 1;
        transform: translateX(0);
      }

      .product-card-premium:hover {
        transform: none;
      }
    }

    /* iOS Safari specific */
    @supports (-webkit-touch-callout: none) {
      .hero-image {
        width: 100%;
        height: auto;
        min-height: 240px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #eaeaea;
      }

      .coll-slider,
      .best-product-slider {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
      }

      .coll-slider::-webkit-scrollbar,
      .best-product-slider::-webkit-scrollbar {
        display: none;
      }

      .btn-shop-now,
      .btn-premium,
      .action-btn {
        touch-action: manipulation;
      }

      a,
      button {
        -webkit-tap-highlight-color: transparent;
      }
    }

    /* Modern Slider Navigation Controls */
    .hero-nav-btn {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      backdrop-filter: blur(4px);
    }

    .carousel-control-prev:hover .hero-nav-btn,
    .carousel-control-next:hover .hero-nav-btn {
      background: rgba(255, 255, 255, 0.9);
      border-color: #fff;
    }

    .carousel-control-prev:hover .hero-nav-btn i,
    .carousel-control-next:hover .hero-nav-btn i {
      color: var(--black) !important;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 5%;
      /* Reduce from 15% so it doesn't overlap the Shop Now button */
      z-index: 10;
    }

    .carousel-control-prev {
      left: 2%;
      opacity: 0.8;
    }

    .carousel-control-next {
      right: 2%;
      opacity: 0.8;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
      opacity: 1;
    }

    /* Modern Dot Indicators instead of dashes */
    .carousel-indicators [data-bs-target] {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.5);
      border: none;
      margin: 0 6px;
      transition: all 0.3s ease;
    }

    .carousel-indicators .active {
      background-color: var(--gold);
      transform: scale(1.2);
    }

    /* Premium Smooth Transition for Hero Carousel */
    #heroCarousel .carousel-item {
      transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1) !important;
    }
  </style>

  <?php
  $sliderRes = $db->query("SELECT title, subtitle, image, mobile_image, image_3, image_4, link, btn_text FROM hero_slider ORDER BY id DESC");
  $sliders = $sliderRes->fetchAll(PDO::FETCH_ASSOC);
  foreach ($sliders as &$s) {
    $s['img_path']        = 'uploads/slider/' . $s['image'];
    $s['mobile_img_path'] = !empty($s['mobile_image']) ? 'uploads/slider/' . $s['mobile_image'] : '';
    $s['image_3_path']    = !empty($s['image_3'])  ? 'uploads/slider/' . $s['image_3']  : '';
    $s['image_4_path']    = !empty($s['image_4'])  ? 'uploads/slider/' . $s['image_4']  : '';
  }
  unset($s);

  $all_sliders = $sliders;
  $hasSliders  = count($all_sliders) > 0;
  ?>

  <!-- Brand Promise Slogan Ticker -->
  <div style="background-color:#fff;border-bottom:1px solid #eee;padding:8px 0;overflow:hidden;">
    <marquee behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();" style="color:#e67e22;font-weight:700;font-size:0.95rem;">
      <span style="margin-right:40px;"><i class="fas fa-star"></i> We don't just sell and walk away &mdash; your complete satisfaction is our promise and responsibility.</span>
    </marquee>
  </div>

  <?php if ($hasSliders): ?>
    <section id="heroCarousel" class="carousel slide hero-section" data-bs-ride="carousel" data-bs-interval="3000" data-bs-wrap="true" data-bs-pause="hover" data-bs-touch="true">
      <div class="carousel-indicators">
        <?php foreach ($all_sliders as $index => $slide): ?>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
        <?php endforeach; ?>
      </div>
      <div class="carousel-inner">
        <?php foreach ($all_sliders as $index => $slide): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <?php
            // Convert spaces to %20 to ensure valid URLs
            $rawLink = $slide['link'] ?: 'shop.php';
            $slideLink = htmlspecialchars(str_replace(' ', '%20', $rawLink));
            ?>
            <a href="<?= $slideLink ?>" style="display: block; position: relative;">
              <div class="hero-overlay"></div>
              <?php
              $imgPanels = array_filter([
                $slide['img_path'],
                $slide['mobile_img_path'],
                $slide['image_3_path'] ?? '',
                $slide['image_4_path'] ?? '',
              ]);
              ?>
              <?php if (count($imgPanels) > 1): ?>
                <div class="hero-dual-wrap">
                  <?php foreach ($imgPanels as $panel): ?>
                    <div class="half">
                      <img src="<?= htmlspecialchars($panel) ?>" alt="<?= htmlspecialchars($slide['title']) ?>">
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <img src="<?= htmlspecialchars($slide['img_path']) ?>" class="hero-image" alt="<?= htmlspecialchars($slide['title']) ?>">
              <?php endif; ?>
            </a>
            <div class="carousel-caption" style="pointer-events: auto;">
              <?php if (!empty($slide['title'])): ?><h5><?= htmlspecialchars($slide['title']) ?></h5><?php endif; ?>
              <?php if (!empty($slide['subtitle'])): ?><h2><?= htmlspecialchars($slide['subtitle']) ?></h2><?php endif; ?>
              <a href="<?= $slideLink ?>" class="btn-premium" style="position: relative; z-index: 10; pointer-events: auto !important; cursor: pointer;"><?= htmlspecialchars($slide['btn_text'] ?: 'Shop Now') ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Navigation Arrows -->
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <div class="hero-nav-btn">
          <i class="fas fa-chevron-left" style="color: #fff; font-size: 1.2rem;"></i>
        </div>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <div class="hero-nav-btn">
          <i class="fas fa-chevron-right" style="color: #fff; font-size: 1.2rem;"></i>
        </div>
        <span class="visually-hidden">Next</span>
      </button>
    </section>
  <?php endif; ?>
  <!-- OUR COLLECTIONS -->
  <section class="section-p coll-section-new">
    <div class="container-fluid" style="max-width:1400px;">
      <div class="section-title-wrapper" style="text-align: center; margin-bottom: 30px;">
        <h2 class="section-title">Our Collections</h2>
        <p class="section-subtitle">Explore our best categories curated for you</p>
      </div>

      <div class="coll-swiper-outer">
        <div class="swiper coll-swiper-new" id="collSwiper">
          <div class="swiper-wrapper">

            <?php
            try {
              $sqlColl = "SELECT id, product_name AS name, category_name AS brand, image AS Image1, 0 AS price, 'admin_coll' AS type FROM collections";
              $stmtColl = $db->query($sqlColl);
              $collResults = $stmtColl->fetchAll(PDO::FETCH_ASSOC);
              $sqlMain = "SELECT id, name, brand, Image1, Image2, price, sub_category_id, 'main' AS type FROM all_category WHERE is_our_collection = 1";
              $stmtMain = $db->query($sqlMain);
              $mainResults = $stmtMain->fetchAll(PDO::FETCH_ASSOC);
              $sqlSub = "SELECT id, name, brand, image1 AS Image1, image2 AS Image2, price, category_id AS sub_category_id, 'sub' AS type FROM subcategories WHERE is_our_collection = 1";
              $stmtSub = $db->query($sqlSub);
              $subResults = $stmtSub->fetchAll(PDO::FETCH_ASSOC);
              $results = array_merge($mainResults, $subResults, $collResults);
              if ($results) {
                foreach ($results as $row) {
                  $id    = (int)$row['id'];
                  $name  = $row['name'] ?? 'No Name';
                  $brand = $row['brand'] ?? 'IshaHiya';
                  $img1  = trim($row['Image1'] ?? '');
                  $catId = (int)($row['sub_category_id'] ?? 0);
                  $uploadServerPathMain = __DIR__ . '/shop_admin/uploads/';
                  $uploadServerPathSub  = __DIR__ . '/shop_admin/uploads/subshop/';
                  if (!empty($img1) && file_exists($uploadServerPathMain . basename($img1))) {
                    $image1 = 'shop_admin/uploads/' . basename($img1);
                  } elseif (!empty($img1) && file_exists($uploadServerPathSub . basename($img1))) {
                    $image1 = 'shop_admin/uploads/subshop/' . basename($img1);
                  } else {
                    $image1 = 'shop_admin/uploads/no-image.png';
                  }

                  if (($row['type'] ?? '') === 'admin_coll') {
                    $link = 'collections.php?collection_id=' . $id;
                  } else {
                    // Pass full exact collection name to filter only intended products
                    $link = 'subshop1.php?category_id=' . $catId . '&filter=' . urlencode($name);
                  }
            ?>
                  <div class="swiper-slide coll-slide-new">
                    <a href="<?= htmlspecialchars($link) ?>" class="coll-card-new">
                      <div class="coll-card-img">
                        <img src="<?= htmlspecialchars($image1) ?>" alt="<?= htmlspecialchars($name) ?>" loading="lazy">
                      </div>
                      <div class="coll-card-info">
                        <div class="coll-card-badge"><?= htmlspecialchars($brand) ?></div>
                        <div class="coll-card-name"><?= htmlspecialchars($name) ?></div>
                      </div>
                    </a>
                  </div>
            <?php }
              }
            } catch (PDOException $e) {
              echo '<p class="text-muted text-center">Collections unavailable.</p>';
            } ?>

          </div><!-- /.swiper-wrapper -->
          <div class="swiper-button-prev coll-nav-btn"></div>
          <div class="swiper-button-next coll-nav-btn"></div>
          <div class="swiper-scrollbar coll-scrollbar-new"></div>
        </div><!-- /.coll-swiper-new -->
      </div><!-- /.coll-swiper-outer -->

    </div>
  </section>

  <!-- Products Section (New Arrivals) -->
  <section id="product1" class="section-p">
    <div class="container">
      <div class="section-title-wrapper text-center mb-4 position-relative">
        <h2 class="section-title m-0" style="font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">
           NEW ARRIVALS
        </h2>
        <p class="text-muted mt-2 mb-0" style="font-style: italic; font-size: 1.1rem; font-weight: 500;">
            "Discover the latest trends in technology & fashion"
        </p>
      </div>
      <div class="premium-grid">
        <?php
        include('db.php'); // connection file
        $sqlNew = "
    SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name,
           CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price,
           CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1,
           id AS cat_id, 'all_category' AS source
    FROM all_category WHERE is_new_arrival = 1
    UNION ALL
    SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name,
           CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price,
           CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1,
           id AS cat_id, 'subcategories' AS source
    FROM subcategories WHERE is_new_arrival = 1
    UNION ALL
    SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name,
           CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price,
           CAST(image AS CHAR CHARACTER SET utf8mb4) AS Image1,
           COALESCE(sub_category_id, category_id) AS cat_id, 'products' AS source
    FROM products WHERE is_new_arrival = 1
";

        $resultNew = $conn->query($sqlNew);
        if ($resultNew && $resultNew->num_rows > 0) {
          while ($row = $resultNew->fetch_assoc()) {
            $pId = $row['id'];
            $pName = $row['name'] ?? 'No Name';
            $pBrand = $row['brand'] ?? 'Premium';
            $pPrice = (float)($row['price'] ?? 0);
            $pImgRaw = trim($row['Image1']);
            $pImgName = basename($pImgRaw);

            // Robust multi-path image resolution
            $image = '';
            if (!empty($pImgName)) {
              $pathSub = 'shop_admin/uploads/subshop/' . $pImgName;
              $pathMain = 'shop_admin/uploads/' . $pImgName;
              $pathSubCat = 'image/subcategories/' . $pImgName;
              $pathRaw = ltrim($pImgRaw, '/');

              if (file_exists(__DIR__ . '/' . $pathSub)) {
                $image = $pathSub;
              } elseif (file_exists(__DIR__ . '/' . $pathMain)) {
                $image = $pathMain;
              } elseif (file_exists(__DIR__ . '/' . $pathSubCat)) {
                $image = $pathSubCat;
              } elseif (file_exists(__DIR__ . '/' . $pathRaw)) {
                $image = $pathRaw;
              } else {
                $image = $pImgRaw;
              }
            }

            if (empty($image) || (!file_exists(__DIR__ . '/' . $image) && strpos($image, 'http') === false)) {
              $image = 'shop_admin/uploads/no-image.png';
            }

            // Route directly to drt.php product details page with hide_timer flag
            $pLink = 'drt.php?product_id=' . $pId . '&source=' . urlencode($row['source'] ?? '') . '&hide_timer=1';
        ?>
            <div class="product-card-premium">
              <div class="product-img-wrapper">
                <img class="product-img-main" src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($pName) ?>" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn <?= in_array($pId, $wishlist_ids) ? 'active' : '' ?>" onclick="toggleWishlist(<?= $pId ?>, this)" title="Add to Wishlist">
                    <i class="<?= in_array($pId, $wishlist_ids) ? 'fas' : 'far' ?> fa-heart"></i>
                  </button>
                </div>
              </div>
              <div class="product-details">
                <span class="product-brand"><?= htmlspecialchars($pBrand) ?></span>
                <h5 class="product-title"><?= htmlspecialchars($pName) ?></h5>
                <a href="<?= htmlspecialchars($pLink) ?>" class="btn-shop-now">View More</a>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<div class='col-12 text-center'><p class='text-muted'>No new arrivals at the moment.</p></div>";
        }
        ?>
      </div>
    </div>
  </section>

  <?php
    // Fetch the active Bumper Banner for the countdown timer
    $timerRes = $conn->query("SELECT end_date FROM bumper_offers WHERE status = 1 AND CURDATE() BETWEEN DATE(start_date) AND DATE(end_date) ORDER BY id DESC LIMIT 1");
    $target_time = "";
    if($timerRow = $timerRes->fetch_assoc()) {
        $target_time = $timerRow['end_date'];
    }
    // Default to midnight tonight if no end_date is set
    if(empty($target_time)) {
        $target_time = date('Y-m-d 23:59:59');
    }

    // Fetch the products explicitly marked as Bumper Offers from all 3 tables that are ACTIVE
    $sqlBumper = "
        SELECT id AS product_id, id AS cat_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, 'all_category' AS source, bumper_end_date, bumper_discount FROM all_category WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
        UNION ALL
        SELECT id AS product_id, id AS cat_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, 'subcategories' AS source, bumper_end_date, bumper_discount FROM subcategories WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
        UNION ALL
        SELECT product_id, COALESCE(sub_category_id, category_id) AS cat_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(image AS CHAR CHARACTER SET utf8mb4) AS image, 'products' AS source, bumper_end_date, bumper_discount FROM products WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
        ORDER BY product_id DESC LIMIT 15
    ";
    $bumperProductsRes = $conn->query($sqlBumper);
    $bumperProducts = [];
    if ($bumperProductsRes) {
        while($row = $bumperProductsRes->fetch_assoc()) {
            $bumperProducts[] = $row;
        }
    }
  ?>
  <!-- Bumper Offers Section (Ecommerce Light Layout) -->
  <section id="bumper-offers" class="section-p" style="background-color: #fcfcfc; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; padding: 40px 0;">
    <div class="container position-relative" style="z-index: 1;">
      
      <div class="section-title-wrapper text-center mb-4 position-relative">
        <h2 class="section-title m-0" style="font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">
           BUMPER OFFERS
        </h2>
        
        <!-- Subtitle Quote -->
        <p class="text-muted mt-2 mb-0" style="font-style: italic; font-size: 1.1rem; font-weight: 500;">
            "Exclusive deals handpicked just for you — limited time only."
        </p>
        
        <!-- View All Link (Aligned to the right on desktop, centered on mobile) -->
        <div class="mt-3 mt-md-0" style="position: absolute; right: 15px; bottom: 0px;">
            <a href="bumper-offers.php" class="text-danger font-weight-bold d-none d-md-block" style="text-decoration: underline; font-size: 0.95rem;">
                View All Offers <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
      </div>
      
      <!-- Mobile View All Link -->
      <div class="text-center mb-3 d-md-none">
          <a href="bumper-offers.php" class="text-danger font-weight-bold" style="text-decoration: underline; font-size: 0.95rem;">
              View All Offers <i class="fas fa-arrow-right ml-1"></i>
          </a>
      </div>
      <style>
        .premium-offer-grid {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 15px;
            padding-bottom: 15px;
            padding-top: 5px;
            scrollbar-width: none; /* Firefox */
            scroll-behavior: smooth;
        }
        .premium-offer-grid::-webkit-scrollbar {
            display: none; /* Chrome */
        }
        .premium-offer-card {
            flex: 0 0 calc(20% - 12px); /* 5 cards per row on desktop */
            min-width: 240px;
            scroll-snap-align: start;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            text-align: center;
            border: 1px solid #f2f2f2;
            transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            overflow: hidden;
        }
        .premium-offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        @media (max-width: 1200px) {
            .premium-offer-card { flex: 0 0 calc(25% - 12px); } /* 4 per row */
        }
        @media (max-width: 992px) {
            .premium-offer-card { flex: 0 0 calc(33.333% - 10px); } /* 3 per row */
        }
        @media (max-width: 768px) {
            .premium-offer-card { flex: 0 0 calc(50% - 8px); } /* 2 per row */
        }
        @media (max-width: 480px) {
            .premium-offer-card { flex: 0 0 calc(100% - 10px); min-width: 260px; } /* 1 per row */
        }
        .premium-offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-decoration: none;
        }
        .po-badge-hot {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .po-badge-discount {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 3;
        }
        .po-img-wrapper {
            height: 220px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fdfdfd;
            position: relative;
            overflow: hidden;
            padding: 15px;
        }
        .po-img-wrapper img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.4s ease;
            z-index: 1;
        }
        .premium-offer-card:hover .po-img-wrapper img {
            transform: scale(1.05);
        }
        .po-action-overlay {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 10px;
            transition: bottom 0.3s ease;
            padding: 15px 10px;
            background: linear-gradient(to top, rgba(0,0,0,0.05), transparent);
            z-index: 2;
        }
        .premium-offer-card:hover .po-action-overlay {
            bottom: 0;
        }
        .po-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .po-action-btn:hover {
            background: #111;
            color: #c59d2f;
            transform: scale(1.1);
        }
        .po-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex: 1;
            background: #fff;
            z-index: 3;
        }
        .po-brand {
            font-size: 11px;
            color: #888;
            margin-bottom: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .po-title {
            font-size: 14px;
            font-weight: 800;
            color: #111;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 40px;
            line-height: 1.4;
            transition: color 0.2s;
        }
        .premium-offer-card:hover .po-title {
            color: #c59d2f;
        }
        .po-stars {
            color: #fadb14;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            justify-content: center;
            gap: 2px;
        }
        .po-price-wrapper {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px dashed #eee;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .po-price {
            font-size: 14px;
            font-weight: 900;
            color: #c59d2f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Slider Navigation Buttons */
        .slider-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 10;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 14px;
            color: #555;
        }
        .slider-nav-btn:hover {
            background: #f8f9fa;
            color: #111;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-50%) scale(1.05);
        }
        .prev-btn { left: -15px; }
        .next-btn { right: -15px; }
        @media (max-width: 768px) {
            .prev-btn { left: -5px; }
            .next-btn { right: -5px; }
            .slider-nav-btn { width: 34px; height: 34px; font-size: 12px; }
        }
      </style>
      
      <div class="position-relative">
          <button class="slider-nav-btn prev-btn" onclick="document.getElementById('bumperSlider').scrollBy({ left: -260, behavior: 'smooth' })">
              <i class="fas fa-chevron-left"></i>
          </button>
          
          <div class="premium-offer-grid" id="bumperSlider">
        <?php
          if (!empty($bumperProducts)) {
            foreach ($bumperProducts as $product) {
              $title = $product['name'] ?? 'Bumper Offer';
              $brand = $product['brand'] ?? 'IshaHiya';
              $price = (float)($product['price'] ?? 0);
              $actual_product_id = (int)$product['product_id'];
              
              $img = trim($product['image']);
              $bannerImg = 'shop_admin/uploads/no-image.png';
              if (!empty($img)) {
                  $imgName = basename($img);
                  if ($product['source'] === 'products') {
                      $bannerImg = 'shop_admin/uploads/' . $imgName;
                  } else {
                      $bannerImg = (strpos($img, 'shop_admin/uploads/') !== false) ? $img : 'shop_admin/uploads/subshop/' . $imgName;
                  }
                  
                  if (!file_exists(__DIR__ . '/' . $bannerImg)) {
                      $bannerImg = 'shop_admin/uploads/no-image.png';
                  }
              }

              $link = "drt.php?product_id=" . $actual_product_id . "&source=" . urlencode($product['source']);
              $discount = (int)$product['bumper_discount'];
              $final_price = $discount > 0 ? round($price * (1 - $discount / 100), 2) : $price;
        ?>
              <a href="<?= htmlspecialchars($link) ?>" class="premium-offer-card">
                  <div class="po-badge-hot"><i class="fas fa-fire mr-1"></i> BUMPER</div>
                  <?php if ($discount > 0): ?>
                  <div class="po-badge-discount"><?= $discount ?>% OFF</div>
                  <?php endif; ?>
                  
                  <div class="po-img-wrapper">
                      <img src="<?= htmlspecialchars($bannerImg) ?>" alt="<?= htmlspecialchars($title) ?>" loading="lazy" style="object-fit: cover;">
                      <div class="po-action-overlay">
                          <span class="po-action-btn" title="Add to Wishlist" onclick="bumperAddToWishlist(event, <?= $actual_product_id ?>, '<?= htmlspecialchars($link) ?>')"><i class="far fa-heart"></i></span>
                          <span class="po-action-btn" title="Quick View" onclick="bumperQuickView(event, <?= $actual_product_id ?>, '<?= htmlspecialchars($link) ?>')"><i class="far fa-eye"></i></span>
                          <span class="po-action-btn" title="Add to Cart" onclick="bumperAddToCart(event, <?= $actual_product_id ?>, <?= htmlspecialchars(json_encode($title)) ?>, <?= $final_price ?>, <?= htmlspecialchars(json_encode($bannerImg)) ?>)"><i class="fas fa-shopping-bag"></i></span>
                      </div>
                  </div>
                  
                  <div class="po-content">
                      <div class="po-brand"><?= htmlspecialchars($brand) ?></div>
                      <h5 class="po-title"><?= htmlspecialchars($title) ?></h5>
                      
                      <div class="po-stars">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                      </div>
                      
                      <div class="po-price-wrapper" style="border-bottom: 1px dashed #eee; padding-bottom: 8px; margin-bottom: 8px;">
                          <?php if ($discount > 0): ?>
                            <del class="text-muted mr-1" style="font-size: 13px;">₹<?= number_format($price, 2) ?></del>
                            <span class="po-price text-danger" style="font-size: 15px; font-weight: bold;">₹<?= number_format($final_price, 2) ?></span>
                          <?php else: ?>
                            <span class="po-price text-danger" style="font-size: 15px;">₹<?= number_format($price, 2) ?></span>
                          <?php endif; ?>
                      </div>
                      
                      <!-- Compact Card Timer -->
                      <div class="po-countdown" data-end-time="<?= date('Y-m-d\TH:i:s', strtotime($product['bumper_end_date'])) ?>" style="background: #fff3f3; border-radius: 4px; padding: 4px 6px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                          <span style="font-size: 10px; font-weight: 800; color: #d93025; text-transform: uppercase;">Ends In</span>
                          <div style="display:flex; align-items:center; gap: 3px; font-size: 11px; font-weight: bold; color: #d93025;">
                              <span class="cd-hours bg-danger text-white rounded px-1 shadow-sm">00</span>:
                              <span class="cd-mins bg-danger text-white rounded px-1 shadow-sm">00</span>:
                              <span class="cd-secs bg-danger text-white rounded px-1 shadow-sm">00</span>
                          </div>
                      </div>
                  </div>
              </a>
        <?php
            }
          } else {
            echo "<div class='col-12 text-center'><p class='text-muted mt-4'>No bumper products available.</p></div>";
          }
        ?>
          </div>
          
          <button class="slider-nav-btn next-btn" onclick="document.getElementById('bumperSlider').scrollBy({ left: 260, behavior: 'smooth' })">
              <i class="fas fa-chevron-right"></i>
          </button>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Real-Time Countdown Timer Logic for Cards
    (function() {
        const countdowns = document.querySelectorAll('.po-countdown');
        
        const updateTimer = () => {
            const now = new Date().getTime();
            
            countdowns.forEach(cd => {
                const targetDate = new Date(cd.getAttribute('data-end-time')).getTime();
                const distance = targetDate - now;

                const hoursEl = cd.querySelector('.cd-hours');
                const minsEl = cd.querySelector('.cd-mins');
                const secsEl = cd.querySelector('.cd-secs');

                if (distance < 0) {
                    hoursEl.innerText = "00";
                    minsEl.innerText = "00";
                    secsEl.innerText = "00";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                hoursEl.innerText = hours.toString().padStart(2, '0');
                minsEl.innerText = minutes.toString().padStart(2, '0');
                secsEl.innerText = seconds.toString().padStart(2, '0');
            });
        };

        updateTimer();
        setInterval(updateTimer, 1000);
    })();

    // Action Overlays Logic
    function bumperAddToCart(e, pid, name, price, image) {
        e.preventDefault();
        e.stopPropagation(); 
        
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = 'cart.php';
        form.style.display = 'none';
        
        let fields = {
            'add_to_cart': '1',
            'product_id': pid,
            'product_quantity': '1',
            'product_name': name,
            'product_price': price,
            'product_image': image,
            'sku_no': 'BUMPER-' + pid
        };
        
        for (let key in fields) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }

    function bumperAddToWishlist(e, pid, fallbackUrl) {
        e.preventDefault();
        e.stopPropagation();
        
        if(pid === 0) { 
            window.location.href = 'wishlist.php';
            return; 
        }
        
        let formData = new FormData();
        formData.append('product_id', pid);
        
        fetch('toggle_wishlist.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = 'wishlist.php';
            } else {
                window.location.href = 'log.php'; 
            }
        })
        .catch(err => alert('Error updating wishlist'));
    }

    function bumperQuickView(e, pid, fallbackUrl) {
        e.preventDefault();
        e.stopPropagation();
        
        if(fallbackUrl) { 
            window.location.href = fallbackUrl; 
            return; 
        }
        window.location.href = 'drt.php?product_id=' + pid + '&source=products';
    }
    </script>
  </section>


  <!-- MODERN BEST OFFERS SECTION (Takealot / Gujju eMarket Inspired) -->
  <style>
    .flash-deals-wrapper {
      max-width: 1400px;
      margin: 40px auto 60px;
      padding: 0 15px;
    }

    .flash-deals-container {
      display: flex;
      flex-direction: column;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      border: 1px solid #f0f0f0;
    }

    @media(min-width: 992px) {
      .flash-deals-container {
        flex-direction: row;
      }
    }

    /* Left Banner */
    .flash-banner {
      flex: 0 0 320px;
      background: #111;
      color: #fff;
      padding: 35px 25px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
    }

    .flash-banner-bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      opacity: 0.4;
      z-index: 1;
      transition: transform 0.5s ease;
    }

    .flash-banner:hover .flash-banner-bg {
      transform: scale(1.05);
    }

    .flash-banner-content {
      position: relative;
      z-index: 2;
    }

    .flash-badge {
      display: inline-block;
      background: #ff4757;
      color: #fff;
      font-size: 0.8rem;
      font-weight: 800;
      padding: 5px 12px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 15px;
      animation: pulseBadge 2s infinite;
    }

    @keyframes pulseBadge {
      0% {
        box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.4);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(255, 71, 87, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(255, 71, 87, 0);
      }
    }

    .flash-title {
      font-size: 2.2rem;
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 15px;
      color: #fff;
    }

    .flash-desc {
      font-size: 1rem;
      color: #ccc;
      margin-bottom: 25px;
      line-height: 1.5;
    }

    /* Countdown */
    .flash-timer {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
    }

    .timer-box {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(5px);
      padding: 10px 0;
      width: 60px;
      border-radius: 8px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .timer-num {
      display: block;
      font-size: 1.4rem;
      font-weight: 800;
      color: #d4af37;
    }

    .timer-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* Promo Code */
    .promo-card {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
      color: #111;
      margin-bottom: 15px;
      border-left: 5px solid #d4af37;
    }

    .promo-card p {
      margin: 0 0 5px;
      font-size: 0.85rem;
      font-weight: 700;
      color: #555;
      text-transform: uppercase;
    }

    .promo-card h4 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 900;
      letter-spacing: 2px;
      color: #000;
    }

    .btn-flash-shop {
      display: block;
      text-align: center;
      background: #d4af37;
      color: #111 !important;
      font-weight: 800;
      padding: 14px 20px;
      border-radius: 8px;
      text-decoration: none;
      text-transform: uppercase;
      transition: all 0.3s;
      margin-top: 10px;
    }

    .btn-flash-shop:hover {
      background: #fff;
      transform: translateY(-3px);
    }

    /* Right Slider */
    .flash-products {
      flex: 1;
      padding: 30px;
      background: #f8f9fa;
      min-width: 0;
      /* Important for Swiper inside flexbox */
    }

    .flash-swiper {
      padding-bottom: 25px;
    }

    .f-card {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      height: 100%;
      display: flex;
      flex-direction: column;
      border: 1px solid #eee;
      transition: all 0.3s ease;
      position: relative;
    }

    .f-card:hover {
      border-color: #d4af37;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
      transform: translateY(-5px);
    }

    .f-badge {
      position: absolute;
      top: 12px;
      left: 12px;
      background: #ff4757;
      color: #fff;
      font-size: 0.75rem;
      font-weight: 800;
      padding: 4px 8px;
      border-radius: 4px;
      z-index: 2;
    }

    .f-img-wrap {
      height: 180px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      position: relative;
    }

    .f-img-wrap img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .f-brand {
      font-size: 0.75rem;
      color: #888;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 5px;
    }

    .f-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: #222;
      line-height: 1.4;
      margin-bottom: 10px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      flex-grow: 1;
    }

    .f-price-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 15px;
    }

    .f-price-new {
      font-size: 1.25rem;
      font-weight: 800;
      color: #111;
    }

    .f-price-old {
      font-size: 0.85rem;
      color: #999;
      text-decoration: line-through;
      font-weight: 500;
    }

    .f-add-btn {
      width: 100%;
      padding: 10px;
      background: #111;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      cursor: pointer;
      transition: 0.3s;
    }

    .f-card:hover .f-add-btn {
      background: #d4af37;
      color: #111;
    }
  </style>



  <?php include 'includes/footer.php'; ?>
  <script>
    function toggleWishlist(productId, btn) {
      const icon = btn.querySelector('i');
      const isActive = btn.classList.contains('active');

      $.ajax({
        url: 'toggle_wishlist.php',
        method: 'POST',
        data: {
          product_id: productId
        },
        success: function(res) {
          if (res.success) {
            // Update Navbar Count
            const countEl = document.getElementById('wishlist-count');
            if (countEl && res.count !== undefined) {
              countEl.textContent = res.count;
            }

            if (res.action === 'added') {
              btn.classList.add('active');
              icon.classList.replace('far', 'fas');
              Swal.fire({
                icon: 'success',
                title: res.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              });
            } else {
              btn.classList.remove('active');
              icon.classList.replace('fas', 'far');
              Swal.fire({
                icon: 'info',
                title: res.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              });
            }
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Oops...',
              text: res.message
            });
          }
        },
        error: function(xhr, status, error) {
          console.error("Wishlist AJAX Error:", status, error, xhr.responseText);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong (Status: ' + status + '). Please try again.'
          });
        }
      });
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Collections Swiper
      new Swiper('#collSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        freeMode: true,
        grabCursor: true,
        navigation: {
          nextEl: '#collSwiper .swiper-button-next',
          prevEl: '#collSwiper .swiper-button-prev'
        },
        scrollbar: {
          el: '#collSwiper .swiper-scrollbar',
          hide: false,
          draggable: true
        },
        breakpoints: {
          480: {
            spaceBetween: 12
          },
          768: {
            spaceBetween: 18
          },
          1024: {
            spaceBetween: 20
          }
        }
      });

      // Note: bestProductSwiper replaced by dynamic Takealot swipers
      // --- ENABLE AUTO-SCROLLING FOR HERO CAROUSEL (Mobile & Desktop) ---
      // 1. Configure and Cycle Bootstrap Hero Carousel
      var heroCarouselEl = document.getElementById('heroCarousel');
      if (heroCarouselEl) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
          // Dispose any existing instance to ensure custom parameters apply fresh
          var existingInstance = bootstrap.Carousel.getInstance(heroCarouselEl);
          if (existingInstance) {
            existingInstance.dispose();
          }

          var carousel = new bootstrap.Carousel(heroCarouselEl, {
            interval: 3000,
            ride: 'carousel',
            wrap: true,
            pause: 'hover',
            touch: true
          });
          carousel.cycle();

          // Safe resume auto-scrolling function after interactions
          var resumeTimer = null;

          function resumeCarousel() {
            if (resumeTimer) clearTimeout(resumeTimer);
            resumeTimer = setTimeout(function() {
              if (carousel) {
                carousel.cycle();
              }
            }, 1000); // Resume 1 second after user interaction ends
          }

          // Resume cycling when mouse leaves carousel (desktop)
          heroCarouselEl.addEventListener('mouseleave', resumeCarousel);

          // Resume cycling when touch interaction ends (mobile / swipe)
          heroCarouselEl.addEventListener('touchend', resumeCarousel);
          heroCarouselEl.addEventListener('touchcancel', resumeCarousel);

          // Resume auto-scroll after clicking next/prev buttons or dots
          var controls = heroCarouselEl.querySelectorAll('.carousel-control-prev, .carousel-control-next, .carousel-indicators button');
          controls.forEach(function(control) {
            control.addEventListener('click', function() {
              if (resumeTimer) clearTimeout(resumeTimer);
              resumeTimer = setTimeout(function() {
                if (carousel) {
                  carousel.cycle();
                }
              }, 2500); // Wait 2.5 seconds after click, then auto-scroll again
            });
          });

          // Ensure cycling resumes when tab gains focus
          window.addEventListener('focus', function() {
            if (carousel) {
              carousel.cycle();
            }
          });
        }
      }

      // 2. Double check all Swipers on the page
      setTimeout(() => {
        document.querySelectorAll('.swiper').forEach(el => {
          if (el.swiper) {
            if (el.swiper.autoplay) el.swiper.autoplay.stop();
            el.swiper.params.autoplay.enabled = false;
            el.swiper.update();
          }
        });
      }, 300);
    });
  </script>
</body>

</html>