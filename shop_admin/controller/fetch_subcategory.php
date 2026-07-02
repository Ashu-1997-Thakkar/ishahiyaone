<?php
include(dirname(__DIR__) . "/config/dbconnect.php"); // apna db connection include karo

if (isset($_POST['main_category_id'])) {
    $mainCatId = intval($_POST['main_category_id']); // sanitize input

    $query = "SELECT id, sub_category_name 
              FROM sub_category 
              WHERE main_category_id = $mainCatId 
              ORDER BY sub_category_name ASC";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<option value="">-- Select Sub Category --</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['sub_category_name']).'</option>';
        }
    } else {
        echo '<option value="">No Sub Categories Found</option>';
    }
}
?>
