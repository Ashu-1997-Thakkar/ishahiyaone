<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Ishahiya</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/banner.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>


    <?php
// Include database connection
include('db.php');

// Get the product_id from the URL
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch product details + main category via sub_category join
    $sql = "SELECT p.*, sc.main_category_id
            FROM products p
            LEFT JOIN sub_category sc ON p.sub_category_id = sc.id
            WHERE p.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name    = $row['name'];
        $brand           = $row['brand'];
        $price           = $row['price'];
        $image           = $row['image'];
        $description     = $row['description'];
        $main_cat_id     = (int)($row['main_category_id'] ?? 0);
        // Fashion main_category_id = 7 (AllGen Fashion Wear)
        $is_fashion      = ($main_cat_id === 7);
    } else {
        echo "<p>Product not found!</p>";
        exit;
    }
} else {
    echo "<p>No product selected!</p>";
    exit;
}

// Close the connection
$conn->close();
?>

  <section id="prodetails" class="section-p1">
    <div class="single-pro-image">
        <img src="./admin/uploads/<?php echo htmlspecialchars($image); ?>" width="100%" id="MainImg" alt="<?php echo htmlspecialchars($product_name); ?>" />
        <div class="small-img-group">
            <div class="small-img-col">
                <img src="./admin/uploads/<?php echo htmlspecialchars($image); ?>" width="100%" class="small-img" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>
            <div class="small-img-col">
                <img src="./admin/uploads/<?php echo htmlspecialchars($image); ?>" width="100%" class="small-img" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>
            <div class="small-img-col">
                <img src="./admin/uploads/<?php echo htmlspecialchars($image); ?>" width="100%" class="small-img" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>
            <div class="small-img-col">
                <img src="./admin/uploads/<?php echo htmlspecialchars($image); ?>" width="100%" class="small-img" alt="<?php echo htmlspecialchars($product_name); ?>" />
            </div>
        </div>
    </div>

    <div class="single-pro-details">
        <h6><?php echo htmlspecialchars($brand); ?> / <?php echo htmlspecialchars($product_name); ?></h6>
        <h4><?php echo htmlspecialchars($product_name); ?></h4>
        <h2>₹<?php echo number_format($price, 2); ?></h2>

  <form id="addToCartForm">
    <input type="hidden" name="add_to_cart" value="1">
    <input type="hidden" name="product_id" value="...">
    <input type="hidden" name="product_name" value="...">
    <input type="hidden" name="product_price" value="..."> <!-- ✅ Correct name -->
    <input type="hidden" name="product_image" value="..."> <!-- ✅ Correct name -->

    
<?php if ($is_fashion): ?>
<label for="size">Select Size</label>
<select name="product_size" id="size" required>
    <option value="" disabled selected>Select Size</option>
    <option value="XS">XS</option>
    <option value="S">Small</option>
    <option value="M">Medium</option>
    <option value="L">Large</option>
    <option value="XL">XL</option>
    <option value="XXL">XXL</option>
</select>
<?php else: ?>
<!-- Non-fashion product: no size selector needed -->
<input type="hidden" name="product_size" value="N/A">
<?php endif; ?>

    <!-- Quantity selection -->
    <label for="quantity">Quantity</label>
    <input type="number" name="product_quantity" id="quantity" value="1" min="1" required />

    <!-- Add to Cart button -->
    <button type="submit" class="normal">Add To Cart</button>
</form>


        <h4>Product Details</h4>
        <span><?php echo htmlspecialchars($description); ?></span>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

  

    <script>
      var MainImg = document.getElementById("MainImg");
      var smallimg = document.getElementsByClassName("small-img");

      smallimg[0].onclick = function () {
        MainImg.src = smallimg[0].src;
      };

      smallimg[1].onclick = function () {
        MainImg.src = smallimg[1].src;
      };

      smallimg[2].onclick = function () {
        MainImg.src = smallimg[2].src;
      };

      smallimg[3].onclick = function () {
        MainImg.src = smallimg[3].src;
      };
    </script>
	
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                text: 'The product has been added to your cart.',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response,
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Request Failed',
            text: 'Something went wrong!',
        });
        console.error('Error:', error);
    });
});
</script>

    <script src="script.js"></script>
  </body>
</html>
