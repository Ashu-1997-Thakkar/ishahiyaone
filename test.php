<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host   = "localhost";
$user   = "ishahiyaone";
$pass   = "BhaV@1437I";
$dbname = "ishahiyaone";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $db  = new PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ---- CART COUNT ----
    $cart_count = 0;
    if (isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id=?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cart_count = $row && $row['total_quantity'] ? (int)$row['total_quantity'] : 0;
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += (int)$item['quantity'];
        }
    }

    // ---- FETCH CATEGORIES ----
    $categories = [];
    try {
        $categories = [
            ["id"=>1,"name"=>"Electronics","icon"=>"fas fa-mobile-alt","children"=>[
                ["id"=>11,"name"=>"Mobile Phones & Tablets"],
                ["id"=>12,"name"=>"Laptops & Computers"],
                ["id"=>13,"name"=>"TV, Audio & Gaming"],
                ["id"=>14,"name"=>"Cameras & Drones"],
                ["id"=>15,"name"=>"Smart Watches & Fitness"]
            ]],
            ["id"=>2,"name"=>"Fashion","icon"=>"fas fa-tshirt","children"=>[
                ["id"=>21,"name"=>"Men's Clothing"],
                ["id"=>22,"name"=>"Women's Clothing"],
                ["id"=>23,"name"=>"Kids' Clothing"],
                ["id"=>24,"name"=>"Shoes"],
                ["id"=>25,"name"=>"Bags & Accessories"]
            ]],
            ["id"=>3,"name"=>"Home & Garden","icon"=>"fas fa-home","children"=>[
                ["id"=>31,"name"=>"Furniture"],
                ["id"=>32,"name"=>"Kitchen & Dining"],
                ["id"=>33,"name"=>"Bedding & Bath"],
                ["id"=>34,"name"=>"Garden & Pool"],
                ["id"=>35,"name"=>"Home Improvement"]
            ]],
            ["id"=>4,"name"=>"Beauty","icon"=>"fas fa-palette","children"=>[
                ["id"=>41,"name"=>"Skincare"],
                ["id"=>42,"name"=>"Makeup"],
                ["id"=>43,"name"=>"Fragrance"],
                ["id"=>44,"name"=>"Hair Care"]
            ]],
            ["id"=>5,"name"=>"Sports & Outdoor","icon"=>"fas fa-football-ball","children"=>[
                ["id"=>51,"name"=>"Gym & Fitness"],
                ["id"=>52,"name"=>"Outdoor & Camping"],
                ["id"=>53,"name"=>"Sports Equipment"],
                ["id"=>54,"name"=>"Cycling"]
            ]],
            ["id"=>6,"name"=>"Baby & Kids","icon"=>"fas fa-baby","children"=>[
                ["id"=>61,"name"=>"Baby Care"],
                ["id"=>62,"name"=>"Toys & Games"],
                ["id"=>63,"name"=>"Kids Fashion"],
                ["id"=>64,"name"=>"School & Office"]
            ]],
            ["id"=>7,"name"=>"Automotive","icon"=>"fas fa-car","children"=>[
                ["id"=>71,"name"=>"Car Parts"],
                ["id"=>72,"name"=>"Car Care"],
                ["id"=>73,"name"=>"Motorcycle"],
                ["id"=>74,"name"=>"GPS & Electronics"]
            ]],
            ["id"=>8,"name"=>"Books & Media","icon"=>"fas fa-book","children"=>[
                ["id"=>81,"name"=>"Books"],
                ["id"=>82,"name"=>"eBooks"],
                ["id"=>83,"name"=>"Movies & TV"],
                ["id"=>84,"name"=>"Music"]
            ]]
        ];
    } catch (Exception $e) {
        $categories = [["id"=>1,"name"=>"Electronics","icon"=>"fas fa-mobile-alt","children"=>[]]];
    }

    // ---- FETCH PRODUCTS ----
    $products = [];
    try {
        $stmt = $db->query("SELECT id, name, price, original_price, img, rating, reviews FROM products ORDER BY id DESC LIMIT 20");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $products = [
            ["id"=>1,"name"=>"Samsung Galaxy S24 Ultra 256GB","price"=>21999,"original_price"=>25999,"img"=>"https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=300&fit=crop","rating"=>4.5,"reviews"=>1234],
            ["id"=>2,"name"=>"Apple AirPods Pro (2nd Gen)","price"=>4299,"original_price"=>5499,"img"=>"https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=300&h=300&fit=crop","rating"=>4.8,"reviews"=>856],
            ["id"=>3,"name"=>"Dell XPS 13 Laptop i7 16GB RAM","price"=>24999,"original_price"=>29999,"img"=>"https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=300&fit=crop","rating"=>4.3,"reviews"=>432],
            ["id"=>4,"name"=>"Nike Air Force 1 '07 White","price"=>1899,"original_price"=>2299,"img"=>"https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=300&fit=crop","rating"=>4.6,"reviews"=>2341],
            ["id"=>5,"name"=>"Sony WH-1000XM5 Headphones","price"=>5999,"original_price"=>7499,"img"=>"https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop","rating"=>4.7,"reviews"=>978],
            ["id"=>6,"name"=>"iPhone 15 Pro Max 256GB","price"=>24999,"original_price"=>27999,"img"=>"https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=300&h=300&fit=crop","rating"=>4.9,"reviews"=>543],
            ["id"=>7,"name"=>"Adidas Ultraboost 22 Running","price"=>2199,"original_price"=>2799,"img"=>"https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop","rating"=>4.4,"reviews"=>756],
            ["id"=>8,"name"=>"Canon EOS R6 Mark II Camera","price"=>42999,"original_price"=>47999,"img"=>"https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=300&h=300&fit=crop","rating"=>4.8,"reviews"=>123],
        ];
    }

    // ---- DAILY DEALS ----
    $daily_deals = [
        ["id"=>101,"name"=>"Wireless Earbuds Pro","price"=>399,"original_price"=>899,"discount"=>56,"time_left"=>"2h 15m","img"=>"https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=250&h=200&fit=crop"],
        ["id"=>102,"name"=>"Smart Fitness Watch","price"=>899,"original_price"=>1499,"discount"=>40,"time_left"=>"5h 32m","img"=>"https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=250&h=200&fit=crop"],
        ["id"=>103,"name"=>"Bluetooth Speaker","price"=>299,"original_price"=>599,"discount"=>50,"time_left"=>"1h 45m","img"=>"https://images.unsplash.com/photo-1589003077984-894e133dabab?w=250&h=200&fit=crop"],
    ];

} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    $cart_count = 0;
    $categories = [["id"=>1,"name"=>"Electronics","icon"=>"fas fa-mobile-alt","children"=>[]]];
    $products   = [];
    $daily_deals = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ishahiya - South Africa's Leading Online Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --takealot-blue: #0a6b9e;
      --takealot-orange: #ff6600;
      --takealot-dark-blue: #003d5c;
      --light-gray: #f8f9fa;
      --border-color: #e9ecef;
      --text-muted: #6c757d;
    }
    
    * { box-sizing: border-box; }
    
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #f8f9fa;
      margin: 0;
      line-height: 1.4;
    }

    /* TOP HEADER */
    .top-header {
      background: var(--takealot-dark-blue);
      color: white;
      padding: 8px 0;
      font-size: 13px;
    }
    .top-header a { 
      color: white; 
      text-decoration: none; 
      margin: 0 10px;
    }
    .top-header a:hover { 
      color: var(--takealot-orange); 
    }

    /* MAIN HEADER */
    .main-header {
      background: white;
      border-bottom: 2px solid var(--takealot-blue);
      position: sticky;
      top: 0;
      z-index: 1000;
      padding: 10px 0;
    }
    
   .logo {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.logo img {
  height: 50px;     /* logo ki height fix */
  width: auto;      /* aspect ratio maintain */
  display: block;
  transition: filter 0.3s ease; /* hover effect ke liye smooth */
}

.logo:hover img {
  filter: brightness(0) saturate(100%) invert(47%) sepia(98%) saturate(2815%) hue-rotate(5deg) brightness(99%) contrast(103%);
  /* yaha tum apne color ka effect adjust kar sakte ho */
}

    .search-input {
      width: 100%;
      padding: 12px 50px 12px 16px;
      border: 2px solid var(--takealot-blue);
      border-radius: 6px;
      font-size: 16px;
      outline: none;
    }
    .search-input:focus {
      border-color: var(--takealot-orange);
      box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
    }
    
    .search-btn {
      position: absolute;
      right: 0;
      top: 0;
      height: 100%;
      background: var(--takealot-blue);
      color: white;
      border: none;
      padding: 0 20px;
      border-radius: 0 6px 6px 0;
      cursor: pointer;
      transition: background 0.3s;
    }
    .search-btn:hover {
      background: var(--takealot-orange);
    }

    .header-icons {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    
    .icon-link {
      position: relative;
      color: var(--takealot-blue);
      font-size: 20px;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-size: 12px;
      transition: color 0.3s;
    }
    .icon-link:hover { color: var(--takealot-orange); }
    .icon-link i { font-size: 22px; margin-bottom: 2px; }
    
    .cart-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--takealot-orange);
      color: white;
      border-radius: 50%;
      padding: 3px 7px;
      font-size: 11px;
      font-weight: bold;
      min-width: 18px;
      text-align: center;
    }

   .category-nav .nav-link {
  color: #333;
  padding: 12px 18px;
  font-weight: 500;
  font-size: 14px;
}
.category-nav .nav-link:hover {
  color: var(--takealot-blue);
  background: #f9f9f9;
}
.dropdown-menu {
  border-radius: 0;
  border: 1px solid #eee;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.dropdown-item {
  font-size: 14px;
}
.dropdown-item:hover {
  background: var(--takealot-blue);
  color: #fff;
}


    /* HERO SECTION */
    .hero-section {
      margin: 20px auto;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .hero-image {
      width: 100%;
      height: 350px;
      object-fit: cover;
    }

    /* DAILY DEALS */
    .deals-section {
      background: linear-gradient(135deg, var(--takealot-blue), var(--takealot-dark-blue));
      color: white;
      padding: 30px 0;
      margin: 30px 0;
    }
    
    .deals-title {
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .deal-card {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: transform 0.3s;
      height: 100%;
    }
    .deal-card:hover {
      transform: translateY(-5px);
    }
    
    .deal-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    
    .deal-info {
      padding: 15px;
      color: #333;
    }
    
    .deal-discount {
      background: var(--takealot-orange);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: bold;
      display: inline-block;
      margin-bottom: 8px;
    }
    
    .deal-timer {
      background: #dc3545;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: bold;
      float: right;
    }

    /* SECTION TITLES */
    .section-title {
      font-size: 28px;
      font-weight: 700;
      color: #333;
      margin: 40px 0 25px;
      position: relative;
      padding-bottom: 10px;
    }
    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: var(--takealot-orange);
      border-radius: 2px;
    }

    /* PRODUCT GRID */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 20px;
    }
    
    .product-card {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: all 0.3s;
      border: 1px solid #f0f0f0;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .product-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-image-container {
      position: relative;
      overflow: hidden;
    }
    
    .product-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .product-card:hover .product-image {
      transform: scale(1.05);
    }
    
    .product-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: var(--takealot-orange);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: bold;
    }
    
    .product-info {
      padding: 15px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    
    .product-title {
      font-size: 15px;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      line-height: 1.3;
      height: 40px;
      overflow: hidden;
    }
    
    .product-rating {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 8px;
    }
    .stars {
      color: #ffc107;
      font-size: 14px;
    }
    .rating-text {
      color: var(--text-muted);
      font-size: 12px;
    }
    
    .product-price {
      margin-bottom: 15px;
    }
    .current-price {
      font-size: 20px;
      font-weight: bold;
      color: var(--takealot-blue);
    }
    .original-price {
      font-size: 14px;
      color: var(--text-muted);
      text-decoration: line-through;
      margin-left: 8px;
    }
    
    .add-to-cart-btn {
      width: 100%;
      background: var(--takealot-blue);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: auto;
    }
    .add-to-cart-btn:hover {
      background: var(--takealot-orange);
      transform: translateY(-1px);
    }

    /* SIDEBAR */
    .sidebar {
      position: sticky;
      top: 120px;
      height: fit-content;
    }
    
    .sidebar-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      margin-bottom: 20px;
    }
    
    .sidebar-title {
      background: var(--takealot-blue);
      color: white;
      padding: 15px;
      font-weight: bold;
      margin: 0;
    }
    
    .category-list {
      padding: 0;
    }
    .category-list .list-group-item {
      border: none;
      border-bottom: 1px solid #f0f0f0;
      padding: 12px 15px;
      transition: background 0.2s;
    }
    .category-list .list-group-item:hover {
      background: #f8f9fa;
    }
    .category-list .list-group-item a {
      color: #333;
      text-decoration: none;
      font-size: 14px;
    }
    .category-list .list-group-item a:hover {
      color: var(--takealot-blue);
    }

    /* FOOTER */
    footer {
      background: var(--takealot-dark-blue);
      color: white;
      padding: 50px 0 20px;
      margin-top: 60px;
    }
    footer h5 {
      color: var(--takealot-orange);
      margin-bottom: 20px;
      font-weight: 600;
    }
    footer a {
      color: #ccc;
      text-decoration: none;
      transition: color 0.3s;
    }
    footer a:hover {
      color: var(--takealot-orange);
    }
    footer .list-unstyled li {
      margin-bottom: 8px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .logo { font-size: 24px; }
      .search-container { margin: 10px 0; }
      .header-icons { gap: 15px; }
      .product-grid { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }
      .section-title { font-size: 24px; }
      .hero-image { height: 250px; }
    }
    
    .loading {
      opacity: 0.7;
      pointer-events: none;
    }
  </style>
