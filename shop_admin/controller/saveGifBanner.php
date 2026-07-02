<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// ✅ DELETE handler
if (isset($_POST['delete_banner'])) {
    $id = (int)$_POST['delete_banner'];
    // Fetch filename to delete the physical file
    $res = $conn->query("SELECT image FROM gif_banner WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $imgFile = $res->fetch_assoc()['image'];
        $filePath = "../../uploads/banner/" . $imgFile;
        if (file_exists($filePath) && !is_dir($filePath)) {
            unlink($filePath);
        }
    }
    if ($conn->query("DELETE FROM gif_banner WHERE id = $id")) {
        echo "success";
    } else {
        echo "Error deleting banner";
    }
    exit;
}

if(isset($_FILES['gif_file'])){
    $fileName = time() . "_" . basename($_FILES['gif_file']['name']);
    $tempName = $_FILES['gif_file']['tmp_name'];
    $folder = "../../uploads/banner/".$fileName;

    // Ensure folder exists
    if(!is_dir("../../uploads/banner/")){
        mkdir("../../uploads/banner/", 0777, true);
    }

    if(move_uploaded_file($tempName, $folder)){
        $title = $_POST['banner_title'];
        if(mysqli_query($conn, "INSERT INTO gif_banner (title, image) VALUES ('$title','$fileName')")) {
            echo "success";
        } else {
            echo "Database error";
        }
    } else {
        echo "Upload Failed";
    }
}
?>
