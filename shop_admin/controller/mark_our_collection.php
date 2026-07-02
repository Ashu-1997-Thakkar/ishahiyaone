<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/dbconnect.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = strtolower(trim($_GET['type']));

    try {
        // ✅ Identify correct table based on type
        if ($type === 'main') {
            $table = 'all_category'; // main shop table
        } elseif ($type === 'sub') {
            $table = 'subcategories'; // subshop table
        } else {
            throw new Exception("Invalid type");
        }

        // ✅ Check current status
        $stmt = $conn->prepare("SELECT is_our_collection FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("Record not found");
        }

        $current = $row['is_our_collection'];

        // ✅ Toggle between mark/unmark
        $newValue = ($current == 1) ? 0 : 1;
        $update = $conn->prepare("UPDATE $table SET is_our_collection = ? WHERE id = ?");
        $update->bind_param("ii", $newValue, $id);
        $update->execute();

        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=updated");
        exit;

    } catch (Exception $e) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode($e->getMessage()));
        exit;
    }

} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=invalid_request");
    exit;
}
?>
