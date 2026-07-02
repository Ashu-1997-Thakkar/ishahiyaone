<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

$search = trim($_GET['s'] ?? '');
$safe_s = $conn->real_escape_string($search);

// Cart & Wishlist counts
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    $cStmt = $conn->prepare("SELECT COUNT(*) AS total FROM cart WHERE user_id=?");
    if ($cStmt) {
        $cStmt->bind_param("i", $uid);
        $cStmt->execute();
        $cRes = $cStmt->get_result();
        if ($cRes && $cRow = $cRes->fetch_assoc()) {
            $cart_count = (int)$cRow['total'];
        }
        $cStmt->close();
    }
} elseif (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
$wishlist_count = isset($_SESSION["wishlist"]) ? count($_SESSION["wishlist"]) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($search !== '' ? 'Search Results for "' . $search . '"' : 'Search Catalog') ?> | IshahiyaOne</title>
    <meta name="description" content="Shop matching eCommerce products for <?= htmlspecialchars($search) ?> at IshahiyaOne. Best prices, premium quality, and fast delivery available.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #0d0d0d;
            color: #ffffff;
            font-family: 'Lato', sans-serif;
            margin: 0;
        }

        /* ===== Enterprise Merchandising Header ===== */
        .search-hero {
            padding: 45px 20px;
            background: #141414;
            border-bottom: 2px solid #d4af37;
            text-align: center;
            margin-bottom: 30px;
        }
        .search-hero h1 {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .search-hero h1 span {
            color: #d4af37;
        }
        .search-seo-meta {
            font-size: 13px;
            color: #888;
            margin-top: 8px;
        }

        .search-container {
            max-width: 1480px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .toolbar-bar {
            background: #161616;
            border: 1px solid #282828;
            border-radius: 8px;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            color: #bbb;
            font-size: 13px;
        }
        .toolbar-bar span { color: #d4af37; font-weight: 700; }

        /* ===== High-Density Enterprise Grid ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(205px, 1fr));
            gap: 18px;
        }

        .product-card {
            background: #181818;
            border: 1px solid #262626;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.35);
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            border-color: #d4af37;
            box-shadow: 0 10px 22px rgba(212, 175, 55, 0.18);
        }

        .product-img {
            position: relative;
            aspect-ratio: 1/1.12;
            background: #222;
            overflow: hidden;
            border-bottom: 1px solid #262626;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }
        .product-card:hover .product-img img {
            transform: scale(1.06);
        }

        .badge-sale { position: absolute; top: 8px; left: 8px; background: #e74c3c; color: #fff; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 3px; z-index: 2; letter-spacing: 0.5px; }
        .heart-btn { position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.65); border: 1px solid #444; color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; font-size: 11px; }

        .product-info {
            padding: 10px 12px 12px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            text-align: left;
        }
        .brand { font-size: 10px; color: #d4af37; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px; }
        .name { font-size: 12.5px; font-weight: 600; color: #eee; margin: 0 0 8px; line-height: 1.4; height: 35px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
        .price-row { display: flex; align-items: baseline; gap: 6px; margin-bottom: 12px; }
        .curr-p { font-size: 16px; font-weight: 800; color: #fff; }
        .old-p { font-size: 11px; color: #777; text-decoration: line-through; }

        .view-cta { display: block; width: 100%; text-align: center; padding: 8px; background: #0a0a0a; color: #d4af37; border: 1px solid #d4af37; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-top: auto; transition: 0.2s; text-decoration: none; }
        .product-card:hover .view-cta { background: #d4af37; color: #000; }

        .empty-results { text-align: center; padding: 80px 20px; background: #141414; border-radius: 12px; border: 1px dashed #333; }
        .empty-results i { font-size: 3.5rem; color: #ff6b6b; margin-bottom: 15px; }
        .empty-results h3 { color: #fff; font-size: 1.5rem; font-weight: 700; }
        .empty-results p { color: #888; max-width: 500px; margin: 10px auto 25px; }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <?php include 'includes/header_nav.php'; ?>

    <section class="search-hero">
        <div class="container">
            <?php if ($search !== ''): ?>
                <h1>Search Results for <span>"<?= htmlspecialchars($search) ?>"</span></h1>
                <div class="search-seo-meta">Showing marketplace catalog matches across leading enterprise categories</div>
            <?php else: ?>
                <h1>Search <span>Marketplace</span></h1>
                <div class="search-seo-meta">Enter a search query in the navigation bar to explore products</div>
            <?php endif; ?>
        </div>
    </section>

    <div class="search-container">
    <?php if ($search !== ''): ?>
        <?php
        // Unified Search Query across all 4 tables
        $sql = "
            SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('all_category' AS CHAR CHARACTER SET utf8mb4) AS source FROM all_category WHERE name LIKE '%$safe_s%' OR brand LIKE '%$safe_s%'
            UNION ALL
            SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('subcategories' AS CHAR CHARACTER SET utf8mb4) AS source FROM subcategories WHERE name LIKE '%$safe_s%' OR brand LIKE '%$safe_s%' OR sku_no LIKE '%$safe_s%'
            UNION ALL
            SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(image AS CHAR CHARACTER SET utf8mb4) AS image, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('products' AS CHAR CHARACTER SET utf8mb4) AS source FROM products WHERE name LIKE '%$safe_s%' OR brand LIKE '%$safe_s%'
            UNION ALL
            SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, CAST('Ishahiya' AS CHAR CHARACTER SET utf8mb4) AS brand, CAST('subshop' AS CHAR CHARACTER SET utf8mb4) AS source FROM subshop WHERE name LIKE '%$safe_s%'
        ";
        $result = $conn->query($sql);
        $totalItems = $result ? $result->num_rows : 0;
        ?>

        <div class="toolbar-bar">
            <div>Breadcrumbs: <a href="index.php" style="color:#aaa;text-decoration:none;">Home</a> &gt; <span>Search Results</span></div>
            <div>Found <span><?= $totalItems ?></span> Matching Items</div>
        </div>

        <?php if ($totalItems > 0): ?>
            <div class="products-grid">
                <?php while ($row = $result->fetch_assoc()): 
                    $pId = (int)$row['id'];
                    $pName = $row['name'] ?? 'Product';
                    $pBrand = $row['brand'] ?: 'Ishahiya';
                    $pPriceRaw = (float)($row['price'] ?? 0);
                    $pPrice = number_format($pPriceRaw, 2);
                    $pOldPrice = number_format($pPriceRaw * 1.35, 2);
                    $imgRaw = trim($row['image'] ?? '');
                    $basename = basename($imgRaw);

                    if (empty($imgRaw)) {
                        $image = 'shop_admin/uploads/no-image.png';
                    } else {
                        if ($row['source'] === 'products') {
                            $image = 'shop_admin/uploads/' . $basename;
                        } else {
                            $image = (strpos($imgRaw, 'shop_admin/') !== false) ? ltrim($imgRaw, '/') : 'shop_admin/uploads/subshop/' . $basename;
                        }
                    }

                    $detailUrl = "drt.php?product_id=$pId&source=" . urlencode($row['source']) . "&hide_timer=1";
                ?>
                    <div class="product-card" onclick="window.location.href='<?= $detailUrl ?>'">
                        <div class="product-img">
                            <span class="badge-sale">MATCH</span>
                            <div class="heart-btn"><i class="fa-regular fa-heart"></i></div>
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($pName) ?>" onerror="this.src='shop_admin/uploads/no-image.png'">
                        </div>
                        <div class="product-info">
                            <div class="brand"><?= htmlspecialchars($pBrand) ?></div>
                            <h4 class="name" title="<?= htmlspecialchars($pName) ?>"><?= htmlspecialchars($pName) ?></h4>
                            <div class="price-row">
                                <span class="curr-p">₹<?= $pPrice ?></span>
                                <?php if ($pPriceRaw > 0): ?><span class="old-p">₹<?= $pOldPrice ?></span><?php endif; ?>
                            </div>
                            <span class="view-cta">View Details</span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-results">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>No Matching Products Found</h3>
                <p>We couldn't find any catalog items matching "<?= htmlspecialchars($search) ?>". Try searching for general keywords like "Puma", "Lehenga", or "Cotton".</p>
                <a href="shop.php" class="btn btn-warning px-4 py-2" style="font-weight:700;">Browse All Collections</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-results">
            <i class="fa-solid fa-keyboard"></i>
            <h3>Start Your Search</h3>
            <p>Type product names, brand keywords, or categories into the navigation bar above to find exactly what you're looking for.</p>
            <a href="shop.php" class="btn btn-warning px-4 py-2" style="font-weight:700;">Explore Shop</a>
        </div>
    <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
