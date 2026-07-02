<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$action = $_POST['action'] ?? '';

if($action == "add"){
  $name = trim($_POST['sub_category_name']);
  $slug = trim($_POST['slug']);
  $mainCatId = (int)$_POST['main_category_id'];
  $icon = trim($_POST['icon']);

  if($name=="" || $slug=="" || !$mainCatId || $icon==""){
    echo "empty"; exit;
  }

  // ✅ Prevent Duplicates
  $check = $conn->prepare("SELECT id FROM sub_category WHERE sub_category_name = ? OR slug = ?");
  $check->bind_param("ss", $name, $slug);
  $check->execute();
  if($check->get_result()->num_rows > 0){
    echo "A sub-category with this name or slug already exists!";
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO sub_category (sub_category_name, slug, main_category_id, icon) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssis", $name, $slug, $mainCatId, $icon);
  echo $stmt->execute() ? "success" : "error: ".$conn->error;
}

if($action == "edit"){
  $id = (int)$_POST['id'];
  $name = trim($_POST['sub_category_name']);
  $slug = trim($_POST['slug']);
  $mainCatId = (int)$_POST['main_category_id'];
  $icon = trim($_POST['icon']);

  if($name=="" || $slug=="" || !$mainCatId || $icon==""){
    echo "empty"; exit;
  }

  $stmt = $conn->prepare("UPDATE sub_category 
                          SET sub_category_name=?, slug=?, main_category_id=?, icon=? 
                          WHERE id=?");
  $stmt->bind_param("ssisi", $name, $slug, $mainCatId, $icon, $id);
  echo $stmt->execute() ? "success" : "error: ".$conn->error;
}

if($action == "delete"){
  $id = (int)$_POST['id'];
  $stmt = $conn->prepare("DELETE FROM sub_category WHERE id=?");
  $stmt->bind_param("i", $id);
  echo $stmt->execute() ? "success" : "error: ".$conn->error;
}
