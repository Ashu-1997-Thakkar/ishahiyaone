<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if (isset($_POST['order_id'])) {
    // Escape the order_id for safety
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

    // Delete the order from the billing_details table
    $sql = "DELETE FROM billing_details WHERE id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => "Order deleted successfully."]);
    } else {
        // If the deletion fails, show an error message
        echo json_encode(['success' => false, 'message' => "Failed to delete the order."]);
    }
    exit;
} else {
    // If order_id is not set, show an error
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>