</head>
<body>

<!-- Top Header -->
<div class="top-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <span><i class="fas fa-phone"></i> Help Centre: 9974328904 | Begin Your Journey to Exceptional Savings </span>
      </div>
      <div class="col-md-6 text-end">
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="account.php">My Account</a>
        <a href="#"><i class="fas fa-map-marker-alt"></i> Track Orders</a>
      </div>
    </div>
  </div>
</div>

<!-- Main Header -->
<header class="main-header">
  <div class="container">
    <div class="d-flex align-items-center">
     <a href="index.php" class="logo">
  <img src="/image/logo/logo.png" alt="Logo">
</a>

      <div class="search-container">
        <input type="text" class="search-input" placeholder="Search millions of products...">
        <button class="search-btn"><i class="fas fa-search"></i></button>
      </div>
      
      <div class="header-icons">
        <a href="account.php" class="icon-link">
          <i class="fas fa-user"></i>
          <span>Account</span>
        </a>
        <a href="wishlist.php" class="icon-link">
          <i class="fas fa-heart"></i>
          <span>Lists</span>
        </a>
        <a href="cart.php" class="icon-link">
          <i class="fas fa-shopping-cart"></i>
          <span>Cart</span>
          <span class="cart-count" id="cartCount"><?= $cart_count ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<!-- Category Navigation (Takealot style) -->
