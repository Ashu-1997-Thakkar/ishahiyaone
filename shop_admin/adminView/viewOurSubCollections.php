<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records for pagination
$totalSql = "SELECT COUNT(*) AS total FROM subcategory";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_assoc($totalResult)['total'];

// Fetch records
$sql = "SELECT * FROM subcategory ORDER BY id DESC LIMIT $limit OFFSET $offset";
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
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-ref {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .price-text {
        font-weight: 700;
        color: #1e293b;
    }

    .desc-cell {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #64748b;
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
            <h4 class="mb-0 font-weight-bold">Sub-Collection Items</h4>
            <span class="text-muted small">Managing detailed items in sub-collections</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <button class="btn btn-danger btn-sm" id="btnDeleteSelectedSubCol" style="display:none;" onclick="deleteSelectedSubCollections()">
                <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountSubCol">0</span>)
            </button>
            <button class="btn btn-dark" data-toggle="modal" data-target="#subcategoryModal" style="background: #111; border: none;">
                <i class="fas fa-plus mr-2" style="color: #c59d2f;"></i> Add Sub-Item
            </button>
        </div>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllSubCollections" style="cursor: pointer;">
                    </th>
                    <th class="text-center">#</th>
                    <th>Ref ID</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($r = mysqli_fetch_assoc($result)): 
                        $img = !empty($r['image1']) ? "./".str_replace('../', '', $r['image1']) : "./assets/images/placeholder.png";
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="subcol-checkbox" value="<?= $r['id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $count++ ?></td>
                            <td><span class="badge-ref">#<?= $r['collection_id'] ?></span></td>
                            <td><img src="<?= $img ?>" class="product-img-v" onerror="this.src='./assets/images/placeholder.png'"></td>
                            <td class="font-weight-600"><?= htmlspecialchars($r['subcategory_name']) ?></td>
                            <td><?= htmlspecialchars($r['brand'] ?? 'N/A') ?></td>
                            <td><span class="price-text">₹<?= number_format((float)$r['price'], 2) ?></span></td>
                            <td><div class="desc-cell" title="<?= htmlspecialchars($r['description']) ?>"><?= htmlspecialchars($r['description']) ?></div></td>
                            <td class="text-center">
                                <button class="btn-action btn-edit" title="Edit" onclick="editSubcategory(<?= $r['id'] ?>)">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Delete" onclick="deleteSubcategory(<?= $r['id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">No sub-collection items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'sub-collections') ?>
    </div>
</div>

<!-- Add Subcategory Modal -->
<div class="modal fade" id="subcategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="./controller/Dft.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color: #c59d2f;"></i>Add New Sub-Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Base Collection</label>
                            <select class="form-control" name="collection_id" required>
                                <option value="" disabled selected>-- Select Collection --</option>
                                <?php 
                                $cols = mysqli_query($conn, "SELECT id, product_name FROM collections");
                                while($c = mysqli_fetch_assoc($cols)) echo "<option value='{$c['id']}'>{$c['product_name']}</option>";
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Item Name</label>
                            <input type="text" class="form-control" name="subcategory_name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Brand</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Description</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <label class="small font-weight-bold">Product Images (Up to 4)</label>
                        <div class="row g-2">
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

<!-- Edit Subcategory Modal -->
<div class="modal fade" id="editSubcategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="./controller/update_subcategory.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color: #c59d2f;"></i>Edit Sub-Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="edit_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Base Collection ID</label>
                            <input type="text" class="form-control" name="collection_id" id="edit_collection_id" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Item Name</label>
                            <input type="text" class="form-control" name="subcategory_name" id="edit_subcategory_name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Brand</label>
                            <input type="text" class="form-control" name="brand" id="edit_brand" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="edit_price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="4" required></textarea>
                        </div>
                        <label class="small font-weight-bold">Update Images (Leave blank to keep current)</label>
                        <div class="row g-2">
                            <div class="col-6 mb-2">
                                <input type="file" name="image1" class="small">
                                <img id="preview_1" src="" class="img-thumbnail mt-1" style="height:40px; display:none;">
                            </div>
                            <div class="col-6 mb-2">
                                <input type="file" name="image2" class="small">
                                <img id="preview_2" src="" class="img-thumbnail mt-1" style="height:40px; display:none;">
                            </div>
                            <div class="col-6 mb-2">
                                <input type="file" name="image3" class="small">
                                <img id="preview_3" src="" class="img-thumbnail mt-1" style="height:40px; display:none;">
                            </div>
                            <div class="col-6 mb-2">
                                <input type="file" name="image4" class="small">
                                <img id="preview_4" src="" class="img-thumbnail mt-1" style="height:40px; display:none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" name="update_subcategory" class="btn btn-primary btn-sm px-4">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// Initialize form handlers immediately (no document.ready needed for AJAX-loaded scripts)
(function() {
    console.log("Sub-Collections module initialized");

    // Handler for Add Subcategory Form
    $('#subcategoryModal form').off('submit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log("Submitting Add form...");
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let res;
                try { res = typeof response === 'string' ? JSON.parse(response) : response; } catch(e) { res = { success: response.includes('success'), message: response }; }

                if (res.success || response.includes('success')) {
                    $('#subcategoryModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success', text: 'Item added successfully', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    loadModule('sub-collections');
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Error saving data' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to server' });
            }
        });
    });

    // Handler for Edit Subcategory Form
    $('#editSubcategoryModal form').off('submit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log("Submitting Edit form...");
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let res;
                try { res = typeof response === 'string' ? JSON.parse(response) : response; } catch(e) { res = { success: response.includes('success'), message: response }; }

                if (res.success || response.includes('success')) {
                    $('#editSubcategoryModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Updated', text: 'Item updated successfully', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    loadModule('sub-collections');
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: res.message || 'Error updating data' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to server' });
            }
        });
    });
})();

