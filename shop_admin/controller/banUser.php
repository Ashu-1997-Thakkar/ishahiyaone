<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
/** @var mysqli $conn */

// ✅ Check Authentication & Role
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin', 'superadmin'])) {
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

// Prevent admin from banning themselves or other admins unless superadmin
if (!in_array($_SESSION['role'], ['super_admin', 'superadmin'])) {
    $checkQuery = $conn->prepare("SELECT role FROM user WHERE id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['role'] === 'admin' || $user['role'] === 'super_admin' || $user['role'] === 'superadmin') {
            die("You cannot ban an admin account.");
        }
    }
}

// Toggle status
$query = "UPDATE user SET status = IF(status='active', 'banned', 'active') WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../index.php?search=#customers?page=1");
} else {
    echo "Error updating record: " . $conn->error;
}
?>
