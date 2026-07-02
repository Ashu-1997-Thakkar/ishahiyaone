<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if(isset($_POST['id'])){
  $id = $_POST['id'];
  $sql = "DELETE FROM vouchers WHERE id=$id";
  if(mysqli_query($conn,$sql)){
    echo "success";
  } else {
    echo "error";
  }
}
?>
