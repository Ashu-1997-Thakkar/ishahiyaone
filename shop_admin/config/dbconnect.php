<?php
$server   = "localhost";
$user     = "ishahiyaone";
$password = "BhaV@1437I";
$database = "ishahiyaone";

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create mysqli connection
$conn = mysqli_connect($server, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// UTF-8 encoding enforce
mysqli_set_charset($conn, "utf8mb4");

// ============================================================================
// ENTERPRISE ROLE-BASED ACCESS CONTROL (RBAC) & ROUTE PROTECTION
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_script = basename($_SERVER['SCRIPT_NAME'] ?? '');
$script_path = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));
$script_file = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');

// List of authentication files that do not require an active admin session
$public_admin_scripts = ['log.php', 'forgot_password.php', 'signup.php', 'test_db.php', 'test_db2.php', 'test_db3.php', 'db_check_v2.php'];

// ONLY enforce Admin RBAC if the requested script is actually inside the shop_admin directory!
if (strpos($script_path, '/shop_admin/') !== false || strpos($script_file, '/shop_admin/') !== false) {
    // For ALL OTHER files in shop_admin/ (index.php, all adminView/*.php, all controller/*.php, etc.)
    if (!in_array($current_script, $public_admin_scripts)) {
        file_put_contents(__DIR__ . '/debug_session.log', "[" . date('Y-m-d H:i:s') . "] URI: " . ($_SERVER['REQUEST_URI'] ?? '') . "\nSession: " . print_r($_SESSION, true) . "\n\n", FILE_APPEND);
        if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin', 'superadmin']) || !isset($_SESSION['user_id']) || (isset($_SESSION['admin_status']) && $_SESSION['admin_status'] !== 'approved')) {
            // If AJAX request or API call, return 403 JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('HTTP/1.1 403 Forbidden');
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'code' => 403,
                    'message' => '403 Forbidden: Admin authorization required. RBAC violation. End-user access denied.'
                ]);
                exit();
            } else {
                // Direct URL access by end-user or unauthenticated visitor
                header("Location: /shop_admin/log.php?error=rbac_denied");
                exit();
            }
        }
    }
}
?>
