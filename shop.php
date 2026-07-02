<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include_once("shop_admin/config/dbconnect.php");
/** @var mysqli $conn */

// Cart count
$cart_count = 0;
if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0) {
    $uid = (int) $_SESSION['user_id'];
    $cStmt = $conn->prepare("SELECT COUNT(*) AS total_quantity FROM cart WHERE user_id = ?");
    if ($cStmt) {
        $cStmt->bind_param("i", $uid);
        $cStmt->execute();
        $cRes = $cStmt->get_result();
        if ($cRes && $cRow = $cRes->fetch_assoc()) {
            $cart_count = (int)$cRow['total_quantity'];
        }
        $cStmt->close();
    }
} elseif (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

// Fetch all main categories for tabs
$mainCatsRes = $conn->query("SELECT * FROM main_category ORDER BY id ASC");
$mainCategories = $mainCatsRes->fetch_all(MYSQLI_ASSOC);

// Fetch all products
$productsRes = $conn->query("SELECT * FROM all_category ORDER BY id DESC");
$allProducts = $productsRes->fetch_all(MYSQLI_ASSOC);

// Group products by main_category_id
$productsByCategory = [];
foreach ($allProducts as $p) {
    $productsByCategory[$p['main_category_id']][] = $p;
}

// Fetch subcategories grouped by main_category_id
$subcatsByMain = [];
$subQ = $conn->query("SELECT * FROM sub_category ORDER BY id ASC");
if ($subQ) {
    while ($sr = $subQ->fetch_assoc()) {
        $mId = (int)($sr['main_category_id'] ?? 0);
        if ($mId > 0) {
            $subcatsByMain[$mId][] = $sr;
        }
    }
}
$subQ2 = $conn->query("SELECT * FROM subcategories ORDER BY id ASC");
if ($subQ2) {
    while ($sr2 = $subQ2->fetch_assoc()) {
        $mId = (int)($sr2['category_id'] ?? 0);
        if ($mId > 0) {
            $subcatsByMain[$mId][] = ['id' => $sr2['id'], 'sub_category_name' => $sr2['name'], 'slug' => $sr2['name']];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once 'includes/seo_master.php'; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="shop.css">
</head>
<body>
<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>

<?php
// Determine custom banner from hero_slider if 'main' parameter is present
$customBannerImg = '';
if (!empty($_GET['main'])) {
    $mainParam = $conn->real_escape_string(trim($_GET['main']));
    // Look for a slider that has a link matching this exact category
    $sliderQ = $conn->query("SELECT image FROM hero_slider WHERE link LIKE '%main=" . $mainParam . "%' LIMIT 1");
    if ($sliderQ && $sliderRow = $sliderQ->fetch_assoc()) {
        if (!empty($sliderRow['image'])) {
            $customBannerImg = 'uploads/slider/' . $sliderRow['image'];
        }
    }
}
$bannerBg = !empty($customBannerImg) ? $customBannerImg : 'image/shop/sh-ban.jpg';
?>

<style>
/* Exact styling harmonized with live reference & enterprise theme */
#sh-banner {
  background-image: url('<?= htmlspecialchars($bannerBg) ?>');
  background-color: #111;
  width: 100%;
  height: 38vh;
  min-height: 250px;
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  flex-direction: column;
  padding: 14px;
  border-bottom: 2px solid #d4af37;
}
#sh-banner h4 { color: #d4af37; font-size: 18px; margin-bottom: 10px; font-weight:800; text-transform:uppercase; letter-spacing:2px; }
#sh-banner h2 { color: whitesmoke; font-size: 38px; font-weight: 800; text-shadow:0 2px 10px rgba(0,0,0,0.8); }

.category-container { text-align: center; padding: 45px 25px; background: #0f0f0f; min-height:600px; }
.category-buttons { margin-bottom: 35px; display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
.category-buttons button { background-color: #1c1c1c; border: 1px solid #333; padding: 12px 26px; cursor: pointer; border-radius: 6px; transition: all 0.3s ease; font-size: 14px; font-weight: 700; color: #bbb; text-transform:uppercase; letter-spacing:1px; }
.category-buttons button:hover { background-color: #2a2a2a; color:#fff; border-color:#d4af37; }
.category-buttons button.active { background-color: #d4af37; color: #000; border-color: #d4af37; box-shadow:0 0 18px rgba(212,175,55,0.4); }

.clothing-category { display: none; opacity: 0; transform: translateY(10px); transition: opacity 0.4s ease, transform 0.4s ease; }
.clothing-category.visible { display: block; opacity: 1; transform: translateY(0); }
.clothing-category h2 { font-size: 28px; margin-bottom: 15px; color: #fff; font-weight: 800; text-transform:uppercase; letter-spacing:1px; }

/* Subcategory Navigation Chips */
.subcat-pills-row { display:flex; flex-wrap:wrap; justify-content:center; gap:10px; margin-bottom:30px; max-width:1100px; margin-left:auto; margin-right:auto; }
.subcat-pill { background:#161616; color:#d4af37; border:1px solid #d4af37; padding:7px 18px; border-radius:20px; font-size:12px; font-weight:700; text-decoration:none; text-transform:uppercase; letter-spacing:0.8px; transition:all 0.2s; display:inline-flex; align-items:center; gap:6px; }
.subcat-pill:hover { background:#d4af37; color:#000; transform:scale(1.05); }

/* High Density Enterprise Grid */
.products-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(195px, 1fr)); gap: 14px; max-width: 1480px; margin: 0 auto; text-align:left; padding: 0 10px; }

.product {
  display: flex !important;
  flex-direction: column !important;
  background: #161616 !important;
  border: 1px solid #282828 !important;
  border-radius: 8px !important;
  margin: 0 !important;
  padding: 0 !important;
  width: 100% !important;
  overflow: hidden;
  position: relative;
  transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0,0,0,0.5);
}
.product:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.22); border-color: #d4af37 !important; }
.product-img-box { position:relative; aspect-ratio:1/1.1; background:#fff; overflow:hidden; border-bottom:1px solid #262626; padding: 8px; display:flex; align-items:center; justify-content:center; }
.product-img-box img { width: 100%; height: 100%; object-fit: contain; transition: transform 0.4s ease; border: none !important; }
.product:hover .product-img-box img { transform: scale(1.05); }

.badge-sale { position:absolute; top:8px; left:8px; background:#e74c3c; color:#fff; font-size:9px; font-weight:800; padding:3px 6px; border-radius:3px; z-index:2; letter-spacing:0.5px; display:none; }
.heart-bookmark { position:absolute; top:8px; right:8px; background:rgba(255,255,255,0.95); border:1px solid rgba(0,0,0,0.1); color:#222; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; z-index:5; font-size:12px; box-shadow:0 2px 6px rgba(0,0,0,0.2); transition:all 0.25s; }
.heart-bookmark:hover { background:#fff; color:#ff3f6c; transform:scale(1.15); box-shadow:0 4px 12px rgba(255,63,108,0.35); }

.product-info-box { padding: 10px 12px; display:flex; flex-direction:column; flex-grow:1; background: #161616; }
.product span { font-size: 10px; color: #d4af37; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.8px; font-weight:700; }
.product h4 { font-size: 12.5px !important; color: #eee !important; margin: 0 0 6px 0 !important; font-weight: 600; line-height:1.35; height:34px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; line-clamp:2; -webkit-box-orient:vertical; }
.price-block { display:flex; align-items:baseline; gap:6px; margin-bottom:10px; }
.product p { color: #fff !important; font-size: 16px !important; font-weight: 800; margin: 0 !important; }
.old-p { font-size:11.5px; color:#777; text-decoration:line-through; }
.cta-btn { display:block; width:100%; text-align:center; padding:7px; background:#0d0d0d; color:#d4af37; border:1px solid #d4af37; border-radius:4px; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; margin-top:auto; transition:all 0.25s; }
.product:hover .cta-btn { background:#d4af37; color:#000; }
</style>

<section id="sh-banner">
  <?php if (empty($customBannerImg)): ?>
  <h4>Welcome to Our Shop</h4>
  <h2>Begin Your Journey to Exceptional Savings</h2>
  <?php endif; ?>
</section>

<div class="category-container">
  <div class="category-buttons">
    <?php foreach($mainCategories as $index => $cat): ?>
      <button id="btn-<?= $cat['id'] ?>" class="<?= $index === 0 ? 'active' : '' ?>" data-slug="<?= htmlspecialchars($cat['slug']) ?>">
        <?= htmlspecialchars($cat['main_category_name']) ?>
      </button>
    <?php endforeach; ?>
  </div>

  <?php foreach($mainCategories as $index => $cat): 
      $catId = $cat['id'];
      $catProducts = $productsByCategory[$catId] ?? [];
      $relatedSubcats = $subcatsByMain[$catId] ?? [];
  ?>
  <div id="cat-<?= $catId ?>" class="clothing-category <?= $index === 0 ? 'visible' : '' ?>">
    <h2><?= htmlspecialchars($cat['main_category_name']) ?></h2>
    
    <?php if(!empty($relatedSubcats)): ?>
    <div class="subcat-pills-row">
      <?php foreach($relatedSubcats as $sub): 
        $subName = $sub['sub_category_name'] ?? $sub['name'] ?? '';
        $subSlug = $sub['slug'] ?? $subName;
      ?>
        <a href="subshop1.php?subcategory=<?= urlencode($subSlug) ?>" class="subcat-pill"><i class="fa-solid fa-folder-open"></i> <?= htmlspecialchars($subName) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="products-wrapper">
      <?php if(!empty($catProducts)): foreach($catProducts as $p): 
        $img = !empty($p['Image1']) ? "shop_admin/uploads/subshop/" . basename($p['Image1']) : "assets/no-image.png";
        $priceRaw = (float)($p['price'] ?? 0);
        $discPct = !empty($p['bumper_discount']) && $p['bumper_discount'] > 0 ? (int)$p['bumper_discount'] : 0;
        if ($discPct > 0 && $discPct < 100) {
          $finalPrice = round($priceRaw * (1 - ($discPct / 100)), 2);
          $oldPrice = number_format($priceRaw, 2);
        } else {
          $finalPrice = $priceRaw;
          $oldPrice = "";
        }
      ?>
        <div class="product" onclick="window.location.href='subshop1.php?category_id=<?= $p['id'] ?>'">
          <div class="product-img-box">
            <?php if($discPct > 0): ?><span class="badge-sale"><?= $discPct ?>% OFF</span><?php endif; ?>
            <div class="heart-bookmark" onclick="toggleWishlistIndex(event, <?= $p['id'] ?>)"><i class="fa-regular fa-heart"></i></div>
            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" decoding="async" onerror="this.src='shop_admin/uploads/no-image.png'">
          </div>
          <div class="product-info-box">
            <span><?= htmlspecialchars($p['brand'] ?: 'IshaHiya') ?></span>
            <h4><?= htmlspecialchars($p['name']) ?></h4>
            <div class="price-block">
              <p>₹<?= number_format($finalPrice, 2) ?></p>
              <?php if($discPct > 0 && !empty($oldPrice)): ?><span class="old-p">₹<?= $oldPrice ?></span><?php endif; ?>
            </div>
            <span class="cta-btn">View Collection</span>
          </div>
        </div>
      <?php endforeach; else: ?>
        <p style="grid-column:1/-1;text-align:center;color:#888;padding:40px 0;font-size:15px;">No collections or items listed under this category yet.</p>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll(".category-buttons button");
    const categories = document.querySelectorAll(".clothing-category");
    const urlParams = new URLSearchParams(window.location.search);
    const categorySlug = urlParams.get('main');

    let targetButton = null;

    buttons.forEach(button => {
      // If URL matches slug, prepare to activate it
      if (categorySlug && button.getAttribute('data-slug') === categorySlug) {
          targetButton = button;
      }

      button.addEventListener("click", () => {
        // Remove active class from all buttons
        buttons.forEach(btn => btn.classList.remove("active"));
        // Remove visible class from all categories
        categories.forEach(category => category.classList.remove("visible"));
        
        // Add active to clicked button
        button.classList.add("active");

        // Extract ID and show corresponding category
        const catId = button.id.replace('btn-', '');
        const targetCategory = document.getElementById('cat-' + catId);
        if (targetCategory) {
            targetCategory.classList.add("visible");
        }
      });
    });

    // Auto-click if arrived from a link (like the Hero Slider)
    if (targetButton) {
        targetButton.click();
    }
});

function toggleWishlistIndex(e, pid) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    let formData = new FormData();
    formData.append('product_id', pid);

    fetch('toggle_wishlist.php', { method: 'POST', body: formData })
    .then(response => response.json())
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
</script>
</body>
</html>
