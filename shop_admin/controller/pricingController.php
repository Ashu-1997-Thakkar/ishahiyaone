<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == 'update') {
    $id = (int)$_POST['id'];
    $field = mysqli_real_escape_string($conn, $_POST['field']);
    $value = mysqli_real_escape_string($conn, $_POST['value']);

    // Allowed fields to prevent SQL injection or unwanted updates
    $allowedFields = ['sms_count', 'paise_per_sms', 'sort_order'];
    if (!in_array($field, $allowedFields)) {
        echo json_encode(['success' => false, 'message' => 'Invalid field']);
        exit;
    }

    $sql = "UPDATE pricing SET $field = '$value' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Fetch new price
        $res = mysqli_query($conn, "SELECT sms_count, paise_per_sms FROM pricing WHERE id = $id");
        $row = mysqli_fetch_assoc($res);
        $newPrice = ($row['sms_count'] * $row['paise_per_sms']) / 100;
        
        echo json_encode(['success' => true, 'new_price' => $newPrice]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
} 
elseif ($action == 'add') {
    $package = mysqli_real_escape_string($conn, $_POST['sms_package']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $count = (int)$_POST['sms_count'];
    $paise = (float)$_POST['paise_per_sms'];
    $sort = (int)$_POST['sort_order'];

    $sql = "INSERT INTO pricing (sms_package, category, sms_count, paise_per_sms, sort_order) 
            VALUES ('$package', '$category', $count, $paise, $sort)";
            
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
}
elseif ($action == 'delete') {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM pricing WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
}
?>
