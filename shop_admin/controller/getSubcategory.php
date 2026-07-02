<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
header('Content-Type: application/json');

// Accept GET or POST
$main_category_id = 0;
if (isset($_POST['main_category_id'])) {
    $main_category_id = intval($_POST['main_category_id']);
} elseif (isset($_GET['main_id'])) {
    $main_category_id = intval($_GET['main_id']);
}

// Validate
if ($main_category_id <= 0) {
    echo json_encode(["error" => "Invalid or missing main category ID"]);
    exit;
}

$sql = "SELECT id, sub_category_name FROM sub_category WHERE main_category_id = ? ORDER BY sub_category_name ASC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $main_category_id);
$stmt->execute();
$result = $stmt->get_result();

$subcategories = [];
while ($row = $result->fetch_assoc()) {
    $subcategories[] = $row;
}

echo json_encode($subcategories);

$stmt->close();
$conn->close();
?>
