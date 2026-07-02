<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once "./shop_admin/config/dbconnect.php";
/** @var mysqli $conn */

/* --- CART COUNT --- */
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
  $uid = (int) $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM cart WHERE user_id=?");
  $stmt->bind_param("i", $uid);
  $stmt->execute();
  $res = $stmt->get_result();
  $cart_count = ($res && ($r = $res->fetch_assoc())) ? (int)$r['total'] : 0;
  $stmt->close();
} elseif (!empty($_SESSION['cart'])) {
  $cart_count = count($_SESSION['cart']);
}

function e($v = "")
{
  return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$subcategory = isset($_GET['subcategory']) ? trim($_GET['subcategory']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop by Category | Premium Fashion Collection — Ishahiya</title>
  <!-- Standard Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="image/logo/ishahiya-logo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="image/logo/ishahiya-logo.png">
  <link rel="apple-touch-icon" sizes="180x180" href="image/logo/ishahiya-logo.png">
  <meta name="description" content="Explore IshahiyaOne's curated category collections — ethnic wear, dresses, tops, festive outfits and more. Find your perfect style with easy size selection and fast delivery.">
  <meta name="robots" content="index, follow">
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/banner.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    /* ====== ENTERPRISE HIGH-DENSITY 2-COL LAYOUT ====== */
    .marketplace-container {
      display: flex;
      max-width: 1480px;
      margin: 15px auto 50px;
      padding: 0 20px;
      gap: 18px;
      align-items: flex-start;
    }

    .marketplace-sidebar {
      width: 235px;
      flex-shrink: 0;
      background: #161616;
      border: 1px solid #2a2a2a;
      border-radius: 8px;
      padding: 15px;
      position: sticky;
      top: 15px;
      color: #fff;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }

    .sidebar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #2a2a2a;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    .sidebar-header h4 {
      margin: 0;
      font-size: 13.5px;
      color: #d4af37;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .reset-link {
      font-size: 11px;
      color: #ff6b6b;
      text-decoration: none;
      font-weight: 700;
      transition: opacity 0.2s;
    }

    .reset-link:hover {
      opacity: 0.8;
    }

    .filter-section {
      margin-bottom: 15px;
      border-bottom: 1px solid #222;
      padding-bottom: 12px;
    }

    .filter-title {
      font-size: 11px;
      font-weight: 800;
      color: #ddd;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .filter-check {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #bbb;
      margin: 6px 0;
      cursor: pointer;
      user-select: none;
    }

    .filter-check input {
      accent-color: #d4af37;
      cursor: pointer;
      width: 14px;
      height: 14px;
    }

    .filter-check span {
      color: #666;
      font-size: 10.5px;
      margin-left: auto;
    }

    .price-labels {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: #d4af37;
      font-weight: 700;
      margin-top: 6px;
    }

    #sidebarPriceSlider {
      width: 100%;
      accent-color: #d4af37;
      cursor: pointer;
    }

    .marketplace-content {
      flex: 1;
      min-width: 0;
    }

    .merchandising-toolbar {
      background: #161616;
      border: 1px solid #2a2a2a;
      border-radius: 8px;
      padding: 10px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      color: #ccc;
      flex-wrap: wrap;
      gap: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .breadcrumbs {
      font-size: 12px;
      color: #888;
    }

    .breadcrumbs a {
      color: #bbb;
      text-decoration: none;
      transition: color 0.2s;
    }

    .breadcrumbs a:hover {
      color: #d4af37;
    }

    .breadcrumbs span {
      color: #d4af37;
      font-weight: 700;
    }

    .toolbar-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .item-count {
      font-size: 12px;
      font-weight: 600;
      color: #eee;
    }

    #toolbarSortSelect {
      background: #222;
      color: #fff;
      border: 1px solid #444;
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 12px;
      outline: none;
      cursor: pointer;
      font-weight: 600;
    }

    /* ====== COMPACT GRID LAYOUT ====== */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(195px, 1fr));
      gap: 14px;
    }

    /* ====== HIGH-DENSITY PRODUCT CARDS ====== */
    .product-card {
      background: #181818;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
      transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
      border: 1px solid #282828;
      display: flex;
      flex-direction: column;
      height: 100%;
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 20px rgba(212, 175, 55, 0.18);
      border-color: #d4af37;
    }

    .product-img {
      position: relative;
      aspect-ratio: 1/1;
      overflow: hidden;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      border-bottom: 1px solid #262626;
    }

    .product-img img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      object-position: center;
      padding: 10px;
      transition: transform 0.4s ease;
    }

    .product-card:hover .product-img img {
      transform: scale(1.05);
    }

    .discount-pill {
      position: absolute;
      top: 6px;
      left: 6px;
      background: #e74c3c;
      color: #fff;
      font-size: 9px;
      font-weight: 800;
      padding: 2px 5px;
      border-radius: 3px;
      z-index: 2;
      letter-spacing: 0.5px;
    }

    .wishlist-heart-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(0, 0, 0, 0.1);
      color: #222;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 5;
      transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      outline: none;
      font-size: 12px;
    }

    .wishlist-heart-btn:hover {
      background: #fff;
      color: #ff3f6c;
      transform: scale(1.15);
      box-shadow: 0 4px 12px rgba(255, 63, 108, 0.35);
    }

    .card-actions-row {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: auto;
      width: 100%;
    }

    .action-btn-cart {
      flex: 1 1 85px;
      min-width: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      padding: 6px 3px;
      background: #0d0d0d;
      color: #d4af37 !important;
      border: 1px solid #d4af37;
      border-radius: 4px;
      text-decoration: none !important;
      font-size: 9.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.2px;
      transition: all 0.25s ease;
      cursor: pointer;
      white-space: nowrap;
      box-sizing: border-box;
    }

    .action-btn-cart:hover {
      background: #d4af37;
      color: #000 !important;
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
    }

    .action-btn-qv {
      flex: 1 1 75px;
      min-width: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 3px;
      padding: 6px 3px;
      background: #1a1a1a;
      color: #fff !important;
      border: 1px solid #444;
      border-radius: 4px;
      text-decoration: none !important;
      font-size: 9.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.2px;
      transition: all 0.25s ease;
      cursor: pointer;
      box-sizing: border-box;
    }

    .qv-mob { display: none; }

    .action-btn-qv:hover {
      background: #fff;
      color: #000 !important;
      border-color: #fff;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }

    .product-info {
      padding: 6px 8px 8px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      text-align: left;
    }

    .brand-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 4px;
    }

    .brand {
      font-size: 10px;
      color: #d4af37;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      font-weight: 700;
    }

    .rating-badge {
      background: #16382a;
      color: #2ecc71;
      font-size: 9.5px;
      font-weight: 800;
      padding: 1px 4px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      gap: 2px;
      border: 1px solid #27ae60;
    }

    .name {
      font-size: 12px;
      color: #f0f0f0;
      font-weight: 600;
      margin: 0 0 6px;
      line-height: 1.35;
      height: 32px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .price-row {
      display: flex;
      align-items: baseline;
      gap: 5px;
      margin-bottom: 6px;
      flex-wrap: wrap;
    }

    .current-price {
      font-size: 16px;
      font-weight: 800;
      color: #fff;
    }

    .old-price {
      font-size: 11px;
      color: #777;
    }

    .save-tag {
      font-size: 11px;
      color: #ff3f6c;
      font-weight: 800;
      letter-spacing: 0.3px;
    }

    .delivery-tag {
      font-size: 10px;
      color: #aaa;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .delivery-tag i {
      color: #f39c12;
      font-size: 10px;
    }

    .view-btn {
      display: block;
      width: 100%;
      text-align: center;
      padding: 7px;
      background: #0d0d0d;
      color: #d4af37 !important;
      border: 1px solid #d4af37;
      border-radius: 4px;
      text-decoration: none !important;
      font-size: 10.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      transition: all 0.25s ease;
      margin-top: auto;
    }

    .view-btn:hover {
      background: #d4af37;
      color: #000 !important;
      box-shadow: 0 0 12px rgba(212, 175, 55, 0.45);
    }

    @media(max-width:992px) {
      .marketplace-container {
        flex-direction: column;
        margin-top: 30px;
      }

      .marketplace-sidebar {
        width: 100%;
        position: relative;
        top: 0;
      }
    }

    @media(max-width:576px) {
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
      }

      .product-info {
        padding: 5px 6px;
      }

      .brand-row {
        margin-bottom: 2px;
      }

      .brand {
        font-size: 9px;
      }

      .rating-badge {
        font-size: 8.5px;
        padding: 1px 3px;
      }

      .current-price {
        font-size: 13.5px;
      }

      .name {
        font-size: 11px;
        height: 28px;
        margin-bottom: 3px;
        line-height: 1.25;
      }

      .delivery-tag {
        font-size: 9px;
        margin-bottom: 6px;
      }

      .view-btn {
        padding: 6px;
        font-size: 9.5px;
      }

      .card-actions-row {
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 4px;
        margin-top: auto;
      }

      .action-btn-cart {
        flex: 1.3;
        width: auto;
        padding: 6px 3px;
        font-size: 8.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .action-btn-qv {
        flex: 1;
        width: auto;
        padding: 6px 3px;
        font-size: 8.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .qv-desk { display: none; }
      .qv-mob { display: inline; }
    }
  </style>
</head>

<body>
  <!-- Header & Navigation -->
  <?php include 'includes/header_nav.php'; ?>
  <?php include 'includes/category_nav.php'; ?>

  <!-- PRODUCT SECTION -->
  <?php

  // ✅ Step 1: If only subcategory name is given, get its category_id
  if (!empty($subcategory) && $category_id == 0) {
    $sub_clean = strtolower(preg_replace('/[^a-z0-9]/i', '', $subcategory));
    $found = false;

    // Search in sub_category table first
    $sub_stmt = $conn->prepare("SELECT id FROM sub_category WHERE REPLACE(LOWER(slug), '-', '') = ? OR REPLACE(LOWER(sub_category_name), ' ', '') = ? LIMIT 1");
    if ($sub_stmt) {
      $sub_stmt->bind_param("ss", $sub_clean, $sub_clean);
      $sub_stmt->execute();
      $sub_res = $sub_stmt->get_result();
      if ($sub_res && $row = $sub_res->fetch_assoc()) {
        $category_id = (int)$row['id'];
        $found = true;
      }
      $sub_stmt->close();
    }

    if (!$found) {
      $sql = "SELECT id AS sub_id, category_id, name FROM subcategories";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $name_clean = strtolower(preg_replace('/[^a-z0-9]/i', '', $row['name']));
          if ($name_clean === $sub_clean) {
            $category_id = (int)$row['category_id'];
            $found = true;
            break;
          }
        }
      }
    }

    if (!$found) {
      echo "<p style='text-align:center;color:red;font-weight:bold;margin-top:40px;'>Invalid subcategory selected.</p>";
      exit;
    }
  }

  // ✅ Step 2: Now we must have either category_id > 0 or valid subcategory
  if ($category_id > 0) {
    // Add dynamic filter based on collection keyword
    $filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
    $filter_sql = "";
    $safe_filter = "";
    if ($filter !== '') {
      $safe_filter = $conn->real_escape_string($filter);
      $filter_sql = " AND sc.name LIKE '%$safe_filter%'";
    }

    // Smart subcategory ID expansion for main category selection
    $cat_ids = [$category_id];
    $subChk = $conn->query("SELECT id FROM sub_category WHERE main_category_id = $category_id");
    if ($subChk) {
      while ($rsub = $subChk->fetch_assoc()) {
        $cat_ids[] = (int)$rsub['id'];
      }
    }
    $cat_in = implode(',', array_unique(array_filter($cat_ids)));

    $matched_source = 'subcategories';
    $sql = "SELECT sc.*, sub.main_category_id FROM subcategories sc 
            LEFT JOIN sub_category sub ON sc.category_id = sub.id 
            WHERE (sc.category_id IN ($cat_in) 
               OR sub.main_category_id IN ($cat_in)) 
               $filter_sql
            ORDER BY sc.id DESC";
    $result = $conn->query($sql);

    // If not found, fallback to subshop table
    if ($result && $result->num_rows == 0) {
      $matched_source = 'subshop';
      $sql = "SELECT * FROM subshop WHERE category_id IN ($cat_in)";
      if ($filter !== '') {
        $sql .= " AND name LIKE '%$safe_filter%'";
      }
      $sql .= " ORDER BY id DESC";
      $result = $conn->query($sql);
    }

    // If still not found, fallback to all_category table
    if ($result && $result->num_rows == 0) {
      $matched_source = 'all_category';
      $sql = "SELECT * FROM all_category WHERE sub_category_id IN ($cat_in) OR main_category_id IN ($cat_in)";
      if ($filter !== '') {
        $sql .= " AND name LIKE '%$safe_filter%'";
      }
      $sql .= " ORDER BY id DESC";
      $result = $conn->query($sql);
    }

    // If STILL not found, fallback to products table
    if ($result && $result->num_rows == 0) {
      $matched_source = 'products';
      $sql = "SELECT * FROM products WHERE sub_category_id IN ($cat_in) OR category_id IN ($cat_in)";
      if ($filter !== '') {
        $sql .= " AND name LIKE '%$safe_filter%'";
      }
      $sql .= " ORDER BY product_id DESC";
      $result = $conn->query($sql);
    }

    // Buffer products for dynamic sidebar brands, ratings, and grid rendering
    $products_buffer = [];
    $brand_counts = [];
    $rating_counts = ['4' => 0, '3' => 0];
    $avail_counts = ['in_stock' => 0, 'express' => 0];
    if ($result && $result->num_rows > 0) {
      $p_idx = 0;
      while ($r = $result->fetch_assoc()) {
        $p_idx++;
        $r['source_table'] = $matched_source;
        $db_rating = isset($r['rating']) && (float)$r['rating'] > 0 ? (float)$r['rating'] : 0;
        if ($db_rating > 0) {
          $r_val = $db_rating;
          $r_bucket = ($r_val >= 4.0) ? "4" : "3";
        } else {
          $r_bucket = ($p_idx % 2 != 0) ? "4" : "3";
          $r_val = ($r_bucket == "4") ? 4.5 : 3.8;
        }
        $a_bucket = ($p_idx % 2 != 0) ? "in_stock" : "express";

        $r['calc_rating'] = $r_val;
        $r['rating_bucket'] = $r_bucket;
        $r['avail_bucket'] = $a_bucket;

        $products_buffer[] = $r;
        $bn = trim($r['brand'] ?? '');
        if (empty($bn)) $bn = 'IshaHiya';
        if (!isset($brand_counts[$bn])) $brand_counts[$bn] = 0;
        $brand_counts[$bn]++;

        $rating_counts[$r_bucket]++;
        $avail_counts[$a_bucket]++;
      }
    }

    $max_catalog_price = 1000;
    if (!empty($products_buffer)) {
      foreach ($products_buffer as $pb) {
        $pr = (float)($pb['price'] ?? 0);
        if ($pr > $max_catalog_price) $max_catalog_price = $pr;
      }
    }
    $max_catalog_price = ceil($max_catalog_price / 1000) * 1000;

    $sub_display_name = htmlspecialchars($subcategory ?: 'Collection');

    // ✅ Step 3: Display Marketplace 2-Column Structure
    echo '<div class="marketplace-container">';

    // Left Sticky Filters Sidebar
    echo '
    <aside class="marketplace-sidebar">
        <div class="sidebar-header">
            <h4><i class="fa-solid fa-sliders"></i> Filters</h4>
            <a href="subshop1.php?subcategory=' . urlencode($subcategory) . '" class="reset-link">Reset All</a>
        </div>

        <div class="filter-section">
            <div class="filter-title">Price Range</div>
            <input type="range" id="sidebarPriceSlider" min="100" max="' . $max_catalog_price . '" step="100" value="' . $max_catalog_price . '" oninput="applyMarketplaceFilters()">
            <div class="price-labels"><span>₹100</span><span id="sidebarPriceValDisplay">₹' . $max_catalog_price . '</span></div>
        </div>

        <div class="filter-section">
            <div class="filter-title">Brand</div>';
    if (empty($brand_counts)) {
      echo '<label class="filter-check"><input type="checkbox" class="brand-filter-cb" value="ishahiya" checked onchange="applyMarketplaceFilters()"> IshaHiya <span>(0)</span></label>';
    } else {
      foreach ($brand_counts as $bName => $bCnt) {
        $bSafe = htmlspecialchars($bName);
        $bVal = htmlspecialchars(strtolower(trim($bName)));
        echo '<label class="filter-check"><input type="checkbox" class="brand-filter-cb" value="' . $bVal . '" checked onchange="applyMarketplaceFilters()"> ' . $bSafe . ' <span>(' . $bCnt . ')</span></label>';
      }
    }
    echo '
        </div>

        <div class="filter-section">
            <div class="filter-title">Customer Rating</div>
            <label class="filter-check"><input type="checkbox" class="rating-filter-cb" value="4" checked onchange="applyMarketplaceFilters()"> 4★ &amp; above <span>(' . ($rating_counts['4'] ?? 0) . ')</span></label>
            <label class="filter-check"><input type="checkbox" class="rating-filter-cb" value="3" checked onchange="applyMarketplaceFilters()"> 3★ &amp; above <span>(' . ($rating_counts['3'] ?? 0) . ')</span></label>
        </div>

        <div class="filter-section" style="border:none;margin:0;padding:0;">
            <div class="filter-title">Availability</div>
            <label class="filter-check"><input type="checkbox" class="avail-filter-cb" value="in_stock" checked onchange="applyMarketplaceFilters()"> In Stock <span>(' . ($avail_counts['in_stock'] ?? 0) . ')</span></label>
            <label class="filter-check"><input type="checkbox" class="avail-filter-cb" value="express" checked onchange="applyMarketplaceFilters()"> Express Delivery <span>(' . ($avail_counts['express'] ?? 0) . ')</span></label>
        </div>
    </aside>';

    // Right Merchandising Area
    echo '<main class="marketplace-content">';

    // Top Merchandising Toolbar
    echo '
    <div class="merchandising-toolbar">
        <div class="breadcrumbs"><a href="index.php">Home</a> &nbsp;/&nbsp; <a href="shop.php">Shop</a> &nbsp;/&nbsp; <span>' . $sub_display_name . '</span></div>
        <div class="toolbar-actions">
            <span class="item-count">Showing ' . count($products_buffer) . ' Products</span>
            <select id="toolbarSortSelect" onchange="applyMarketplaceSort()">
                <option value="default">Sort by: Recommended</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </div>
    </div>';

    $user_wishlist_ids = [];
    if (isset($_SESSION['user_id'])) {
      $uid_safe = (int)$_SESSION['user_id'];
      $wQ = $conn->query("SELECT product_id FROM wishlist WHERE user_id = $uid_safe");
      if ($wQ) {
        while ($wR = $wQ->fetch_assoc()) {
          $user_wishlist_ids[] = (int)$wR['product_id'];
        }
      }
    }

    if (!empty($products_buffer)) {
      echo "<div class='products-grid'>";
      foreach ($products_buffer as $row) {
        $product_id = $row['product_id'] ?? $row['id'] ?? $row['sub_id'] ?? $row['subcategory_id'] ?? 0;
        $name = e($row['name'] ?? '');
        $price_raw = (float)($row['price'] ?? 0);
        $disc_pct = !empty($row['bumper_discount']) && $row['bumper_discount'] > 0 ? (int)$row['bumper_discount'] : 0;
        if ($disc_pct > 0 && $disc_pct < 100) {
          $final_price_raw = round($price_raw * (1 - ($disc_pct / 100)), 2);
          $price = number_format($final_price_raw, 2);
          $old_price = number_format($price_raw, 2);
        } else {
          $final_price_raw = $price_raw;
          $price = number_format($price_raw, 2);
          $old_price = "";
        }
        $img = trim($row['Image1'] ?? $row['image1'] ?? $row['image'] ?? '');
        $basename = basename($img);

        if (empty($img)) {
          $imagePath = 'shop_admin/uploads/no-image.png';
        } else {
          $pathMain = __DIR__ . '/shop_admin/uploads/' . $basename;
          $pathSub = __DIR__ . '/shop_admin/uploads/subshop/' . $basename;
          if (file_exists($pathSub)) {
            $imagePath = 'shop_admin/uploads/subshop/' . $basename;
          } elseif (file_exists($pathMain)) {
            $imagePath = 'shop_admin/uploads/' . $basename;
          } else {
            $imagePath = (strpos($img, 'shop_admin/') !== false) ? ltrim($img, '/') : 'shop_admin/uploads/subshop/' . $basename;
          }
        }

        $src = $row['source_table'] ?? 'all_category';
        $detail_url = "drt.php?product_id=$product_id&source=$src&hide_timer=1";

        $isWished = in_array((int)$product_id, $user_wishlist_ids);
        $heartClass = $isWished ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
        $heartStyle = $isWished ? 'color: #ff3f6c;' : '';
        $btnClass = $isWished ? 'wishlist-heart-btn active' : 'wishlist-heart-btn';

        $card_brand = e(strtolower(trim($row['brand'] ?? 'IshaHiya')));
        $card_rating_bucket = $row['rating_bucket'] ?? "4";
        $card_calc_rating = number_format((float)($row['calc_rating'] ?? 4.5), 1);
        $card_avail_bucket = $row['avail_bucket'] ?? "in_stock";
        echo "<div class='product-card' data-brand='$card_brand' data-price='$price_raw' data-rating='$card_rating_bucket' data-avail='$card_avail_bucket'>";
        echo "  <div class='product-img'>";
        echo "    <button class='$btnClass' type='button' onclick='toggleWishlistAjax(this, $product_id)' title='Add to Wishlist'><i class='$heartClass' style='$heartStyle'></i></button>";
        echo "    <img src='$imagePath' alt='$name' loading='lazy' decoding='async' onerror=\"this.onerror=null;this.src='shop_admin/uploads/no-image.png';\">";
        echo "  </div>";
        echo "  <div class='product-info'>";
        echo "    <div class='brand-row'><span class='brand'>" . e($row['brand'] ?? 'IshaHiya') . "</span><div class='rating-badge'>$card_calc_rating <i class='fa-solid fa-star'></i></div></div>";
        echo "    <h4 class='name' title='$name'>$name</h4>";
        echo "    <div class='price-row'><span class='current-price'>₹$price</span>";
        if ($disc_pct > 0) {
          echo " <del class='old-price'>₹$old_price</del> <span class='save-tag'>({$disc_pct}% OFF)</span>";
        }
        echo "</div>";
        echo "    <div class='delivery-tag'><i class='fa-solid fa-bolt'></i> Fast Delivery available</div>";

        if (!empty($row['main_category_id']) && $row['main_category_id'] == 2) {
          $sizes_result = $conn->query("SELECT * FROM sizes ORDER BY size_name ASC");
        }

        $raw_name = $row['name'] ?? '';
        $name_js = htmlspecialchars(json_encode($raw_name), ENT_QUOTES, "UTF-8");
        $img_js = htmlspecialchars(json_encode($imagePath), ENT_QUOTES, "UTF-8");
        $sku_val = $row['sku'] ?? $row['sku_no'] ?? '';
        $sku_js = htmlspecialchars(json_encode($sku_val), ENT_QUOTES, "UTF-8");

        echo "    <div class='card-actions-row'>";
        echo "      <button type='button' onclick='addToCartCatalog($product_id, $name_js, $final_price_raw, $img_js, $sku_js)' class='action-btn-cart'><i class='fa-solid fa-cart-shopping'></i> Add to Cart</button>";
        echo "      <a href='$detail_url' class='action-btn-qv' title='Quick View'><i class='fa-regular fa-eye'></i> <span class='qv-desk'>Quick View</span><span class='qv-mob'>View</span></a>";
        echo "    </div>";
        echo "  </div>";
        echo "</div>";
      }
      echo "</div>";
    } else {
      echo "<div style='background:#1a1a1a;border:1px solid #333;border-radius:12px;padding:60px 20px;text-align:center;color:#fff;'>";
      echo "  <i class='fa-solid fa-box-open' style='font-size:48px;color:#d4af37;margin-bottom:15px;'></i>";
      echo "  <h3 style='color:#fff;margin-bottom:8px;'>No Products Found</h3>";
      echo "  <p style='color:#888;font-size:14px;max-width:400px;margin:0 auto 20px;'>We couldn't find any products matching your selected category filters. Try clearing some filters.</p>";
      echo "  <a href='shop.php' style='display:inline-block;background:#d4af37;color:#000;font-weight:700;padding:10px 24px;border-radius:6px;text-decoration:none;'>Browse All Products</a>";
      echo "</div>";
    }

    echo '</main>'; // Close content
    echo '</div>'; // Close container
  } else {
    echo "<p style='text-align:center;padding:40px;color:#fff;'>Invalid category selected.</p>";
  }


  $conn->close();
  ?>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const slider = document.getElementById('ajaxPriceSlider');
      const sort = document.getElementById('ajaxSortSelect');
      const display = document.getElementById('priceValDisplay');
      const grid = document.querySelector('.products-grid');
      if (!slider || !grid) return;

      const catId = <?= (int)$category_id ?>;

      const fetchFiltered = () => {
        grid.style.opacity = '0.4';
        fetch(`api/v1/catalog/filter.php?category_id=${catId}&max_price=${slider.value}&sort=${sort.value}`)
          .then(r => r.json())
          .then(data => {
            grid.style.opacity = '1';
            if (data.status === 'success' && data.products && data.products.length > 0) {
              let html = '';
              data.products.forEach(p => {
                html += `
                          <div class='product-card'>
                            <div class='product-img'><img src='${p.image}' alt='${p.name}' onerror="this.src='shop_admin/uploads/no-image.png'"></div>
                            <div class='product-info'>
                              <span class='brand'>${p.brand}</span>
                              <h4 class='name'>${p.name}</h4>
                              <div class='price'>₹${p.price}</div>
                              <a href='${p.url}' class='view-btn'>View Details</a>
                            </div>
                          </div>`;
              });
              grid.innerHTML = html;
            } else {
              grid.innerHTML = "<p style='grid-column:1/-1;text-align:center;color:#ff6b6b;font-weight:bold;padding:40px 0;'>No matching products found for selected filters.</p>";
            }
          }).catch(() => grid.style.opacity = '1');
      };

      slider.addEventListener('input', (e) => {
        display.textContent = '₹' + e.target.value;
      });
      slider.addEventListener('change', fetchFiltered);
      sort.addEventListener('change', fetchFiltered);
    });
  </script>




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

  <script src="vb.js"></script>
  <script>
    function checkStock(productId, size, dropdown) {
      const stockLabel = dropdown.closest("form").querySelector(".stock-status");

      if (!size) {
        stockLabel.textContent = "Please select size";
        stockLabel.style.color = "gray";
        return;
      }

      // AJAX request
      fetch("check_stock.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "prd_id=" + encodeURIComponent(productId) + "&size=" + encodeURIComponent(size)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.Stock > 0) {
              stockLabel.textContent = "Available In Stock";
              stockLabel.style.color = "#2a8105";
              stockLabel.dataset.stock = "1"; // ✅ mark as available
            } else {
              stockLabel.textContent = "Out of Stock";
              stockLabel.style.color = "red";
              stockLabel.dataset.stock = "0"; // ✅ mark as unavailable
            }
          } else {
            stockLabel.textContent = "Error checking stock";
            stockLabel.style.color = "gray";
            stockLabel.dataset.stock = "0";
          }
        })
        .catch(() => {
          stockLabel.textContent = "Error connecting server";
          stockLabel.style.color = "gray";
          stockLabel.dataset.stock = "0";
        });
    }

    // ✅ Prevent add to cart when stock is 0
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", function(e) {
        const stockLabel = form.querySelector(".stock-status");
        if (stockLabel && stockLabel.dataset.stock === "0") {
          e.preventDefault();
          alert("Sorry, this product is out of stock!");
        }
      });
    });

    // ✅ Wishlist Toggle & Redirect Engine (Matches index.php standard)
    function toggleWishlistAjax(btn, productId) {
      if (window.event) window.event.stopPropagation();
      let formData = new FormData();
      formData.append('product_id', productId);

      fetch('toggle_wishlist.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.href = 'wishlist.php';
        } else {
          window.location.href = 'log.php';
        }
      })
      .catch(err => {
        window.location.href = 'wishlist.php';
      });
    }

    // ✅ Quick Add to Cart & Redirect Engine
    function addToCartCatalog(productId, name, price, image, sku) {
      let formData = new FormData();
      formData.append('add_to_cart', '1');
      formData.append('product_id', productId);
      formData.append('product_size', ''); 
      formData.append('product_quantity', '1');
      formData.append('product_image', image);
      formData.append('product_name', name);
      formData.append('product_price', price);
      formData.append('sku_no', sku);

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Adding to Cart...',
          didOpen: () => { Swal.showLoading(); },
          allowOutsideClick: false,
          background: '#111',
          color: '#fff'
        });
      }

      fetch('cart.php', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.text())
      .then(data => {
        window.location.href = 'cart.php';
      })
      .catch(err => {
        console.error(err);
        window.location.href = 'cart.php';
      });
    }

    // ✅ Dynamic Client-Side Filtering (Brand, Price, Rating, Availability)
    function applyMarketplaceFilters() {
      const priceSlider = document.getElementById('sidebarPriceSlider');
      const maxPrice = priceSlider ? parseFloat(priceSlider.value) : 200000;
      const priceDisplay = document.getElementById('sidebarPriceValDisplay');
      if (priceDisplay && priceSlider) {
        priceDisplay.textContent = '₹' + priceSlider.value;
      }

      const brandCbs = document.querySelectorAll('.brand-filter-cb');
      const checkedBrands = [];
      let totalBrands = 0;
      brandCbs.forEach(cb => {
        totalBrands++;
        if (cb.checked) {
          checkedBrands.push(cb.value.toLowerCase());
        }
      });
      const showAllBrands = (checkedBrands.length === 0 || checkedBrands.length === totalBrands);

      // Customer Rating Filters (Exact same behavior to Brand)
      const ratingAllCbs = document.querySelectorAll('.rating-filter-cb');
      const checkedRatings = [];
      let totalRatings = 0;
      ratingAllCbs.forEach(cb => {
        totalRatings++;
        if (cb.checked) checkedRatings.push(cb.value);
      });
      const showAllRatings = (checkedRatings.length === 0 || checkedRatings.length === totalRatings);

      // Availability Filters (Exact same behavior to Brand)
      const availAllCbs = document.querySelectorAll('.avail-filter-cb');
      const checkedAvails = [];
      let totalAvails = 0;
      availAllCbs.forEach(cb => {
        totalAvails++;
        if (cb.checked) checkedAvails.push(cb.value);
      });
      const showAllAvail = (checkedAvails.length === 0 || checkedAvails.length === totalAvails);

      const cards = document.querySelectorAll('.product-card');
      let visibleCount = 0;
      cards.forEach(card => {
        const cardBrand = (card.dataset.brand || '').toLowerCase();
        const cardPrice = parseFloat(card.dataset.price || 0);
        const cardRating = card.dataset.rating || "4";
        const cardAvail = card.dataset.avail || "in_stock";

        const brandMatch = showAllBrands || checkedBrands.includes(cardBrand);
        const priceMatch = cardPrice <= maxPrice;
        const ratingMatch = showAllRatings || checkedRatings.includes(cardRating);
        const availMatch = showAllAvail || checkedAvails.includes(cardAvail);

        if (brandMatch && priceMatch && ratingMatch && availMatch) {
          card.style.display = '';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      const countEl = document.querySelector('.item-count');
      if (countEl) {
        countEl.textContent = 'Showing ' + visibleCount + ' Products';
      }
    }

    // ✅ Dynamic Client-Side Sorting
    function applyMarketplaceSort() {
      const sortVal = document.getElementById('toolbarSortSelect').value;
      const grid = document.querySelector('.products-grid');
      if (!grid) return;

      const cards = Array.from(grid.querySelectorAll('.product-card'));
      cards.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price || 0);
        const priceB = parseFloat(b.dataset.price || 0);
        if (sortVal === 'price_asc') return priceA - priceB;
        if (sortVal === 'price_desc') return priceB - priceA;
        return 0;
      });

      cards.forEach(card => grid.appendChild(card));
    }
  </script>
</body>

</html>