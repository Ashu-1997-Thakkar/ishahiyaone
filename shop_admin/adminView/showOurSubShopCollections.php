<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SQL for SubShop Collections
$sql = "
    SELECT 
        sc.id AS product_id,
        COALESCE(NULLIF(TRIM(sc.name), ''), 'Default Product Name') AS product_name,
        sc.brand,
        sc.price,
        COALESCE(NULLIF(TRIM(sc.Size), ''), 'One Size') AS size,
        sc.sku_no,
        sc.image1, sc.image2, sc.image3, sc.image4,
        COALESCE(mc.main_category_name, 'Uncategorized') AS main_category_name,
        COALESCE(sub.sub_category_name, 'Uncategorized') AS sub_category_name,
        sc.is_new_arrival,
        sc.is_our_collection,
        sc.is_best,
        sc.is_bumper_offer,
        sc.quantity,
        sc.description,
        sub.main_category_id,
        sc.category_id as sub_category_id
    FROM subcategories sc
    LEFT JOIN sub_category sub ON sc.category_id = sub.id
    LEFT JOIN main_category mc ON sub.main_category_id = mc.id
    ORDER BY sc.id DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $sql);
$sn = $offset + 1;

// Keywords that indicate NON-fashion categories (size is irrelevant)
$non_fashion_keywords = ['electronics', 'laptop', 'computer', 'desktop', 'mobile', 'phone', 'tablet', 'camera', 'tv', 'television', 'appliance', 'gadget', 'tech'];

function isFashionCategory(?string $categoryName): bool {
    global $non_fashion_keywords;
    $lower = strtolower($categoryName ?? '');
    foreach ($non_fashion_keywords as $keyword) {
        if (strpos($lower, $keyword) !== false) {
            return false; // it's an electronics/tech category
        }
    }
    return true; // assume fashion if no tech keyword found
}
?>

