<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRes  = $conn->query("SELECT COUNT(*) AS total FROM gif_banner");
$totalRows = $totalRes->fetch_assoc()['total'];

$result = $conn->query("SELECT * FROM gif_banner ORDER BY id DESC LIMIT $limit OFFSET $offset");
$count  = $offset + 1;
?>

<style>
  .compressed-table td,
  .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.82rem;
    vertical-align: middle !important;
  }
  .gif-thumb {
    width: 90px;
    height: 52px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: transform 0.25s;
  }
  .gif-thumb:hover {
    transform: scale(2.2);
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
  .page-link { color: #c59d2f; }
  .page-link:hover { color: #9a7a20; }
</style>

<div class="container-fluid mt-3">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">GIF Banners</h3>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGifModal">
      + Upload GIF Banner
    </button>
  </div>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center">S.N.</th>
          <th>Banner Title</th>
          <th class="text-center">Preview</th>
          <th class="text-center">Uploaded At</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td class="text-center text-muted">#<?= $count++ ?></td>

            <td>
              <div class="text-dark font-weight-600" style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                <?= htmlspecialchars($row['title'] ?? '') ?>
              </div>
            </td>

            <td class="text-center">
              <?php
                $imgSrc = '../uploads/banner/' . rawurlencode($row['image']);
              ?>
              <?php if (!empty($row['image'])): ?>
                <img src="<?= $imgSrc ?>"
                     alt="<?= htmlspecialchars($row['title']) ?>"
                     class="gif-thumb"
                     onerror="this.onerror=null;this.src='assets/images/placeholder.png';">
              <?php else: ?>
                <span class="text-muted small">No File</span>
              <?php endif; ?>
            </td>

            <td class="text-center">
              <span class="date-badge">
                <?= !empty($row['created_at']) ? date('d M Y, h:i A', strtotime($row['created_at'])) : '—' ?>
              </span>
            </td>

            <td>
              <div class="action-btn-group justify-content-center">
                <button class="btn-action btn-action-delete"
                        title="Delete"
                        onclick="confirmGifDelete(<?= (int)$row['id'] ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">No GIF banners uploaded yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">
      Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> banners
    </small>
    <?= renderPagination($totalRows, $limit, $page, 'gif-banners') ?>
  </div>

</div>

<!-- ===================== ADD GIF BANNER MODAL ===================== -->
<div class="modal fade" id="addGifModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a1a2e;">
        <h5 class="modal-title text-white">
          <i class="fas fa-image mr-2" style="color:#c59d2f;"></i>Upload GIF Banner
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="controller/saveGifBanner.php" method="POST" enctype="multipart/form-data" id="addGifForm">

          <div class="mb-3">
            <label class="font-weight-bold small">Banner Title</label>
            <input type="text" name="banner_title" class="form-control" placeholder="Enter banner title..." required>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold small">Select GIF File</label>
            <input type="file" name="gif_file" class="form-control-file" accept="image/gif,image/*" required>
            <small class="text-muted">Accepted: .gif, .png, .jpg, .webp</small>
          </div>

          <div class="text-right mt-3">
            <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success btn-sm px-4">
              <i class="fas fa-upload mr-1"></i> Upload Banner
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
function getGifBannerCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
}

$('#addGifForm').on('submit', function(e){
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
                showToast("GIF Banner uploaded successfully!", "success");
                $('.modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadModule('gif-banners', getGifBannerCurrentPage());
            } else {
                alert("Backend Error: " + response);
                showToast("Error: " + response, "danger");
            }
        },
        error: function(xhr, status, error) {
            alert("AJAX Error: " + error);
            showToast("Failed to upload banner", "danger");
        }
    });
});

function confirmGifDelete(id) {
  showConfirm(
    "Delete Banner?",
    "Are you sure you want to delete this GIF banner? This action cannot be undone.",
    function () {
        $.ajax({
            url: "controller/saveGifBanner.php",
            type: "POST",
            data: {delete_banner: id},
            success: function(response){
                if (response.trim() === 'success') {
                    showToast("Banner Deleted!", "danger");
                    loadModule('gif-banners', getGifBannerCurrentPage());
                } else {
                    showToast("Error: " + response, "danger");
                }
            },
            error: function() {
                showToast("Failed to delete banner", "danger");
            }
        });
    }
  );
}
</script>
