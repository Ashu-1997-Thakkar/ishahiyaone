<?php
include_once "../config/dbconnect.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['is_bumper_offer']) && isset($_POST['source'])) {
    $product_id = (int)$_POST['product_id'];
    $is_bumper = (int)$_POST['is_bumper_offer'];
    $source = $_POST['source'];
    
    $allowed_sources = ['products', 'all_category', 'subcategories'];
    if (!in_array($source, $allowed_sources)) {
        echo "invalid source";
        exit;
    }
    
    $id_field = ($source === 'products') ? 'product_id' : 'id';
    
    $conn->begin_transaction();
    try {
        $stmt1 = $conn->prepare("UPDATE `$source` SET is_bumper_offer = ? WHERE `$id_field` = ?");
        $stmt1->bind_param("ii", $is_bumper, $product_id);
        $stmt1->execute();
        
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
