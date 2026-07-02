<?php
session_start();
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $source = isset($_POST['source']) ? trim($_POST['source']) : '';
    
    $bumper_title = isset($_POST['bumper_title']) ? trim($_POST['bumper_title']) : '';
    $bumper_start_date = isset($_POST['bumper_start_date']) && !empty($_POST['bumper_start_date']) ? $_POST['bumper_start_date'] : null;
    $bumper_end_date = isset($_POST['bumper_end_date']) && !empty($_POST['bumper_end_date']) ? $_POST['bumper_end_date'] : null;
    $bumper_discount = isset($_POST['bumper_discount']) ? intval($_POST['bumper_discount']) : 0;

    if ($product_id <= 0 || empty($source)) {
        echo "Invalid parameters";
        exit;
    }

    $allowed_sources = [
        'products' => 'product_id',
        'subcategories' => 'id',
        'all_category' => 'id'
    ];

    if (!array_key_exists($source, $allowed_sources)) {
        echo "Invalid source table";
        exit;
    }

    $id_col = $allowed_sources[$source];
    
    // Convert to null if empty to avoid invalid datetime, or replace 'T' with space for MySQL compatibility
    if ($bumper_start_date === '') {
        $bumper_start_date = null;
    } else {
        $bumper_start_date = str_replace('T', ' ', $bumper_start_date);
        if (strlen($bumper_start_date) == 16) $bumper_start_date .= ':00'; // Append seconds if missing
    }
    
    if ($bumper_end_date === '') {
        $bumper_end_date = null;
    } else {
        $bumper_end_date = str_replace('T', ' ', $bumper_end_date);
        if (strlen($bumper_end_date) == 16) $bumper_end_date .= ':00'; // Append seconds if missing
    }

    $sql = "UPDATE $source SET 
            bumper_title = ?, 
            bumper_start_date = ?, 
            bumper_end_date = ?, 
            bumper_discount = ? 
            WHERE $id_col = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $bumper_title, $bumper_start_date, $bumper_end_date, $bumper_discount, $product_id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request method";
}
?>
