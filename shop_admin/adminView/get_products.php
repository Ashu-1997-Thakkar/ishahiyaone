<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_GET['order_id'])) {
    // Escape the order_id to prevent SQL injection
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

    // SQL query to select both product_name and sku_no for the given order_id (mapped to id column)
    $sql = "SELECT product_name, sku_no FROM billing_details WHERE id = '$order_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // If there is a SQL error, return an error message
        $err = mysqli_error($conn);
        echo json_encode(['success' => false, 'message' => "SQL Error: $err"]);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        // Fetch the result from the query
        $row = mysqli_fetch_assoc($result);

        // Split and clean up the product names
        $productNames = array_map('trim', explode(',', $row['product_name']));
        
        // Filter out any empty product names
        $productNames = array_filter($productNames, function($p) {
            return $p !== '';
        });
        
        // Re-index the array after filtering
        $productNames = array_values($productNames);

        // Retrieve the SKU number
        $skuNo = $row['sku_no'];

        // Return the products and SKU number in the response
        echo json_encode(['success' => true, 'products' => $productNames, 'sku_no' => $skuNo]);
    } else {
        // If no order found, return a message
        echo json_encode(['success' => false, 'message' => 'Order not found or product list empty']);
    }
    exit;
}
?>
