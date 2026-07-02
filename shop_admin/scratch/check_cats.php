<?php
require_once '../config/dbconnect.php';
$r = $conn->query("SELECT id, name, main_category_id, sub_category_id, category_id FROM subcategories");
while($row = $r->fetch_assoc()) {
    echo $row['id']." | name: ".$row['name']." | main: ".$row['main_category_id']." | sub: ".$row['sub_category_id']." | cat: ".$row['category_id']."<br>";
}
