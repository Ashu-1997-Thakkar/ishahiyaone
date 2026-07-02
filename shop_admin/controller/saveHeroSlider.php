<?php
include_once "../config/dbconnect.php";
/** @var mysqli $conn */

// ---- DELETE LOGIC ----
if (isset($_POST['delete_slider'])) {
    $id = (int)$_POST['delete_slider'];

    // Get image names to delete files
    $stmt = $conn->prepare("SELECT image, mobile_image, image_3, image_4 FROM hero_slider WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $filePath = "../../uploads/slider/" . $row['image'];
        if (!empty($row['image']) && file_exists($filePath)) {
            unlink($filePath);
        }
        $mFilePath = "../../uploads/slider/" . $row['mobile_image'];
        if (!empty($row['mobile_image']) && file_exists($mFilePath)) {
            unlink($mFilePath);
        }
        $filePath3 = "../../uploads/slider/" . $row['image_3'];
        if (!empty($row['image_3']) && file_exists($filePath3)) {
            unlink($filePath3);
        }
        $filePath4 = "../../uploads/slider/" . $row['image_4'];
        if (!empty($row['image_4']) && file_exists($filePath4)) {
            unlink($filePath4);
        }
    }

    $delStmt = $conn->prepare("DELETE FROM hero_slider WHERE id = ?");
    $delStmt->bind_param("i", $id);
    if ($delStmt->execute()) {
        echo "success";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    exit;
}

// ---- UPDATE LOGIC ----
if (isset($_POST['update_slider'])) {
    $id       = (int)($_POST['edit_slider_id'] ?? 0);
    $title    = $_POST['title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $link     = $_POST['link'] ?? '#';
    $btn_text = $_POST['btn_text'] ?? 'Shop Now';

    $location = "../../uploads/slider/";
    if (!is_dir($location)) mkdir($location, 0777, true);

    $newDesktopName = null;
    $newMobileName  = null;
    $newImage3Name  = null;
    $newImage4Name  = null;

    // Check if new desktop image uploaded
    if (!empty($_FILES['slider_image']['name'])) {
        $name = $_FILES['slider_image']['name'];
        $temp = $_FILES['slider_image']['tmp_name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $newDesktopName = "slide_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        if (move_uploaded_file($temp, $location . $newDesktopName)) {
            // Delete old desktop image
            $oldStmt = $conn->prepare("SELECT image FROM hero_slider WHERE id = ?");
            $oldStmt->bind_param("i", $id);
            $oldStmt->execute();
            $oldRes = $oldStmt->get_result()->fetch_assoc();
            if ($oldRes && !empty($oldRes['image']) && file_exists($location . $oldRes['image'])) {
                unlink($location . $oldRes['image']);
            }
        }
    }

    // Check if new mobile image uploaded
    if (!empty($_FILES['mobile_image']['name'])) {
        $name = $_FILES['mobile_image']['name'];
        $temp = $_FILES['mobile_image']['tmp_name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $newMobileName = "slide_mobile_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        if (move_uploaded_file($temp, $location . $newMobileName)) {
            // Delete old mobile image
            $oldStmt = $conn->prepare("SELECT mobile_image FROM hero_slider WHERE id = ?");
            $oldStmt->bind_param("i", $id);
            $oldStmt->execute();
            $oldRes = $oldStmt->get_result()->fetch_assoc();
            if ($oldRes && !empty($oldRes['mobile_image']) && file_exists($location . $oldRes['mobile_image'])) {
                unlink($location . $oldRes['mobile_image']);
            }
        }
    }

    // Check if new image 3 uploaded
    if (!empty($_FILES['slider_image_3']['name'])) {
        $name = $_FILES['slider_image_3']['name'];
        $temp = $_FILES['slider_image_3']['tmp_name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $newImage3Name = "slide_3_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        if (move_uploaded_file($temp, $location . $newImage3Name)) {
            // Delete old image 3
            $oldStmt = $conn->prepare("SELECT image_3 FROM hero_slider WHERE id = ?");
            $oldStmt->bind_param("i", $id);
            $oldStmt->execute();
            $oldRes = $oldStmt->get_result()->fetch_assoc();
            if ($oldRes && !empty($oldRes['image_3']) && file_exists($location . $oldRes['image_3'])) {
                unlink($location . $oldRes['image_3']);
            }
        }
    }

    // Check if new image 4 uploaded
    if (!empty($_FILES['slider_image_4']['name'])) {
        $name = $_FILES['slider_image_4']['name'];
        $temp = $_FILES['slider_image_4']['tmp_name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $newImage4Name = "slide_4_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        if (move_uploaded_file($temp, $location . $newImage4Name)) {
            // Delete old image 4
            $oldStmt = $conn->prepare("SELECT image_4 FROM hero_slider WHERE id = ?");
            $oldStmt->bind_param("i", $id);
            $oldStmt->execute();
            $oldRes = $oldStmt->get_result()->fetch_assoc();
            if ($oldRes && !empty($oldRes['image_4']) && file_exists($location . $oldRes['image_4'])) {
                unlink($location . $oldRes['image_4']);
            }
        }
    }

    // Build update query dynamically
    $query = "UPDATE hero_slider SET title=?, subtitle=?, link=?, btn_text=?";
    $params = [$title, $subtitle, $link, $btn_text];
    $types = "ssss";

    if ($newDesktopName !== null) {
        $query .= ", image=?";
        $params[] = $newDesktopName;
        $types .= "s";
    }
    if ($newMobileName !== null) {
        $query .= ", mobile_image=?";
        $params[] = $newMobileName;
        $types .= "s";
    }
    if ($newImage3Name !== null) {
        $query .= ", image_3=?";
        $params[] = $newImage3Name;
        $types .= "s";
    }
    if ($newImage4Name !== null) {
        $query .= ", image_4=?";
        $params[] = $newImage4Name;
        $types .= "s";
    }

    $query .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Database Error: " . $conn->error;
    }
    exit;
}

// ---- UPLOAD LOGIC ----
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['slider_image'])) {
    $title    = $_POST['title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $link     = $_POST['link'] ?? '#';
    $btn_text = $_POST['btn_text'] ?? 'Shop Now';

    $location = "../../uploads/slider/";
    if (!is_dir($location)) {
        mkdir($location, 0777, true);
    }

    // Handle desktop image
    $name     = $_FILES['slider_image']['name'];
    $temp     = $_FILES['slider_image']['tmp_name'];
    $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $newDesktopFileName = "slide_" . time() . "_" . rand(1000, 9999) . "." . $ext;

    if (move_uploaded_file($temp, $location . $newDesktopFileName)) {
        // Handle optional mobile image
        $newMobileFileName = null;
        if (!empty($_FILES['mobile_image']['name'])) {
            $mName = $_FILES['mobile_image']['name'];
            $mTemp = $_FILES['mobile_image']['tmp_name'];
            $mExt  = strtolower(pathinfo($mName, PATHINFO_EXTENSION));
            $newMobileFileName = "slide_mobile_" . time() . "_" . rand(1000, 9999) . "." . $mExt;
            move_uploaded_file($mTemp, $location . $newMobileFileName);
        }

        // Handle optional image 3
        $newImage3FileName = null;
        if (!empty($_FILES['slider_image_3']['name'])) {
            $i3Name = $_FILES['slider_image_3']['name'];
            $i3Temp = $_FILES['slider_image_3']['tmp_name'];
            $i3Ext  = strtolower(pathinfo($i3Name, PATHINFO_EXTENSION));
            $newImage3FileName = "slide_3_" . time() . "_" . rand(1000, 9999) . "." . $i3Ext;
            move_uploaded_file($i3Temp, $location . $newImage3FileName);
        }

        // Handle optional image 4
        $newImage4FileName = null;
        if (!empty($_FILES['slider_image_4']['name'])) {
            $i4Name = $_FILES['slider_image_4']['name'];
            $i4Temp = $_FILES['slider_image_4']['tmp_name'];
            $i4Ext  = strtolower(pathinfo($i4Name, PATHINFO_EXTENSION));
            $newImage4FileName = "slide_4_" . time() . "_" . rand(1000, 9999) . "." . $i4Ext;
            move_uploaded_file($i4Temp, $location . $newImage4FileName);
        }

        $stmt = $conn->prepare("INSERT INTO hero_slider (image, mobile_image, image_3, image_4, title, subtitle, link, btn_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $newDesktopFileName, $newMobileFileName, $newImage3FileName, $newImage4FileName, $title, $subtitle, $link, $btn_text);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Database Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload desktop image.";
    }
    exit;
}
?>
