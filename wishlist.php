<?php
session_start();
require_once __DIR__ . '/db.php';

// Calculate cart count
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += (int)($item['quantity'] ?? $item['qty'] ?? 1);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist | Ishahiya</title>
    <!-- Standard Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/logo/ishahiya-logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="image/logo/ishahiya-logo.png">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --gold: #d4af37;
            --dark-gold: #c5a02d;
            --black: #000;
            --near-black: #0a0a0a;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(212, 175, 55, 0.1);
        }

        body {
            background-color: var(--black);
            color: #fff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        .wishlist-hero {
            padding: 35px 0 20px;
            text-align: center;
            background: radial-gradient(circle at center, #111 0%, #000 100%);
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .wishlist-hero h1 {
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 8px;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
        }

        .wishlist-hero p {
            color: #666;
            font-size: 1.1rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Gallery Grid Logic */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px 0;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        /* Product Gallery Card */
        .product-gallery-card {
            background: var(--near-black);
            border: 1px solid #1a1a1a;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-gallery-card:hover {
            transform: translateY(-12px);
            border-color: var(--gold);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5), 0 0 0 1px var(--gold);
        }

        .pg-img-container {
            position: relative;
            width: 100%;
            padding-top: 110%; /* Reduced from 130% for a tighter aspect ratio */
            background: #fff;
            overflow: hidden;
        }

        .pg-img-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .product-gallery-card:hover .pg-img-container img {
            transform: scale(1.1);
        }

        /* Floating Remove Button */
        .pg-remove-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 38px;
            height: 38px;
            background: rgba(255, 71, 87, 0.9);
            color: #fff;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 20;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .pg-remove-btn:hover {
            background: #ff4757;
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 0 20px rgba(255, 71, 87, 0.4);
        }

        /* Card Content */
        .pg-content {
            padding: 15px; /* Reduced from 25px */
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px; /* Tighter spacing */
        }

        .pg-tag {
            font-size: 0.7rem;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 12px;
            display: block;
            font-weight: 700;
        }

        .pg-title {
            font-size: 1rem; /* Reduced from 1.1rem */
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Prevent multiline titles from taking space */
        }

        .pg-price-box {
            margin-bottom: 10px; /* Reduced from 20px */
        }

        .pg-price {
            font-size: 1.5rem;
            font-weight: 900;
            color: #fff;
            display: block;
        }

        .pg-price::before {
            content: '₹';
            font-size: 1rem;
            margin-right: 2px;
            color: var(--gold);
        }

        .special-offer-price::before {
            content: none !important;
        }

        .pg-action-btn {
            background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%);
            color: #000;
            text-decoration: none !important;
            padding: 10px; /* Reduced from 14px */
            border-radius: 8px; /* Reduced border radius */
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            display: block;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }

        .pg-action-btn:hover {
            transform: scale(1.02);
            background: #fff;
            color: #000;
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }

        /* Empty State */
        .pg-empty {
            text-align: center;
            padding: 50px 20px;
            background: var(--glass);
            border-radius: 20px;
            border: 1px dashed #333;
        }

        .pg-empty i {
            font-size: 3.5rem;
            color: #222;
            margin-bottom: 15px;
            display: block;
        }

        .pg-empty h3 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #555;
            margin-bottom: 15px;
        }

        .btn-gold-outline {
            border: 2px solid var(--gold);
            color: var(--gold);
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 800;
            text-transform: uppercase;
            transition: 0.3s;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-gold-outline:hover {
            background: var(--gold);
            color: #000;
        }

        /* Responsive Fixes */
        @media (max-width: 992px) {
            .wishlist-hero h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .pg-content {
                padding: 15px;
            }

            .pg-title {
                font-size: 0.9rem;
            }

            .pg-price {
                font-size: 1.2rem;
            }

            .pg-action-btn {
                font-size: 0.75rem;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/header_nav.php'; ?>
    <?php include 'includes/category_nav.php'; ?>

    <div class="wishlist-hero">
        <div class="container">
            <h1>Wishlist </h1>
            <p>Curated Selection by You</p>
            <a href="cart.php" class="btn-gold-outline" style="margin-top: 15px; padding: 10px 30px; font-size: 0.9rem;"><i class="fas fa-shopping-cart"></i> Go to Checkout</a>
        </div>
    </div>

    <main class="container mb-5">
        <?php
        if (!isset($_SESSION['user_id'])) {
            if (isset($_SESSION['customer_id'])) { $_SESSION['user_id'] = (int)$_SESSION['customer_id']; }
            elseif (isset($_SESSION['id'])) { $_SESSION['user_id'] = (int)$_SESSION['id']; }
            elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                $uSafe = $conn->real_escape_string($_SESSION['username']);
                $q = $conn->query("SELECT id FROM user WHERE email = '$uSafe' LIMIT 1");
                if ($q && $r = $q->fetch_assoc()) { $_SESSION['user_id'] = (int)$r['id']; }
            }
        }

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            echo '<div class="pg-empty">
                <i class="fas fa-user-lock"></i>
                <h3>Vault Locked</h3>
                <p class="text-muted">Please sign in to access your curated gallery.</p>
                <a href="log.php" class="btn-gold-outline">Sign In Now</a>
              </div>';
        } else {
            $user_id = (int)$_SESSION['user_id'];
            $sql = "
            SELECT 
                w.product_id,
                COALESCE(p.name, ac.name, s.name, c.product_name, bo.title) as name,
                COALESCE(p.price, ac.price, s.price, 0) as price,
                COALESCE(p.image, ac.Image1, s.Image1, s.image1, c.image, bo.banner_image) as image,
                COALESCE(p.sku, s.sku_no, '') as sku_no,
                bo.sub_category_id as bo_sub_category_id,
                CASE 
                    WHEN p.product_id IS NOT NULL THEN 'products'
                    WHEN ac.id IS NOT NULL THEN 'all_category'
                    WHEN s.id IS NOT NULL THEN 'subcategories'
                    WHEN c.id IS NOT NULL THEN 'collections'
                    WHEN bo.id IS NOT NULL THEN 'bumper_offers'
                END as source
            FROM wishlist w
            LEFT JOIN products p ON p.product_id = w.product_id
            LEFT JOIN all_category ac ON ac.id = w.product_id
            LEFT JOIN subcategories s ON s.id = w.product_id
            LEFT JOIN collections c ON c.id = w.product_id
            LEFT JOIN bumper_offers bo ON bo.id = -w.product_id
            WHERE w.user_id = ?
            GROUP BY w.product_id
        ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<div class="gallery-grid">';
                while ($row = $result->fetch_assoc()) {
                    $pid = $row['product_id'];
                    $name = $row['name'] ?? 'Premium Product';
                    $price = (float)$row['price'];
                    $img_raw = $row['image'] ?? '';
                    $source = $row['source'];
                    $sku = $row['sku_no'] ?? '';

                    // Image Path Logic
                    $image = 'shop_admin/uploads/no-image.png';
                    if (!empty($img_raw)) {
                        $img_name = basename($img_raw);
                        if ($source === 'products' || $source === 'collections') {
                            $image = 'shop_admin/uploads/' . $img_name;
                        } else if ($source === 'bumper_offers') {
                            $image = 'uploads/offers/' . $img_raw;
                        } else {
                            $image = (strpos($img_raw, 'shop_admin/uploads/') !== false) ? $img_raw : 'shop_admin/uploads/subshop/' . $img_name;
                        }
                    }

                    // Link Logic
                    if ($source === 'all_category') $link = "subshop1.php?category_id=$pid";
                    elseif ($source === 'collections') $link = "collections.php?collection_id=$pid";
                    elseif ($source === 'bumper_offers') $link = "subshop1.php?subcategory_id=" . ($row['bo_sub_category_id'] ?? 0);
                    else $link = "drt.php?product_id=$pid";
        ?>
                    <div class="gallery-item-wrapper" id="wishlist-item-<?= $pid ?>">
                        <div class="product-gallery-card">
                            <button class="pg-remove-btn" onclick="toggleWishlist(<?= $pid ?>, this, true)" title="Remove from Gallery">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="pg-img-container">
                                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>" onerror="this.src='shop_admin/uploads/no-image.png'">
                            </div>
                            <div class="pg-content">
                                <div class="pg-meta">
                                    <span class="pg-tag">Exclusive Collection</span>
                                    <h5 class="pg-title"><?= htmlspecialchars($name) ?></h5>
                                </div>
                                <?php if ($source === 'bumper_offers' || $price <= 0): ?>
                                    <div class="pg-price-box">
                                        <span class="pg-price special-offer-price" style="font-size: 1rem; color: var(--gold);">SPECIAL OFFER</span>
                                    </div>
                                <?php else: ?>
                                    <div class="pg-price-box">
                                        <span class="pg-price"><?= number_format($price) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div style="display: flex; gap: 10px;">
                                    <a href="<?= $link ?>" class="pg-action-btn" style="flex: 1; text-align: center; padding: 10px 5px; font-size: 0.75rem;">View</a>
                                    <?php if ($source === 'products' || $source === 'subcategories'): ?>
                                        <button onclick='addToCartFromWishlist(<?= $pid ?>, <?= htmlspecialchars(json_encode($name), ENT_QUOTES, "UTF-8") ?>, <?= $price ?>, <?= htmlspecialchars(json_encode($image), ENT_QUOTES, "UTF-8") ?>, <?= htmlspecialchars(json_encode($sku), ENT_QUOTES, "UTF-8") ?>)' class="pg-action-btn" style="flex: 1; text-align: center; padding: 12px 5px; font-size: 0.75rem; background: #fff; color: #000; box-shadow: 0 4px 15px rgba(255,255,255,0.2); border: none;">Add to Cart</button>
                                    <?php endif; ?>
                                </div>                            </div>
                        </div>
                    </div>
        <?php
                }
                echo '</div>';
            } else {
                echo '<div class="pg-empty">
                    <i class="far fa-heart"></i>
                    <h3>Gallery is Empty</h3>
                    <p class="text-muted">Start building your collection of favorites today.</p>
                    <a href="index.php" class="btn-gold-outline">Explore Shop</a>
                  </div>';
            }
            $stmt->close();
        }
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Wishlist Logic -->
    <script>
        function toggleWishlist(productId, btn, isWishlistPage = false) {
            $.ajax({
                url: 'toggle_wishlist.php',
                method: 'POST',
                data: {
                    product_id: productId
                },
                success: function(res) {
                    if (res.success) {
                        // Update Navbar Badge
                        const countEl = document.getElementById('wishlist-count');
                        if (countEl && res.count !== undefined) {
                            countEl.textContent = res.count;
                        }

                        if (isWishlistPage && res.action === 'removed') {
                            $('#wishlist-item-' + productId).fadeOut(500, function() {
                                $(this).remove();
                                if ($('.product-gallery-card').length === 0) location.reload();
                            });
                        }

                        Swal.fire({
                            icon: res.action === 'added' ? 'success' : 'info',
                            title: res.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            background: '#111',
                            color: '#fff',
                            iconColor: res.action === 'added' ? '#d4af37' : '#888'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sync Error',
                        text: 'Please try again.',
                        background: '#111',
                        color: '#fff'
                    });
                }
            });
        }

        function addToCartFromWishlist(productId, name, price, image, sku) {
            let formData = new FormData();
            formData.append('add_to_cart', '1');
            formData.append('product_id', productId);
            formData.append('product_size', ''); 
            formData.append('product_quantity', '1');
            formData.append('product_image', image);
            formData.append('product_name', name);
            formData.append('product_price', price);
            formData.append('sku_no', sku);

            Swal.fire({
                title: 'Adding to Cart...',
                didOpen: () => { Swal.showLoading() },
                allowOutsideClick: false,
                background: '#111',
                color: '#fff'
            });

            fetch('cart.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    window.location.href = 'cart.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Add to Cart Failed',
                        text: 'Could not add product to cart. Please try again.',
                        background: '#111',
                        color: '#fff'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Please check your connection and try again.',
                    background: '#111',
                    color: '#fff'
                });
            });
        }
    </script>
</body>

</html>