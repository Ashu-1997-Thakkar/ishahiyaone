<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// Check DB connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Optional: check if product exists
    $check = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$id'");
    if (mysqli_num_rows($check) == 0) {
        die("No product found with ID: $id");
    }

    // Delete the product
    $query = "DELETE FROM products WHERE product_id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to the actual product listing page
        header("Location: ../adminView/viewProducts.php?deleted=1");
        exit();
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
} else {
    echo "No ID provided.";
}
?>
