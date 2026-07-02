<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if (isset($_POST['addProduct'])) {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $rating = $_POST['rating'] ?? 0;

  // Get Men category_id
  $catQuery = mysqli_query($conn, "SELECT category_id FROM category WHERE category_name = 'Men'");
  $catRow = mysqli_fetch_assoc($catQuery);
  $category_id = $catRow['category_id'];

  // Upload image
  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];
  move_uploaded_file($tmp, "../uploads/" . $image);

  $sql = "INSERT INTO products (name, description, price, rating, image, category_id)
          VALUES ('$name', '$description', '$price', '$rating', '$image', '$category_id')";
  
  if (mysqli_query($conn, $sql)) {
    header("Location: ../adminView/viewMenProducts.php?added=1");
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
