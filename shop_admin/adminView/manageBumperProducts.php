<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$filterCat = isset($_GET['category']) ? (int)$_GET['category'] : '';
$filterSubCat = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : '';
$filterSearch = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '1'; // Default to 1 (Only show Bumper Offers)

$where = "WHERE 1=1";
if ($filterSearch !== '') {
    $where .= " AND (name LIKE '%$filterSearch%' OR brand LIKE '%$filterSearch%')";
}
if ($filterStatus === '1') {
    $where .= " AND is_bumper_offer = 1";
} elseif ($filterStatus === '0') {
    $where .= " AND (is_bumper_offer = 0 OR is_bumper_offer IS NULL)";
}

// Fetch products with filters across all 3 tables
$sql = "
    SELECT SQL_CALC_FOUND_ROWS * FROM (
        SELECT id AS product_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS image, 'Our Shop' AS category_name, 'all_category' AS source, is_bumper_offer, CAST(bumper_title AS CHAR CHARACTER SET utf8mb4) AS bumper_title, bumper_start_date, bumper_end_date, bumper_discount FROM all_category
        UNION ALL
        SELECT id AS product_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(image1 AS CHAR CHARACTER SET utf8mb4) AS image, 'Sub Shop' AS category_name, 'subcategories' AS source, is_bumper_offer, CAST(bumper_title AS CHAR CHARACTER SET utf8mb4) AS bumper_title, bumper_start_date, bumper_end_date, bumper_discount FROM subcategories
        UNION ALL
        SELECT product_id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(brand AS CHAR CHARACTER SET utf8mb4) AS brand, price, CAST(image AS CHAR CHARACTER SET utf8mb4) AS image, 'New Arrival' AS category_name, 'products' AS source, is_bumper_offer, CAST(bumper_title AS CHAR CHARACTER SET utf8mb4) AS bumper_title, bumper_start_date, bumper_end_date, bumper_discount FROM products
    ) as combined_products
    $where
    ORDER BY product_id DESC
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);
$sn = $offset + 1;

$totalRes = $conn->query("SELECT FOUND_ROWS() as total");
$totalRows = $totalRes->fetch_assoc()['total'];
?>

