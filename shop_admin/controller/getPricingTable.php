<?php
include dirname(__DIR__) . "/config/dbconnect.php";

$query = mysqli_query($conn, "SELECT * FROM pricing ORDER BY id DESC");

echo '<table class="table table-bordered">
        <tr>
          <th>ID</th>
          <th>Category</th>
          <th>Subcategory</th>
          <th>Price</th>
          <th>Action</th>
        </tr>';

while($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['main_category_name']."</td>
            <td>".$row['subcategory_name']."</td>
            <td>₹".$row['price']."</td>
            <td>
              <button class='btn btn-sm btn-warning' 
                onclick=\"editPricing('".$row['id']."','".$row['main_category_name']."','".$row['subcategory_name']."','".$row['price']."')\">
                Edit
              </button>

              <button class='btn btn-sm btn-danger' onclick=\"deletePricing(".$row['id'].")\">
                Delete
              </button>
            </td>
          </tr>";
}

echo "</table>";
