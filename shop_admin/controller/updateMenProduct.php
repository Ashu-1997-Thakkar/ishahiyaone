<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if (isset($_POST['updateProduct'])) {
  $id = $_POST['product_id'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $rating = $_POST['rating'];

  if ($_FILES['image']['name']) {
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/" . $image);
  } else {
    $image = $_POST['old_image'];
  }

  $sql = "UPDATE products 
          SET name='$name', description='$description', price='$price', rating='$rating', image='$image'
          WHERE product_id = $id";

  if (mysqli_query($conn, $sql)) {
    header("Location: ../adminView/viewMenProducts.php?updated=1");
  } else {
    echo "Update failed: " . mysqli_error($conn);
  }
}
?>
