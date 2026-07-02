<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

$sql = "SELECT * FROM products WHERE is_best = 1"; // Assuming you have a column like `is_best`
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Action</th>
          </tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><img src='../assets/images/" . $row['image'] . "' width='60' /></td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>₹" . $row['price'] . "</td>";
        echo "<td>
                <button class='btn btn-danger btn-sm' onclick='removeFromBest(" . $row['product_id'] . ")'>Remove</button>
              </td>";
        echo "</tr>";
    }

    echo '</tbody></table></div>';
} else {
    echo "<p>No best products found.</p>";
}
?>
