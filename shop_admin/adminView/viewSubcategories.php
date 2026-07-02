<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$result = $conn->query("SELECT * FROM sub_category");

echo "<table class='table'>";
echo "<tr><th>ID</th><th>Name</th></tr>";

while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row['sub_category_id']."</td>";
    echo "<td>".$row['sub_category_name']."</td>";
    echo "</tr>";
}

echo "</table>";
?>