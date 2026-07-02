<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// Only proceed for POST requests with valid 'product_id'
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        $sql = "UPDATE all_category SET is_best = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo "Marked as best successfully.";
            } else {
                http_response_code(500);
                echo "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo "SQL prepare failed: " . $conn->error;
        }
    } else {
        http_response_code(400); // Bad request
        echo "Invalid or missing product_id.";
    }
} else {
    http_response_code(405); // Method not allowed
    echo "Only POST requests are allowed.";
}
?>
