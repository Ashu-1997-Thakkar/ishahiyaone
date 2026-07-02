<?php
include(dirname(__DIR__) . "/config/dbconnect.php");

// Fetch all product-size variations with stock
$sql = "SELECT 
          v.*, 
          p.name AS product_name, 
          s.size_name, 
          c.name AS category_name
        FROM product_size_variation v
        JOIN products p ON p.product_id = v.product_id
        JOIN sizes s ON s.size_id = v.size_id
        JOIN all_category c ON c.id = p.category_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Sizes Availability</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .badge-success { background-color: #28a745; }
    .badge-danger { background-color: #dc3545; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Product Stock Availability</h2>


  <!-- ✅ Add New Stock Button (general, no product_id) -->
  <a href="addStock.php" class="btn btn-success mb-3">Add New Stock</a>


  <table class="table table-bordered table-hover">
    <thead style="background-color: #c59d2f; color: white;">
      <tr>
        <th>S.N.</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Size</th>
        <th>Stock Quantity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $count = 1;
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
      ?>
      <tr>
        <td><?= $count++ ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td><?= htmlspecialchars($row['size_name']) ?></td>
        <td>
          <?php 
            if ($row['quantity_in_stock'] > 0) {
              echo "<span class='badge badge-success'>{$row['quantity_in_stock']} in stock</span>";
            } else {
              echo "<span class='badge badge-danger'>Out of stock</span>";
            }
          ?>
        </td>
        <td>
          <button onclick="variationEditForm('<?= $row['variation_id'] ?>')" class="btn btn-sm btn-primary">Edit</button>
          <button onclick="variationDelete('<?= $row['variation_id'] ?>')" class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <?php
          }
        } else {
          echo "<tr><td colspan='6' class='text-center text-muted'>No product size variations found.</td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<script>
function variationEditForm(id) {
  window.location.href = 'updateVariationController.php?id=' + id;
}

function variationDelete(id) {
  if (confirm("Are you sure you want to delete this variation?")) {
    window.location.href = 'deleteVariationController.php?id=' + id;
  }
}
</script>
</body>
</html>
