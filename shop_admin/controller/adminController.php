<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../config/dbconnect.php";
header('Content-Type: application/json');

/** @var mysqli $conn */

// Verify admin session and Super Admin role
if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['super_admin', 'superadmin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access: Super Administrative privileges required.']);
    exit();
}

// Ensure status column exists in admin table
$colCheck = $conn->query("SHOW COLUMNS FROM admin LIKE 'status'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE admin ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'approved'");
}

$action = $_POST['action'] ?? ($_GET['action'] ?? 'list');

// 1️⃣ LIST ALL ADMINS & STATS
if ($action === 'list') {
    $result = $conn->query("SELECT id, full_name, username, email, role, COALESCE(status, 'approved') as status FROM admin ORDER BY id DESC");
    $admins = [];
    $stats = [
        'total' => 0,
        'approved' => 0,
        'pending' => 0,
        'revoked' => 0
    ];

    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
        $stats['total']++;
        $st = strtolower(trim($row['status']));
        if ($st === 'approved') {
            $stats['approved']++;
        } elseif ($st === 'pending') {
            $stats['pending']++;
        } elseif ($st === 'revoked' || $st === 'inactive' || $st === 'disabled') {
            $stats['revoked']++;
        } else {
            $stats['approved']++;
        }
    }

    echo json_encode(['status' => 'success', 'data' => $admins, 'stats' => $stats]);
    exit();
}

// 2️⃣ UPDATE ADMIN STATUS (RBAC PERMISSION GRANT / REVOKE)
if ($action === 'update_status') {
    $id = intval($_POST['id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? 'approved');
    
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Admin ID.']);
        exit();
    }

    // Prevent Super Admin from accidentally revoking their own active session if they are ID 1 or current user
    if ($id === intval($_SESSION['user_id'] ?? 0) && $newStatus === 'revoked') {
        echo json_encode(['status' => 'error', 'message' => 'You cannot revoke your own active administrative session!']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE admin SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Admin status successfully updated to " . strtoupper($newStatus)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

// 3️⃣ UPDATE ADMIN ROLE
if ($action === 'update_role') {
    $id = intval($_POST['id'] ?? 0);
    $newRole = trim($_POST['role'] ?? 'admin');
    
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Admin ID.']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE admin SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Admin role successfully updated to " . strtoupper($newRole)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

// 4️⃣ ADD NEW ADMIN DIRECTLY FROM DASHBOARD
if ($action === 'add') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? $email);
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? 'admin');
    $status = trim($_POST['status'] ?? 'approved'); // Created by Super Admin defaults to approved!

    if (empty($fullName) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Full Name, Email, and Password are required!']);
        exit();
    }

    // Check duplicate
    $check = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'An admin account with this Email or Username already exists!']);
        $check->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO admin (full_name, email, username, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullName, $email, $username, $password, $role, $status);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'New admin account created successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create admin: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

// 5️⃣ DELETE ADMIN ACCOUNT
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Admin ID.']);
        exit();
    }

    if ($id === intval($_SESSION['user_id'] ?? 0)) {
        echo json_encode(['status' => 'error', 'message' => 'You cannot delete your own active account!']);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Admin account deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete admin: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
exit();
?>
