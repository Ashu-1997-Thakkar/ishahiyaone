<?php
include dirname(__DIR__) . "/config/dbconnect.php";

$id = $_POST['id'];

mysqli_query($conn, "DELETE FROM pricing WHERE id=$id");
echo "deleted";
