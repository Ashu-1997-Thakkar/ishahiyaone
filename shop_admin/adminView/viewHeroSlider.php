<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRes  = $conn->query("SELECT COUNT(*) AS total FROM hero_slider");
$totalRows = $totalRes->fetch_assoc()['total'];

$result = $conn->query("SELECT * FROM hero_slider ORDER BY id DESC LIMIT $limit OFFSET $offset");
$count  = $offset + 1;

// Fetch main categories for the redirect link dropdown
$mainCatResult = $conn->query("SELECT * FROM main_category ORDER BY main_category_name ASC");
$mainCategories = [];
if ($mainCatResult) {
  while ($row = $mainCatResult->fetch_assoc()) {
    $mainCategories[] = $row;
  }
}
?>

<style>
  .compressed-table td,
  .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.82rem;
    vertical-align: middle !important;
  }

  .slider-thumb {
    width: 120px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: transform 0.25s;
  }

  .slider-thumb:hover {
    transform: scale(2.5);
    z-index: 20;
    position: relative;
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

  .page-item.active .page-link {
    background-color: #c59d2f !important;
    border-color: #c59d2f !important;
  }

  .page-link {
    color: #c59d2f;
  }

  .page-link:hover {
    color: #9a7a20;
  }
</style>

<div class="container-fluid mt-3">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Hero Slider Management</h3>
    <div class="d-flex align-items-center" style="gap: 10px;">
      <button class="btn btn-danger btn-sm" id="btnDeleteSelectedHeroSlider" style="display:none;" onclick="deleteSelectedHeroSlider()">
        <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountHeroSlider">0</span>)
      </button>
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSliderModal">
        + Add New Slide
      </button>
    </div>
  </div>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center" style="width: 40px;">
            <input type="checkbox" id="selectAllHeroSlider" style="cursor: pointer;">
          </th>
          <th class="text-center">S.N.</th>
          <th class="text-center">Preview</th>
          <th>Title & Subtitle</th>
          <th>Link & Button</th>
          <th class="text-center">Created At</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="heroslider-checkbox" value="<?= $row['id'] ?>" style="cursor: pointer;">
              </td>
              <td class="text-center text-muted">#<?= $count++ ?></td>

              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
                  <div>
                    <?php if (!empty($row['image'])): ?>
                      <img src="../uploads/slider/<?= rawurlencode($row['image']) ?>"
                        alt="Image 1"
                        class="slider-thumb"
                        style="width: 45px; height: 45px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
                      <div class="small text-muted mt-1" style="font-size: 0.65rem;">Image 1</div>
                    <?php else: ?>
                      <span class="text-muted small" style="font-size: 0.65rem;">No Image 1</span>
                    <?php endif; ?>
                  </div>
                  <div>
                    <?php if (!empty($row['mobile_image'])): ?>
                      <img src="../uploads/slider/<?= rawurlencode($row['mobile_image']) ?>"
                        alt="Image 2"
                        class="slider-thumb"
                        style="width: 45px; height: 45px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
                      <div class="small text-muted mt-1" style="font-size: 0.65rem;">Image 2</div>
                    <?php else: ?>
                      <span class="text-muted small" style="font-size: 0.65rem;">No Image 2</span>
                    <?php endif; ?>
                  </div>
                  <div>
                    <?php if (!empty($row['image_3'])): ?>
                      <img src="../uploads/slider/<?= rawurlencode($row['image_3']) ?>"
                        alt="Image 3"
                        class="slider-thumb"
                        style="width: 45px; height: 45px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
                      <div class="small text-muted mt-1" style="font-size: 0.65rem;">Image 3</div>
                    <?php else: ?>
                      <span class="text-muted small" style="font-size: 0.65rem;">No Image 3</span>
                    <?php endif; ?>
                  </div>
                  <div>
                    <?php if (!empty($row['image_4'])): ?>
                      <img src="../uploads/slider/<?= rawurlencode($row['image_4']) ?>"
                        alt="Image 4"
                        class="slider-thumb"
                        style="width: 45px; height: 45px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
                      <div class="small text-muted mt-1" style="font-size: 0.65rem;">Image 4</div>
                    <?php else: ?>
                      <span class="text-muted small" style="font-size: 0.65rem;">No Image 4</span>
                    <?php endif; ?>
                  </div>
                </div>
              </td>

              <td>
                <div class="font-weight-600 text-dark"><?= htmlspecialchars($row['title'] ?? '—') ?></div>
                <div class="small text-muted" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  <?= htmlspecialchars($row['subtitle'] ?? '—') ?>
                </div>
              </td>

              <td>
                <div class="small">Link: <span class="text-primary"><?= htmlspecialchars($row['link'] ?? '—') ?></span></div>
                <div class="small">Btn: <span class="badge badge-info"><?= htmlspecialchars($row['btn_text'] ?? 'Shop Now') ?></span></div>
              </td>

              <td class="text-center">
                <span class="date-badge">
                  <?= !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '—' ?>
                </span>
              </td>

              <td>
                <div class="action-btn-group justify-content-center">
                  <button class="btn-action btn-action-edit"
                    title="Edit"
                    onclick="editSlider(<?= (int)$row['id'] ?>, '<?= htmlspecialchars(addslashes($row['title'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($row['subtitle'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($row['link'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($row['btn_text'] ?? ''), ENT_QUOTES) ?>')">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button class="btn-action btn-action-delete"
                    title="Delete"
                    onclick="confirmSliderDelete(<?= (int)$row['id'] ?>)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">No slider images uploaded yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">
      Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> slides
    </small>
    <?= renderPagination($totalRows, $limit, $page, 'hero-slider') ?>
  </div>

</div>

<!-- ===================== ADD SLIDER MODAL ===================== -->
<div class="modal fade" id="addSliderModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a1a2e;">
        <h5 class="modal-title text-white">
          <i class="fas fa-images mr-2" style="color:#c59d2f;"></i>Add New Hero Slide
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="controller/saveHeroSlider.php" method="POST" enctype="multipart/form-data" id="addSliderForm">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Slide Title</label>
              <input type="text" name="title" class="form-control" placeholder="E.g. New Season">
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Button Text</label>
              <input type="text" name="btn_text" class="form-control" placeholder="E.g. Shop Now">
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Main Subtitle</label>
            <textarea name="subtitle" class="form-control" rows="2" placeholder="E.g. ELEVATE YOUR STYLE"></textarea>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Redirect Link</label>
            <select name="link" class="form-control">
              <option value="bumper-offers">http://localhost/ishahiyaone/bumper-offers</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Select Image 1 (800x800 recommended)</label>
              <input type="file" name="slider_image" class="form-control-file" accept="image/*" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Select Image 2 (Optional - 800x800 recommended)</label>
              <input type="file" name="mobile_image" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Select Image 3 (Optional - 800x800 recommended)</label>
              <input type="file" name="slider_image_3" class="form-control-file" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Select Image 4 (Optional - 800x800 recommended)</label>
              <input type="file" name="slider_image_4" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div class="text-right mt-3">
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success btn-sm px-4">
              <i class="fas fa-upload mr-1"></i> Save Slide
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- ===================== EDIT SLIDER MODAL ===================== -->
<div class="modal fade" id="editSliderModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a1a2e;">
        <h5 class="modal-title text-white">
          <i class="fas fa-edit mr-2" style="color:#c59d2f;"></i>Edit Hero Slide
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="editSliderForm">
          <input type="hidden" name="edit_slider_id" id="edit_slider_id">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Slide Title</label>
              <input type="text" name="title" id="edit_title" class="form-control" placeholder="E.g. New Season">
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Button Text</label>
              <input type="text" name="btn_text" id="edit_btn_text" class="form-control" placeholder="E.g. Shop Now">
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Main Subtitle</label>
            <textarea name="subtitle" id="edit_subtitle" class="form-control" rows="2" placeholder="E.g. ELEVATE YOUR STYLE"></textarea>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Redirect Link</label>
            <select name="link" id="edit_link" class="form-control">
              <option value="bumper-offers">http://localhost/ishahiyaone/bumper-offers</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Replace Image 1 <span class="text-muted">(leave blank to keep current)</span></label>
              <input type="file" name="slider_image" id="edit_slider_image" class="form-control-file" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Replace Image 2 <span class="text-muted">(leave blank to keep current)</span></label>
              <input type="file" name="mobile_image" id="edit_mobile_image" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Replace Image 3 <span class="text-muted">(leave blank to keep current)</span></label>
              <input type="file" name="slider_image_3" id="edit_slider_image_3" class="form-control-file" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
              <label class="font-weight-bold small">Replace Image 4 <span class="text-muted">(leave blank to keep current)</span></label>
              <input type="file" name="slider_image_4" id="edit_slider_image_4" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div class="text-right mt-3">
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm px-4">
              <i class="fas fa-save mr-1"></i> Update Slide
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function getSliderCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
  }

  // ── ADD ──────────────────────────────────────────────────────────────────────
  $('#addSliderForm').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let actionUrl = $(this).attr('action');

    $.ajax({
      url: actionUrl,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.trim() === 'success') {
          showToast("Hero Slide added successfully!", "success");
          $('.modal').modal('hide');
          $('body').removeClass('modal-open');
          $('.modal-backdrop').remove();
          loadModule('hero-slider', getSliderCurrentPage());
        } else {
          alert("Backend Error: " + response);
        }
      },
      error: function() {
        showToast("Failed to upload slide", "danger");
      }
    });
  });

  // ── EDIT ─────────────────────────────────────────────────────────────────────
  function editSlider(id, title, subtitle, link, btn_text) {
    $('#edit_slider_id').val(id);
    $('#edit_title').val(title);
    $('#edit_subtitle').val(subtitle);
    $('#edit_link').val(link);
    $('#edit_btn_text').val(btn_text);
    $('#edit_slider_image').val('');
    $('#edit_mobile_image').val('');
    $('#edit_slider_image_3').val('');
    $('#edit_slider_image_4').val('');
    $('#editSliderModal').modal('show');
  }

  $('#editSliderForm').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('update_slider', 1);

    $.ajax({
      url: "controller/saveHeroSlider.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.trim() === 'success') {
          showToast("Slide updated successfully!", "success");
          $('#editSliderModal').modal('hide');
          $('body').removeClass('modal-open');
          $('.modal-backdrop').remove();
          loadModule('hero-slider', getSliderCurrentPage());
        } else {
          alert("Backend Error: " + response);
        }
      },
      error: function() {
        showToast("Failed to update slide", "danger");
      }
    });
  });

  // ── DELETE ────────────────────────────────────────────────────────────────────
  function confirmSliderDelete(id) {
    showConfirm(
      "Delete Slide?",
      "Are you sure you want to delete this hero slide? It will be removed from the website homepage.",
      function() {
        $.ajax({
          url: "controller/saveHeroSlider.php",
          type: "POST",
          data: {
            delete_slider: id
          },
          success: function(response) {
            if (response.trim() === 'success') {
              showToast("Slide Deleted!", "danger");
              loadModule('hero-slider', getSliderCurrentPage());
            } else {
              showToast("Error: " + response, "danger");
            }
          },
          error: function() {
            showToast("Failed to delete slide", "danger");
          }
        });
      }
    );
  }

  // ── SELECT ALL & BULK DELETE ──────────────────────────────────────────────────
  function updateBulkDeleteHeroSliderBtn() {
    let selectedCount = $('.heroslider-checkbox:checked').length;
    if (selectedCount > 0) {
      $('#selectedCountHeroSlider').text(selectedCount);
      $('#btnDeleteSelectedHeroSlider').fadeIn(200);
    } else {
      $('#btnDeleteSelectedHeroSlider').fadeOut(200);
    }
    let totalCount = $('.heroslider-checkbox').length;
    $('#selectAllHeroSlider').prop('checked', totalCount > 0 && selectedCount === totalCount);
  }

  $('#selectAllHeroSlider').on('change', function() {
    $('.heroslider-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteHeroSliderBtn();
  });

  $(document).on('change', '.heroslider-checkbox', function() {
    updateBulkDeleteHeroSliderBtn();
  });

  function deleteSelectedHeroSlider() {
    let selectedIds = [];
    $('.heroslider-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
      "Delete Multiple Slides?",
      `Are you sure you want to delete ${selectedIds.length} selected slides?`,
      function() {
        let errors = 0;
        $('#btnDeleteSelectedHeroSlider').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

        let requests = selectedIds.map(id =>
          $.ajax({
            url: "controller/saveHeroSlider.php",
            type: "POST",
            data: { delete_slider: id },
            error: function() { errors++; }
          })
        );

        $.when.apply($, requests).always(function() {
          if (errors === 0) {
            showToast(`Successfully deleted ${selectedIds.length} slides!`, 'success');
          } else {
            showToast(`Deleted with some errors.`, 'warning');
          }
          loadModule('hero-slider', getSliderCurrentPage());
        });
      }
    );
  }
</script>