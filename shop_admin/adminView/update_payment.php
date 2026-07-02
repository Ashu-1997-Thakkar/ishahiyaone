<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";

// ✅ Security check (sirf admin allow)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];

    if (!in_array($status, ['Paid', 'Pending'])) {
        echo json_encode(["status" => "error", "message" => "Invalid status!"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE billing_details SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Payment status updated!"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request!"]);
}
