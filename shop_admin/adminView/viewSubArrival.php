<!-- viewSubArrival.php -->
<?php include_once dirname(__DIR__) . "/config/dbconnect.php"; ?>
<h3 class="text-center">Sub Arrival Items</h3>
<div class="text-end mb-3">
  <button class="btn btn-primary" onclick="showAddSubArrivalModal()">+ Add Sub Arrival</button>
</div>

<table class="table table-bordered">
  <thead style="background-color: #c59d2f; color: white;">
    <tr>
      <th>S.N.</th>
      <th>Image</th>
      <th>Product Name</th>
      <th>Description</th>
      <th>Category</th>
      <th>Price</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    require_once dirname(__DIR__) . '/config/pagination_helper.php';

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $recordsPerPage = 10;
    $offset = ($page - 1) * $recordsPerPage;
    
    $totalQuery = "SELECT COUNT(*) FROM new_sub_arrivals";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRecords = mysqli_fetch_array($totalResult)[0];

    $sql = "SELECT * FROM new_sub_arrivals ORDER BY id DESC LIMIT {$offset}, {$recordsPerPage}";
    $result = mysqli_query($conn, $sql);
    $sn = $offset + 1;
    while ($row = mysqli_fetch_assoc($result)) {
      $desc = htmlspecialchars($row['description']);
      $shortDesc = strlen($desc) > 80 ? substr($desc, 0, 80) . "..." : $desc;
      echo '<tr>
        <td>' . $sn++ . '</td>
        <td><img src="../uploads/subarrivals/' . $row['image'] . '" width="60"></td>
        <td>' . $row['name'] . '</td>
        <td><span title="' . $desc . '">' . $shortDesc . '</span></td>
        <td>' . $row['category'] . '</td>
        <td>' . $row['price'] . '</td>
        <td>
          <button class="btn btn-sm btn-primary" onclick="editSubArrival(' . $row['id'] . ')">Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteSubArrival(' . $row['id'] . ')">Delete</button>
        </td>
      </tr>';
    }
    ?>
  </tbody>
</table>

<?php echo renderPagination($totalRecords, $recordsPerPage, $page, 'sub-arrival'); ?>
