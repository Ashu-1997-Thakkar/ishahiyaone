<?php
include(dirname(__DIR__) . "/config/dbconnect.php");

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $updated = false;

    // Determine target table
    $table = 'all_category';
    if ($type === 'sub') {
        $table = 'subcategories';
    }

    // Step 1: Fetch current best status
    $sql = "SELECT is_best FROM $table WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row) {
        // Step 2: Toggle
        $newStatus = ($row['is_best'] == 1) ? 0 : 1;
        $update = "UPDATE $table SET is_best = ? WHERE id = ?";
        $stmt1 = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt1, "ii", $newStatus, $id);
        if(mysqli_stmt_execute($stmt1)) {
            $updated = true;
        }
        mysqli_stmt_close($stmt1);
    }

    if ($updated) {
        echo "success";
        exit;
    } else {
        echo "Product not found";
    }
} else {
    echo "Invalid request";
}
?>
