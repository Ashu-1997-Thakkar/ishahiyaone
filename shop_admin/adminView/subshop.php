<?php
include_once "./shop_admin/config/dbconnect.php";
/** @var mysqli $conn */

// Get category_id from the URL
$category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subcategories</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../../image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../image/logo/ishahiya-logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../apple-touch-icon.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Styling same as your original for brevity */
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            padding: 20px;
        }
        .product-card {
            width: 260px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            background-color: #fff;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .product-title, .product-price {
            padding: 10px;
            text-align: left;
        }
        .product-price {
            color: #e74c3c;
            font-weight: bold;
        }
        .size-dropdown select {
            width: 100%;
            padding: 8px;
        }
        .add-to-cart-btn {
            background-color: transparent;
            border: 1px solid #00b894;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
        }
    </style>
</head>
<body>

<h2 style="text-align: center; margin-top: 30px;">Subcategory Products</h2>

<div class="product-grid">
<?php
if ($category_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $imagePath = !empty($row['image1']) ? "shop_admin/" . htmlspecialchars($row['image1']) : '';
?>
    <div class="product-card">
        <a href="product-detail.php?product_id=<?= $row['id'] ?>">
            <?php if ($imagePath): ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <?php else: ?>
                <div style="width:100%; height:300px; background:#f0f0f0;">No Image</div>
            <?php endif; ?>
        </a>
        <div class="product-title"><?= htmlspecialchars($row['name']) ?></div>
        <div class="product-price">₹<?= number_format($row['price'], 2) ?></div>

        <form method="POST" action="cart.php">
            <input type="hidden" name="add_to_cart" value="1">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
            <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
            <input type="hidden" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image1']) ?>">

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
                <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Cart" width="20">
            </button>
        </form>
    </div>
<?php
        endwhile;
    else:
        echo "<p style='text-align:center;'>No products found in this category.</p>";
    endif;
} else {
    echo "<p style='text-align:center;'>Invalid category selected.</p>";
}
?>
</div>

</body>
</html>
