<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['addProduct'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $upload_path = "../uploads/" . $image;
    move_uploaded_file($tmp, $upload_path);

    $sql = "INSERT INTO products (name, price, category, image) VALUES ('$name', '$price', '$category', '$image')";
    mysqli_query($conn, $sql);

    header("Location: ../index.php?page=men");
}
