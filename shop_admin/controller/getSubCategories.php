<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$catId = intval($_POST['category_id'] ?? 0);

echo '<option value="0">— Select Sub Category —</option>';

if ($catId > 0) {
    $stmt = $conn->prepare("SELECT id, sub_category_name FROM sub_category WHERE main_category_id = ? ORDER BY sub_category_name ASC");
    $stmt->bind_param("i", $catId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . (int)$row['id'] . '">' . htmlspecialchars($row['sub_category_name']) . '</option>';
    }
}
?>
