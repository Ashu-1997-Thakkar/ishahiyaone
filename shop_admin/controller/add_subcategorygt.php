<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['add_subcategory'])) {
  $collection_id = $_POST['collection_id'];
$subcategory_name = $_POST['subcategory_name'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$image = $_FILES['image']['name'];
  // Image Upload
  $image = $_FILES['image_path']['name'];
  $tmp = $_FILES['image_path']['tmp_name'];
  $folder = "../uploads/subcategories/" . $image;
  move_uploaded_file($tmp, $folder);

  $sql = "INSERT INTO subcategory (collection_id, subcategory_name, product_name, price, image)
          VALUES ('$collection_id', '$subcategory_name', '$product_name', '$price', '$image')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Subcategory Added'); window.location.href='../admin-panel-file.php';</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
