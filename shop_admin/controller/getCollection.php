<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once dirname(__DIR__) . "/config/dbconnect.php";

if (empty($_GET['id'])) {
    echo json_encode(["error" => "Product ID missing"]);
    exit;
}

$id = intval($_GET['id']);

// ✅ Use correct product table
$sql = "SELECT * FROM subcategories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Product not found"]);
    exit;
}

$row = $result->fetch_assoc();

$data = [
    "id" => $row["id"] ?? "",
    "product_name" => $row["name"] ?? $row["product_name"] ?? "",
    "brand" => $row["brand"] ?? "",
    "price" => $row["price"] ?? "",
    "sku_no" => $row["sku_no"] ?? "",
    "description" => $row["description"] ?? "",
    "image1" => $row["image1"] ?? "",
    "image2" => $row["image2"] ?? "",
    "image3" => $row["image3"] ?? "",
    "image4" => $row["image4"] ?? "",
    "size" => !empty($row["Size"]) ? (json_decode($row["Size"], true) ?: explode(",", $row["Size"])) : [],
    "main_category_id" => $row["main_category_id"] ?? "",
    "category_id" => $row["category_id"] ?? "",
    "Stock" => $row["Stock"] ?? "",
    "is_new_arrival" => (bool)($row["is_new_arrival"] ?? 0),
    "is_our_collection" => (bool)($row["is_our_collection"] ?? 0),
    "is_best" => (bool)($row["is_best"] ?? 0)
];

echo json_encode($data);
$stmt->close();
$conn->close();
?>
