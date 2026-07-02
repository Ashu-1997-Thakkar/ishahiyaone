<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM subcategory WHERE id=$id");
header("Location: ../view_subcategories.php");
?>