function editSubcategory(id) {
    console.log("Editing item:", id);
    $.ajax({
        url: `./controller/edit_subcategory.php?id=${id}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.id) {
                $('#edit_id').val(data.id);
                $('#edit_collection_id').val(data.collection_id);
                $('#edit_subcategory_name').val(data.subcategory_name);
                $('#edit_brand').val(data.brand);
                $('#edit_price').val(data.price);
                $('#edit_description').val(data.description);
                
                // Previews
                for(let i=1; i<=4; i++) {
                    const field = 'image' + i;
                    const preview = $('#preview_' + i);
                    if (data[field]) {
                        preview.attr('src', './' + data[field].replace('../', '')).show();
                    } else {
                        preview.hide();
                    }
                }
                $('#editSubcategoryModal').modal('show');
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.error || "Error loading data" });
            }
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Error', text: "Failed to fetch item details" });
        }
    });
}

function deleteSubcategory(id) {
    console.log("Delete triggered for ID:", id);
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Deletion confirmed for ID:", id);
            $.ajax({
                url: './controller/subCategoryDelete.php',
                method: 'GET',
                data: { id: id },
                success: function(response) {
                    let res;
                    try { res = typeof response === 'string' ? JSON.parse(response) : response; } catch(e) { res = { success: response.includes('success'), message: response }; }

                    if (res.success || response.includes('success')) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Item has been removed.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        loadModule('sub-collections');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.message || "Failed to delete" });
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: "Network error occurred" });
                }
            });
        }
    });
}

// --- Select All & Bulk Delete for Sub-Collections ---
function updateBulkDeleteSubColBtn() {
    let selectedCount = $('.subcol-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountSubCol').text(selectedCount);
        $('#btnDeleteSelectedSubCol').fadeIn(200);
    } else {
        $('#btnDeleteSelectedSubCol').fadeOut(200);
    }
    let totalCount = $('.subcol-checkbox').length;
    $('#selectAllSubCollections').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllSubCollections').on('change', function() {
    $('.subcol-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteSubColBtn();
});

$(document).on('change', '.subcol-checkbox', function() {
    updateBulkDeleteSubColBtn();
});

function deleteSelectedSubCollections() {
    let selectedIds = [];
    $('.subcol-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    Swal.fire({
        title: 'Are you sure?',
        text: `You want to delete ${selectedIds.length} selected items?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete them!'
    }).then((result) => {
        if (result.isConfirmed) {
            let errors = 0;
            $('#btnDeleteSelectedSubCol').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/subCategoryDelete.php',
                    method: 'GET',
                    data: { id: id },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: `Successfully deleted ${selectedIds.length} items.`, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                } else {
                    Swal.fire({ icon: 'warning', title: 'Warning', text: 'Deleted with some errors.' });
                }
                loadModule('sub-collections');
            });
        }
    });
}
</script>
