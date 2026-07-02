<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type       = mysqli_real_escape_string($conn, $_POST['type']);
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];
    $status     = $_POST['status'];
    $created_at = date("Y-m-d H:i:s");

    // ✅ Validate required fields
    if (empty($type) || empty($start_date) || empty($end_date) || empty($status)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // ✅ Image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp']; // ⚠️ no avif

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Invalid file type. Allowed: JPG, PNG, GIF, WEBP.'); window.history.back();</script>";
            exit;
        }

        $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $_FILES['image']['name']);

        $uploadDir  = __DIR__ . "/../uploads/";
        $uploadPath = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // ✅ Insert query
            $sql = "INSERT INTO vouchers (image, start_date, end_date, status, `Type`)
                    VALUES ('$imageName', '$start_date', '$end_date', '$status', '$type')";

            if (mysqli_query($conn, $sql)) {
                echo "success";
                exit;
            } else {
                echo "Database Error: " . mysqli_error($conn);
                exit;
            }
        } else {
            echo "Upload failed. Check folder permissions.";
            exit;
        }
    } else {
        echo "Please upload an image.";
        exit;
    }
}
?>
