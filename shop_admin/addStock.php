<?php
include_once __DIR__ . "/config/dbconnect.php";


// Insert stock if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $size_id = $_POST['size_id'];
    $stock_quantity = $_POST['stock_quantity'];

    // Check if variation exists
    $check_sql = "SELECT * FROM product_size_variation WHERE product_id = ? AND size_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $product_id, $size_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update stock
        $update_sql = "UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock + ? WHERE product_id = ? AND size_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $stock_quantity, $product_id, $size_id);
        $update_stmt->execute();
        $message = "Stock updated successfully.";
    } else {
        // Insert new variation
        $insert_sql = "INSERT INTO product_size_variation (product_id, size_id, quantity_in_stock) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $product_id, $size_id, $stock_quantity);
        $insert_stmt->execute();
        $message = "New stock added successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Stock</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/logo/ishahiya-logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Stock</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Product</label>
            <select name="product_id" class="form-control" required>
                <option value="">Select Product</option>
                <?php
                $product_sql = "SELECT product_id, name FROM products";
                $products = $conn->query($product_sql);
                while ($product = $products->fetch_assoc()) {
                    echo "<option value='{$product['product_id']}'>{$product['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Size</label>
            <select name="size_id" class="form-control" required>
                <option value="">Select Size</option>
                <?php
                $size_sql = "SELECT size_id, size_name FROM sizes";
                $sizes = $conn->query($size_sql);
                while ($size = $sizes->fetch_assoc()) {
                    echo "<option value='{$size['size_id']}'>{$size['size_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock_quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Stock</button>
        <a href="viewProductSizes.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
