<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$result = mysqli_query($conn, "SELECT s.*, c.name as collection_name FROM subcategory s JOIN collections c ON s.collection_id = c.id");
?>

<table border="1" cellpadding="10">
  <tr>
    <th>ID</th>
    <th>Collection</th>
    <th>Name</th>
    <th>Image</th>
    <th>Price</th>
    <th>Actions</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['collection_name'] ?></td>
    <td><?= $row['subcategory_name'] ?></td>
    <td><img src="<?= $row['image_path'] ?>" width="60"></td>
    <td>₹ <?= $row['price'] ?></td>
    <td>
      <a href="edit_subcategory.php?id=<?= $row['id'] ?>">Edit</a> |
      <a href="controller/delete_subcategory.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this?')">Delete</a>
    </td>
  </tr>
  <?php } ?>
</table>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
