<?php
include 'shop_admin/config/dbconnect.php';

$prd_id = 19;
$size = '';

$sql_base = "SELECT main_category_id, Stock FROM subcategories WHERE id = ? LIMIT 1";
$stmt_base = $conn->prepare($sql_base);
$stmt_base->bind_param("i", $prd_id);
$stmt_base->execute();
$row_base = $stmt_base->get_result()->fetch_assoc();

print_r($row_base);
?>
