<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * from category ORDER BY category_id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$count = $offset + 1;

$totalRes = $conn->query("SELECT COUNT(*) as total FROM category");
$totalRows = $totalRes->fetch_assoc()['total'];
?>

<style>
  .compressed-table td, .compressed-table th {
    padding: 8px 12px !important; /* Half padding for compressed look */
    font-size: 0.9rem;
  }
  .compressed-table thead th {
    padding: 12px 12px !important;
  }
</style>

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Category Items</h3>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
      + New Category
    </button>
  </div>

  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center" style="width: 80px;">S.N.</th>
          <th>Category Name</th>
          <th class="text-center" style="width: 100px;">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if ($result && $result->num_rows > 0){
          while ($row=$result-> fetch_assoc()) {
      ?>
      <tr>
        <td class="text-center"><span class="font-weight-bold">#<?=$count?></span></td>
        <td><span class="text-dark font-weight-600"><?=htmlspecialchars($row["category_name"])?></span></td>   
        <td>
          <div class="action-btn-group justify-content-center">
            <button class="btn-action btn-action-delete" title="Delete" onclick="handleCategoryDelete('<?=$row['category_id']?>')">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php
            $count++;
          }
        } else {
          echo "<tr><td colspan='3' class='text-center py-4 text-muted'>No Categories found</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> categories</small>
    <?= renderPagination($totalRows, $limit, $page, 'categories') ?>
  </div>

  <!-- Add Category Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Category Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form enctype='multipart/form-data' action="./controller/addCatController.php" method="POST">
            <div class="form-group mb-3">
              <label for="c_name">Category Name:</label>
              <input type="text" class="form-control" name="c_name" id="c_name" required autocomplete="off" placeholder="Enter category name">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success w-100" name="upload">Add Category</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function handleCategoryDelete(id) {
    showConfirm(
        "Delete Category Item?",
        "Are you sure you want to delete this category item? This action cannot be undone.",
        function() {
            categoryDelete(id);
        }
    );
}
</script>