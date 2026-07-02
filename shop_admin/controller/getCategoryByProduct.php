<?php
include dirname(__DIR__) . '/config/dbconnect.php'; // make sure DB connection is included

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT category_name FROM collections WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['category_name' => $row['category_name']]);
    } else {
        echo json_encode(['category_name' => 'Not Found']);
    }
} else {
    echo json_encode(['error' => 'Missing ID']);
}
?>
