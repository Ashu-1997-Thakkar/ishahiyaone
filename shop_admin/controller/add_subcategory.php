<?php
include_once "../shop_admin/config/dbconnect.php";
/** @var mysqli $conn */
?>

<form action="controller/add_subcategorygt.php" method="POST" enctype="multipart/form-data">
  <label>Choose Collection:</label>
  <select name="collection_id" required>
    <option value="">-- Select Collection --</option>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM collections");
    while ($row = mysqli_fetch_assoc($res)) {
      echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
    ?>
  </select>

  <label>Subcategory Name:</label>
  <input type="text" name="subcategory_name" required>

  <label>Created By:</label>
  <input type="text" name="name" required>

  <label>Price:</label>
  <input type="number" name="price" required>

  <label>Image:</label>
  <input type="file" name="image_path" required>

  <button type="submit" name="add_subcategory">Add Subcategory</button>
</form>
