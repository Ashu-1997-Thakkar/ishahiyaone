<?php 
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM vouchers ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
$count = $offset + 1;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM vouchers");
$totalRows = $totalRes->fetch_assoc()['total'];
?>

<style>
  .compressed-table td, .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.9rem;
    vertical-align: middle !important;
  }
  .voucher-img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s;
  }
  .voucher-img:hover {
    transform: scale(1.4);
    z-index: 5;
    position: relative;
  }
  .status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
  }
  .status-active { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
  .status-inactive { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
</style>

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Festival Offers Management</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addVoucherModal">
      + Add Festival Offer
    </button>
  </div>

  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center">S.N.</th>
          <th>Offer Image</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th class="text-center">Status</th>
          <th>Type</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) { 
            $statusClass = ($row['status'] == 'Active') ? 'status-active' : 'status-inactive';
        ?>
          <tr>
            <td class="text-center text-muted">#<?= $count++ ?></td>
            <td>
              <?php if (!empty($row['image'])) { ?>
                <img src="./uploads/<?= htmlspecialchars($row['image']); ?>" class="voucher-img" 
                     onerror="this.onerror=null; this.src='assets/images/placeholder.png';">
              <?php } else { echo "<span class='text-muted small'>No Image</span>"; } ?>
            </td>
            <td><span class="text-dark font-weight-600"><?= htmlspecialchars($row['start_date']); ?></span></td>
            <td><span class="text-dark font-weight-600"><?= htmlspecialchars($row['end_date']); ?></span></td>
            <td class="text-center">
              <span class="status-badge <?= $statusClass ?>">
                <?= htmlspecialchars($row['status']); ?>
              </span>
            </td>
            <td>
              <span class="badge badge-light border"><?= htmlspecialchars($row['Type']); ?></span>
            </td>
            <td>
              <div class="action-btn-group justify-content-center">
                <button class="btn-action btn-action-edit" title="Edit" onclick="editVoucher(<?= $row['id']; ?>)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-action-delete" title="Delete" onclick="handleVoucherDelete(<?= $row['id']; ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
        <?php } } else {
          echo "<tr><td colspan='7' class='text-center py-5 text-muted'>No vouchers found.</td></tr>";
        } ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> vouchers</small>
    <?= renderPagination($totalRows, $limit, $page, 'festivals') ?>
  </div>
</div>

<!-- Add Voucher Modal -->
<div class="modal fade" id="addVoucherModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create New Voucher</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="addVoucherForm" method="POST" action="adminView/storeVoucher.php" enctype="multipart/form-data" onsubmit="submitVoucherForm(event, this)">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Offer Banner Image</label>
            <input type="file" class="form-control" name="image" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Type</label>
              <select class="form-select" name="type" required>
                <option value="">Select Type</option>
                <option value="Festival">Festival</option>
                <option value="Discount">Discount</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" name="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control" name="start_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">End Date</label>
              <input type="date" class="form-control" name="end_date" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Publish Offer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Offer Modal -->
<div class="modal fade" id="editVoucherModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Offer</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="editVoucherForm" method="POST" action="adminView/updateVoucher.php" enctype="multipart/form-data" onsubmit="submitVoucherForm(event, this)">
        <div class="modal-body" id="editVoucherBody">
          <!-- AJAX Content -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Update Offer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editVoucher(id){
  $.ajax({
    url: "adminView/getVoucher.php", 
    type: "GET",
    data: {id: id},
    success: function(response){
      $("#editVoucherBody").html(response);
      $("#editVoucherModal").modal("show");
    }
  });
}

function getFestivalCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
}

function submitVoucherForm(event, formElement) {
    event.preventDefault();
    let formData = new FormData(formElement);
    let actionUrl = $(formElement).attr('action');

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if (response.trim() === 'success') {
                showToast("Offer saved successfully!", "success");
                $('.modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadModule('festivals', getFestivalCurrentPage());
            } else {
                showToast("Error: " + response, "danger");
            }
        },
        error: function() {
            showToast("Failed to save voucher", "danger");
        }
    });
}

function handleVoucherDelete(id){
    showConfirm(
        "Delete Voucher?",
        "Are you sure you want to remove this festival voucher? This will hide it from the storefront immediately.",
        function() {
            $.ajax({
                url: "adminView/deleteVoucher.php",
                type: "POST",
                data: {id: id},
                success: function(response){
                    showToast("Offer Deleted!", "danger");
                    loadModule('festivals', getFestivalCurrentPage());
                },
                error: function() {
                    showToast("Failed to delete voucher", "danger");
                }
            });
        }
    );
}
</script>
