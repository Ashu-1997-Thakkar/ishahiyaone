<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// Fetch products to show in dropdown
$stmt = $conn->prepare("SELECT id, name FROM subcategory");
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Upload Additional Images for Product</h2>

<form action="../controller/updateProductWithImages.php" method="POST" enctype="multipart/form-data">


  <label for="product_id">Select Product:</label>
  <select name="product_id" required>
    <option value="" disabled selected>Select Product</option>
    <?php while ($row = $result->fetch_assoc()): ?>
      <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
    <?php endwhile; ?>
  </select>

  <br><br>

  <label for="images[]">Upload Additional Images:</label>
  <input type="file" name="images[]" multiple required accept="image/*" />

  <br><br>
  <button type="submit">Upload Images</button>
</form>
