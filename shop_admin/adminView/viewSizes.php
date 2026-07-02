<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * from sizes ORDER BY size_id ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$count = $offset + 1;

$totalRes = $conn->query("SELECT COUNT(*) as total FROM sizes");
$totalRows = $totalRes->fetch_assoc()['total'];
?>

<style>
  .compressed-table td, .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.9rem;
  }
  .compressed-table thead th {
    padding: 12px 12px !important;
  }
</style>

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Available Sizes</h3>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
      + New Size
    </button>
  </div>

  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center" style="width: 80px;">S.N.</th>
          <th>Size</th>
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
        <td><span class="text-dark font-weight-600"><?=htmlspecialchars($row["size_name"])?></span></td>   
        <td>
          <div class="action-btn-group justify-content-center">
            <button class="btn-action btn-action-delete" title="Delete" onclick="handleSizeDelete('<?=$row['size_id']?>')">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php
            $count++;
          }
        } else {
          echo "<tr><td colspan='3' class='text-center py-4 text-muted'>No Sizes found</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> records</small>
    <?= renderPagination($totalRows, $limit, $page, 'sizes') ?>
  </div>

  <!-- Add Size Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Size Record</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="addSizeForm">
            <div class="form-group mb-3">
              <label for="size">Size Name / Number:</label>
              <input type="text" class="form-control" name="size" id="size" required autocomplete="off" placeholder="e.g. XL, 42, 10 to 11 Y">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success w-100">Add Size</button>
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
$(document).off("submit", "#addSizeForm").on("submit", "#addSizeForm", function(e){
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

    $.post("./controller/addSizeController.php", $(this).serialize() + "&upload=1", function(res){
        btn.prop('disabled', false).text('Add Size');
        if(res.trim() == "success"){
            showToast("Size Successfully Added", "success");
            $("#myModal").modal("hide");
            // Keep current page
            const params = new URLSearchParams(window.location.hash.split('?')[1]);
            const page = params.get('page') || 1;
            loadModule('sizes', page);
        } else {
            showToast("Error: " + res, "danger");
        }
    });
});

function handleSizeDelete(id) {
    showConfirm(
        "Delete Size Record?",
        "Are you sure you want to delete this size record? This action cannot be undone.",
        function() {
            sizeDelete(id);
        }
    );
}
</script>