<nav class="category-nav bg-light border-bottom">
  <div class="container">
    <ul class="nav justify-content-center align-items-center">
      <?php foreach($categories as $cat): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3"
             href="category.php?id=<?= $cat['id'] ?>"
             id="cat<?= $cat['id'] ?>"
             role="button"
             data-bs-toggle="dropdown"
             aria-expanded="false">
            <i class="<?= $cat['icon'] ?>"></i>
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php if(!empty($cat['children'])): ?>
            <ul class="dropdown-menu" aria-labelledby="cat<?= $cat['id'] ?>">
              <?php foreach($cat['children'] as $child): ?>
                <li><a class="dropdown-item" href="category.php?id=<?= $child['id'] ?>">
                  <?= htmlspecialchars($child['name']) ?>
                </a></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>


<!-- Hero Carousel -->
<div class="container hero-section">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&h=350&fit=crop" class="hero-image" alt="Electronics Sale">
      </div>
      <div class="carousel-item">
        <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?w=1200&h=350&fit=crop" class="hero-image" alt="Fashion Sale">
      </div>
      <div class="carousel-item">
        <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=1200&h=350&fit=crop" class="hero-image" alt="Home Sale">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<!-- Daily Deals Section -->
<section class="deals-section">
  <div class="container">
    <h2 class="deals-title">⏰ Daily Deals - Limited Time Only!</h2>
    <div class="row">
      <?php foreach($daily_deals as $deal): ?>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="deal-card">
            <img src="<?= $deal['img'] ?>" alt="<?= htmlspecialchars($deal['name']) ?>" class="deal-image">
            <div class="deal-info">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="deal-discount"><?= $deal['discount'] ?>% OFF</span>
                <span class="deal-timer"><?= $deal['time_left'] ?> left</span>
              </div>
              <h6 class="mb-2"><?= htmlspecialchars($deal['name']) ?></h6>
              <div class="product-price">
                <span class="current-price">R <?= number_format($deal['price']) ?></span>
                <span class="original-price">R <?= number_format($deal['original_price']) ?></span>
              </div>
              <button class="add-to-cart-btn" onclick="addToCart(<?= $deal['id'] ?>)">
                <i class="fas fa-bolt"></i> Grab Deal
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Main Content -->
<div class="container">
  <div class="row">
    
    <!-- Sidebar -->
    <div class="col-lg-3 col-md-4">
      <div class="sidebar">
     <!-- Sidebar -->
