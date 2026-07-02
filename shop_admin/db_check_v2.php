<?php
$conn = mysqli_connect("localhost", "root", "", "ishahiyaone");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$res = $conn->query("DESCRIBE products");
echo "PRODUCTS TABLE:\n";
while($row = $res->fetch_assoc()) {
    echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
