<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add New Offer
    if (isset($_POST['save_offer'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $discount_percent = !empty($_POST['discount_percent']) ? (int)$_POST['discount_percent'] : 0;
        $promo_code = isset($_POST['promo_code']) ? mysqli_real_escape_string($conn, $_POST['promo_code']) : '';
        
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $status = isset($_POST['status']) ? 1 : 0;
        $sub_category_id = 'NULL';
        $image = '';

      // Image upload handling
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $targetDir = "../../uploads/offers/";

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $image = time() . "_" . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image)) {
        // Successfully uploaded
    } else {
        echo "Image upload failed.";
        exit;
    }
}

        $sql = "INSERT INTO bumper_offers (title, description, sub_category_id, discount_percent, promo_code, start_date, end_date, status, banner_image) 
                VALUES ('$title', '$description', $sub_category_id, $discount_percent, '$promo_code', '$start_date', '$end_date', '$status', '$image')";
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error adding bumper offer: " . mysqli_error($conn);
        }
    }

    // Edit Existing Offer
    if (isset($_POST['update_offer'])) {
        $offer_id = (int)$_POST['offer_id'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $discount_percent = !empty($_POST['discount_percent']) ? (int)$_POST['discount_percent'] : 0;
        $promo_code = isset($_POST['promo_code']) ? mysqli_real_escape_string($conn, $_POST['promo_code']) : '';
        
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $status = isset($_POST['status']) ? 1 : 0;
        $sub_category_id = 'NULL';

        $image_sql = '';
        if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
            if ($_FILES['image']['error'] == 0) {
                $targetDir = "../../uploads/offers/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $image = time() . "_" . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image)) {
                    $image_sql = ", banner_image='$image'";
                } else {
                    echo "Error moving uploaded file. Check directory permissions.";
                    exit;
                }
            } else {
                echo "Image upload error code: " . $_FILES['image']['error'] . " (Check file size limits)";
                exit;
            }
        }

        $sql = "UPDATE bumper_offers SET 
                title='$title', 
                description='$description',
                sub_category_id=$sub_category_id,
                discount_percent=$discount_percent,
                promo_code='$promo_code',
                start_date='$start_date', 
                end_date='$end_date', 
                status='$status' 
                $image_sql
                WHERE id=$offer_id";

        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error updating bumper offer: " . mysqli_error($conn);
        }
    }
}

// Delete Offer
if (isset($_POST['delete_offer'])) {
    $offer_id = (int)$_POST['delete_offer'];

    $sql = "DELETE FROM bumper_offers WHERE id=$offer_id";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error deleting bumper offer: " . mysqli_error($conn);
    }
}

if (empty($_POST) && empty($_FILES)) {
    echo "DEBUG INFO: \n";
    echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
    echo "CONTENT_LENGTH: " . $_SERVER['CONTENT_LENGTH'] . "\n";
    echo "CONTENT_TYPE: " . $_SERVER['CONTENT_TYPE'] . "\n";
    echo "POST: " . print_r($_POST, true) . "\n";
    echo "FILES: " . print_r($_FILES, true) . "\n";
}
?>