<aside class="sidebar">
  <button class="sidebar-title">
    <i class="fas fa-bars"></i> Shop by category
  </button>

  <ul class="department-list">
    <li><a href="#"><i class="fas fa-tv"></i> Electronics</a></li>
    <li><a href="#"><i class="fas fa-tshirt"></i> Fashion</a></li>
    <li><a href="#"><i class="fas fa-couch"></i> Home & Garden</a></li>
    <li><a href="#"><i class="fas fa-spa"></i> Beauty</a></li>
    <li><a href="#"><i class="fas fa-futbol"></i> Sports & Outdoor</a></li>
    <li><a href="#"><i class="fas fa-baby"></i> Baby & Kids</a></li>
    <li><a href="#"><i class="fas fa-car"></i> Automotive</a></li>
    <li><a href="#"><i class="fas fa-book"></i> Books & Media</a></li>
  </ul>
</aside>


        <!-- Features -->
        <div class="sidebar-card">
          <div class="p-3">
            <div class="mb-3">
              <i class="fas fa-shipping-fast text-primary me-2"></i>
              <small><strong>Free Delivery</strong><br>On orders over R450</small>
            </div>
            <div class="mb-3">
              <i class="fas fa-undo text-success me-2"></i>
              <small><strong>Easy Returns</strong><br>30-day return policy</small>
            </div>
            <div class="mb-3">
              <i class="fas fa-shield-alt text-warning me-2"></i>
              <small><strong>Secure Payment</strong><br>Your data is protected</small>
            </div>
            <div>
              <i class="fas fa-headset text-info me-2"></i>
              <small><strong>24/7 Support</strong><br>We're here to help</small>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Products -->
    <div class="col-lg-9 col-md-8">
      
      <!-- Featured Products Section -->
      <section class="mb-5">
        <h2 class="section-title">🔥 Trending Products</h2>
        <div class="product-grid">
          <?php foreach(array_slice($products, 0, 8) as $p): ?>
            <div class="product-card">
              <div class="product-image-container">
                <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                <?php if(!empty($p['original_price']) && $p['original_price'] > $p['price']): ?>
                  <span class="product-badge">
                    <?= round((($p['original_price'] - $p['price']) / $p['original_price']) * 100) ?>% OFF
                  </span>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <h6 class="product-title"><?= htmlspecialchars($p['name']) ?></h6>
                <div class="product-rating">
                  <div class="stars">
                    <?php 
                    $rating = $p['rating'];
                    for($i = 1; $i <= 5; $i++) {
                      if($i <= $rating) echo '★';
                      elseif($i - 0.5 <= $rating) echo '☆';
                      else echo '☆';
                    }
                    ?>
                  </div>
                  <span class="rating-text">(<?= number_format($p['reviews']) ?>)</span>
                </div>
                <div class="product-price">
                  <span class="current-price">R <?= number_format($p['price']) ?></span>
                  <?php if(!empty($p['original_price']) && $p['original_price'] > $p['price']): ?>
                    <span class="original-price">R <?= number_format($p['original_price']) ?></span>
                  <?php endif; ?>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart(<?= $p['id'] ?>)">
                  <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Electronics Section -->
      <section class="mb-5">
        <h2 class="section-title">📱 Electronics & Tech</h2>
        <div class="product-grid">
          <?php foreach(array_slice($products, 8, 4) as $p): ?>
            <div class="product-card">
              <div class="product-image-container">
                <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                <?php if(!empty($p['original_price']) && $p['original_price'] > $p['price']): ?>
                  <span class="product-badge">
                    <?= round((($p['original_price'] - $p['price']) / $p['original_price']) * 100) ?>% OFF
                  </span>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <h6 class="product-title"><?= htmlspecialchars($p['name']) ?></h6>
                <div class="product-rating">
                  <div class="stars">
                    <?php 
                    $rating = $p['rating'];
                    for($i = 1; $i <= 5; $i++) {
                      if($i <= $rating) echo '★';
                      elseif($i - 0.5 <= $rating) echo '☆';
                      else echo '☆';
                    }
                    ?>
                  </div>
                  <span class="rating-text">(<?= number_format($p['reviews']) ?>)</span>
                </div>
                <div class="product-price">
                  <span class="current-price">R <?= number_format($p['price']) ?></span>
                  <?php if(!empty($p['original_price']) && $p['original_price'] > $p['price']): ?>
                    <span class="original-price">R <?= number_format($p['original_price']) ?></span>
                  <?php endif; ?>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart(<?= $p['id'] ?>)">
                  <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
          <a href="category.php?id=1" class="btn btn-outline-primary btn-lg">
            View All Electronics <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
      </section>

      <!-- Newsletter Signup -->
      <section class="mb-5">
        <div class="row">
          <div class="col-12">
            <div class="card bg-gradient" style="background: linear-gradient(135deg, var(--takealot-blue), var(--takealot-orange));">
              <div class="card-body text-white text-center py-5">
                <h3 class="mb-3">📧 Stay Updated with Latest Deals!</h3>
                <p class="mb-4">Get exclusive offers, new arrivals, and special discounts delivered to your inbox.</p>
                <div class="row justify-content-center">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="email" class="form-control form-control-lg" placeholder="Enter your email address">
                      <button class="btn btn-warning btn-lg" type="button">
                        <i class="fas fa-paper-plane"></i> Subscribe
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4">
        <h5><i class="fas fa-info-circle"></i> About Ishahiya</h5>
        <ul class="list-unstyled">
          <li><a href="about.php">About Us</a></li>
          <li><a href="careers.php">Careers</a></li>
          <li><a href="press.php">Press Centre</a></li>
          <li><a href="investor.php">Investor Relations</a></li>
          <li><a href="sustainability.php">Sustainability</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h5><i class="fas fa-question-circle"></i> Help & Support</h5>
        <ul class="list-unstyled">
          <li><a href="help.php">Help Centre</a></li>
          <li><a href="returns.php">Returns & Refunds</a></li>
          <li><a href="shipping.php">Shipping Info</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="track.php">Track Your Order</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h5><i class="fas fa-store"></i> Shop with Us</h5>
        <ul class="list-unstyled">
          <li><a href="category.php?id=1">Electronics</a></li>
          <li><a href="category.php?id=2">Fashion</a></li>
          <li><a href="category.php?id=3">Home & Garden</a></li>
          <li><a href="category.php?id=4">Beauty</a></li>
          <li><a href="category.php?id=5">Sports</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h5><i class="fas fa-handshake"></i> Partner with Us</h5>
        <ul class="list-unstyled">
          <li><a href="sell.php">Sell on Ishahiya</a></li>
          <li><a href="supplier.php">Become a Supplier</a></li>
          <li><a href="advertise.php">Advertise with Us</a></li>
          <li><a href="affiliate.php">Affiliate Program</a></li>
          <li><a href="api.php">Developer API</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Social Media & Payment Methods -->
    <hr class="my-4">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h6 class="mb-3">Follow Us:</h6>
        <div class="d-flex gap-3">
          <a href="#" class="text-white"><i class="fab fa-facebook-f fa-2x"></i></a>
          <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
          <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
          <a href="#" class="text-white"><i class="fab fa-youtube fa-2x"></i></a>
          <a href="#" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
        </div>
      </div>
      <div class="col-md-6 text-end">
        <h6 class="mb-3">We Accept:</h6>
        <div class="d-flex justify-content-end gap-2">
          <i class="fab fa-cc-visa fa-2x"></i>
          <i class="fab fa-cc-mastercard fa-2x"></i>
          <i class="fab fa-cc-paypal fa-2x"></i>
          <i class="fas fa-university fa-2x"></i>
        </div>
      </div>
    </div>
    
    <hr class="my-4">
    <div class="row">
      <div class="col-12 text-center">
        <p class="mb-0">&copy; <?= date("Y") ?> Ishahiya Online Store. All rights reserved. | 
          <a href="privacy.php" class="text-white-50">Privacy Policy</a> | 
          <a href="terms.php" class="text-white-50">Terms of Service</a> |
          <a href="cookies.php" class="text-white-50">Cookie Policy</a>
        </p>
        <p class="mt-2 text-white-50 small">
          <i class="fas fa-shield-alt"></i> Secure Shopping Experience | 
          <i class="fas fa-truck"></i> Fast & Reliable Delivery |
          <i class="fas fa-award"></i> Trusted by Millions
        </p>
      </div>
    </div>
  </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-4" style="display: none; z-index: 1000;">
  <i class="fas fa-chevron-up"></i>
