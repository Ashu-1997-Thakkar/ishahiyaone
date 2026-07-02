<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";

// ✅ Role-based restriction
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    die("Unauthorized access");
}

if (isset($_REQUEST['id']) && isset($_REQUEST['action'])) {
    $id = (int)$_REQUEST['id'];
    $action = $_REQUEST['action'];
    $isAjax = isset($_REQUEST['ajax']);

    if ($action === 'verify') {
        // ✅ Manually verify inquiry
        $sql = "UPDATE inquiries SET is_verified = 1 WHERE id = $id";
        if ($conn->query($sql)) {
            if ($isAjax) { echo json_encode(['success' => true]); exit; }
            header("Location: ../index.php#inquiries");
            exit;
        }
    } elseif ($action === 'delete') {
        // ✅ Delete inquiry
        $sql = "DELETE FROM inquiries WHERE id = $id";
        if ($conn->query($sql)) {
            if ($isAjax) { echo json_encode(['success' => true]); exit; }
            header("Location: ../index.php#inquiries");
            exit;
        }
    }
    if ($isAjax) { echo json_encode(['success' => false, 'error' => $conn->error]); exit; }
}

if (!isset($_REQUEST['ajax'])) {
    header("Location: ../index.php#inquiries");
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
}
?>
