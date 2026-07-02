<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
require_once dirname(__DIR__) . '/config/pagination_helper.php';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;
$offset = ($page - 1) * $recordsPerPage;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$whereClause = "1=1";
if (!empty($search)) {
    $escapedSearch = mysqli_real_escape_string($conn, $search);
    $whereClause .= " AND (sc.name LIKE '%$escapedSearch%' OR sc.id LIKE '%$escapedSearch%' OR sc.category_id LIKE '%$escapedSearch%')";
}

$totalQuery = "SELECT COUNT(*) FROM subcategories sc WHERE $whereClause";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRecords = mysqli_fetch_array($totalResult)[0];

$sql = "SELECT 
    sc.id AS product_id, 
    sc.name, 
    COALESCE(mc.main_category_name, '') AS main_category_name,
    COALESCE(sub.sub_category_name, '') AS category_name, 
    sc.image1, 
    sc.category_id, 
    sc.Stock
  FROM subcategories sc
  LEFT JOIN main_category mc ON sc.main_category_id = mc.id
  LEFT JOIN sub_category sub ON sc.category_id = sub.id
  WHERE $whereClause
  ORDER BY sc.id DESC
  LIMIT {$offset}, {$recordsPerPage}";

$result = mysqli_query($conn, $sql);
$count = $offset + 1;
?>

<style>
  .compressed-table td, .compressed-table th {
    padding: 8px 12px !important;
    font-size: 0.9rem;
  }
  .product-img-mini {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  .stock-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
  }
  .stock-low { background: #fee2e2; color: #dc2626; }
  .stock-ok { background: #f0fdf4; color: #16a34a; }
</style>

<div class="container-fluid mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Quantity Availability</h3>
    
    <div class="d-flex align-items-center" style="gap: 15px;">
      <!-- Search Bar -->
      <form class="form-inline" method="GET" action="index.php" onsubmit="event.preventDefault(); loadModule('stock&search=' + encodeURIComponent(this.search.value));">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control" placeholder="Search S.N, ID, Name..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 4px 0 0 4px; width: 220px;">
          <div class="input-group-append">
            <button class="btn btn-dark" type="submit" style="background:#111; border:none; border-radius: 0 4px 4px 0;">
              <i class="fas fa-search" style="color:#d4af37;"></i>
            </button>
          </div>
        </div>
      </form>

      <button class="btn btn-danger btn-sm" id="btnDeleteSelectedStock" style="display:none;" onclick="deleteSelectedStock()">
        <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountStock">0</span>)
      </button>
      <div class="text-muted small">
        Total Products: <strong><?= $totalRecords ?></strong>
      </div>
    </div>
  </div>

  <div class="table-responsive-custom">
    <table class="custom-table compressed-table">
      <thead>
        <tr>
          <th class="text-center" style="width: 40px;">
            <input type="checkbox" id="selectAllStock" style="cursor: pointer;">
          </th>
          <th class="text-center">S.N</th>
          <th>Product ID</th>
          <th>Ref ID</th>
          <th>Product Details</th>
          <th>Main Category</th>
          <th>Sub Category</th>
          <th class="text-center">Quantity</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $imgName = $row["image1"] ?? '';
            if (empty($imgName)) {
                $imagePath = 'assets/images/placeholder.png';
            } elseif (strpos($imgName, 'uploads/') !== false) {
                $imagePath = $imgName;
            } else {
                $imagePath = 'uploads/subshop/' . $imgName;
            }
            $stockClass = ($row['Stock'] <= 5) ? 'stock-low' : 'stock-ok';
      ?>
        <tr>
          <td class="text-center">
            <input type="checkbox" class="stock-checkbox" value="<?= $product_id ?>" style="cursor: pointer;">
          </td>
          <td class="text-center text-muted">#<?= $count++ ?></td>
          <td><span class="font-weight-bold">#<?= $row['product_id'] ?></span></td>
          <td><span class="text-muted"><?= htmlspecialchars($row['category_id']) ?></span></td>
          <td>
            <div class="d-flex align-items-center">
              <img src="<?= $imagePath ?>" class="product-img-mini mr-3" 
                   onerror="this.onerror=null; this.src='assets/images/placeholder.png';">
              <div class="text-dark font-weight-600"><?= htmlspecialchars($row['name']) ?></div>
            </div>
          </td>
          <td><?= htmlspecialchars($row['main_category_name']) ?></td>
          <td><?= htmlspecialchars($row['category_name']) ?></td>
          <td class="text-center">
            <span class="stock-badge <?= $stockClass ?>">
              <?= $row['Stock'] ?>
            </span>
          </td>
          <td>
            <div class="action-btn-group justify-content-center">
              <button class="btn-action btn-action-edit" title="Edit Stock" onclick="editCollection(<?= $product_id ?>)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn-action" style="background: #e0f2fe; color: #0369a1;" title="Subcategory" 
                      onclick="openSubcategoryModal(<?= $row['product_id'] ?>, '<?= htmlspecialchars($row['category_name']) ?>')">
                <i class="fas fa-sitemap"></i>
              </button>
              <button class="btn-action btn-action-delete" title="Delete" onclick="handleStockDelete(<?= $product_id ?>)">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </td>
        </tr>
      <?php 
          }
        } else {
          echo "<tr><td colspan='8' class='text-center py-5 text-muted'>No inventory records found.</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $recordsPerPage, $totalRecords) ?> of <?= $totalRecords ?> items</small>
    <?= renderPagination($totalRecords, $recordsPerPage, $page, 'stock') ?>
  </div>
</div>

<!-- Edit Collection Modal -->
<div class="modal fade" id="editCollectionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form id="updateStockForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Stock</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="form-group mb-3">
          <label>Product Name</label>
          <input type="text" class="form-control bg-light" id="edit_product_name" readonly>
        </div>
        <div class="form-group mb-3">
          <label for="stock">Current Stock Quantity</label>
          <input type="number" class="form-control" name="stock" id="stock" required min="0">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success w-100">Update Inventory</button>
      </div>
    </form>
  </div>
</div>

<!-- Subcategory Modal -->
<div class="modal fade" id="subcategoryModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="/shop_admin/controller/Dft.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Subcategory</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="category_name" id="modal_category_name_real">
          <input type="hidden" name="collection_id" id="modal_collection_id">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Parent Category</label>
              <input type="text" class="form-control bg-light" id="modal_category_display" disabled>
            </div>
            <div class="col-md-6 mb-3">
              <label>Subcategory Name</label>
              <input type="text" class="form-control" name="subcategory_name" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Brand</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Price</label>
              <input type="number" class="form-control" name="price" required>
            </div>
            <div class="col-12 mb-3">
              <label>Description</label>
              <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="col-md-3 mb-3">
              <label>Img 1</label><input type="file" class="form-control-file" name="image1">
            </div>
            <div class="col-md-3 mb-3">
              <label>Img 2</label><input type="file" class="form-control-file" name="image2">
            </div>
            <div class="col-md-3 mb-3">
              <label>Img 3</label><input type="file" class="form-control-file" name="image3">
            </div>
            <div class="col-md-3 mb-3">
              <label>Img 4</label><input type="file" class="form-control-file" name="image4">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Save Subcategory</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editCollection(id) {
    if (!id) { showToast('Invalid product id', 'danger'); return; }
    const url = `./controller/getCollection.php?id=${encodeURIComponent(id)}`;
    fetch(url)
      .then(async response => {
        const data = await response.json();
        if (!data || data.error) {
            showToast(data?.error || "No data received", 'danger');
            return;
        }
        document.getElementById('edit_id').value = data.id || '';
        document.getElementById('edit_product_name').value = data.product_name || '';
        document.getElementById('stock').value = (data.Stock !== undefined && data.Stock !== null) ? data.Stock : 0;
        $('#editCollectionModal').modal('show');
      })
      .catch(err => showToast("Error fetching product data", 'danger'));
}

function openSubcategoryModal(productId, categoryName) {
  document.getElementById('modal_category_display').value = categoryName;
  document.getElementById('modal_collection_id').value = productId;
  document.getElementById('modal_category_name_real').value = categoryName;
  $('#subcategoryModal').modal('show');
}

$(document).off('submit', '#updateStockForm').on('submit', '#updateStockForm', function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');

    $.ajax({
        url: './controller/updatecater.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            btn.prop('disabled', false).html('Update Inventory');
            if (res.success) {
                showToast(res.message, 'success');
                $('#editCollectionModal').modal('hide');
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('stock', page);
            } else {
                showToast(res.message || "Failed to update stock", 'danger');
            }
        },
        error: function() {
            btn.prop('disabled', false).html('Update Inventory');
            showToast("Server error updating stock", 'danger');
        }
    });
});

