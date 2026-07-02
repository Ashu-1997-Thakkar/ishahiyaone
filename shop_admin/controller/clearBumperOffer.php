<?php
include_once "../config/dbconnect.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['source'])) {
    $product_id = (int)$_POST['product_id'];
    $source = $_POST['source'];
    
    $allowed_sources = ['products', 'all_category', 'subcategories'];
    if (!in_array($source, $allowed_sources)) {
        echo "invalid source";
        exit;
    }
    
    $id_field = ($source === 'products') ? 'product_id' : 'id';
    
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("UPDATE `$source` SET is_bumper_offer = 0, bumper_start_date = NULL, bumper_end_date = NULL, bumper_discount = NULL, bumper_title = NULL WHERE `$id_field` = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "error: " . $e->getMessage();
    }
} else {
    echo "invalid request";
}
?>
