<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add New Offer
    if (isset($_POST['save_offer'])) {
        $offer_title = mysqli_real_escape_string($conn, $_POST['offer_title']);
        $timer_text = mysqli_real_escape_string($conn, $_POST['timer_text']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $active = isset($_POST['active']) ? 1 : 0;
        $sub_category_id = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : NULL;
        $image = '';

      // Image upload handling
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    // Correct folder path
    $targetDir = "../../uploads/offers/";

    // Create folder if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique image name
    $image = time() . "_" . basename($_FILES['image']['name']);

    // Move uploaded file to the correct directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image)) {
        // Successfully uploaded
    } else {
        // Optional: handle upload error
        echo "<script>alert('Image upload failed.');</script>";
    }
}


        // Insert into database
        $sql = "INSERT INTO special_offer (title, sub_category_id, timer_text, start_date, end_date, active, image) 
                VALUES ('$offer_title', " . ($sub_category_id ?? "NULL") . ", '$timer_text', '$start_date', '$end_date', '$active', '$image')";
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error adding offer: " . mysqli_error($conn);
        }
    }

    // Edit Existing Offer
    if (isset($_POST['update_offer'])) {
        $offer_id = (int)$_POST['offer_id'];
        $offer_title = mysqli_real_escape_string($conn, $_POST['offer_title']);
        $timer_text = mysqli_real_escape_string($conn, $_POST['timer_text']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $active = isset($_POST['active']) ? 1 : 0;
        $sub_category_id = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : NULL;

        // Handle image upload
        $image_sql = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = "../../uploads/offers/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
            $image_sql = ", image='$image'";
        }

        // Update record in database
        $sql = "UPDATE special_offer SET 
                title='$offer_title', 
                sub_category_id=" . ($sub_category_id ?? "NULL") . ",
                timer_text='$timer_text', 
                start_date='$start_date', 
                end_date='$end_date', 
                active='$active' 
                $image_sql
                WHERE id=$offer_id";

        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error updating offer: " . mysqli_error($conn);
        }
    }
}

// Delete Offer (also converted to support AJAX POST)
if (isset($_POST['delete_offer'])) {
    $offer_id = (int)$_POST['delete_offer'];

    $sql = "DELETE FROM special_offer WHERE id=$offer_id";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error deleting offer";
    }
}
?>
