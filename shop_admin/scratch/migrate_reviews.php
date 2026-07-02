<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/dbconnect.php';
/** @var mysqli $conn */

$sql = "CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT DEFAULT 0,
    user_name VARCHAR(100) NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_title VARCHAR(255) DEFAULT '',
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql)) {
    echo "SUCCESS: product_reviews table created or already exists.";
} else {
    echo "ERROR: " . $conn->error;
}
