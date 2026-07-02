<?php
include 'db.php';
$res = mysqli_query($conn, 'SELECT * FROM wishlist');
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . " | User: " . $row['user_id'] . " | Product: " . $row['product_id'] . "\n";
}
?>
