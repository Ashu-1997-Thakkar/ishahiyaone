<?php
session_start();
require_once __DIR__ . '/db.php'; // ✅ Standardized DB connection

// Retrieve product id from URL
$product_id = isset($_GET['category']) ? (int) $_GET['category'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections | Shop Related Products — Ishahiya</title>
    <meta name="description" content="Browse related products and collections at IshahiyaOne. Explore curated fashion picks including ethnic wear, dresses and festival outfits with fast delivery across India.">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/banner.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 40px;
            justify-content: flex-start; /* Align items to the left */
        }
        .product-card {
            width: 220px; /* Card width */
            text-align: center;
            flex: 0 1 auto; /* Allow cards to grow or shrink, but don't stretch them to fill the available space */
            margin-bottom: 30px; /* Space below the cards */
        }
        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-title { margin-top: 10px; font-weight: bold; font-size: 14px; }
        .product-price { color: orange; font-size: 16px; }
        .back-btn { margin: 20px auto; display: block; background: #555; text-decoration: none; color: white; text-align: center; width: fit-content; padding: 10px 20px; }

        /* Zoom Effect */
        .single-pro-image img {
            transition: transform 0.3s ease;
            cursor: zoom-in;
        }

        .single-pro-image:hover img {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
<style>
    .product-card img {
  width: 220px;
  height: 300px;
  object-fit: cover;
  border-radius: 6px;
}

</style>
<?php include 'includes/header_nav.php'; ?>

<style>
/* Grid Layout */
.product-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 24px;
    padding: 20px;
}

/* Product Card */
.product-card {
    width: 260px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    background-color: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Product Image */
.product-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

/* Title & Price */
.product-title {
    font-size: 16px;
    font-weight: 600;
    margin: 12px 10px 6px 10px;
    color: #333;
    text-align: left;
}

.product-price {
    font-size: 16px;
    color: #e74c3c;
    font-weight: bold;
    margin: 0 10px 10px 10px;
    text-align: left;
}

/* Dropdown */
.size-dropdown {
    padding: 0 10px;
    margin-bottom: 10px;
}

.size-dropdown select {
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Add to Cart Button - Circular Simple Icon */
.add-to-cart-btn {
    background-color: transparent;
    border: 1px solid #00b894;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin: 0 auto 15px;
}

.add-to-cart-btn img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}
</style>

<?php
// Retrieve collection id from URL
$collection_id = isset($_GET['collection_id']) ? (int) $_GET['collection_id'] : (isset($_GET['category']) ? (int)$_GET['category'] : 0);

// Fetch Collection Name
$coll_name = "Collections";
if ($collection_id > 0) {
    $stmt = $conn->prepare("SELECT product_name FROM collections WHERE id = ?");
    $stmt->bind_param("i", $collection_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $coll_name = $row['product_name'];
    }
}
?>

<h2 style="text-align: center; font-size: 28px; font-weight: 700; margin-top: 30px; margin-bottom: 10px; color: #2c3e50;">
    <?= htmlspecialchars($coll_name) ?>
</h2>

<?php if ($collection_id > 0) { ?>
<div class="product-grid">
<?php
$sql = "SELECT * FROM subcategory WHERE collection_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $collection_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $id = $row['id'];
        $product_name = $row['subcategory_name'] ?? 'No Name';
        $brand = $row['brand'] ?? '';
        $price = isset($row['price']) ? (float)$row['price'] : 0;
        
        // Image path - subcategory table stores paths like "uploads/subcategories/filename.jpg"
        $imagePath = !empty($row['image1']) ? "shop_admin/" . $row['image1'] : 'uploads/no-image.png';
?>
    <div class="product-card">
        <a href="drt.php?product_id=<?= $id ?>">
            <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($product_name) ?>" style="width:100%; height:300px; object-fit:cover;" onerror="this.src='uploads/no-image.png';">
        </a>

        <div class="product-title"><?= htmlspecialchars($product_name) ?></div>
        <div class="product-brand"><?= htmlspecialchars($brand) ?></div>
        <div class="product-price">₹<?= number_format($price, 2) ?></div>

        <form method="POST" action="cart.php">
            <input type="hidden" name="add_to_cart" value="1">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product_name) ?>">
            <input type="hidden" name="product_price" value="<?= $price ?>">
            <input type="hidden" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?= $imagePath ?>">

            <div class="size-dropdown">
                <select name="product_size" required>
                    <option value="">Select Size</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>

            <button type="submit" class="add-to-cart-btn">
                <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Cart" style="width:24px;">
            </button>
        </form>
    </div>
<?php
        endwhile;
    else:
        echo "<p style='text-align:center;'>No products found in this collection.</p>";
    endif;
} else {
    echo "<p style='text-align:center;'>No collection selected.</p>";
}
?>
</div>



<?php include 'includes/footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
