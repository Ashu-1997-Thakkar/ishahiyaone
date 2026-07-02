<?php
session_start();
include 'shop_admin/config/dbconnect.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header("Location: index.php");
    exit;
}

// --- Fetch product safely from any of the 3 tables ---
$product = null;

// 1. Try products table
$sql = "SELECT p.*, 'products' AS table_type,
               sub.id AS subcat_id, sub.sub_category_name AS subcat_name, mc.id AS mc_id, mc.main_category_name AS cat_name
        FROM products p
        LEFT JOIN sub_category sub ON p.sub_category_id = sub.id
        LEFT JOIN main_category mc ON sub.main_category_id = mc.id
        WHERE p.product_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    // 2. Try subcategories
    $sql = "SELECT sc.*, 'subcategories' AS table_type,
                   sub.id AS subcat_id, sub.sub_category_name AS subcat_name, mc.id AS mc_id, mc.main_category_name AS cat_name
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
    // 3. Try all_category
    $sql = "SELECT ac.*, 'all_category' AS table_type,
                   sub.id AS subcat_id, sub.sub_category_name AS subcat_name, mc.id AS mc_id, mc.main_category_name AS cat_name
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
    die("<h2 class='text-center mt-5'>Product not found</h2>");
}

// Map dynamic column names across tables
$product['name'] = $product['name'] ?? $product['product_name'] ?? 'Product Name';
$product['brand'] = $product['brand'] ?? 'IshaHiya';
$product['price'] = $product['price'] ?? 0;
$product['description'] = $product['description'] ?? '';
$product['stock'] = $product['stock'] ?? $product['quantity'] ?? 10;
$table_type = $product['table_type'] ?? 'products';

// Prepare images
$images = [];
$imgFields = [
    $product['image'] ?? $product['image1'] ?? $product['Image1'] ?? null,
    $product['image2'] ?? $product['Image2'] ?? null,
    $product['image3'] ?? $product['Image3'] ?? null,
    $product['image4'] ?? $product['Image4'] ?? null,
];

foreach ($imgFields as $imgVal) {
    if (!empty($imgVal)) {
        if (strpos($imgVal, 'shop_admin/uploads/') !== false) {
            $images[] = $imgVal;
        } else {
            if ($table_type === 'products') {
                $images[] = 'shop_admin/uploads/' . basename($imgVal);
            } else {
                $images[] = 'shop_admin/uploads/subshop/' . basename($imgVal);
            }
        }
    }
}

if (empty($images)) {
    $images[] = 'shop_admin/uploads/no-image.png';
}