</button>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Add to Cart Function
function addToCart(productId) {
  const btn = event.target;
  const originalText = btn.innerHTML;
  
  // Loading state
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
  btn.disabled = true;
  
  fetch('add_to_cart.php?id=' + productId)
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        document.getElementById("cartCount").innerText = data.cart_count;
        btn.innerHTML = '<i class="fas fa-check"></i> Added!';
        btn.style.background = '#28a745';
        
        // Reset button after 2 seconds
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.disabled = false;
        }, 2000);
      } else {
        btn.innerHTML = '<i class="fas fa-exclamation"></i> Error';
        btn.style.background = '#dc3545';
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.disabled = false;
        }, 2000);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      btn.innerHTML = originalText;
      btn.disabled = false;
    });
}

// Search Functionality
document.querySelector('.search-input').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    performSearch();
  }
});

document.querySelector('.search-btn').addEventListener('click', performSearch);

function performSearch() {
  const query = document.querySelector('.search-input').value;
  if(query.trim()) {
    window.location.href = 'search.php?q=' + encodeURIComponent(query);
  }
}

// Back to Top Button
window.onscroll = function() {
  const backToTop = document.getElementById('backToTop');
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
    backToTop.style.display = 'block';
  } else {
    backToTop.style.display = 'none';
  }
};

document.getElementById('backToTop').addEventListener('click', function() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
});

// Auto-slide carousel
document.addEventListener('DOMContentLoaded', function() {
  const carousel = new bootstrap.Carousel(document.getElementById('heroCarousel'), {
    interval: 5000,
    ride: 'carousel'
  });
});

// Loading animation for product cards
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-5px)';
  });
  
  card.addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0)';
  });
});

// Newsletter subscription
document.querySelector('.btn-warning').addEventListener('click', function() {
  const email = this.previousElementSibling.value;
  if(email && email.includes('@')) {
    this.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
    this.disabled = true;
    setTimeout(() => {
      this.innerHTML = '<i class="fas fa-paper-plane"></i> Subscribe';
      this.disabled = false;
      this.previousElementSibling.value = '';
    }, 3000);
  } else {
    alert('Please enter a valid email address');
  }
});
</script>

</body>
</html>