<?php
// Fix this line at top of edit_subcategory.php:
include_once dirname(__DIR__) . "/config/dbconnect.php";

header('Content-Type: application/json');

// Check if 'id' is passed in the query string
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Sanitize ID input

    // Prepare the SQL query to fetch subcategory data
    $stmt = $conn->prepare("SELECT * FROM subcategory WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);  // Return the data in JSON format
    } else {
        echo json_encode(["error" => "No record found"]);  // If no matching data
    }
} else {
    echo json_encode(["error" => "Invalid ID"]);  // If 'id' is not passed
}
?>