<style>
    .compressed-table td, .compressed-table th {
        padding: 6px 10px !important;
        font-size: 0.8rem;
        vertical-align: middle !important;
    }
    .custom-table thead {
        background-color: #c59d2f;
        color: white;
    }
    .product-thumb-sm {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .status-toggle {
        cursor: pointer;
        font-size: 1.2rem;
        transition: 0.3s;
    }
    .status-toggle.active { color: #22c55e; }
    .status-toggle.inactive { color: #cbd5e1; }
    
    .filter-bar {
        background: #111;
        padding: 6px 12px;
        border-radius: 6px;
        margin-bottom: 12px;
        border: 1px solid #333;
    }
    .btn-preview {
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-preview:hover {
        background: #dbeafe;
        color: #1d4ed8;
        text-decoration: none;
    }
</style>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Manage Bumper Products</h3>
            <span class="badge badge-dark mt-1">Total: <?= $totalRows ?> Products</span>
        </div>
        <div class="action-btn-group d-flex align-items-center" style="gap: 10px;">
            <button class="btn btn-success btn-sm" id="btnBulkAddBumper" style="display:none;" onclick="bulkUpdateBumperStatus(1)">
                <i class="fas fa-plus-circle mr-1"></i> Mark as Bumper (<span class="selectedCount">0</span>)
            </button>
            <button class="btn btn-danger btn-sm" id="btnBulkRemoveBumper" style="display:none;" onclick="bulkUpdateBumperStatus(0)">
                <i class="fas fa-minus-circle mr-1"></i> Remove Bumper (<span class="selectedCount">0</span>)
            </button>
            <a href="../bumper-offers.php" target="_blank" class="btn btn-outline-secondary btn-sm" style="color:#c59d2f; border-color:#c59d2f;">
                <i class="fas fa-external-link-alt mr-1"></i> View Live Page
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-bar">
        <form id="bumperFilterForm" class="row align-items-center m-0" onsubmit="applyBumperFilters(event)">
            <div class="col-md-5 form-group mb-0 px-1">
                <input type="text" name="search" class="form-control form-control-sm" value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Search product name or brand...">
            </div>
            <div class="col-md-4 form-group mb-0 px-1">
                <select name="status" class="form-control form-control-sm">
                    <option value="">Status: All Products</option>
                    <option value="1" <?= $filterStatus === '1' ? 'selected' : '' ?>>Status: Bumper Offer</option>
                    <option value="0" <?= $filterStatus === '0' ? 'selected' : '' ?>>Status: Not Bumper Offer</option>
                </select>
            </div>
            <div class="col-md-3 form-group mb-0 px-1 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-50" style="background:#c59d2f; border:none; padding: 4px;"><i class="fas fa-filter"></i> Apply</button>
                <button type="button" class="btn btn-sm btn-secondary w-50" style="padding: 4px;" onclick="resetBumperFilters()"><i class="fas fa-undo"></i> Reset</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:40px;">
                        <input type="checkbox" id="selectAllBumper" style="cursor: pointer;">
                    </th>
                    <th class="text-center">S.N.</th>
                    <th class="text-center">Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-center">Brand</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Offer Details</th>
                    <th class="text-center">Bumper Offer?</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="bumper-checkbox" value="<?= $row['product_id'] ?>" data-source="<?= $row['source'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted">#<?= $sn++ ?></td>
                            <td class="text-center">
                                <?php 
                                    $img = trim($row['image']);
                                    $imgPath = '../assets/no-image.png';
                                    if (!empty($img)) {
                                        $imgPath = (strpos($img, 'uploads/') !== false) ? "./" . str_replace('../', '', $img) : "./uploads/" . basename($img);
                                    }
                                ?>
                                <img src="<?= $imgPath ?>" class="product-thumb-sm" onerror="this.src='../assets/no-image.png'">
                            </td>
                            <td>
                                <div class="font-weight-600 text-dark d-inline-block"><?= htmlspecialchars($row['name']) ?></div>
                                <span class="text-muted small ml-1">(ID: #<?= $row['product_id'] ?>)</span>
                            </td>
                            <td>
                                <span class="badge badge-light border"><?= htmlspecialchars($row['category_name']) ?></span>
                                <?php if (!empty($row['sub_category_name'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($row['source']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center text-muted"><?= htmlspecialchars($row['brand'] ?? '—') ?></td>
                            <td class="text-center font-weight-700 text-dark">₹<?= number_format($row['price'], 2) ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['bumper_start_date']) && !empty($row['bumper_end_date'])): ?>
                                    <div class="small text-muted d-flex align-items-center justify-content-center" style="gap: 5px;">
                                        <span><?= date('d M y', strtotime($row['bumper_start_date'])) ?> - <?= date('d M y', strtotime($row['bumper_end_date'])) ?></span>
                                        <?php if ($row['bumper_discount'] > 0): ?>
                                            <span class="badge badge-danger"><?= $row['bumper_discount'] ?>% OFF</span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Not Configured</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php $isActive = $row['is_bumper_offer'] == 1; ?>
                                <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                                    <i class="fas fa-toggle-<?= $isActive ? 'on active' : 'off inactive' ?> status-toggle"
                                       onclick="toggleBumperOffer(<?= $row['product_id'] ?>, '<?= $row['source'] ?>', this)" title="Activate/Deactivate"></i>
                                    
                                    <a href="../drt.php?product_id=<?= $row['product_id'] ?>&source=<?= urlencode($row['source']) ?>" target="_blank" class="btn btn-sm btn-info text-white" style="padding: 2px 6px; font-size: 11px;" title="Preview Details Page">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="javascript:void(0);" onclick='openBumperOfferModal(<?= json_encode($row) ?>)' class="btn btn-sm btn-primary" style="padding: 2px 6px; font-size: 11px;" title="Edit Offer Details">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="javascript:void(0);" onclick="removeBumperOffer(<?= $row['product_id'] ?>, '<?= $row['source'] ?>')" class="btn btn-sm btn-danger" style="padding: 2px 6px; font-size: 11px;" title="Remove Offer">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">No products match the filters.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="small text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> items</div>
        <?php 
            // We need to keep filter query params in the pagination links
            $queryParams = $_GET;
            unset($queryParams['page']);
            $queryString = http_build_query($queryParams);
        ?>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <?php for ($i = 1; $i <= ceil($totalRows/$limit); $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="javascript:void(0)" onclick="window.location.hash = 'bumper-products?page=<?= $i ?><?= $queryString ? '&'.$queryString : '' ?>'"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<!-- ============================================================ -->
<!-- EDIT BUMPER OFFER DETAILS MODAL -->
<!-- ============================================================ -->
<div class="modal fade" id="editBumperOfferModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: #111; border-bottom: 2px solid #c59d2f;">
                <h5 class="modal-title text-white font-weight-bold" style="letter-spacing: 0.5px;">
                    <i class="fas fa-crown mr-2" style="color: #c59d2f;"></i> Configure Bumper Offer
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8;">&times;</button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 25px;">
                <form id="editBumperOfferForm">
                    <input type="hidden" name="product_id" id="bo_product_id">
                    <input type="hidden" name="source" id="bo_source">
                    
                    <div class="form-group mb-4">
                        <label class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Offer Campaign Title</label>
                        <input type="text" class="form-control form-control-lg border-0 shadow-sm" name="bumper_title" id="bo_title" placeholder="e.g. Mega Summer Sale" style="font-size: 0.95rem; border-radius: 6px;">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i>This title will be highlighted on the product page.</small>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 form-group mb-0">
                            <label class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Campaign Starts</label>
                            <input type="datetime-local" class="form-control border-0 shadow-sm" name="bumper_start_date" id="bo_start_date" style="font-size: 0.85rem; border-radius: 6px;">
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Campaign Ends</label>
                            <input type="datetime-local" class="form-control border-0 shadow-sm" name="bumper_end_date" id="bo_end_date" style="font-size: 0.85rem; border-radius: 6px;">
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Discount Value</label>
                        <div class="input-group shadow-sm" style="border-radius: 6px; overflow: hidden;">
                            <input type="number" class="form-control border-0" name="bumper_discount" id="bo_discount" placeholder="e.g. 50" style="height: 45px;">
                            <div class="input-group-append">
                                <span class="input-group-text border-0 text-dark font-weight-bold" style="background: #e2e8f0;">% OFF</span>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: #e2e8f0; margin: 25px -25px;">

                    <div class="d-flex justify-content-end align-items-center" style="gap: 10px;">
                        <button type="button" class="btn btn-light shadow-sm" data-dismiss="modal" style="border-radius: 6px; font-weight: 600; padding: 8px 20px;">Cancel</button>
                        <button type="submit" class="btn shadow-sm" id="btnSaveBumperOffer" style="background: linear-gradient(135deg, #d4af37, #aa8222); color: white; border: none; border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openBumperOfferModal(row) {
    $('#bo_product_id').val(row.product_id);
    $('#bo_source').val(row.source);
    $('#bo_title').val(row.bumper_title || '');
    
    // Format dates for datetime-local input (YYYY-MM-DDThh:mm)
    let startDate = row.bumper_start_date ? row.bumper_start_date.replace(' ', 'T').substring(0, 16) : '';
    let endDate = row.bumper_end_date ? row.bumper_end_date.replace(' ', 'T').substring(0, 16) : '';
    
    $('#bo_start_date').val(startDate);
    $('#bo_end_date').val(endDate);
    $('#bo_discount').val(row.bumper_discount || 0);
    
    $('#editBumperOfferModal').modal('show');
}

$('#editBumperOfferForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#btnSaveBumperOffer');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
    
    $.ajax({
        url: 'controller/updateBumperOfferDetails.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            if (res.trim() === 'success') {
                showToast("Offer details updated!", "success");
                $('#editBumperOfferModal').modal('hide');
                
                let formData = new FormData(document.getElementById('bumperFilterForm'));
                let queryString = new URLSearchParams(formData).toString();
                let currentHash = window.location.hash.substring(1);
                let pageMatch = currentHash.match(/page=(\d+)/);
                let pageStr = pageMatch ? 'page=' + pageMatch[1] + '&' : '';
                
                loadModule('bumper-products?' + pageStr + queryString);
            } else {
                showToast("Failed to update details: " + res, "danger");
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            }
        },
        error: function() {
            showToast("Server error", "danger");
            btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
        }
    });
});
function loadFilterSubCategories(catId) {
    if (!catId) {
        $("#filter_subcat").html('<option value="">All Subcategories</option>');
        return;
    }
    $.post("controller/getSubCategories.php", { category_id: catId }, function(data) {
        $("#filter_subcat").html('<option value="">All Subcategories</option>' + data);
    });
}

function applyBumperFilters(e) {
    e.preventDefault();
    let formData = new FormData(document.getElementById('bumperFilterForm'));
    let queryString = new URLSearchParams(formData).toString();
    window.location.hash = 'bumper-products?' + queryString;
}

function resetBumperFilters() {
    window.location.hash = 'bumper-products';
}

// Bulk Selection Logic
function updateBulkBumperButtons() {
    let selectedCount = $('.bumper-checkbox:checked').length;
    if (selectedCount > 0) {
        $('.selectedCount').text(selectedCount);
        $('#btnBulkAddBumper, #btnBulkRemoveBumper').fadeIn(200);
    } else {
        $('#btnBulkAddBumper, #btnBulkRemoveBumper').fadeOut(200);
    }
    let totalCount = $('.bumper-checkbox').length;
    $('#selectAllBumper').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllBumper').off('change').on('change', function() {
    $('.bumper-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkBumperButtons();
});

$(document).off('change', '.bumper-checkbox').on('change', '.bumper-checkbox', function() {
    updateBulkBumperButtons();
});

function bulkUpdateBumperStatus(newValue) {
    let selectedItems = [];
    $('.bumper-checkbox:checked').each(function() { 
        selectedItems.push({ id: $(this).val(), source: $(this).data('source') }); 
    });
    if (selectedItems.length === 0) return;

    let actionText = newValue === 1 ? "mark as bumper offer" : "remove from bumper offers";
    
    showConfirm(
        "Update Bulk Status?",
        `Are you sure you want to ${actionText} for ${selectedItems.length} selected products?`,
        function() {
            let errors = 0;
            $('#btnBulkAddBumper, #btnBulkRemoveBumper').prop('disabled', true);
            
            let requests = selectedItems.map(item =>
                $.ajax({
                    url: 'controller/updateBumperOfferStatus.php',
                    method: 'POST',
                    data: { product_id: item.id, source: item.source, is_bumper_offer: newValue },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully updated ${selectedItems.length} products!`, 'success');
                } else {
                    showToast(`Updated with some errors.`, 'warning');
                }
                
                let formData = new FormData(document.getElementById('bumperFilterForm'));
                let queryString = new URLSearchParams(formData).toString();
                // Extract current page if needed
                let currentHash = window.location.hash.substring(1);
                let pageMatch = currentHash.match(/page=(\d+)/);
                let pageStr = pageMatch ? 'page=' + pageMatch[1] + '&' : '';
                
                loadModule('bumper-products?' + pageStr + queryString);
            });
        }
    );
}

function toggleBumperOffer(productId, source, iconElement) {
    let $icon = $(iconElement);
    let isActive = $icon.hasClass('fa-toggle-on');
    let newValue = isActive ? 0 : 1;
    
    // Optimistic UI update
    if (newValue === 1) {
        $icon.removeClass('fa-toggle-off inactive').addClass('fa-toggle-on active');
    } else {
        $icon.removeClass('fa-toggle-on active').addClass('fa-toggle-off inactive');
    }
    
    $.ajax({
        url: "controller/updateBumperOfferStatus.php",
        type: "POST",
        data: { product_id: productId, source: source, is_bumper_offer: newValue },
        success: function(response) {
            if (response.trim() !== 'success') {
                // Revert on failure
                if (isActive) {
                    $icon.removeClass('fa-toggle-off inactive').addClass('fa-toggle-on active');
                } else {
                    $icon.removeClass('fa-toggle-on active').addClass('fa-toggle-off inactive');
                }
                showToast("Failed to update status", "danger");
            } else {
                showToast("Bumper Offer status updated!", "success");
            }
        },
        error: function() {
            // Revert on failure
            if (isActive) {
                $icon.removeClass('fa-toggle-off inactive').addClass('fa-toggle-on active');
            } else {
                $icon.removeClass('fa-toggle-on active').addClass('fa-toggle-off inactive');
            }
            showToast("Server error", "danger");
        }
    });
}

function removeBumperOffer(productId, source) {
    showConfirm("Remove Bumper Offer?", "Are you sure you want to completely remove this product from the Bumper Offers list and clear its dates?", function() {
        $.ajax({
            url: "controller/clearBumperOffer.php",
            type: "POST",
            data: { product_id: productId, source: source },
            success: function(response) {
                if (response.trim() === 'success') {
                    showToast("Product removed from Bumper Offers", "success");
                    // Reload to reflect removed status in the table
                    let formData = new FormData(document.getElementById('bumperFilterForm'));
                    let queryString = new URLSearchParams(formData).toString();
                    let currentHash = window.location.hash.substring(1);
                    let pageMatch = currentHash.match(/page=(\d+)/);
                    let pageStr = pageMatch ? 'page=' + pageMatch[1] + '&' : '';
                    loadModule('bumper-products?' + pageStr + queryString);
                } else {
                    showToast("Failed to remove product", "danger");
                }
            }
        });
    });
}
</script>
