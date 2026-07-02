<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo "Unauthorized";
    exit;
}

$host = "localhost";
$user = "ishahiyaone";
$password = "BhaV@1437I";
$dbname = "ishahiyaone";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $new_password = $_POST['password'] ?? '';
        $email = $_SESSION["username"]; // Ensure the user is updating their own profile

        if (empty($user_id) || empty($name)) {
            echo "Missing required fields";
            exit;
        }

        if (!empty($new_password)) {
            // Hash the new password properly
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE user SET name = ?, password = ? WHERE id = ? AND email = ?");
            $stmt->execute([$name, $hashedPassword, $user_id, $email]);
        } else {
            // Update Name only
            $stmt = $db->prepare("UPDATE user SET name = ? WHERE id = ? AND email = ?");
            $stmt->execute([$name, $user_id, $email]);
        }

        echo "success";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
