<?php
include '../adminHeader.php';
include_once dirname(__DIR__) . "/config/dbconnect.php";

// Get men category id
$catQuery = mysqli_query($conn, "SELECT category_id FROM category WHERE category_name = 'Men' LIMIT 1");
$catRow = mysqli_fetch_assoc($catQuery);
$category_id = $catRow ? (int)$catRow['category_id'] : 0;

// Fetch products for Men
$productQuery = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $category_id ORDER BY id DESC");
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Men Products</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">+ Add Product</button>
  </div>

  <table class="table table-bordered table-hover">
    <thead style="background-color: #c59d2f; color: white;">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Price</th>
        <th>Image</th>
        <th width="150">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($productQuery)): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['brand']) ?></td>
          <td>₹<?= number_format($row['price'], 2) ?></td>
          <td>
            <?php if ($row['image']): ?>
              <img src="../uploads/<?= $row['image'] ?>" height="50">
            <?php endif; ?>
          </td>
          <td>
            <a href="../editItemForm.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="../delete_product.php?id=<?= $row['id'] ?>&redirect=adminView/men.php" onclick="return confirm('Delete this product?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" action="../add_men_product.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Men Product</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="category_id" value="<?= $category_id ?>">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" required class="form-control">
        </div>
        <div class="form-group">
          <label>Brand</label>
          <input type="text" name="brand" class="form-control">
        </div>
        <div class="form-group">
          <label>Price</label>
          <input type="number" name="price" step="0.01" required class="form-control">
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label>Image</label>
          <input type="file" name="image" class="form-control-file">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Add Product</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
