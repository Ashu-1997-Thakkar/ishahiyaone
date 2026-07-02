<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records for pagination
$totalSql = "SELECT COUNT(*) AS total FROM collections";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_assoc($totalResult)['total'];

// Fetch records
$sql = "SELECT id AS product_id, product_name, category_name, image FROM collections ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
$count = $offset + 1;
?>

<style>
    .compressed-table td,
    .compressed-table th {
        padding: 10px 12px !important;
        font-size: 0.85rem;
        vertical-align: middle !important;
        border-color: #f1f5f9 !important;
    }

    .custom-table thead {
        background-color: #c59d2f;
        color: white;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    .product-img-v {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-category {
        background: #f8fafc;
        color: #64748b;
        padding: 4px 10px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .btn-edit { background: #e0f2fe; color: #0284c7; }
    .btn-edit:hover { background: #0284c7; color: white; }
    
    .btn-sub { background: #f0fdf4; color: #16a34a; }
    .btn-sub:hover { background: #16a34a; color: white; }

    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    .page-item.active .page-link {
        background-color: #c59d2f !important;
        border-color: #c59d2f !important;
    }
    .page-link { color: #c59d2f; }

    .view-header {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>

<div class="container-fluid py-3">
    <div class="view-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 font-weight-bold">Collection Items</h4>
            <span class="text-muted small">Displaying <?= $totalRows ?> curated products</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <button class="btn btn-danger btn-sm" id="btnDeleteSelectedCol" style="display:none;" onclick="deleteSelectedCollections()">
                <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountCol">0</span>)
            </button>
            <button class="btn btn-dark" data-toggle="modal" data-target="#addProductModal" style="background: #111; border: none;">
                <i class="fas fa-plus mr-2" style="color: #c59d2f;"></i> Add Collection
            </button>
        </div>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllCollections" style="cursor: pointer;">
                    </th>
                    <th class="text-center">#</th>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Title</th>
                    <th>Category</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($r = mysqli_fetch_assoc($result)): 
                        $img = !empty($r['image']) ? "./uploads/".htmlspecialchars($r['image']) : "./assets/images/placeholder.png";
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="col-checkbox" value="<?= $r['product_id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $count++ ?></td>
                            <td class="font-weight-bold text-primary">#<?= $r['product_id'] ?></td>
                            <td><img src="<?= $img ?>" class="product-img-v" onerror="this.src='./assets/images/placeholder.png'"></td>
                            <td class="font-weight-600"><?= htmlspecialchars($r['product_name']) ?></td>
                            <td><span class="badge-category"><?= htmlspecialchars($r['category_name']) ?></span></td>
                            <td class="text-center">
                                <button class="btn-action btn-edit" title="Edit" onclick="editCollection(<?= $r['product_id'] ?>)">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn-action btn-sub" title="SubCategory" onclick="openSubcategoryModal(<?= $r['product_id'] ?>, '<?= addslashes($r['category_name']) ?>')">
                                    <i class="fas fa-layer-group"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Delete" onclick="deleteCollection(<?= $r['product_id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">No collection items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'collections') ?>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="./controller/addCollection.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color: #c59d2f;"></i>New Collection</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="product_name" required>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Category</label>
                    <select class="form-control" name="category_name" required>
                        <option value="" disabled selected>— Select Category —</option>
                        <?php 
                        $cats = mysqli_query($conn, "SELECT main_category_name FROM main_category ORDER BY main_category_name ASC");
                        while($c = mysqli_fetch_assoc($cats)) echo "<option value='".htmlspecialchars($c['main_category_name'])."'>".htmlspecialchars($c['main_category_name'])."</option>";
                        ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="small font-weight-bold">Main Image</label>
                    <input type="file" class="form-control-file" name="image" required>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" name="upload" value="1" class="btn btn-primary btn-sm px-4">Add Item</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editCollectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="./controller/updateCollection.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color: #c59d2f;"></i>Edit Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="hidden" name="old_image" id="old_image">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Product Name</label>
                    <input type="text" class="form-control" name="edit_product_name" id="edit_product_name" required>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Category</label>
                    <select class="form-control" name="category_name" id="modal_category_name" required>
                        <?php 
                        $cats_edit = mysqli_query($conn, "SELECT main_category_name FROM main_category ORDER BY main_category_name ASC");
                        while($c = mysqli_fetch_assoc($cats_edit)) echo "<option value='".htmlspecialchars($c['main_category_name'])."'>".htmlspecialchars($c['main_category_name'])."</option>";
                        ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="small font-weight-bold">New Image (optional)</label>
                    <input type="file" class="form-control-file" name="new_image">
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm px-4">Update</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="subcategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="./controller/Dft.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Add Subcategory Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="collection_id" id="modal_collection_id">
                        <input type="hidden" name="category_name" id="modal_category_name_real">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Category</label>
                            <input type="text" class="form-control bg-light" id="modal_category_display" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Subcategory Name</label>
                            <input type="text" class="form-control" name="subcategory_name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Brand</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Price (₹)</label>
                            <input type="text" class="form-control" name="price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Description</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <label class="small font-weight-bold">Images</label>
                        <div class="row">
                            <div class="col-6 mb-2"><input type="file" name="image1" class="small"></div>
                            <div class="col-6 mb-2"><input type="file" name="image2" class="small"></div>
                            <div class="col-6 mb-2"><input type="file" name="image3" class="small"></div>
                            <div class="col-6 mb-2"><input type="file" name="image4" class="small"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm px-4">Save Data</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#subcategoryModal form').off('submit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let res;
                try { 
                    res = typeof response === 'string' ? JSON.parse(response) : response; 
                } catch(e) { 
                    res = { success: response.includes('success'), message: response }; 
                }

                if (res.success || (typeof response === 'string' && response.includes('success'))) {
                    $('#subcategoryModal').modal('hide');
                    $('#subcategoryModal form')[0].reset();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Success', text: 'Subcategory added successfully', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    } else {
                        alert('Subcategory added successfully!');
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Error saving data' });
                    } else {
                        alert('Failed: ' + (res.message || 'Error saving data'));
                    }
                }
            },
            error: function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to server' });
                } else {
                    alert('Network error connecting to server');
                }
            }
        });
    });
});

function editCollection(id) {
    fetch(`./controller/getCollection.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.id) {
                $('#edit_id').val(data.id);
                $('#edit_product_name').val(data.product_name || data.name);
                $('#old_image').val(data.image1 || data.image);
                $('#modal_category_name').val(data.category_name);
                $('#editCollectionModal').modal('show');
            } else {
                showToast(data.error || "Error loading data", "danger");
            }
        });
}

function deleteCollection(id) {
    showConfirm("Delete Item?", "Permanently remove this from collections?", function() {
        $.ajax({
            url: './controller/deleteCollection.php',
            method: 'GET',
            data: { id: id },
            success: function(res) {
                if (res.success) {
                    showToast(res.message, "success");
                    loadModule('collections');
                } else {
                    showToast(res.message, "danger");
                }
            },
            error: function() { showToast("Network error", "danger"); }
        });
    });
}

function openSubcategoryModal(id, cat) {
    $('#modal_collection_id').val(id);
    $('#modal_category_name_real').val(cat);
    $('#modal_category_display').val(cat);
    $('#subcategoryModal').modal('show');
}

// --- Select All & Bulk Delete for Collections ---
function updateBulkDeleteColBtn() {
    let selectedCount = $('.col-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountCol').text(selectedCount);
        $('#btnDeleteSelectedCol').fadeIn(200);
    } else {
        $('#btnDeleteSelectedCol').fadeOut(200);
    }
    let totalCount = $('.col-checkbox').length;
    $('#selectAllCollections').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllCollections').on('change', function() {
    $('.col-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteColBtn();
});

$(document).on('change', '.col-checkbox', function() {
    updateBulkDeleteColBtn();
});

function deleteSelectedCollections() {
    let selectedIds = [];
    $('.col-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm("Delete Multiple Items?", `Are you sure you want to delete ${selectedIds.length} selected items?`, function() {
        let errors = 0;
        $('#btnDeleteSelectedCol').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

        let requests = selectedIds.map(id =>
            $.ajax({
                url: './controller/deleteCollection.php',
                method: 'GET',
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
            loadModule('collections');
        });
    });
}
</script>
