<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Product ID not provided.");
}

$id = intval($_GET['id']);  // Safely convert to integer

$sql = "SELECT * FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die("❌ Product not found.");
}

$product = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../../image/logo/ishahiya-logo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../image/logo/ishahiya-logo.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../../apple-touch-icon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Edit Men Product</h4>

  <form method="POST" action="../controller/updateMenProduct.php" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
    <input type="hidden" name="old_image" value="<?= $product['image'] ?>">

    <div class="mb-3">
      <label>Product Name</label>
      <input type="text" name="name" value="<?= $product['name'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control"><?= $product['description'] ?></textarea>
    </div>

    <div class="mb-3">
      <label>Price</label>
      <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Rating</label>
      <input type="number" name="rating" value="<?= $product['rating'] ?>" step="0.1" max="5" class="form-control">
    </div>

    <div class="mb-3">
      <label>Image</label><br>
      <img src="../uploads/<?= $product['image'] ?>" width="100"><br>
      <input type="file" name="image" class="form-control mt-2">
    </div>

    <button type="submit" name="updateProduct" class="btn btn-primary">Update</button>
    <a href="viewMenProducts.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
