<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$row = [];

if(isset($_GET['id'])){
  $id = (int)$_GET['id'];
  $sql = "SELECT * FROM vouchers WHERE id=$id";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result) ?? [];
}
?>

<input type="hidden" name="id" value="<?= $row['id'] ?>">

<div class="mb-3">
  <label class="form-label">Current Image</label><br>
  <img src="uploads/<?= $row['image']; ?>" width="100" class="mb-2"><br>
  <input type="file" class="form-control" name="image">
</div>
<div class="mb-3">
  <label class="form-label">Type</label>
  <select class="form-select" name="type" required>
    <option value="Festival" <?= $row['Type']=='Festival'?'selected':'' ?>>Festival</option>
    <option value="Discount" <?= $row['Type']=='Discount'?'selected':'' ?>>Discount</option>
  </select>
</div>
<div class="mb-3">
  <label class="form-label">Start Date</label>
  <input type="date" class="form-control" name="start_date" value="<?= $row['start_date'] ?>" required>
</div>
<div class="mb-3">
  <label class="form-label">End Date</label>
  <input type="date" class="form-control" name="end_date" value="<?= $row['end_date'] ?>" required>
</div>
<div class="mb-3">
  <label class="form-label">Status</label>
  <select class="form-select" name="status" required>
    <option value="Active" <?= $row['status']=='Active'?'selected':'' ?>>Active</option>
    <option value="Inactive" <?= $row['status']=='Inactive'?'selected':'' ?>>Inactive</option>
  </select>
</div>
