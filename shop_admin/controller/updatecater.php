<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
    $stock   = isset($_POST['stock']) ? $_POST['stock'] : '';

    if ($edit_id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid product id."]);
        exit;
    }

    if (!is_numeric($stock)) {
        echo json_encode(["success" => false, "message" => "Stock must be a number."]);
        exit;
    }

    $stock = intval($stock);

    $stmt = $conn->prepare("UPDATE subcategories SET Stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $stock, $edit_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Inventory stock updated successfully!"]);
        exit;
    } else {
        $err = htmlspecialchars($stmt->error);
        echo json_encode(["success" => false, "message" => "Error updating product: {$err}"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}
?>
