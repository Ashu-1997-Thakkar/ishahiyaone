<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if(isset($_POST['id'])){
  $id = $_POST['id'];
  $type = $_POST['type'];
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $status = $_POST['status'];

  // File upload
  if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
    $image = time()."_".$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
    $imgSql = ", image='$image'";
  } else {
    $imgSql = "";
  }

  $sql = "UPDATE vouchers SET Type='$type', start_date='$start', end_date='$end', status='$status' $imgSql WHERE id=$id";

  if(mysqli_query($conn,$sql)){
    echo "success";
  } else {
    echo "Update failed: " . mysqli_error($conn);
  }
}
?>
