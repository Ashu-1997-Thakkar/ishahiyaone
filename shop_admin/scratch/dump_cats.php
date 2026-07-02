<?php
require_once '../config/dbconnect.php';
$res = $conn->query("SELECT id, main_category_id, sub_category_name, slug FROM sub_category");
while($r = $res->fetch_assoc()) {
    echo $r['id'] . " | main: " . $r['main_category_id'] . " | name: " . $r['sub_category_name'] . " | slug: " . $r['slug'] . "<br>";
}