$mainImage = $images[0];
$price = (float)$product['price'];
$fakeMrp = $price * 1.3; // Simulate a 30% markup for MRP if no discount field exists
$discountPercent = 30;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - IshaHiyaOne</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Breadcrumb */
        .pdp-breadcrumb { font-size: 13px; color: #666; margin-bottom: 20px; }
        .pdp-breadcrumb a { color: #007bff; text-decoration: none; }
        .pdp-breadcrumb a:hover { text-decoration: underline; }
        
        /* Gallery */
        .main-img-container {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-img-container img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
            cursor: zoom-in;
        }
        .main-img-container:hover img {
            transform: scale(1.5);
        }
        .thumb-grid {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .thumb-item {
            width: 70px;
            height: 70px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            padding: 5px;
            background: #fff;
            transition: all 0.2s;
        }
        .thumb-item.active { border: 2px solid #c59d2f; box-shadow: 0 0 5px rgba(197,157,47,0.5); }
        .thumb-item img { width: 100%; height: 100%; object-fit: contain; }
        
        /* Product Details */
        .pdp-brand { color: #007185; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .pdp-title { font-size: 24px; font-weight: 700; color: #111; line-height: 1.3; margin: 8px 0; }
        .pdp-rating { display: flex; align-items: center; font-size: 14px; margin-bottom: 15px; }
        .pdp-rating .fa-star { color: #fadb14; }
        .pdp-rating-text { color: #007185; margin-left: 8px; cursor: pointer; }
        .pdp-rating-text:hover { color: #c59d2f; text-decoration: underline; }
        
        .pdp-price-block { margin: 20px 0; padding: 15px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
        .pdp-discount { color: #cc0c39; font-size: 24px; font-weight: 300; margin-right: 10px; }
        .pdp-price { font-size: 32px; font-weight: 600; color: #111; }
        .pdp-mrp { color: #565959; font-size: 14px; text-decoration: line-through; margin-left: 10px; }
        
        .pdp-stock-status { font-size: 18px; font-weight: 600; margin-bottom: 15px; }
        .in-stock { color: #007600; }
        .out-stock { color: #cc0c39; }
        
        /* Action Buttons */
        .btn-add-cart { background: #ffd814; border-color: #fcd200; color: #111; border-radius: 50px; font-weight: 600; padding: 12px 0; width: 100%; margin-bottom: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-add-cart:hover { background: #f7ca00; }
        .btn-buy-now { background: #ffa41c; border-color: #ff8f00; color: #111; border-radius: 50px; font-weight: 600; padding: 12px 0; width: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-buy-now:hover { background: #fa8900; }
        
        .specs-table { font-size: 14px; width: 100%; margin-top: 20px; }
        .specs-table th { color: #0f1111; font-weight: 700; width: 35%; padding: 8px 0; border-bottom: 1px solid #eee; }
        .specs-table td { color: #565959; padding: 8px 0; border-bottom: 1px solid #eee; }
        
        /* Description Container */
        .pdp-description-box { background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #eaeaea; margin-top: 40px; }
        .pdp-description-box h3 { font-size: 20px; font-weight: 700; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Related Products */
        .related-card { border: 1px solid #eaeaea; border-radius: 8px; padding: 15px; text-align: center; background: #fff; text-decoration: none; display: block; transition: box-shadow 0.3s; }
        .related-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-decoration: none; }
        .related-card img { height: 150px; object-fit: contain; margin-bottom: 10px; width: 100%; }
        .related-card .rtitle { color: #007185; font-size: 14px; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .related-card .rprice { color: #B12704; font-size: 16px; font-weight: 700; margin-top: 5px; }
    </style>
</head>
<body>

<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>

<div class="container mt-4 mb-5">
    
    <!-- Breadcrumb -->
    <div class="pdp-breadcrumb">
        <a href="index.php">Home</a> &rsaquo; 
        <a href="shop.php?category=<?= $product['mc_id'] ?>"><?= htmlspecialchars($product['cat_name'] ?? 'Category') ?></a> &rsaquo; 
        <a href="subshop1.php?subcategory_id=<?= $product['subcat_id'] ?>"><?= htmlspecialchars($product['subcat_name'] ?? 'Subcategory') ?></a> &rsaquo; 
        <span class="text-dark font-weight-bold"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="row">
        <!-- Left Column: Gallery -->
        <div class="col-lg-5 mb-4">
            <div class="main-img-container" id="img-zoom-container">
                <img src="<?= htmlspecialchars($mainImage) ?>" id="main-product-image" alt="Product Image">
            </div>
            <div class="thumb-grid">
                <?php foreach($images as $idx => $img): ?>
                <div class="thumb-item <?= $idx === 0 ? 'active' : '' ?>" onclick="changeImage('<?= htmlspecialchars($img) ?>', this)">
                    <img src="<?= htmlspecialchars($img) ?>" alt="Thumbnail">
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="col-lg-7">
            <div class="pdp-brand"><?= htmlspecialchars($product['brand'] ?? 'IshaHiya') ?></div>
            <h1 class="pdp-title"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="pdp-rating">
                <?php
                $rating = (int)($product['rating'] ?? 5);
                for($i=0; $i<5; $i++) {
                    echo $i < $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }
                ?>
                <i class="fas fa-chevron-down ml-1 text-muted" style="font-size:10px"></i>
                <span class="pdp-rating-text">1,248 ratings | 84 answered questions</span>
            </div>
            
            <div class="pdp-price-block">
                <div>
                    <span class="pdp-discount">-<?= $discountPercent ?>%</span>
                    <span class="pdp-price">&#8377;<?= number_format($price, 2) ?></span>
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    M.R.P.: <span class="pdp-mrp">&#8377;<?= number_format($fakeMrp, 2) ?></span>
                </div>
                <div class="mt-2 text-success font-weight-bold" style="font-size:14px;">Inclusive of all taxes</div>
            </div>

            <!-- Stock Status -->
            <?php if((int)$product['stock'] > 0): ?>
                <div class="pdp-stock-status in-stock">In stock.</div>
            <?php else: ?>
                <div class="pdp-stock-status out-stock">Out of stock.</div>
            <?php endif; ?>

            <div class="mb-3 text-muted" style="font-size: 14px;">
                <strong>SKU:</strong> <?= htmlspecialchars($product['sku'] ?? 'N/A') ?>
            </div>

            <ul style="font-size: 14px; color: #0f1111; padding-left: 20px; margin-bottom: 25px;">
                <li>Premium quality guaranteed by <?= htmlspecialchars($product['brand'] ?? 'IshaHiya') ?></li>
                <li>10 Days Replacement Policy</li>
                <li>Cash on Delivery available</li>
                <li>Fast shipping across India</li>
            </ul>

            <div class="row mt-4">
                <div class="col-md-6 mb-2">
                    <button class="btn btn-add-cart"><i class="fas fa-shopping-cart mr-2"></i> Add to Cart</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-buy-now"><i class="fas fa-bolt mr-2"></i> Buy Now</button>
                </div>
            </div>
            
            <div class="mt-4">
                <table class="specs-table">
                    <tr><th>Brand</th><td><?= htmlspecialchars($product['brand'] ?? 'N/A') ?></td></tr>
                    <tr><th>Category</th><td><?= htmlspecialchars($product['cat_name'] ?? 'N/A') ?></td></tr>
                    <tr><th>Item Weight</th><td>500 Grams</td></tr>
                    <tr><th>Generic Name</th><td><?= htmlspecialchars($product['subcat_name'] ?? 'Product') ?></td></tr>
                </table>
            </div>

        </div>
    </div>

    <!-- Product Description -->
    <div class="pdp-description-box">
        <h3>Product Description</h3>
        <div style="font-size: 15px; color: #333; line-height: 1.6;">
            <?= !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : 'No detailed description available for this product.' ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php
    $relStmt = $conn->prepare("SELECT product_id, name, price, image FROM products WHERE sub_category_id = ? AND product_id != ? ORDER BY RAND() LIMIT 5");
    $relStmt->bind_param("ii", $product['sub_category_id'], $product_id);
    $relStmt->execute();
    $relRes = $relStmt->get_result();
    if($relRes->num_rows > 0):
    ?>
    <div class="pdp-description-box" style="margin-top: 30px;">
        <h3>Related products from this subcategory</h3>
        <div class="row">
            <?php while($rel = $relRes->fetch_assoc()): ?>
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <a href="product_details.php?id=<?= $rel['product_id'] ?>" class="related-card">
                    <img src="shop_admin/uploads/<?= htmlspecialchars($rel['image'] ?? 'no-image.png') ?>" alt="<?= htmlspecialchars($rel['name']) ?>">
                    <div class="rtitle" title="<?= htmlspecialchars($rel['name']) ?>"><?= htmlspecialchars($rel['name']) ?></div>
                    <div class="rprice">&#8377;<?= number_format($rel['price'], 2) ?></div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
    // Gallery Thumbnails Logic
    function changeImage(src, thumbElement) {
        document.getElementById('main-product-image').src = src;
        
        // Update active border
        let thumbs = document.querySelectorAll('.thumb-item');
        thumbs.forEach(t => t.classList.remove('active'));
        thumbElement.classList.add('active');
    }

    // Simple Zoom Effect on Mouse Move
    const container = document.getElementById('img-zoom-container');
    const img = document.getElementById('main-product-image');

    container.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = container.getBoundingClientRect();
        const x = (e.clientX - left) / width * 100;
        const y = (e.clientY - top) / height * 100;
        
        img.style.transformOrigin = `${x}% ${y}%`;
    });

    container.addEventListener('mouseleave', () => {
        img.style.transformOrigin = 'center center';
    });
</script>

<?php include 'includes/footer.php'; // Optional: include your main site footer ?>
</body>
</html>
