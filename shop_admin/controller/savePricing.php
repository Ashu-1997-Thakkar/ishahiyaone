<?php
include dirname(__DIR__) . "/config/dbconnect.php";

$main_category = mysqli_real_escape_string($conn, $_POST['main_category']);
$subcategory   = mysqli_real_escape_string($conn, $_POST['subcategory']);
$package       = mysqli_real_escape_string($conn, $_POST['package']);

$promotional_price = $_POST['promotional_price'] ?: 0;
$promotional_paise = $_POST['promotional_paise'] ?: 0;

$transactional_price = $_POST['transactional_price'] ?: 0;
$transactional_paise = $_POST['transactional_paise'] ?: 0;

$voice_price = $_POST['voice_price'] ?: 0;
$voice_paise = $_POST['voice_paise'] ?: 0;

$sql = "INSERT INTO pricing_table 
(main_category, subcategory, package, promotional_price, promotional_paise,
 transactional_price, transactional_paise, voice_price, voice_paise)
VALUES 
('$main_category', '$subcategory', '$package', '$promotional_price', '$promotional_paise',
 '$transactional_price', '$transactional_paise', '$voice_price', '$voice_paise')";

if(mysqli_query($conn, $sql)) {
    header("Location: ../adminView/pricing-status.php?success=1");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
