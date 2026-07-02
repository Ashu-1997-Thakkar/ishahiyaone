<?php
// Database connection settings (PDO)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "velvetvougedb";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/banner.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .single-pro-image {
            width: 100%;
            max-width: 500px;
            margin-right: 50px;
            position: relative;
        }
        .small-img-group {
            display: flex;
            justify-content: start;
            gap: 10px;
            margin-top: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            scrollbar-width: none;
        }
        .small-img-group::-webkit-scrollbar { display: none; }
        .small-img-col {
            flex: 0 0 auto;
            width: 80px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .small-img-col img {
            width: 100%;
            object-fit: cover;
            border-radius: 5px;
            transition: 0.3s ease;
        }
        .small-img-col:hover img {
            border: 2px solid #555;
        }
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


    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>

<?php
include('db.php');

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $sql = "SELECT * FROM subcategory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $subcategory_name = $row['subcategory_name'];
        $price = $row['price'];
        $description = $row['description'] ?? 'No description available.';
      $mainImage = $row['image1']; // or whatever column stores the main image

        
        // Collect all image paths in an array
        $images = [];
        for ($i = 2; $i <= 4; $i++) {
            $imageField = 'image' . $i;
            if (!empty($row[$imageField])) {
                $images[] = $row[$imageField];
            }
        }

        // Format the description with line breaks and headers
        // Use regular expression to match each section header like LEHENGA, BLOUSE, DUPATTA
        $formatted_description = preg_replace('/(LEHENGA:|BLOUSE \(CHOLI\):|DUPATTA:)/', '<br><strong>$1</strong>', $description);
        // Convert newline characters to <br> tags for better formatting
        $formatted_description = nl2br($formatted_description);

    } else {
        echo "<p>Product not found!</p>";
        exit;
    }
} else {
    echo "<p>No product selected!</p>";
    exit;
}

$conn->close();
?>
<style>
    
</style>
<section id="prodetails" class="section-p1">
    <div class="single-pro-image">
        <img id="MainImg" src="admin/<?= htmlspecialchars($row['image1']) ?>" alt="<?= htmlspecialchars($row['subcategory_name']) ?>">

        <div class="small-img-group">
            <?php foreach ($images as $imgPath): ?>
                <div class="small-img-col">
                    <img src="./admin/<?php echo htmlspecialchars($imgPath); ?>" width="100%" class="small-img" alt="Thumbnail">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="single-pro-details">
  <h4><?= htmlspecialchars($row['brand']) . '/ ' . htmlspecialchars($row['subcategory_name']) ?></h4>

        <h2>₹<?php echo number_format($price, 2); ?></h2>

   <form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($subcategory_name); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">
    <input type="hidden" name="image" value="<?php echo htmlspecialchars($images[0]); ?>">
    
    <label for="size">Select Size</label>
    <select name="size" id="size" required>
        <option value="" disabled selected>Select Size</option>
        <option value="XS">XS</option>
        <option value="S">Small</option>
        <option value="M">Medium</option>
        <option value="L">Large</option>
        <option value="XL">XL</option>
        <option value="XXL">XXL</option>
    </select>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1" required />
    
    <button type="submit" name="add_to_cart" class="normal">Add To Cart</button>
</form>


        <h4>Product Details</h4>
        <span><?php echo $formatted_description ?></span>
    </div>
</section>

<script>
    const MainImg = document.getElementById("MainImg");
    const smallImgs = document.querySelectorAll(".small-img");
    smallImgs.forEach(img => {
        img.addEventListener("click", () => {
            MainImg.src = img.src;
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