<style>
    .compressed-table td, .compressed-table th {
        padding: 6px 10px !important;
        font-size: 0.78rem;
        vertical-align: middle !important;
        white-space: nowrap;
    }
    .product-thumb-xs {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .custom-table thead th {
        background-color: #c59d2f;
        color: white;
        padding: 8px 10px !important;
        border: none;
    }
    .badge-feature {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-right: 2px;
    }
    /* Pagination gold theme */
    .page-item.active .page-link {
        background-color: #c59d2f !important;
        border-color: #c59d2f !important;
    }
    .page-link { color: #c59d2f; }
    .btn-action-custom {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        transition: opacity 0.2s;
        line-height: 1.2;
        display: inline-block;
        text-align: center;
    }
    .btn-action-custom:hover { opacity: 0.85; }
    .btn-edit-blue { background-color: #0d6efd; color: white; border: 1px solid #0d6efd; }
    .btn-delete-red { background-color: #dc3545; color: white; border: 1px solid #dc3545; }
    .btn-arrival-yellow { background-color: #ffc107; color: black; border: 1px solid #ffc107; }
    .btn-arrival-outline { background-color: transparent; color: #ffc107; border: 1px solid #ffc107; }
    .btn-col-grey { background-color: #6c757d; color: white; border: 1px solid #6c757d; }
    .btn-col-outline { background-color: transparent; color: #6c757d; border: 1px solid #6c757d; }
    .btn-sub-cyan { background-color: #0dcaf0; color: black; border: 1px solid #0dcaf0; }
    .btn-best-green { background-color: #198754; color: white; border: 1px solid #198754; }
    .btn-best-outline { background-color: transparent; color: #198754; border: 1px solid #198754; }
    .btn-bumper-red { background-color: #dc3545; color: white; border: 1px solid #dc3545; }
    .btn-bumper-outline { background-color: transparent; color: #dc3545; border: 1px solid #dc3545; }

    /* Action Grid Layout */
    .action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px;
        width: 210px;
        margin: 0 auto;
    }
    .action-grid .full-width {
        grid-column: span 2;
    }
</style>

<div class="module-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 font-weight-bold text-dark">SubShop Collections</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted">Inventory</a></li>
                    <li class="breadcrumb-item active text-gold" aria-current="page">SubShop</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-column align-items-end" style="gap: 8px;">
            <!-- Row 1: Search + Refresh -->
            <div class="d-flex align-items-center" style="gap: 8px;">
                <div class="search-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="subShopSearch" class="form-control form-control-sm search-input" placeholder="Search products or SKU..." onkeyup="liveSearchSubShop()">
                </div>
                <button class="btn btn-outline-secondary btn-sm" onclick="loadModule('sub-shop')">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <!-- Row 2: Add + Bulk Delete -->
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button class="btn btn-danger btn-sm" id="btnDeleteSelectedSub" style="display:none;" onclick="deleteSelectedSubShop()">
                    <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountSub">0</span>)
                </button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSubShopModal">
                    <i class="fas fa-plus mr-1"></i> Add Sub Collection
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table custom-table compressed-table mb-0" id="subShopTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllSubProducts" style="cursor: pointer;">
                    </th>
                    <th class="text-center">S.N</th>
                    <th class="text-center">Product ID</th>
                    <th>Product Name</th>
                    <th>Main-Category</th>
                    <th>Sub-Category</th>
                    <th>Size</th>
                    <th>SKU</th>
                    <th class="text-right">Price (₹)</th>
                    <th class="text-center">Images</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="sub-product-checkbox" value="<?= $row['product_id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted">#<?= $sn++ ?></td>
                            <td class="text-center font-weight-600"><?= $row['product_id'] ?></td>
                            <td>
                                <div class="font-weight-600 text-dark" title="<?= htmlspecialchars($row['product_name']) ?>">
                                    <?= mb_strimwidth(htmlspecialchars($row['product_name']), 0, 30, "...") ?>
                                </div>
                                <div class="mt-1">
                                    <?php if ($row['is_new_arrival']): ?>
                                        <span class="badge badge-warning badge-feature" title="New Arrival">NEW</span>
                                    <?php endif; ?>
                                    <?php if ($row['is_our_collection']): ?>
                                        <span class="badge badge-info badge-feature" title="Our Collection">COL</span>
                                    <?php endif; ?>
                                    <?php if ($row['is_best']): ?>
                                        <span class="badge badge-success badge-feature" title="Best Seller">BEST</span>
                                    <?php endif; ?>
                                    <?php if ($row['is_bumper_offer']): ?>
                                        <span class="badge badge-danger badge-feature" title="Bumper Offer">BUMPER</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($row['main_category_name'] ?? 'N/A') ?></span></td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($row['sub_category_name'] ?? 'N/A') ?></span></td>
                            <td>
                                <?php if (isFashionCategory($row['main_category_name'])): ?>
                                    <small class="text-muted"><?= htmlspecialchars($row['size'] ?? '—') ?></small>
                                <?php else: ?>
                                    <small class="text-muted">—</small>
                                <?php endif; ?>
                            </td>
                            <td><code class="small text-primary"><?= htmlspecialchars($row['sku_no'] ?? '—') ?></code></td>
                            <td class="text-right font-weight-600">₹<?= number_format($row['price'], 2) ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <?php for($i=1; $i<=4; $i++): 
                                        $img = $row["image$i"]; 
                                        if($img): 
                                            // Handle cases where the path might already be stored in the DB
                                            $final_img = (strpos($img, 'uploads/') !== false) ? $img : "uploads/subshop/" . $img;
                                    ?>
                                            <img src="<?= htmlspecialchars($final_img) ?>" class="product-thumb-xs" onerror="this.src='assets/no-image.png'">
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-grid">
                                    <div class="d-flex justify-content-center" style="grid-column: span 2; gap: 8px; margin-bottom: 2px;">
                                        <button onclick="editSubShop(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" class="btn btn-primary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0;" title="Edit" data-toggle="tooltip">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </button>
                                        <button onclick="deleteSubShop(<?= $row['product_id'] ?>)" class="btn btn-danger rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0;" title="Delete" data-toggle="tooltip">
                                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    
                                    <button onclick="toggleSubStatus(<?= $row['product_id'] ?>, 'best')" class="btn-action-custom full-width w-100 <?= $row['is_best'] ? 'btn-best-outline' : 'btn-best-green' ?>"><?= $row['is_best'] ? 'Unmark Best' : 'Mark as Best' ?></button>
                                    <button onclick="toggleSubStatus(<?= $row['product_id'] ?>, 'bumper')" class="btn-action-custom full-width w-100 <?= $row['is_bumper_offer'] ? 'btn-bumper-outline' : 'btn-bumper-red' ?>"><?= $row['is_bumper_offer'] ? 'Unmark Bumper' : 'Mark as Bumper' ?></button>
                                    <button onclick="toggleSubStatus(<?= $row['product_id'] ?>, 'arrival')" class="btn-action-custom full-width w-100 <?= $row['is_new_arrival'] ? 'btn-arrival-outline' : 'btn-arrival-yellow' ?>"><?= $row['is_new_arrival'] ? 'Unmark New Arrival' : 'Mark as New Arrival' ?></button>
                                    <button onclick="toggleSubStatus(<?= $row['product_id'] ?>, 'collection')" class="btn-action-custom full-width w-100 <?= $row['is_our_collection'] ? 'btn-col-outline' : 'btn-col-grey' ?>"><?= $row['is_our_collection'] ? 'Unmark Our Collection' : 'Mark Our Collection' ?></button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center py-4 text-muted">No products found in SubShop collection.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        <?php
        $totalRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM subcategories");
        $totalRows = mysqli_fetch_assoc($totalRes)['total'];
        echo renderPagination($totalRows, $limit, $page, 'sub-shop');
        ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- ADD SUB COLLECTION MODAL -->
<!-- ============================================================ -->
<div class="modal fade" id="addSubShopModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color:#c59d2f;"></i>Add Sub Collection</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addSubShopForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Main Category</label>
                            <select class="form-control main-category" name="main_category_id" id="add_main_category_id" required>
                                <option value="">-- Select --</option>
                                <?php
                                $mcRes2 = mysqli_query($conn, "SELECT id, main_category_name FROM main_category ORDER BY main_category_name ASC");
                                while ($mc = mysqli_fetch_assoc($mcRes2)) echo "<option value='{$mc['id']}'>" . htmlspecialchars($mc['main_category_name']) . "</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Sub Category</label>
                            <select name="category_id" id="add_category_id" class="form-control sub-category" required>
                                <option value="">-- Select Main First --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600 small">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Brand</label>
                            <input type="text" class="form-control" name="brand" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Price (₹)</label>
                            <input type="number" class="form-control" name="price" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">SKU No</label>
                            <input type="text" class="form-control" name="sku_no" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Stock Quantity</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600 small">Description</label>
                        <textarea class="form-control" name="description" rows="2" required></textarea>
                    </div>
                    <div class="form-group sizes-section">
                        <label class="font-weight-600 small">Sizes <small class="text-muted">(for fashion items only)</small></label>
                        <div class="border rounded p-2" style="max-height: 100px; overflow-y: auto;">
                            <?php
                            $sizesRes2 = mysqli_query($conn, "SELECT size_name FROM sizes ORDER BY size_id ASC");
                            while ($s = mysqli_fetch_assoc($sizesRes2)): $name = htmlspecialchars($s['size_name']); ?>
                                <div class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input type="checkbox" class="custom-control-input add-size-opt" id="add_sz_<?= str_replace(' ', '_', $name) ?>" value="<?= $name ?>">
                                    <label class="custom-control-label small" for="add_sz_<?= str_replace(' ', '_', $name) ?>"><?= $name ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="size" id="add_size_input" value="[]">
                    </div>
                    <!-- 4 Image Uploads -->
                    <label class="font-weight-600 small">Product Images (up to 4)</label>
                    <div class="row">
                        <?php for($i=1; $i<=4; $i++): ?>
                        <div class="col-md-3 form-group text-center">
                            <label class="font-weight-600 small">Img <?= $i ?></label>
                            <div class="mb-1">
                                <img id="add_prev_<?= $i ?>" src="/shop_admin/assets/no-image.png" style="width:100%; height:70px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
                            </div>
                            <input type="file" class="form-control-file small" name="image<?= $i ?>" accept="image/*" onchange="previewFileSub(this,'add_prev_<?= $i ?>')">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4" id="btnAddSubSubmit">
                            <i class="fas fa-save mr-1"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- EDIT SUB SHOP MODAL -->
<!-- ============================================================ -->
<div class="modal fade" id="editSubShopModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color: #c59d2f;"></i>Edit SubShop Product</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="controller/updateCollection.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Main Category</label>
                            <select class="form-control main-category" name="main_category_id" id="edit_main_category_id" required>
                                <option value="">-- Select --</option>
                                <?php
                                $mcRes = mysqli_query($conn, "SELECT id, main_category_name FROM main_category ORDER BY main_category_name ASC");
                                while ($mc = mysqli_fetch_assoc($mcRes)) echo "<option value='{$mc['id']}'>".htmlspecialchars($mc['main_category_name'])."</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Sub Category</label>
                            <select name="category_id" id="edit_category_id" class="form-control sub-category" required>
                                <option value="">-- Select Main First --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600 small">Product Name</label>
                            <input type="text" class="form-control" name="edit_product_name" id="edit_product_name" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Brand</label>
                            <input type="text" class="form-control" name="brand" id="edit_brand" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Price (₹)</label>
                            <input type="number" class="form-control" name="price" id="edit_price" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">SKU No</label>
                            <input type="text" class="form-control" name="sku_no" id="edit_sku_no" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Stock Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="edit_quantity" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-600 small">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="2" required></textarea>
                    </div>

                    <div class="form-group sizes-section">
                        <label class="font-weight-600 small">Sizes</label>
                        <div class="border rounded p-2" style="max-height: 100px; overflow-y: auto;">
                            <?php
                            $sizesRes = mysqli_query($conn, "SELECT size_name FROM sizes ORDER BY size_id ASC");
                            while ($s = mysqli_fetch_assoc($sizesRes)): $name = htmlspecialchars($s['size_name']); ?>
                                <div class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input type="checkbox" class="custom-control-input edit-size-opt" id="sz_<?= str_replace(' ', '_', $name) ?>" value="<?= $name ?>">
                                    <label class="custom-control-label small" for="sz_<?= str_replace(' ', '_', $name) ?>"><?= $name ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="size" id="edit_size_input">
                    </div>

                    <div class="row">
                        <?php for($i=1; $i<=4; $i++): ?>
                        <div class="col-md-3 form-group text-center">
                            <label class="font-weight-600 small">Img <?= $i ?></label>
                            <div class="mb-2">
                                <img id="edit_prev_<?= $i ?>" src="/shop_admin/assets/no-image.png" style="width:100%; height:70px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
                            </div>
                            <input type="file" class="form-control-file small" name="new_image<?= $i ?>" onchange="previewFileSub(this, 'edit_prev_<?= $i ?>')">
                            <input type="hidden" name="old_image<?= $i ?>" id="old_image<?= $i ?>">
                        </div>
                        <?php endfor; ?>
                    </div>

                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function liveSearchSubShop() {
    let input = $('#subShopSearch').val().toLowerCase();
    $("#subShopTable tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(input) > -1)
    });
}

function previewFileSub(input, prevId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { $('#' + prevId).attr('src', e.target.result); }
        reader.readAsDataURL(input.files[0]);
    }
}

function editSubShop(data) {
    $('#edit_id').val(data.product_id);
    $('#edit_product_name').val(data.product_name);
    $('#edit_brand').val(data.brand);
    $('#edit_price').val(data.price);
    $('#edit_sku_no').val(data.sku_no);
    $('#edit_quantity').val(data.quantity);
    $('#edit_description').val(data.description);
    
    // Load subcategories
    $('#edit_main_category_id').val(data.main_category_id);
    loadSubCategories(data.main_category_id, data.sub_category_id);

    // Hide sizes if category is electronics
    let mainCatText = $('#edit_main_category_id').find("option:selected").text().trim().toLowerCase();
    let sizesSection = $('#editSubShopModal form').find(".sizes-section");
    if (mainCatText.includes("electronic")) {
        sizesSection.hide();
    } else {
        sizesSection.show();
    }
    
    // Set sizes
    let sizes = [];
    try { sizes = JSON.parse(data.size || '[]'); } catch(e) { sizes = data.size ? data.size.split(',') : []; }
    $('.edit-size-opt').prop('checked', false);
    sizes.forEach(s => $(`#sz_${s.trim().replace(/ /g, '_')}`).prop('checked', true));
    $('#edit_size_input').val(JSON.stringify(sizes));
    
    // Set images
    for(let i=1; i<=4; i++) {
        let img = data['image' + i];
        if(img) {
            let finalImg = (img.indexOf('uploads/') !== -1) ? img : 'uploads/subshop/' + img;
            $('#edit_prev_' + i).attr('src', finalImg);
            $('#old_image' + i).val(img);
        } else {
            $('#edit_prev_' + i).attr('src', 'assets/no-image.png');
            $('#old_image' + i).val('');
        }
    }
    
    $('#editSubShopModal').modal('show');
}

// Sync size checkboxes to hidden input (Edit)
$(document).on('change', '.edit-size-opt', function() {
    let selected = [];
    $('.edit-size-opt:checked').each(function() { selected.push($(this).val()); });
    $('#edit_size_input').val(JSON.stringify(selected));
});

// Sync size checkboxes to hidden input (Add)
$(document).on('change', '.add-size-opt', function() {
    let selected = [];
    $('.add-size-opt:checked').each(function() { selected.push($(this).val()); });
    $('#add_size_input').val(JSON.stringify(selected));
});

// ---- AJAX: Add Sub Collection ----
$('#addSubShopForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#btnAddSubSubmit');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
    const fd = new FormData(this);
    $.ajax({
        url: 'controller/addSubCollection.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                $('#addSubShopModal').modal('hide');
                $('#addSubShopForm')[0].reset();
                // Reset image previews
                for(let i=1; i<=4; i++) $('#add_prev_'+i).attr('src','/shop_admin/assets/no-image.png');
                $('.add-size-opt').prop('checked', false);
                loadModule('sub-shop', getSubShopCurrentPage());
            } else {
                showToast(res.message || 'Failed to add product', 'danger');
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Product');
            }
        },
        error: function() {
            showToast('Server error adding product', 'danger');
            btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Product');
        }
    });
});

// Reset Add modal when closed
$('#addSubShopModal').on('hidden.bs.modal', function() {
    $('#addSubShopForm')[0].reset();
    for(let i=1; i<=4; i++) $('#add_prev_'+i).attr('src','/shop_admin/assets/no-image.png');
    $('.add-size-opt').prop('checked', false);
    $('#add_size_input').val('[]');
    $('#btnAddSubSubmit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Product');
});

// ---- AJAX: Edit Sub Shop ----
$('#editSubShopModal form').on('submit', function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...');
    const fd = new FormData(this);
    $.ajax({
        url: 'controller/updateCollection.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                $('#editSubShopModal').modal('hide');
                loadModule('sub-shop', getSubShopCurrentPage());
            } else {
                showToast(res.message || 'Failed to update product', 'danger');
                btn.prop('disabled', false).html('Update Product');
            }
        },
        error: function() {
            showToast('Server error updating product', 'danger');
            btn.prop('disabled', false).html('Update Product');
        }
    });
});

// Load subcategory dynamically for Add modal
$(document).on('change', '#add_main_category_id', function() {
    let mainId = $(this).val();
    let subSelect = $('#add_category_id');
    let mainCatText = $(this).find("option:selected").text().trim().toLowerCase();
    let sizesSection = $(this).closest("form").find(".sizes-section");

    if (mainCatText.includes("electronic")) {
        sizesSection.hide();
    } else {
        sizesSection.show();
    }

    if (!mainId) { subSelect.html('<option value="">-- Select Main First --</option>'); return; }
    $.getJSON(`controller/getSubcategory.php?main_id=${mainId}`, function(data) {
        let html = '<option value="">-- Select Sub Category --</option>';
        data.forEach(sub => html += `<option value="${sub.id}">${sub.sub_category_name || sub.category_name}</option>`);
        subSelect.html(html);
    });
});

function toggleSubStatus(id, type) {
    let url = '';
    if(type === 'arrival') url = `controller/toggle_new_arrival.php?id=${id}&type=sub`;
    else if(type === 'best') url = `controller/toggle_best.php?id=${id}&type=sub`;
    else if(type === 'collection') url = `controller/mark_our_collection.php?id=${id}&type=sub`;
    else if(type === 'bumper') {
        // Use the centralized bumper status controller
        let isBumper = $(`button[onclick="toggleSubStatus(${id}, 'bumper')"]`).hasClass('btn-bumper-red') ? 1 : 0;
        $.ajax({
            url: "controller/updateBumperOfferStatus.php",
            type: "POST",
            data: { product_id: id, source: 'subcategories', is_bumper_offer: isBumper },
            success: function(response) {
                if (response.trim() === 'success') {
                    showToast("Bumper Offer status updated!", "success");
                    loadModule('sub-shop', getSubShopCurrentPage());
                } else {
                    showToast("Failed to update status", "danger");
                }
            }
        });
        return;
    }
    
    $.get(url, function() {
        loadModule('sub-shop', getSubShopCurrentPage());
        showToast("Status updated successfully", "success");
    });
}

function deleteSubShop(id) {
    showConfirm("Delete Product?", "Are you sure you want to delete this product from SubShop collection?", function() {
        $.post('controller/deleteProduct.php', { id: id }, function(res) {
            loadModule('sub-shop');
            showToast("Product deleted", "success");
        });
    });
}

// --- Select All & Bulk Delete Logic for SubShop ---
function updateBulkDeleteSubBtn() {
    let selectedCount = $('.sub-product-checkbox:checked').length;
    if(selectedCount > 0) {
        $('#selectedCountSub').text(selectedCount);
        $('#btnDeleteSelectedSub').fadeIn(200);
    } else {
        $('#btnDeleteSelectedSub').fadeOut(200);
    }
    
    let totalCount = $('.sub-product-checkbox').length;
    $('#selectAllSubProducts').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllSubProducts').on('change', function() {
    $('.sub-product-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteSubBtn();
});

$(document).on('change', '.sub-product-checkbox', function() {
    updateBulkDeleteSubBtn();
});

function deleteSelectedSubShop() {
    let selectedIds = [];
    $('.sub-product-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) return;

    showConfirm("Delete Multiple Products?", `Are you sure you want to delete ${selectedIds.length} selected products?`, function() {
        let deletedCount = 0;
        let errors = 0;
        
        $('#btnDeleteSelectedSub').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

        let requests = selectedIds.map(id => {
            return $.ajax({
                url: "controller/deleteProduct.php",
                type: "POST",
                data: { id: id },
                success: function(response) {
                    // Usually returning empty on success or 'success', ignore for now
                },
                error: function() { errors++; }
            });
        });

        $.when.apply($, requests).always(function() {
            if (errors === 0) {
                showToast(`Successfully deleted ${selectedIds.length} products!`, "success");
            } else {
                showToast(`Deleted with some errors.`, "warning");
            }
            loadModule('sub-shop', getSubShopCurrentPage());
        });
    });
}

function getSubShopCurrentPage() {
    let currentUrl = window.location.hash;
    let pageMatch = currentUrl.match(/page=(\d+)/);
    return pageMatch ? pageMatch[1] : 1;
}
// ------------------------------------------------

function loadSubCategories(mainId, selectedId = null) {
    if(!mainId) {
        $('#edit_category_id').html('<option value="">-- Select Main First --</option>');
        return;
    }
    $.getJSON(`controller/getSubcategory.php?main_id=${mainId}`, function(data) {
        let html = '<option value="">-- Select Sub Category --</option>';
        data.forEach(sub => {
            html += `<option value="${sub.id}">${sub.sub_category_name || sub.category_name}</option>`;
        });
        $('#edit_category_id').html(html);
        if(selectedId) {
            $('#edit_category_id').val(selectedId);
        }
    });
}

// Subcategory loader trigger
$(document).on("change", "#edit_main_category_id", function() {
    let mainCatText = $(this).find("option:selected").text().trim().toLowerCase();
    let sizesSection = $(this).closest("form").find(".sizes-section");

    if (mainCatText.includes("electronic")) {
        sizesSection.hide();
    } else {
        sizesSection.show();
    }

    loadSubCategories($(this).val());
});
</script>

<style>
.product-thumb-xs { width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; transition: 0.2s; }
.product-thumb-xs:hover { transform: scale(2.5); z-index: 100; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
.badge-feature { font-size: 9px; padding: 2px 4px; margin-right: 2px; letter-spacing: 0.5px; }
.search-group { position: relative; }
.search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #999; font-size: 12px; }
.search-input { padding-left: 30px !important; width: 250px; border-radius: 20px !important; border: 1px solid #ddd; }
.search-input:focus { border-color: #c59d2f; box-shadow: 0 0 0 0.2rem rgba(197, 157, 47, 0.1); }
</style>
