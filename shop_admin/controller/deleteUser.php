<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
/** @var mysqli $conn */

// ✅ Check Authentication & Role
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    die("Access Denied");
}

// ✅ Validate CSRF Token
if (!isset($_GET['csrf']) || $_GET['csrf'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
}

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid User ID.");
}

$id = (int)$_GET['id'];

// Prevent admin from deleting themselves or other admins unless superadmin
if ($_SESSION['role'] !== 'superadmin') {
    $checkQuery = $conn->prepare("SELECT role FROM user WHERE id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['role'] === 'admin' || $user['role'] === 'superadmin') {
            die("You cannot delete an admin account.");
        }
    }
}

// Proceed to delete
$stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => true]);
    } else {
        header("Location: ../index.php?search=#customers?page=1");
    }
} else {
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
