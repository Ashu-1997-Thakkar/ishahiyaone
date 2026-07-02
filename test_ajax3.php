<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['prd_id'] = 19;
$_POST['size'] = 'One Size';

include 'shop_admin/config/dbconnect.php';

  $prd_id = intval($_POST['prd_id']);
  $size = isset($_POST['size']) ? trim($_POST['size']) : '';
  
  $actual_stock = 0;
  
  // 1. Fetch main_category_id and Stock from subcategories
  $sql_base = "SELECT main_category_id, Stock FROM subcategories WHERE id = ? LIMIT 1";
  $stmt_base = $conn->prepare($sql_base);
  $stmt_base->bind_param("i", $prd_id);
  $stmt_base->execute();
  $row_base = $stmt_base->get_result()->fetch_assoc();
  
  if ($row_base) {
      $main_cat_id = (int)$row_base['main_category_id'];
      
      if ($main_cat_id === 7 && !empty($size) && $size !== '-') {
          // Fashion product: check product_size_variation
          $sql_stock = "
              SELECT psv.quantity_in_stock 
              FROM product_size_variation psv
              JOIN sizes s ON psv.size_id = s.size_id
              WHERE psv.product_id = ? AND s.size_name = ?
              LIMIT 1
          ";
          $stmt = $conn->prepare($sql_stock);
          $stmt->bind_param("is", $prd_id, $size);
          $stmt->execute();
          $row = $stmt->get_result()->fetch_assoc();
          if ($row) {
              $actual_stock = (int)$row['quantity_in_stock'];
          }
      } else {
          // Standard product (e.g. Electronics): use the Stock column
          $actual_stock = (int)$row_base['Stock'];
      }
  }

  echo json_encode([
      "available" => $actual_stock > 0,
      "stock" => $actual_stock,
      "main_cat_id" => $main_cat_id
  ]);
?>
