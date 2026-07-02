<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);

// Centralized & Secure Cart Count Logic
$cart_count = 0;
if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0) {
  require_once __DIR__ . '/../db.php';
  if (isset($conn)) {
    $uid = (int) $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM cart WHERE user_id=?");
    if ($stmt) {
      $stmt->bind_param("i", $uid);
      $stmt->execute();
      $res = $stmt->get_result();
      $cart_count = ($res && ($r = $res->fetch_assoc())) ? (int)$r['total'] : 0;
      $stmt->close();
    }
  }
} elseif (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  $cart_count = count($_SESSION['cart']);
}
$wishlist_count = isset($_SESSION["wishlist"]) && is_array($_SESSION["wishlist"]) ? count($_SESSION["wishlist"]) : 0;
?>

<!-- HEADER TOP BAR -->
<header class="header">
  <div class="header__top">
    <div class="container">
      <!-- Contact Information Section -->
      <div class="header__contact">
        <span><i class="fa-solid fa-phone" style="color: var(--first-color);"></i> (+91) 9974328904</span>
        <span style="color: #ccc;">|</span>
        <span>India</span>
      </div>

      <!-- Alert News Section -->
      <p class="header__alert-news">Discover unbeatable deals across your favorite products</p>

      <!-- User Info Section (Login/Logout) -->
      <div class="header__user-info">
        <?php
        require_once __DIR__ . '/../db.php'; // Database connection relative to includes/

        // Check if mobile number exists in session (from OTP verification) or username (from standard login)
        $displayName = null;

        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            $email = $conn->real_escape_string($_SESSION['username']);
            $res = $conn->query("SELECT name FROM user WHERE email = '$email'");
            if ($res && $row = $res->fetch_assoc()) {
                $displayName = $row['name'];
            } else {
                $displayName = $_SESSION['username'];
            }
        } elseif (isset($_SESSION['user_phone']) && !empty($_SESSION['user_phone'])) {
            $displayName = $_SESSION['user_phone'];
        }

        if ($displayName) {
          echo '<div class="header__top-action">';
          echo '<i class="fa-solid fa-user"></i> Welcome: ' . htmlspecialchars($displayName);
          echo '</div>';
          echo '<span style="color: #ccc;">|</span>';
          echo '<a href="logout.php" class="header__top-action"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>';
        } else {
          // If not logged in at all
          echo '<a href="log.php" class="header__top-action"><i class="fa-regular fa-user"></i> Login <span style="color: #ccc; margin: 0 4px;">|</span> Sign up</a>';
        }
        ?>
      </div>
    </div>
  </div>
</header>


<nav class="nav">
  <!-- Logo -->
  <a href="index.php" class="nav__logo">
    <img src="image/logo/ishahiya-logo.png" alt="Ishahiya Logo" loading="lazy" style="max-height:55px; width:auto;">
  </a>

  <!-- Hamburger Menu -->
  <div class="nav__toggle" id="nav-toggle">
    <i class="fa-solid fa-bars"></i>
  </div>

  <!-- Menu -->
  <div class="nav__menu" id="nav-menu">
    <!-- Close Button -->
    <div class="close-btn" id="close-btn">
      <i class="fa-solid fa-times"></i>
    </div>

    <ul class="nav__list">
      <li><a href="index.php" class="nav__link <?= ($current_page == 'index.php') ? 'active-link' : '' ?>">Home</a></li>
      <li><a href="shop.php" class="nav__link <?= ($current_page == 'shop.php' || $current_page == 'sproduct.php') ? 'active-link' : '' ?>">Shop</a></li>
      <li><a href="about.php" class="nav__link <?= (strcasecmp($current_page, 'about.php') == 0) ? 'active-link' : '' ?>">About</a></li>
      <li><a href="accounts.php" class="nav__link <?= ($current_page == 'accounts.php') ? 'active-link' : '' ?>">Account</a></li>
      <li><a href="contact.php" class="nav__link <?= ($current_page == 'contact.php') ? 'active-link' : '' ?>">Contact</a></li>

    </ul>
  </div>

  <!-- Search Form - Premium Design -->
  <form action="search.php" method="get" class="search-form">
    <input type="text" name="s" placeholder="Search for products, brands and more" required>
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>

  <!-- Wishlist + Cart -->
  <div class="header__user-actions">
    <a href="wishlist.php" class="header__action-btn">
      <img src="image/icon/icon-heart.svg" alt="Wishlist Icon" />
      <span class="count" id="wishlist-count"><?php 
        require_once __DIR__ . '/../db.php';
        if (!isset($_SESSION['user_id'])) {
            if (isset($_SESSION['customer_id'])) { $_SESSION['user_id'] = (int)$_SESSION['customer_id']; }
            elseif (isset($_SESSION['id'])) { $_SESSION['user_id'] = (int)$_SESSION['id']; }
            elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                $uSafe = $conn->real_escape_string($_SESSION['username']);
                $q = $conn->query("SELECT id FROM user WHERE email = '$uSafe' LIMIT 1");
                if ($q && $r = $q->fetch_assoc()) { $_SESSION['user_id'] = (int)$r['id']; }
            }
        }
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $uid = (int)$_SESSION['user_id'];
            $wres = $conn->query("SELECT COUNT(*) as total FROM wishlist WHERE user_id = '$uid'");
            $wrow = $wres ? $wres->fetch_assoc() : null;
            echo $wrow['total'] ?? 0;
        } else {
            echo 0;
        }
      ?></span>
    </a>
    <a href="cart.php" class="header__action-btn">
      <img src="image/icon/icon-cart.svg" alt="Cart Icon" />
      <span class="count"><?php echo $cart_count; ?></span>
    </a>
  </div>
</nav>
<script src="js/search-autocomplete.js"></script>