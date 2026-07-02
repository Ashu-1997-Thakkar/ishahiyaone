<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if(isset($_POST['action'])){
    
    // ADD
    if($_POST['action'] == "add"){
        $name = $_POST['main_category_name'];
        $slug = $_POST['slug'];
        $icon = $_POST['icon_class'];   // ✅ icon value bhi le lo

        $sql = "INSERT INTO main_category (main_category_name, slug, icon_class) 
                VALUES ('$name', '$slug', '$icon')";
        if($conn->query($sql)){
            echo "success";
        }else{
            echo "error: ".$conn->error;
        }
    }

    // EDIT
    if($_POST['action'] == "edit"){
        $id   = $_POST['id'];
        $name = $_POST['main_category_name'];
        $slug = $_POST['slug'];
        $icon = $_POST['icon_class'];   // ✅ icon value bhi le lo

        $sql = "UPDATE main_category 
                SET main_category_name='$name', slug='$slug', icon_class='$icon' 
                WHERE id=$id";
        if($conn->query($sql)){
            echo "success";
        }else{
            echo "error: ".$conn->error;
        }
    }

    // DELETE
    if($_POST['action'] == "delete"){
        $id = $_POST['id'];
        $sql = "DELETE FROM main_category WHERE id=$id";
        if($conn->query($sql)){
            echo "success";
        }else{
            echo "error: ".$conn->error;
        }
    }
}
?>
