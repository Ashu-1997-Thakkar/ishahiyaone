<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// ----------------- REPLACE FROM HERE -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Read POST
    $product_name    = trim($_POST['name'] ?? '');
    $collection_id   = intval($_POST['collection_id'] ?? 0);   // The Product ID from viewMen.php (all_category)
    $main_category_id = intval($_POST['main_category_id'] ?? 0); 
    $subcategory_id  = intval($_POST['sub_category_id'] ?? 0); 

    // Case 1: CLONE TO SUBSHOP (from viewMen.php 'Add Sub' button)
    // If name is empty but we have a product ID (collection_id) and subcategory ID
    if (empty($product_name) && $collection_id > 0 && $subcategory_id > 0) {
        
        // 1. Fetch existing product details from all_category
        $prod_stmt = $conn->prepare("SELECT * FROM all_category WHERE id = ?");
        $prod_stmt->bind_param("i", $collection_id);
        $prod_stmt->execute();
        $product = $prod_stmt->get_result()->fetch_assoc();
        $prod_stmt->close();

        if ($product) {
            // 2. Clone into subcategories table
            $stmt = $conn->prepare("
                INSERT INTO subcategories 
                (name, brand, price, sku_no, description, image1, image2, image3, image4, category_id, is_our_collection, Stock, quantity)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)
            ");
            
            $stmt->bind_param(
                "ssdssssssiii",
                $product['name'],
                $product['brand'],
                $product['price'],
                $product['sku_no'],
                $product['description'],
                $product['Image1'],
                $product['Image2'],
                $product['Image3'],
                $product['Image4'],
                $subcategory_id,
                $product['Stock'],
                $product['Stock']
            );

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Database Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Source product not found.";
        }
        
        $conn->close();
        exit;
    }

    // Update Case to handle both manual inserts and modified cloned inserts
    $brand           = trim($_POST['brand'] ?? '');
    $price           = floatval($_POST['price'] ?? 0);
    $sku_no          = trim($_POST['sku_no'] ?? '');
    $description     = trim($_POST['description'] ?? '');

    $sizeArray = json_decode($_POST['size'] ?? '[]', true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $sizeArray = !empty($_POST['size']) ? array_map('trim', explode(",", $_POST['size'])) : [];
    }
    $sizeJson = !empty($sizeArray) ? json_encode($sizeArray, JSON_UNESCAPED_UNICODE) : null;

    $upload_dir = "../../shop_admin/uploads/subshop/";
    $image1 = uploadImage('image1', $upload_dir) ?? trim($_POST['old_image1'] ?? '');
    $image2 = uploadImage('image2', $upload_dir) ?? trim($_POST['old_image2'] ?? '');
    $image3 = uploadImage('image3', $upload_dir) ?? trim($_POST['old_image3'] ?? '');
    $image4 = uploadImage('image4', $upload_dir) ?? trim($_POST['old_image4'] ?? '');

    $stmt = $conn->prepare("
        INSERT INTO subcategories
        (name, brand, price, sku_no, description, image1, image2, image3, image4, category_id, size, is_our_collection, Stock, quantity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)
    ");
    
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    $stock = intval($_POST['quantity'] ?? 0);

    $stmt->bind_param(
        'ssdssssssisii',
        $product_name, $brand, $price, $sku_no, $description, $image1, $image2, $image3, $image4, $subcategory_id, $sizeJson, $stock, $stock
    );

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Insert Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    echo "Invalid access.";
}
// ----------------- REPLACE TO HERE -----------------

// ✅ Image upload helper
function uploadImage(string $inputName, string $upload_dir) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
    if (!$ext) return null;
    $filename = time() . '_' . uniqid() . '.' . $ext;
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $target_path = $upload_dir . $filename;
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $target_path)) {
        return $filename;
    }
    return null;
}

?>
