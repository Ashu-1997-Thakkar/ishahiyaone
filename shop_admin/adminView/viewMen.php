<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$totalSql = "SELECT COUNT(*) AS total FROM all_category";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_assoc($totalResult)['total'];

// Fetch products with category names
$sql = "SELECT 
            p.id AS product_id,
            p.name AS product_name,
            p.brand,
            p.Image1 AS image1,
            p.main_category_id,
            p.sub_category_id,
            mc.main_category_name,
            sc.sub_category_name,
            p.is_new_arrival,
            p.is_our_collection,
            p.is_best
        FROM all_category p
        LEFT JOIN main_category mc ON p.main_category_id = mc.id
        LEFT JOIN sub_category sc ON p.sub_category_id = sc.id
        ORDER BY p.id DESC
        LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);
$sn = $offset + 1;
?>

<style>
    .compressed-table td,
    .compressed-table th {
        padding: 6px 10px !important;
        font-size: 0.78rem;
        vertical-align: middle !important;
        white-space: nowrap;
    }

    .product-thumb-sm {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    .custom-table thead th {
        background-color: #c59d2f;
        color: white;
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

    .page-link {
        color: #c59d2f;
    }

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

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Shop Collection</h3>
            <span class="badge badge-dark">Total: <?= $totalRows ?> Products</span>
        </div>
        <div class="action-btn-group d-flex align-items-center" style="gap: 10px;">
            <button class="btn btn-danger btn-sm" id="btnDeleteSelected" style="display:none;" onclick="deleteSelectedOurShop()">
                <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCount">0</span>)
            </button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProductModal">
                <i class="fas fa-plus mr-1"></i> Add Product
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="loadModule('our-shop')">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllProducts" style="cursor: pointer;">
                    </th>
                    <th class="text-center">S.N</th>
                    <th class="text-center">ID</th>
                    <th class="text-center">Image</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Main Category</th>
                    <th>Sub Category</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="product-checkbox" value="<?= $row['product_id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted">#<?= $sn++ ?></td>
                            <td class="text-center font-weight-600"><?= $row['product_id'] ?></td>
                            <td class="text-center">
                                <?php
                                $img = trim($row['image1']);
                                $imgPath = !empty($img) ? ltrim($img, '/') : 'assets/no-image.png';
                                ?>
                                <img src="<?= $imgPath ?>" class="product-thumb-sm" onerror="this.src='assets/no-image.png'">
                            </td>
                            <td>
                                <div class="font-weight-600 text-dark" title="<?= htmlspecialchars($row['product_name']) ?>">
                                    <?= mb_strimwidth(htmlspecialchars($row['product_name']), 0, 25, "...") ?>
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
                                </div>
                            </td>
                            <td><span class="text-muted"><?= htmlspecialchars($row['brand'] ?: '—') ?></span></td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($row['main_category_name'] ?: 'No Category') ?></span></td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($row['sub_category_name'] ?: 'No Subcategory') ?></span></td>
                            <td class="text-center">
                                <div class="action-grid">
                                    <div class="d-flex justify-content-center" style="grid-column: span 2; gap: 8px; margin-bottom: 2px;">
                                        <button onclick="editCollection(<?= $row['product_id'] ?>)" class="btn btn-primary rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0;" title="Edit" data-toggle="tooltip">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </button>
                                        <button onclick="deleteOurShopProduct(<?= $row['product_id'] ?>)" class="btn btn-danger rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0;" title="Delete" data-toggle="tooltip">
                                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    
                                    <button onclick="openSubcategoryModal(<?= $row['product_id'] ?>, <?= (int)$row['main_category_id'] ?>)" class="btn-action-custom btn-sub-cyan w-100">Add Sub</button>
                                    <button onclick="toggleBestStatus(<?= $row['product_id'] ?>)" class="btn-action-custom w-100 <?= $row['is_best'] ? 'btn-best-outline' : 'btn-best-green' ?>"><?= $row['is_best'] ? 'Unmark Best' : 'Mark as Best' ?></button>
                                    
                                    <button onclick="toggleArrivalStatus(<?= $row['product_id'] ?>)" class="btn-action-custom full-width w-100 <?= $row['is_new_arrival'] ? 'btn-arrival-outline' : 'btn-arrival-yellow' ?>"><?= $row['is_new_arrival'] ? 'Unmark New Arrival' : 'Mark as New Arrival' ?></button>
                                    <button onclick="toggleCollectionStatus(<?= $row['product_id'] ?>)" class="btn-action-custom full-width w-100 <?= $row['is_our_collection'] ? 'btn-col-outline' : 'btn-col-grey' ?>"><?= $row['is_our_collection'] ? 'Unmark Our Collection' : 'Mark Our Collection' ?></button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">No products found in shop collection.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> products</small>
        <?= renderPagination($totalRows, $limit, $page, 'our-shop') ?>
    </div>
</div>

<!-- ===================== MODALS ===================== -->

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color: #c59d2f;"></i>Add New Product</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="controller/addallcat.php" method="POST" enctype="multipart/form-data" id="addProductForm">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Main Category</label>
                            <select class="form-control main-category" name="main_category_id" id="add_main_category_id" required>
                                <option value="">-- Select --</option>
                                <?php
                                $mcRes = $conn->query("SELECT * FROM main_category ORDER BY main_category_name ASC");
                                while ($mc = $mcRes->fetch_assoc()) echo "<option value='{$mc['id']}'>" . htmlspecialchars($mc['main_category_name']) . "</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Sub Category</label>
                            <select name="sub_category_id" id="add_category_id" class="form-control sub-category" required>
                                <option value="">-- Select Main First --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600 small">Product Name</label>
                            <input type="text" class="form-control" name="name" required>
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
                            while ($s = mysqli_fetch_assoc($sizesRes2)): $name_sz = htmlspecialchars($s['size_name']); ?>
                                <div class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input type="checkbox" class="custom-control-input add-size-opt" id="add_sz_<?= str_replace(' ', '_', $name_sz) ?>" value="<?= $name_sz ?>">
                                    <label class="custom-control-label small" for="add_sz_<?= str_replace(' ', '_', $name_sz) ?>"><?= $name_sz ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="size" id="add_size_input" value="[]">
                    </div>
                    <label class="font-weight-600 small">Product Images (up to 4)</label>
                    <div class="row">
                        <?php for($i=1; $i<=4; $i++): ?>
                        <div class="col-md-3 form-group text-center">
                            <label class="font-weight-600 small">Img <?= $i ?></label>
                            <div class="mb-1">
                                <img id="add_prev_<?= $i ?>" src="assets/no-image.png" style="width:100%; height:70px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
                            </div>
                            <input type="file" class="form-control-file small" name="image<?= $i ?>" accept="image/*" onchange="previewFile(this,'add_prev_<?= $i ?>')">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload" class="btn btn-primary btn-sm px-4">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color: #c59d2f;"></i>Edit Product</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="controller/updateProductWithImages.php" method="POST" enctype="multipart/form-data" id="editProductForm">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Main Category</label>
                            <select class="form-control main-category" name="main_category_id" id="edit_main_category" required>
                                <option value="">-- Select --</option>
                                <?php
                                $mcRes = $conn->query("SELECT * FROM main_category ORDER BY main_category_name ASC");
                                while ($mc = $mcRes->fetch_assoc()) echo "<option value='{$mc['id']}'>" . htmlspecialchars($mc['main_category_name']) . "</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Sub Category</label>
                            <select name="sub_category_id" id="edit_sub_category" class="form-control sub-category" required>
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600 small">Product Name</label>
                            <input type="text" class="form-control" name="name" id="edit_product_name" required>
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
                        <label class="font-weight-600 small">Sizes <small class="text-muted">(for fashion items only)</small></label>
                        <div class="border rounded p-2" style="max-height: 100px; overflow-y: auto;">
                            <?php
                            $sizesRes2 = mysqli_query($conn, "SELECT size_name FROM sizes ORDER BY size_id ASC");
                            while ($s = mysqli_fetch_assoc($sizesRes2)): $name_sz = htmlspecialchars($s['size_name']); ?>
                                <div class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input type="checkbox" class="custom-control-input edit-size-opt" id="edit_sz_<?= str_replace(' ', '_', $name_sz) ?>" value="<?= $name_sz ?>">
                                    <label class="custom-control-label small" for="edit_sz_<?= str_replace(' ', '_', $name_sz) ?>"><?= $name_sz ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="size" id="edit_size_input" value="[]">
                    </div>
                    <label class="font-weight-600 small">Product Images (up to 4)</label>
                    <div class="row">
                        <?php for($i=1; $i<=4; $i++): ?>
                        <div class="col-md-3 form-group text-center">
                            <label class="font-weight-600 small">Img <?= $i ?></label>
                            <div class="mb-1">
                                <img id="edit_preview_<?= $i ?>" src="assets/no-image.png" style="width:100%; height:70px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
                            </div>
                            <input type="file" class="form-control-file small" name="image<?= $i ?>" accept="image/*" onchange="previewFile(this,'edit_preview_<?= $i ?>')">
                            <input type="hidden" name="old_image<?= $i ?>" id="old_image<?= $i ?>">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Subcategory Modal -->
<div class="modal fade" id="subcategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-sitemap mr-2" style="color: #c59d2f;"></i>Manage Subcategory (Add Sub)</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="controller/SubVc.php" method="POST" enctype="multipart/form-data" id="subProductForm">
                <div class="modal-body">
                    <input type="hidden" name="collection_id" id="sub_product_id">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Main Category</label>
                            <select class="form-control main-category" name="main_category_id" id="sub_main_category" required>
                                <option value="">-- Select --</option>
                                <?php
                                $mcRes = $conn->query("SELECT * FROM main_category ORDER BY main_category_name ASC");
                                while ($mc = $mcRes->fetch_assoc()) echo "<option value='{$mc['id']}'>" . htmlspecialchars($mc['main_category_name']) . "</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600 small">Target Sub Category</label>
                            <select name="sub_category_id" id="sub_target_category" class="form-control sub-category" required>
                                <option value="">-- Select Main First --</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600 small">Product Name</label>
                            <input type="text" class="form-control" name="name" id="sub_product_name" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Brand</label>
                            <input type="text" class="form-control" name="brand" id="sub_brand" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Price (₹)</label>
                            <input type="number" class="form-control" name="price" id="sub_price" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">SKU No</label>
                            <input type="text" class="form-control" name="sku_no" id="sub_sku_no" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600 small">Stock Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="sub_quantity" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600 small">Description</label>
                        <textarea class="form-control" name="description" id="sub_description" rows="2" required></textarea>
                    </div>
                    <div class="form-group sizes-section">
                        <label class="font-weight-600 small">Sizes <small class="text-muted">(for fashion items only)</small></label>
                        <div class="border rounded p-2" style="max-height: 100px; overflow-y: auto;">
                            <?php
                            $sizesRes2 = mysqli_query($conn, "SELECT size_name FROM sizes ORDER BY size_id ASC");
                            while ($s = mysqli_fetch_assoc($sizesRes2)): $name_sz = htmlspecialchars($s['size_name']); ?>
                                <div class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input type="checkbox" class="custom-control-input sub-size-opt" id="sub_sz_<?= str_replace(' ', '_', $name_sz) ?>" value="<?= $name_sz ?>">
                                    <label class="custom-control-label small" for="sub_sz_<?= str_replace(' ', '_', $name_sz) ?>"><?= $name_sz ?></label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="size" id="sub_size_input" value="[]">
                    </div>
                    <label class="font-weight-600 small">Product Images (up to 4)</label>
                    <div class="row">
                        <?php for($i=1; $i<=4; $i++): ?>
                        <div class="col-md-3 form-group text-center">
                            <label class="font-weight-600 small">Img <?= $i ?></label>
                            <div class="mb-1">
                                <img id="sub_preview_<?= $i ?>" src="assets/no-image.png" style="width:100%; height:70px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
                            </div>
                            <!-- Note: SubVc case 2 expects image1, image2... for uploads -->
                            <input type="file" class="form-control-file small" name="image<?= $i ?>" accept="image/*" onchange="previewFile(this,'sub_preview_<?= $i ?>')">
                            <!-- We also pass old images if we want to copy them over without re-uploading -->
                            <input type="hidden" name="old_image<?= $i ?>" id="sub_old_image<?= $i ?>">
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">Update Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewFile(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(`#${previewId}`).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Sync size checkboxes to hidden input (Add Sub)
    $(document).on('change', '.sub-size-opt', function() {
        let selected = [];
        $('.sub-size-opt:checked').each(function() { selected.push($(this).val()); });
        $('#sub_size_input').val(JSON.stringify(selected));
    });

    function openSubcategoryModal(id, mainId) {
        // Pre-fill fields with existing product data
        $.getJSON(`controller/getAllCategory.php?id=${id}`, function(res) {
            if (res.success) {
                let d = res.data;
                $('#sub_product_id').val(d.id);
                $('#sub_main_category').val(d.main_category_id).trigger('change');
                setTimeout(() => {
                    // Pre-select the existing sub_category_id if available
                    if (d.sub_category_id) {
                        $('#sub_target_category').val(d.sub_category_id);
                    }
                }, 500);

                $('#sub_product_name').val(d.name);
                $('#sub_brand').val(d.brand);
                $('#sub_price').val(d.price);
                $('#sub_sku_no').val(d.sku_no || '');
                $('#sub_quantity').val(d.quantity || 0);
                $('#sub_description').val(d.description || '');

                // Set image
                for(let i=1; i<=4; i++) {
                    let img = d['Image' + i] || d['image' + i];
                    if(img) {
                        $(`#sub_preview_${i}`).attr('src', img.replace(/^\/+/, ''));
                        $(`#sub_old_image${i}`).val(img);
                    } else {
                        $(`#sub_preview_${i}`).attr('src', 'assets/no-image.png');
                        $(`#sub_old_image${i}`).val('');
                    }
                }

                // Set sizes
                let sizes = [];
                try { sizes = JSON.parse(d.size || '[]'); } catch(e) { sizes = d.size ? d.size.split(',') : []; }
                $('.sub-size-opt').prop('checked', false);
                sizes.forEach(s => $(`#sub_sz_${s.trim().replace(/ /g, '_')}`).prop('checked', true));
                $('#sub_size_input').val(JSON.stringify(sizes));

                $('#subcategoryModal').modal('show');
            }
        });
    }

    function getOurShopCurrentPage() {
        let currentUrl = window.location.hash;
        let pageMatch = currentUrl.match(/page=(\d+)/);
        return pageMatch ? pageMatch[1] : 1;
    }

    // --- Select All & Bulk Delete Logic ---
    function updateBulkDeleteBtn() {
        let selectedCount = $('.product-checkbox:checked').length;
        if(selectedCount > 0) {
            $('#selectedCount').text(selectedCount);
            $('#btnDeleteSelected').fadeIn(200);
        } else {
            $('#btnDeleteSelected').fadeOut(200);
        }
        
        let totalCount = $('.product-checkbox').length;
        $('#selectAllProducts').prop('checked', totalCount > 0 && selectedCount === totalCount);
    }

    $('#selectAllProducts').on('change', function() {
        $('.product-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkDeleteBtn();
    });

    $(document).on('change', '.product-checkbox', function() {
        updateBulkDeleteBtn();
    });

    function deleteSelectedOurShop() {
        let selectedIds = [];
        $('.product-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        showConfirm("Delete Multiple Products?", `Are you sure you want to delete ${selectedIds.length} selected products?`, function() {
            // Delete them one by one or create a new endpoint? 
            // It's safer to delete sequentially if a bulk endpoint doesn't exist, 
            // or we can pass an array to deleteallcat.php if it supports it.
            // Since deleteallcat.php likely expects a single id, we'll send multiple requests for now to ensure compatibility.
            let deletedCount = 0;
            let errors = 0;
            
            $('#btnDeleteSelected').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id => {
                return $.ajax({
                    url: "controller/deleteallcat.php",
                    type: "POST",
                    data: { id: id },
                    success: function(response) {
                        if (response.trim() !== 'success') errors++;
                    },
                    error: function() { errors++; }
                });
            });

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} products!`, "success");
                } else {
                    showToast(`Deleted with ${errors} errors.`, "warning");
                }
                loadModule('our-shop', getOurShopCurrentPage());
            });
        });
    }
    // ----------------------------------------

    $('#addProductForm').on('submit', function(e) {
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
                    showToast("Product added successfully!", "success");
                    $('.modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadModule('our-shop', getOurShopCurrentPage());
                } else {
                    alert("Backend Error: " + response);
                    showToast("Error: " + response, "danger");
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error);
                showToast("Failed to add product", "danger");
            }
        });
    });

    $('#editProductForm').on('submit', function(e) {
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
                    showToast("Product updated successfully!", "success");
                    $('.modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadModule('our-shop', getOurShopCurrentPage());
                } else {
                    alert("Backend Error: " + response);
                    showToast("Error: " + response, "danger");
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error);
                showToast("Failed to update product", "danger");
            }
        });
    });

    $('#subProductForm').on('submit', function(e) {
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
                    showToast("Subcategory updated successfully!", "success");
                    $('.modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadModule('our-shop', getOurShopCurrentPage());
                } else {
                    alert("Backend Error: " + response);
                    showToast("Error: " + response, "danger");
                }
            },
            error: function(xhr, status, error) {
                alert("AJAX Error: " + error);
                showToast("Failed to update subcategory", "danger");
            }
        });
    });

    // Sync size checkboxes to hidden input (Add)
    $(document).on('change', '.add-size-opt', function() {
        let selected = [];
        $('.add-size-opt:checked').each(function() { selected.push($(this).val()); });
        $('#add_size_input').val(JSON.stringify(selected));
    });

    // Sync size checkboxes to hidden input (Edit)
    $(document).on('change', '.edit-size-opt', function() {
        let selected = [];
        $('.edit-size-opt:checked').each(function() { selected.push($(this).val()); });
        $('#edit_size_input').val(JSON.stringify(selected));
    });

    function editCollection(id) {
        $.getJSON(`controller/getAllCategory.php?id=${id}`, function(res) {
            if (res.success) {
                let d = res.data;
                $('#edit_product_id').val(d.id);
                $('#edit_product_name').val(d.name);
                $('#edit_brand').val(d.brand);
                $('#edit_price').val(d.price);
                $('#edit_sku_no').val(d.sku_no || '');
                $('#edit_quantity').val(d.quantity || 0);
                $('#edit_description').val(d.description || '');

                // Set image
                for(let i=1; i<=4; i++) {
                    let img = d['Image' + i] || d['image' + i];
                    if(img) {
                        $(`#edit_preview_${i}`).attr('src', img.replace(/^\/+/, ''));
                        $(`#old_image${i}`).val(img);
                    } else {
                        $(`#edit_preview_${i}`).attr('src', 'assets/no-image.png');
                        $(`#old_image${i}`).val('');
                    }
                }

                // Set sizes
                let sizes = [];
                try { sizes = JSON.parse(d.size || '[]'); } catch(e) { sizes = d.size ? d.size.split(',') : []; }
                $('.edit-size-opt').prop('checked', false);
                sizes.forEach(s => $(`#edit_sz_${s.trim().replace(/ /g, '_')}`).prop('checked', true));
                $('#edit_size_input').val(JSON.stringify(sizes));

                // Trigger category load
                $('#edit_main_category').val(d.main_category_id).trigger('change');
                setTimeout(() => {
                    $('#edit_sub_category').val(d.sub_category_id);
                }, 500);

                $('#editProductModal').modal('show');
            }
        });
    }

    function deleteOurShopProduct(id) {
        showConfirm("Delete Product?", "Are you sure you want to delete this product from the shop collection?", function() {
            $.ajax({
                url: "controller/deleteallcat.php",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.trim() === 'success') {
                        showToast("Product deleted successfully!", "danger");
                        loadModule('our-shop', getOurShopCurrentPage());
                    } else {
                        showToast("Error: " + response, "danger");
                    }
                },
                error: function() {
                    showToast("Failed to delete product", "danger");
                }
            });
        });
    }

    function toggleArrivalStatus(id) {
        $.get(`controller/toggle_new_arrival.php?id=${id}&type=main`, function() {
            loadModule('our-shop');
            showToast("New Arrival status updated", "success");
        });
    }

    function toggleBestStatus(id) {
        $.get(`controller/toggle_best.php?id=${id}&type=main`, function() {
            loadModule('our-shop');
            showToast("Best status updated", "success");
        });
    }

    function toggleCollectionStatus(id) {
        $.get(`controller/mark_our_collection.php?id=${id}&type=main`, function() {
            loadModule('our-shop');
            showToast("Collection status updated", "success");
        });
    }

    // Logic for dynamic subcategories
    $(document).on("change", ".main-category", function() {
        let mainCatId = $(this).val();
        let mainCatText = $(this).find("option:selected").text().trim().toLowerCase();
        let subSelect = $(this).closest("form").find(".sub-category");
        let sizesSection = $(this).closest("form").find(".sizes-section");

        if (mainCatText.includes("electronic")) {
            sizesSection.hide();
        } else {
            sizesSection.show();
        }

        if (!mainCatId) {
            subSelect.html('<option value="">-- Select Main First --</option>');
            return;
        }
        $.post("controller/get_subcategories.php", {
            main_category_id: mainCatId
        }, function(res) {
            let data = JSON.parse(res);
            let html = '<option value="">-- Select Sub Category --</option>';
            if (data.status === "success") {
                data.data.forEach(sc => html += `<option value="${sc.id}">${sc.sub_category_name}</option>`);
            }
            subSelect.html(html);
        });
    });
</script>