<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

header('Content-Type: application/json');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM collections WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Collection item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting item: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
}
exit();
?>
