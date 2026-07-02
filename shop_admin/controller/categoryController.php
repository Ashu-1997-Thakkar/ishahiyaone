<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$action = $_POST['action'] ?? '';

if($action == "add"){
    $name = trim($_POST['main_category_name']);
    $slug = trim($_POST['slug']);

    $icon = trim($_POST['icon_class'] ?? '');
    
    if($name == "" || $slug == ""){
        echo "empty";
        exit;
    }

    // ✅ Prevent Duplicates
    $check = $conn->prepare("SELECT id FROM main_category WHERE main_category_name = ? OR slug = ?");
    $check->bind_param("ss", $name, $slug);
    $check->execute();
    if($check->get_result()->num_rows > 0){
        echo "A category with this name or slug already exists!";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO main_category (main_category_name, slug, icon_class) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $slug, $icon);

    if($stmt->execute()){
        echo "success";
    } else {
        echo "error: ".$conn->error;
    }
}

if($action == "edit"){
    $id   = (int)$_POST['id'];
    $name = trim($_POST['main_category_name']);
    $slug = trim($_POST['slug']);
    $icon = trim($_POST['icon_class'] ?? '');

    $stmt = $conn->prepare("UPDATE main_category SET main_category_name=?, slug=?, icon_class=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $slug, $icon, $id);

    if($stmt->execute()){
        echo "success";
    } else {
        echo "error: ".$conn->error;
    }
}

if($action == "delete"){
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM main_category WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        echo "success";
    } else {
        echo "error: ".$conn->error;
    }
}
?>