function handleStockDelete(id) {
    showConfirm(
        "Delete Product?",
        "Are you sure you want to permanently delete this product and its inventory records?",
        function() {
            $.post("./controller/deleteProduct.php", { id: id }, function(res) {
                if(res.success) {
                    showToast(res.message, 'success');
                    const params = new URLSearchParams(window.location.hash.split('?')[1]);
                    const page = params.get('page') || 1;
                    loadModule('stock', page);
                } else {
                    showToast(res.message || "Failed to delete", 'danger');
                }
            });
        }
    );
}

// --- Select All & Bulk Delete for Stock ---
function updateBulkDeleteStockBtn() {
    let selectedCount = $('.stock-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountStock').text(selectedCount);
        $('#btnDeleteSelectedStock').fadeIn(200);
    } else {
        $('#btnDeleteSelectedStock').fadeOut(200);
    }
    let totalCount = $('.stock-checkbox').length;
    $('#selectAllStock').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllStock').on('change', function() {
    $('.stock-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteStockBtn();
});

$(document).on('change', '.stock-checkbox', function() {
    updateBulkDeleteStockBtn();
});

function deleteSelectedStock() {
    let selectedIds = [];
    $('.stock-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Products?",
        `Are you sure you want to permanently delete ${selectedIds.length} selected items?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedStock').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/deleteProduct.php',
                    method: 'POST',
                    data: { id: id },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} items!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('stock', page);
            });
        }
    );
}
</script>
