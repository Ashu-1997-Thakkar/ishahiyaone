<?php
session_start();
include('db.php');

// Define pagination
$limit = 12; // Products per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch all bumper products
$sql = "
    SELECT SQL_CALC_FOUND_ROWS * FROM (
        SELECT id AS product_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1, id AS cat_id, 'all_category' AS source, created_at, bumper_end_date, bumper_discount FROM all_category WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
        UNION ALL
        SELECT id AS product_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1, id AS cat_id, 'subcategories' AS source, created_at, bumper_end_date, bumper_discount FROM subcategories WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
        UNION ALL
        SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(image AS CHAR CHARACTER SET utf8mb4) AS Image1, COALESCE(sub_category_id, category_id) AS cat_id, 'products' AS source, created_at, bumper_end_date, bumper_discount FROM products WHERE is_bumper_offer = 1 AND bumper_start_date IS NOT NULL AND bumper_end_date IS NOT NULL AND CURDATE() BETWEEN DATE(bumper_start_date) AND DATE(bumper_end_date)
    ) as combined_offers
    ORDER BY created_at DESC
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);
$products = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Get total count
$countResult = $conn->query("SELECT FOUND_ROWS() as total");
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bumper Offers | Ishahiya</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="shop.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --gold: #d4af37;
            --dark-bg: #000000;
        }
        body { background-color: var(--dark-bg); color: #fff; }
        
        .offers-hero {
            background: linear-gradient(180deg, #181818 0%, #0a0a0a 100%);
            padding: 35px 20px;
            text-align: center;
            border-bottom: 2px solid var(--gold);
            margin-bottom: 20px;
        }
        .offers-hero h1 {
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }
        .offers-hero p {
            font-size: 0.95rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ccc;
            margin-bottom: 0;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 300px));
            gap: 25px;
            padding: 25px 0 50px 0;
            justify-content: center;
        }
        
        .offer-card {
            background: #181818;
            border: 1px solid #282828;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            width: 100%;
        }
        .offer-card:hover {
            border-color: var(--gold);
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
        }
        .offer-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ff4757;
            color: #fff;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 4px;
            z-index: 10;
            letter-spacing: 0.5px;
        }
        .offer-img {
            width: 100%;
            height: 250px;
            position: relative;
            background: #202020;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            text-decoration: none;
        }
        .offer-img img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .offer-details {
            padding: 18px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .offer-brand {
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .offer-title {
            font-size: 1.05rem;
            font-weight: 600;
            margin: 8px 0 12px 0;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8rem;
            line-height: 1.4rem;
        }
        .offer-price {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
        }
        .offer-price::before { content: '₹'; color: var(--gold); margin-right: 2px; font-size: 1rem; }
        
        .btn-gold-offer {
            background: linear-gradient(135deg, #d4af37, #f3e5ab, #aa8c2c);
            color: #000 !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        .btn-gold-offer:hover {
            background: linear-gradient(135deg, #f3e5ab, #d4af37, #f3e5ab);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            margin-bottom: 50px;
        }
        .page-link {
            background: #111;
            border: 1px solid #333;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }
        .page-link:hover, .page-link.active {
            background: var(--gold);
            color: #000;
            border-color: var(--gold);
        }
    </style>
</head>
<body>
    <?php include 'includes/header_nav.php'; ?>
    <?php include 'includes/category_nav.php'; ?>
    
    <div class="offers-hero">
        <div class="container">
            <h1>Exclusive Bumper Offers</h1>
            <p>Don't miss out on these limited-time deals</p>
        </div>
    </div>
    
    <div class="container">
        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open" style="font-size: 4rem; color: var(--gold); opacity: 0.5; margin-bottom: 20px;"></i>
                <h3 style="color: var(--gold);">No Offers Currently Available</h3>
                <p>Check back later for exciting new deals!</p>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): 
                    $pId = (int)$p['product_id'];
                    $pName = $p['name'];
                    $pBrand = $p['brand'] ?? 'Premium';
                    $pPrice = (float)$p['price'];
                    $img = $p['Image1'];
                    
                    $imagePath = 'shop_admin/uploads/no-image.png';
                    if (!empty($img)) {
                        $imgName = basename($img);
                        if ($p['source'] === 'products' || $p['source'] === 'collections') {
                            $imagePath = 'shop_admin/uploads/' . $imgName;
                        } else {
                            $imagePath = (strpos($img, 'shop_admin/uploads/') !== false) ? $img : 'shop_admin/uploads/subshop/' . $imgName;
                        }
                    }
                    
                    // Generate Link
                    $link = 'drt.php?product_id=' . $pId . '&source=' . urlencode($p['source']);
                    $discount = (int)$p['bumper_discount'];
                    $finalPrice = $discount > 0 ? round($pPrice * (1 - $discount / 100), 2) : $pPrice;
                ?>
                <div class="offer-card">
                    <div class="offer-badge">BUMPER DEAL</div>
                    <?php if ($discount > 0): ?>
                    <div class="offer-badge" style="top: auto; bottom: 10px; background: var(--gold); color: #000;"><?= $discount ?>% OFF</div>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($link) ?>" class="offer-img">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($pName) ?>" loading="lazy" decoding="async">
                    </a>
                    <div class="offer-details">
                        <div>
                            <div class="offer-brand"><?= htmlspecialchars($pBrand) ?></div>
                            <h4 class="offer-title" title="<?= htmlspecialchars($pName) ?>"><?= htmlspecialchars($pName) ?></h4>
                            <?php if ($discount > 0): ?>
                              <div class="offer-price">
                                <del style="color: #888; font-size: 1rem; margin-right: 6px; font-weight: 600;">₹<?= number_format($pPrice, 2) ?></del>
                                <span>₹<?= number_format($finalPrice, 2) ?></span>
                              </div>
                            <?php else: ?>
                              <div class="offer-price"><?= number_format($pPrice, 2) ?></div>
                            <?php endif; ?>
                        </div>
                        <a href="<?= htmlspecialchars($link) ?>" class="btn-gold-offer">View Offer <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="page-link">&laquo; Prev</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
