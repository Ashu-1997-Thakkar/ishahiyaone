<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (!isset($_GET['id'])) {
    die("❌ Invalid Request");
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM vouchers WHERE id=$id");
$voucher = mysqli_fetch_assoc($result);

if (!$voucher) {
    die("❌ Voucher not found.");
}

// ✅ Handle update on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type       = mysqli_real_escape_string($conn, $_POST['type']);
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];
    $status     = $_POST['status'];
    $updated_at = date("Y-m-d H:i:s");

    $imageName = $voucher['image']; // default old image

    // ✅ Check if new image uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $newImage = time() . '_' . basename($_FILES['image']['name']);
        $uploadDir = __DIR__ . "/../uploads/";
        $uploadPath = $uploadDir . $newImage;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // ✅ Delete old image
            $oldPath = $uploadDir . $voucher['image'];
            if (!empty($voucher['image']) && file_exists($oldPath)) {
                unlink($oldPath);
            }
            $imageName = $newImage;
        }
    }

    // ✅ Update DB
    $sql = "UPDATE vouchers 
            SET image='$imageName', start_date='$start_date', end_date='$end_date', 
                status='$status', `Type`='$type', updated_at='$updated_at'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: showFestivalDhamaka.php?success=1");
        exit;
    } else {
        die("❌ Update Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Voucher</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../../image/logo/ishahiya-logo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../image/logo/ishahiya-logo.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../../apple-touch-icon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Edit Voucher</h4>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <?php if ($voucher['image']) { ?>
        <img src="../uploads/<?= htmlspecialchars($voucher['image']); ?>" width="100" class="rounded mb-2">
      <?php } else { echo "No Image"; } ?>
      <input type="file" class="form-control" name="image">
    </div>

    <div class="mb-3">
      <label class="form-label">Type</label>
      <select class="form-select" name="type" required>
        <option value="Festival" <?= $voucher['Type']=="Festival"?"selected":"" ?>>Festival</option>
        <option value="Discount" <?= $voucher['Type']=="Discount"?"selected":"" ?>>Discount</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Start Date</label>
      <input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($voucher['start_date']); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">End Date</label>
      <input type="date" class="form-control" name="end_date" value="<?= htmlspecialchars($voucher['end_date']); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <select class="form-select" name="status" required>
        <option value="Active" <?= $voucher['status']=="Active"?"selected":"" ?>>Active</option>
        <option value="Inactive" <?= $voucher['status']=="Inactive"?"selected":"" ?>>Inactive</option>
      </select>
    </div
