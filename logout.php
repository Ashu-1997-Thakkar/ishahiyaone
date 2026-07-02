<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data (complete logout)
$_SESSION = array(); // Clear all session variables

// If session uses cookies, delete them as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finally destroy the session
session_destroy();

// Redirect user to home or login page
header("Location: index.php");
exit;
?>
