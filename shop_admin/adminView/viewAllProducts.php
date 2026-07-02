<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch products with category names (joined with main_category)
$sql = "SELECT p.product_id, p.name, p.description, p.price, p.image, p.created_at, p.rating,
               COALESCE(mc.main_category_name, 'Uncategorized') AS category_name,
               p.category_id, p.sub_category_id,
               COALESCE(sc.sub_category_name, '') AS sub_category_name
        FROM products p
        LEFT JOIN main_category mc ON p.category_id = mc.id
        LEFT JOIN sub_category sc ON p.sub_category_id = sc.id
        ORDER BY p.product_id DESC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
$sn = $offset + 1;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalRows = $totalRes->fetch_assoc()['total'];
?>

<style>
    .compressed-table td, .compressed-table th {
        padding: 6px 10px !important;
        font-size: 0.78rem;
        vertical-align: middle !important;
    }
    .custom-table thead {
        background-color: #c59d2f;
        color: white;
    }
    .custom-table thead th {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        border: none;
    }
    .product-thumb-sm {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    /* Pagination gold theme */
    .page-item.active .page-link {
        background-color: #c59d2f !important;
        border-color: #c59d2f !important;
    }
    .page-link { color: #c59d2f; }
    
    /* Text Buttons from Screenshot */
    .btn-action-custom {
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        border: none;
        color: white;
        margin-right: 4px;
        transition: 0.2s;
    }
    .btn-edit-blue { background-color: #2563eb; }
    .btn-delete-red { background-color: #dc2626; }
    .btn-sub-blue { background-color: #2563eb; min-width: 80px; }
    
    .btn-action-custom:hover { opacity: 0.85; transform: translateY(-1px); }

    /* Modern Size Selection */
    .size-label {
        display: inline-block;
        padding: 5px 12px;
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        margin: 0;
    }
    .size-check-input:checked + .size-label {
        background: #c59d2f;
        color: white;
        border-color: #c59d2f;
        box-shadow: 0 2px 4px rgba(197, 157, 47, 0.3);
    }
    .size-label:hover { border-color: #c59d2f; }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
        line-height: 1.4;
    }
    
    .btn-action-custom {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        margin: 0 2px;
    }

    /* Ultra High Density Compression */
    .compressed-form .form-group {
        margin-bottom: 8px !important;
    }
    .compressed-form .row {
        margin-left: -5px;
        margin-right: -5px;
    }
    .compressed-form [class*="col-"] {
        padding-left: 5px;
        padding-right: 5px;
    }
    .compressed-form .form-control {
        height: 30px !important;
        padding: 2px 8px !important;
        font-size: 0.82rem !important;
        border-radius: 4px;
    }
    .compressed-form textarea.form-control {
        height: 60px !important;
    }
    .compressed-form label {
        margin-bottom: 1px !important;
        font-size: 11px !important;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .modal-body-tight {
        padding: 10px 15px !important;
    }
    .size-label {
        padding: 3px 8px !important;
        font-size: 0.75rem !important;
        border-radius: 4px !important;
    }
    .size-selection-grid {
        padding: 8px !important;
    }

    /* Full screen modal for better management */
    @media (min-width: 992px) {
        .modal-full {
            max-width: 95% !important;
        }
    }
</style>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Product Items</h3>
            <span class="badge badge-dark">Total: <?= $totalRows ?> Items</span>
        </div>
        <div class="action-btn-group d-flex align-items-center" style="gap: 10px;">
            <button class="btn btn-danger btn-sm" id="btnDeleteSelectedNewArr" style="display:none;" onclick="deleteSelectedNewArrivals()">
                <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountNewArr">0</span>)
            </button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProductModal" style="background-color: #c59d2f; border: none;">
                <i class="fas fa-plus mr-1"></i> Add Product
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="loadModule('new-arrivals')">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllNewArr" style="cursor: pointer;">
                    </th>
                    <th class="text-center">S.N.</th>
                    <th class="text-center">Product Image</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Category Name</th>
                    <th class="text-center">Unit Price</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="newarr-checkbox" value="<?= $row['product_id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted">#<?= $sn++ ?></td>
                            <td class="text-center">
                                <?php 
                                    $img = trim($row['image']);
                                    if (empty($img)) {
                                        $imgPath = '../assets/no-image.png';
                                    } else {
                                        $imgPath = (strpos($img, 'uploads/') !== false) ? "./" . str_replace('../', '', $img) : "./uploads/" . basename($img);
                                    }
                                ?>
                                <img src="<?= $imgPath ?>" class="product-thumb-sm" onerror="this.src='../assets/no-image.png'">
                            </td>
                            <td>
                                <div class="font-weight-600 text-dark"><?= htmlspecialchars($row['name']) ?></div>
                                <small class="text-muted">ID: #<?= $row['product_id'] ?></small>
                            </td>
                            <td style="max-width: 200px;">
                                <div class="text-truncate-2 small text-muted"><?= htmlspecialchars($row['description']) ?></div>
                            </td>
                            <td>
                                <span class="badge badge-light border"><?= htmlspecialchars($row['category_name']) ?></span>
                                <?php if (!empty($row['sub_category_name'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($row['sub_category_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center font-weight-700 text-dark">
                                ₹<?= number_format($row['price'], 2) ?>
                            </td>
                            <td class="text-center">
                                <span class="text-warning"><i class="fas fa-star mr-1"></i><?= $row['rating'] ?: '0' ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn-action-custom btn-edit-blue" onclick="itemEditForm('<?= $row['product_id'] ?>')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action-custom btn-delete-red" onclick="handleItemDelete('<?= $row['product_id'] ?>')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn-action-custom btn-sub-blue" onclick="loadModule('sub-collections?product_id=<?= $row['product_id'] ?>')" title="Sub-Categories">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">No product items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="small text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> items</div>
        <?= renderPagination($totalRows, $limit, $page, 'collections') ?>
    </div>
    

</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" role="dialog">
    <div class="modal-dialog modal-lg modal-full">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus mr-2" style="color: #c59d2f;"></i>New Product Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body modal-body-tight">
                <form id="addProductForm" class="compressed-form">
                    <!-- Line 1: Name -->
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="font-weight-600">Product Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="Enter product name">
                        </div>
                    </div>

                    <!-- Line 2: Brand, Price, SKU, Stock -->
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="font-weight-600">Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Brand name">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="font-weight-600">Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required placeholder="0.00">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="font-weight-600">SKU</label>
                            <input type="text" class="form-control" name="sku" placeholder="SKU code">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="font-weight-600">Stock Qty</label>
                            <input type="number" class="form-control" name="stock" value="0">
                        </div>
                    </div>

                    <!-- Line 3: Description -->
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="font-weight-600">Description</label>
                            <textarea class="form-control" name="description" rows="2" required placeholder="Write description..."></textarea>
                        </div>
                    </div>

                    <!-- Line 4: Category & Sub Category -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Category</label>
                            <select name="category" id="p_category" class="form-control" required onchange="loadSubCategories(this.value)">
                                <option value="" disabled selected>— Select Category —</option>
                                <?php
                                $cat_res = $conn->query("SELECT id, main_category_name FROM main_category ORDER BY main_category_name ASC");
                                while ($catRow = $cat_res->fetch_assoc()) {
                                    echo "<option value='" . (int)$catRow['id'] . "'>" . htmlspecialchars($catRow['main_category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Sub Category</label>
                            <select name="sub_category" id="p_sub_category" class="form-control">
                                <option value="0">— Select Sub Category —</option>
                            </select>
                        </div>
                    </div>

                    <!-- Line 5: Sizes -->
                    <div class="form-group mb-1">
                        <label class="font-weight-600">Available Sizes</label>
                        <div class="size-selection-grid border rounded d-flex flex-wrap bg-white">
                            <?php
                            $size_result = $conn->query("SELECT * FROM sizes ORDER BY size_name ASC");
                            while ($sRow = $size_result->fetch_assoc()) {
                                echo "<div class='size-checkbox-item mr-2 mb-1'>
                                        <input type='checkbox' id='sz_{$sRow['size_id']}' name='sizes[]' value='{$sRow['size_id']}' class='d-none size-check-input'>
                                        <label for='sz_{$sRow['size_id']}' class='size-label'>".htmlspecialchars($sRow['size_name'])."</label>
                                      </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Line 6: Image -->
                    <div class="form-group mb-2">
                        <label class="font-weight-600">Product Image</label>
                        <div class="custom-file" style="height: 30px;">
                            <input type="file" class="custom-file-input" name="file" id="p_file" required style="height: 30px;">
                            <label class="custom-file-label" for="p_file" style="height: 30px; line-height: 20px; font-size: 0.8rem;">Choose image...</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-1" style="background: #c59d2f; border: none; font-weight: 700; font-size: 0.95rem;">
                        <i class="fas fa-plus-circle mr-1"></i> Add Product Item
                    </button>

                </form>


            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" role="dialog">
    <div class="modal-dialog modal-lg modal-full">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color: #c59d2f;"></i>Edit Product Detail</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div id="editProductModalBody" class="modal-body modal-body-tight">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function loadSubCategories(catId) {
    $.post("controller/getSubCategories.php", { category_id: catId }, function(data) {
        $("#p_sub_category").html(data);
    });
}

$(document).off("submit", "#addProductForm").on("submit", "#addProductForm", function(e){
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Adding...');

    const formData = new FormData(this);
    formData.append("upload", "1");

    $.ajax({
        url: "controller/addItemController.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res){
            btn.prop('disabled', false).text('Add Product Item');
            if(res.trim() == "success"){
                showToast("Product Added Successfully!", "success");
                $("#addProductModal").modal("hide");
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('new-arrivals', page);
            } else {
                showToast("Error: " + res, "danger");
            }
        }
    });
});

function handleItemDelete(id) {
    showConfirm(
        "Delete Product?",
        "Are you sure you want to delete this product permanently?",
        function() {
            $.post("./controller/deleteItemController.php", { record: id }, function(res) {
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('new-arrivals', page);
                showToast("Product deleted successfully", "success");
            });
        }
    );
}

// --- Select All & Bulk Delete for New Arrivals ---
function updateBulkDeleteNewArrBtn() {
    let selectedCount = $('.newarr-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountNewArr').text(selectedCount);
        $('#btnDeleteSelectedNewArr').fadeIn(200);
    } else {
        $('#btnDeleteSelectedNewArr').fadeOut(200);
    }
    let totalCount = $('.newarr-checkbox').length;
    $('#selectAllNewArr').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllNewArr').on('change', function() {
    $('.newarr-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteNewArrBtn();
});

$(document).on('change', '.newarr-checkbox', function() {
    updateBulkDeleteNewArrBtn();
});

function deleteSelectedNewArrivals() {
    let selectedIds = [];
    $('.newarr-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Products?",
        `Are you sure you want to delete ${selectedIds.length} selected products permanently?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedNewArr').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/deleteItemController.php',
                    method: 'POST',
                    data: { record: id },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} products!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('new-arrivals', page);
            });
        }
    );
}
</script>
