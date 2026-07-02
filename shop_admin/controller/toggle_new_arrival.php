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

    // Try target table first (or just the target if type is specified)
    $sql = "SELECT is_new_arrival FROM $table WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $newStatus = ($row['is_new_arrival'] == 1) ? 0 : 1;
        $update = "UPDATE $table SET is_new_arrival = $newStatus WHERE id = $id";
        if (mysqli_query($conn, $update)) {
            $updated = true;
        }
    }

    // Fallback logic for legacy compatibility (only if type was not specified)
    if (!$updated && empty($type)) {
        $fallbackTable = ($table === 'all_category') ? 'subcategories' : 'all_category';
        $sql2 = "SELECT is_new_arrival FROM $fallbackTable WHERE id = $id";
        $result2 = mysqli_query($conn, $sql2);
        if ($result2 && mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $newStatus2 = ($row2['is_new_arrival'] == 1) ? 0 : 1;
            $update2 = "UPDATE $fallbackTable SET is_new_arrival = $newStatus2 WHERE id = $id";
            if (mysqli_query($conn, $update2)) {
                $updated = true;
            }
        }
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
