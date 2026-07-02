<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");
include_once(dirname(__DIR__) . "/config/pagination_helper.php");

$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRes    = $conn->query("SELECT COUNT(*) AS total FROM special_offer");
$totalRows   = $totalRes->fetch_assoc()['total'];

// Fetch sub-categories for the join
$result = $conn->query("
    SELECT so.*, sc.sub_category_name
    FROM special_offer so
    LEFT JOIN sub_category sc ON so.sub_category_id = sc.id
    ORDER BY so.id DESC
    LIMIT $limit OFFSET $offset
");
$count = $offset + 1;
?>

<style>
  .compressed-table td,
  .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.82rem;
    vertical-align: middle !important;
  }
  .offer-thumb {
    width: 52px;
    height: 52px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s;
  }
  .offer-thumb:hover {
    transform: scale(1.6);
    z-index: 10;
    position: relative;
  }
  .status-active {
    background: #d1fae5;
    color: #065f46;
    padding: 2px 10px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.72rem;
  }
  .status-inactive {
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 10px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.72rem;
  }
  .date-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
  }
  /* Override pagination to match gold theme */
  .page-item.active .page-link {
    background-color: #c59d2f !important;
    border-color: #c59d2f !important;
  }
  .page-link { color: #c59d2f; }
  .page-link:hover { color: #9a7a20; }
</style>

<div class="container-fluid mt-3">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Special Offers</h3>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addOfferModal">
      + Add New Offer
    </button>
  </div>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center">S.N.</th>
          <th>Offer Title</th>
          <th>Timer Text</th>
          <th>Subcategory</th>
          <th class="text-center">Image</th>
          <th class="text-center">Start Date</th>
          <th class="text-center">End Date</th>
          <th class="text-center">Status</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td class="text-center text-muted">#<?= $count++ ?></td>

            <td>
              <div class="text-dark font-weight-600" style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                <?= htmlspecialchars($row['title'] ?? '') ?>
              </div>
            </td>

            <td>
              <div style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#64748b;">
                <?= htmlspecialchars($row['timer_text'] ?? '') ?>
              </div>
            </td>

            <td>
              <span class="badge badge-light border">
                <?= htmlspecialchars($row['sub_category_name'] ?? '—') ?>
              </span>
            </td>

            <td class="text-center">
              <?php if (!empty($row['image'])): ?>
                <img src="../uploads/offers/<?= rawurlencode($row['image']) ?>"
                     alt="Offer Image"
                     class="offer-thumb"
                     onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
              <?php else: ?>
                <span class="text-muted small">No Image</span>
              <?php endif; ?>
            </td>

            <td class="text-center">
              <span class="date-badge"><?= date('d M Y', strtotime($row['start_date'])) ?></span>
            </td>

            <td class="text-center">
              <span class="date-badge"><?= date('d M Y', strtotime($row['end_date'])) ?></span>
            </td>

            <td class="text-center">
              <?php if ($row['active'] == 1): ?>
                <span class="status-active">Active</span>
              <?php else: ?>
                <span class="status-inactive">Inactive</span>
              <?php endif; ?>
            </td>

            <td>
              <div class="action-btn-group justify-content-center">
                <button class="btn-action btn-action-edit"
                        title="Edit"
                        data-toggle="modal"
                        data-target="#editOfferModal<?= $row['id'] ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-action-delete"
                        title="Delete"
                        onclick="confirmOfferDelete(<?= $row['id'] ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>

          <!-- ===================== EDIT MODAL ===================== -->
          <div class="modal fade" id="editOfferModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header" style="background:#1a1a2e;">
                  <h5 class="modal-title text-white">
                    <i class="fas fa-tag mr-2" style="color:#c59d2f;"></i>Edit Special Offer
                  </h5>
                  <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <form action="controller/offerController.php" method="POST" enctype="multipart/form-data" class="editOfferForm">
                    <input type="hidden" name="offer_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="update_offer" value="1">

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small">Offer Title</label>
                        <input type="text" name="offer_title" class="form-control"
                               value="<?= htmlspecialchars($row['title'] ?? '') ?>" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small">Subcategory</label>
                        <select name="sub_category_id" class="form-control">
                          <option value="">-- Select Subcategory --</option>
                          <?php
                          $subRes2 = $conn->query("SELECT * FROM sub_category ORDER BY id ASC");
                          while ($sc = $subRes2->fetch_assoc()):
                            $sel = ($sc['id'] == $row['sub_category_id']) ? 'selected' : '';
                          ?>
                          <option value="<?= $sc['id'] ?>" <?= $sel ?>><?= htmlspecialchars($sc['sub_category_name']) ?></option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="font-weight-bold small">Timer Text / Details</label>
                      <textarea name="timer_text" class="form-control" rows="2"><?= htmlspecialchars($row['timer_text'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small">Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                               value="<?= date('Y-m-d', strtotime($row['start_date'])) ?>" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small">End Date</label>
                        <input type="date" name="end_date" class="form-control"
                               value="<?= date('Y-m-d', strtotime($row['end_date'])) ?>" required>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="font-weight-bold small">Offer Image</label>
                      <input type="file" name="image" class="form-control-file">
                      <?php if (!empty($row['image'])): ?>
                        <img src="../uploads/offers/<?= rawurlencode($row['image']) ?>"
                             class="mt-2 rounded border" style="max-width:140px; height:80px; object-fit:cover;"
                             onerror="this.style.display='none'">
                      <?php endif; ?>
                    </div>

                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" name="active" value="1"
                             <?= ($row['active'] == 1) ? 'checked' : '' ?>>
                      <label class="form-check-label font-weight-bold">Active</label>
                    </div>

                    <div class="text-right">
                      <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-success btn-sm px-4">
                        <i class="fas fa-save mr-1"></i> Update Offer
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- ===================== END EDIT MODAL ===================== -->

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center py-5 text-muted">No special offers found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">
      Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> offers
    </small>
    <?= renderPagination($totalRows, $limit, $page, 'special-offers') ?>
  </div>

</div>

<!-- ===================== ADD OFFER MODAL ===================== -->
<div class="modal fade" id="addOfferModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a1a2e;">
        <h5 class="modal-title text-white">
          <i class="fas fa-plus-circle mr-2" style="color:#c59d2f;"></i>Add New Special Offer
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="controller/offerController.php" method="POST" enctype="multipart/form-data" id="addOfferForm">
          <input type="hidden" name="save_offer" value="1">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Offer Title</label>
              <input type="text" name="offer_title" class="form-control" placeholder="e.g. Buy 2 Get 1 Free" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Subcategory</label>
              <select name="sub_category_id" class="form-control">
                <option value="">-- Select Subcategory --</option>
                <?php
                $subCatAll = $conn->query("SELECT * FROM sub_category ORDER BY id ASC");
                while ($sc = $subCatAll->fetch_assoc()):
                ?>
                <option value="<?= $sc['id'] ?>"><?= htmlspecialchars($sc['sub_category_name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Timer Text / Details</label>
            <textarea name="timer_text" class="form-control" rows="2" placeholder="Description for the offer"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Start Date</label>
              <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">End Date</label>
              <input type="date" name="end_date" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Offer Image</label>
            <input type="file" name="image" class="form-control-file">
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" value="1" checked>
            <label class="form-check-label font-weight-bold">Active</label>
          </div>

          <div class="text-right">
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success btn-sm px-4">
              <i class="fas fa-save mr-1"></i> Save Offer
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
function getSpecialOfferCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
}

$('#addOfferForm').on('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let actionUrl = $(this).attr('action');

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if (response.trim() === 'success') {
                showToast("Offer added successfully!", "success");
                $('.modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadModule('special-offers', getSpecialOfferCurrentPage());
            } else {
                alert("Backend Error: " + response);
                showToast("Error: " + response, "danger");
            }
        },
        error: function(xhr, status, error) {
            alert("AJAX Error: " + error);
            showToast("Failed to add offer", "danger");
        }
    });
});

$('.editOfferForm').on('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let actionUrl = $(this).attr('action');

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if (response.trim() === 'success') {
                showToast("Offer updated successfully!", "success");
                $('.modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadModule('special-offers', getSpecialOfferCurrentPage());
            } else {
                alert("Backend Error: " + response);
                showToast("Error: " + response, "danger");
            }
        },
        error: function(xhr, status, error) {
            alert("AJAX Error: " + error);
            showToast("Failed to update offer", "danger");
        }
    });
});

function confirmOfferDelete(offerId) {
  showConfirm(
    "Delete Offer?",
    "Are you sure you want to delete this special offer? This action cannot be undone.",
    function () {
        $.ajax({
            url: "controller/offerController.php",
            type: "POST",
            data: {delete_offer: offerId},
            success: function(response){
                if (response.trim() === 'success') {
                    showToast("Offer Deleted!", "danger");
                    loadModule('special-offers', getSpecialOfferCurrentPage());
                } else {
                    showToast("Error: " + response, "danger");
                }
            },
            error: function() {
                showToast("Failed to delete offer", "danger");
            }
        });
    }
  );
}
</script>
