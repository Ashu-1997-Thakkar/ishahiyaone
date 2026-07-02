<?php
/**
 * IshahiyaOne — Database Connection (MySQLi)
 * Credentials are pulled from config.php (single source of truth).
 */

require_once __DIR__ . '/config.php';

// Enable strict MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8 encoding
mysqli_set_charset($conn, DB_CHARSET);