<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF check
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch");
    }

    // Input sanitization
    $id    = (int) $_POST['id'];
    $name  = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $role  = $conn->real_escape_string(trim($_POST['role']));

    $query = "UPDATE user 
              SET name='$name', email='$email', role='$role' 
              WHERE id=$id";

    if ($conn->query($query)) {
        header("Location: ../index.php#customers");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    die("Invalid request");
}
