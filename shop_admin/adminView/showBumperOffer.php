<?php
include_once(dirname(__DIR__) . "/config/dbconnect.php");
include_once(dirname(__DIR__) . "/config/pagination_helper.php");

$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRes    = $conn->query("SELECT COUNT(*) AS total FROM bumper_offers");
$totalRows   = $totalRes->fetch_assoc()['total'];

// Fetch sub-categories for the join
$result = $conn->query("
    SELECT bo.*, sc.sub_category_name
    FROM bumper_offers bo
    LEFT JOIN sub_category sc ON bo.sub_category_id = sc.id
    ORDER BY bo.id DESC
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
    <h3 class="mb-0">Bumper Offers</h3>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addBumperOfferModal">
      + Add New Bumper Offer
    </button>
  </div>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center" width="5%">S.N.</th>
          <th width="15%">OFFER TITLE</th>
          <th width="15%">TIMER TEXT</th>
          <th class="text-center" width="10%">IMAGE</th>
          <th class="text-center" width="10%">START DATE</th>
          <th class="text-center" width="10%">END DATE</th>
          <th class="text-center" width="5%">STATUS</th>
          <th class="text-center" width="10%">ACTION</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td class="text-center text-muted">#<?= $count++ ?></td>
            
            <td>
              <div class="text-dark font-weight-600" style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                <?= htmlspecialchars($row['title'] ?? '—') ?>
              </div>
            </td>

            <td>
              <span class="text-muted small" style="max-width:200px; display:inline-block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                <?= htmlspecialchars($row['description'] ?? '—') ?>
              </span>
            </td>

            <td class="text-center">
              <?php if (!empty($row['banner_image'])): ?>
                <img src="../uploads/offers/<?= rawurlencode($row['banner_image']) ?>"
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
              <?php if ($row['status'] == 1): ?>
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
                        data-target="#editBumperOfferModal<?= $row['id'] ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-action-delete"
                        title="Delete"
                        onclick="confirmBumperOfferDelete(<?= $row['id'] ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>

              <!-- ===================== EDIT MODAL ===================== -->
              <div class="modal fade text-left" id="editBumperOfferModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header" style="background:#1a1a2e;">
                      <h5 class="modal-title text-white">
                        <i class="fas fa-bolt mr-2" style="color:#c59d2f;"></i>Edit Bumper Offer
                      </h5>
                      <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <form action="controller/bumperOfferController.php" method="POST" enctype="multipart/form-data" class="editBumperOfferForm">
                        <input type="hidden" name="offer_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="update_offer" value="1">

                        <div class="row">
                          <div class="col-md-12 mb-3">
                            <label class="font-weight-bold small">Offer Title</label>
                            <input type="text" name="title" class="form-control"
                                   value="<?= htmlspecialchars($row['title'] ?? '') ?>" required>
                          </div>
                        </div>

                        <div class="mb-3">
                          <label class="font-weight-bold small">Timer Text</label>
                          <textarea name="description" class="form-control" rows="2" placeholder="e.g. Ends in 2 Days!"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
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
                          <label class="font-weight-bold small">Banner Image</label>
                          <input type="file" name="image" class="form-control-file">
                          <?php if (!empty($row['banner_image'])): ?>
                            <img src="../uploads/offers/<?= rawurlencode($row['banner_image']) ?>"
                                 class="mt-2 rounded border" style="max-width:140px; height:80px; object-fit:cover;"
                                 onerror="this.style.display='none'">
                          <?php endif; ?>
                        </div>

                        <div class="form-check mb-3">
                          <input class="form-check-input" type="checkbox" name="status" value="1"
                                 <?= ($row['status'] == 1) ? 'checked' : '' ?>>
                          <label class="form-check-label font-weight-bold">Active</label>
                        </div>

                        <div class="text-right">
                          <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
                          <button type="button" class="btn btn-success btn-sm px-4" onclick="forceSubmitBumperOffer(this)">
                            <i class="fas fa-save mr-1"></i> Update Offer
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <!-- ===================== END EDIT MODAL ===================== -->

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center py-5 text-muted">No bumper offers found.</td>
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
    <?= renderPagination($totalRows, $limit, $page, 'bumper-offer') ?>
  </div>

</div>

<!-- ===================== ADD OFFER MODAL ===================== -->
<div class="modal fade" id="addBumperOfferModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a1a2e;">
        <h5 class="modal-title text-white">
          <i class="fas fa-plus-circle mr-2" style="color:#c59d2f;"></i>Add New Bumper Offer
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="controller/bumperOfferController.php" method="POST" enctype="multipart/form-data" id="addBumperOfferForm">
          <input type="hidden" name="save_offer" value="1">

          <div class="row">
            <div class="col-md-12 mb-3">
              <label class="font-weight-bold small">Offer Title</label>
              <input type="text" name="title" class="form-control" placeholder="e.g. Mega Flash Sale" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Timer Text</label>
            <textarea name="description" class="form-control" rows="2" placeholder="e.g. Ends in 2 Days!"></textarea>
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
            <label class="font-weight-bold small">Offer Banner Image</label>
            <input type="file" name="image" class="form-control-file">
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
            <label class="form-check-label font-weight-bold">Active</label>
          </div>

          <div class="text-right">
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success btn-sm px-4" onclick="forceSubmitBumperOffer(this)">
              <i class="fas fa-save mr-1"></i> Save Offer
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
function getBumperOfferCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
}



window.forceSubmitBumperOffer = function(btn) {
    console.log("Edit Bumper Offer Form Submitting manually...");
    
    try {
        let form = $(btn).closest('form')[0];
        let formData = new FormData(form);
        let actionUrl = "controller/bumperOfferController.php";
        console.log("Action URL explicitly set to:", actionUrl);
        
        let submitBtn = $(btn);
        let originalHtml = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...').prop('disabled', true);

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("Backend Response:", response);
                if (response.trim() === 'success') {
                    showToast("Bumper offer updated successfully!", "success");
                    $('.modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadModule('bumper-offer', getBumperOfferCurrentPage());
                } else {
                    submitBtn.html(originalHtml).prop('disabled', false);
                    alert("Backend Error: " + response);
                    showToast("Error: " + response, "danger");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error", xhr.responseText);
                submitBtn.html(originalHtml).prop('disabled', false);
                alert("AJAX Error: " + error + "\n" + xhr.responseText);
                showToast("Failed to update offer", "danger");
            }
        });
    } catch (err) {
        console.error("Form JS Error:", err);
        alert("Javascript Error during submit: " + err.message);
    }
};

function confirmBumperOfferDelete(offerId) {
  showConfirm(
    "Delete Offer?",
    "Are you sure you want to delete this bumper offer? This action cannot be undone.",
    function () {
        $.ajax({
            url: "controller/bumperOfferController.php",
            type: "POST",
            data: {delete_offer: offerId},
            success: function(response){
                if (response.trim() === 'success') {
                    showToast("Offer Deleted!", "danger");
                    loadModule('bumper-offer', getBumperOfferCurrentPage());
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
