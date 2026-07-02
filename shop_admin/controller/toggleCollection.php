<?php
include(dirname(__DIR__) . "/config/dbconnect.php");

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $updated = false;

    // 1️⃣ First check in all_category table
    $sql = "SELECT is_our_collection FROM all_category WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $newStatus = ($row['is_our_collection'] == 1) ? 0 : 1;

        $update = "UPDATE all_category SET is_our_collection=$newStatus WHERE id=$id";
        if (mysqli_query($conn, $update)) {
            $updated = true;
        }
    }

    // 2️⃣ If not found in all_category, check in subcategories table
    if (!$updated) {
        $sql2 = "SELECT is_our_collection FROM subcategories WHERE id=$id";
        $result2 = mysqli_query($conn, $sql2);
        if ($result2 && mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $newStatus2 = ($row2['is_our_collection'] == 1) ? 0 : 1;

            $update2 = "UPDATE subcategories SET is_our_collection=$newStatus2 WHERE id=$id";
            if (mysqli_query($conn, $update2)) {
                $updated = true;
            }
        }
    }

    // 3️⃣ Redirect or error message
    if ($updated) {
        header("Location: ../adminView/viewMen.php?msg=Updated");
        exit;
    } else {
        echo "❌ Product not found in all_category or subcategories.";
    }
} else {
    echo "❌ Invalid request.";
}
?>
