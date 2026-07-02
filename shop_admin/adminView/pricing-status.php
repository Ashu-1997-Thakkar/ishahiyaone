<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Category Filter
$category = $_GET['category'] ?? 'Promotional';

// Count total records for pagination
$totalSql = "SELECT COUNT(*) AS total FROM pricing WHERE category = ?";
$stmt = $conn->prepare($totalSql);
$stmt->bind_param("s", $category);
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];

// Fetch records
$sql = "SELECT *, (sms_count * paise_per_sms / 100) as price FROM pricing WHERE category = ? ORDER BY sort_order ASC, id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $category, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$count = $offset + 1;
?>

<style>
    .compressed-table td,
    .compressed-table th {
        padding: 8px 12px !important;
        font-size: 0.82rem;
        vertical-align: middle !important;
        white-space: nowrap;
    }

    .custom-table thead {
        background-color: #c59d2f;
        color: white;
    }

    .editable {
        cursor: pointer;
        border-bottom: 1px dashed #c59d2f;
    }

    .editable:hover {
        background: #fffcf0;
    }

    .saving {
        opacity: .5;
    }

    .small-muted {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .price-text {
        font-weight: 700;
        color: #059669;
    }

    /* Pagination gold theme */
    .page-item.active .page-link {
        background-color: #c59d2f !important;
        border-color: #c59d2f !important;
    }

    .page-link {
        color: #c59d2f;
    }

    .category-card {
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 15px;
    }
</style>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Pricing Admin (Auto Calculation)</h3>
            <p class="text-muted small mb-0">Managing Category: <span class="badge badge-warning"><?= htmlspecialchars($category) ?></span></p>
        </div>
        <div class="action-btn-group">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPricingModal">
                <i class="fas fa-plus mr-1"></i> Add Package
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="loadModule('pricing')">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Category Selector -->
    <div class="category-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="font-weight-600 mb-0 small text-uppercase text-muted">Select Category to View</label>
                <select id="categoryFilter" class="form-control form-control-sm mt-1" onchange="changePricingCategory(this.value)">
                    <option value="Promotional" <?= $category == 'Promotional' ? 'selected' : '' ?>>Promotional</option>
                    <option value="Transactional" <?= $category == 'Transactional' ? 'selected' : '' ?>>Transactional</option>
                    <option value="Voice SMS" <?= $category == 'Voice SMS' ? 'selected' : '' ?>>Voice SMS</option>
                </select>
            </div>
            <div class="col-md-8 text-right">
                <div class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i> Click on <u>SMS Count</u> or <u>Paise</u> values to edit inline.
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table custom-table compressed-table mb-0" id="pricingTable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">S.N.</th>
                    <th>Package Name</th>
                    <th>Category</th>
                    <th class="text-right">SMS Count</th>
                    <th class="text-right">Paise / SMS</th>
                    <th class="text-right">Total Price (₹)</th>
                    <th class="text-center">Sort</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($r = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $count++ ?></td>
                            <td class="font-weight-bold"><?= htmlspecialchars($r['sms_package']) ?></td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($r['category']) ?></span></td>
                            <td class="text-right">
                                <span class="editable px-2" data-id="<?= $r['id'] ?>" data-field="sms_count">
                                    <?= number_format($r['sms_count']) ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <span class="editable px-2" data-id="<?= $r['id'] ?>" data-field="paise_per_sms">
                                    <?= $r['paise_per_sms'] ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="price-text" id="price-<?= $r['id'] ?>">₹<?= number_format($r['price'], 2) ?></div>
                                <div class="small-muted">Auto-calculated</div>
                            </td>
                            <td class="text-center">
                                <span class="editable px-1" data-id="<?= $r['id'] ?>" data-field="sort_order">
                                    <?= $r['sort_order'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action btn-action-delete" title="Delete" onclick="deletePricingRecord(<?= $r['id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No pricing packages found for this category.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> records</small>
        <?= renderPagination($totalRows, $limit, $page, 'pricing') ?>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPricingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addPricingForm" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color: #c59d2f;"></i>Add Pricing Package</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-600 small">Package Name</label>
                    <input name="sms_package" class="form-control" placeholder="e.g. 1 Lakh SMS" required>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-600 small">Category</label>
                            <select name="category" class="form-control">
                                <option value="Promotional">Promotional</option>
                                <option value="Transactional">Transactional</option>
                                <option value="Voice SMS">Voice SMS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-600 small">Sort Order</label>
                            <input name="sort_order" type="number" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-600 small">SMS Count</label>
                            <input name="sms_count" type="number" class="form-control" value="100000" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-600 small">Paise per SMS</label>
                            <input name="paise_per_sms" type="number" class="form-control" value="10" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm px-4" type="submit">Create Package</button>
            </div>
        </form>
    </div>
</div>

<script>
function changePricingCategory(cat) {
    window.location.hash = `pricing?category=${cat}`;
}

// Inline Edit Logic
$(document).off('click', '.editable').on('click', '.editable', function() {
    if ($(this).find('input').length) return;
    
    const el = $(this);
    const id = el.data('id');
    const field = el.data('field');
    const oldVal = el.text().trim().replace(/,/g, '');
    
    const input = $('<input type="number" class="form-control form-control-sm" style="width:100px; display:inline-block;">');
    input.val(oldVal);
    el.html(input);
    input.focus();
    
    input.on('blur', function() {
        const newVal = $(this).val();
        if (newVal === oldVal || newVal === "") {
            el.text(Number(oldVal).toLocaleString());
            return;
        }
        
        el.addClass('saving');
        $.ajax({
            url: 'controller/pricingController.php',
            method: 'POST',
            data: { action: 'update', id: id, field: field, value: newVal },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    el.text(Number(newVal).toLocaleString());
                    if (res.new_price !== undefined) {
                        $(`#price-${id}`).text('₹' + Number(res.new_price).toLocaleString(undefined, {minimumFractionDigits: 2}));
                    }
                    showToast("Value updated", "success");
                } else {
                    showToast(res.message || "Update failed", "danger");
                    el.text(Number(oldVal).toLocaleString());
                }
            },
            error: function() {
                showToast("Network error", "danger");
                el.text(Number(oldVal).toLocaleString());
            },
            complete: function() {
                el.removeClass('saving');
            }
        });
    });
    
    input.on('keyup', function(e) {
        if (e.key === 'Enter') $(this).blur();
        if (e.key === 'Escape') {
            el.text(Number(oldVal).toLocaleString());
        }
    });
});

function deletePricingRecord(id) {
    showConfirm("Delete Package?", "Are you sure you want to remove this pricing package?", function() {
        $.ajax({
            url: 'controller/pricingController.php',
            method: 'POST',
            data: { action: 'delete', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    showToast("Deleted successfully", "success");
                    loadModule('pricing');
                } else {
                    showToast(res.message || "Delete failed", "danger");
                }
            }
        });
    });
}

$('#addPricingForm').on('submit', function(e) {
    e.preventDefault();
    const fd = $(this).serialize() + '&action=add';
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true);
    
    $.ajax({
        url: 'controller/pricingController.php',
        method: 'POST',
        data: fd,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                $('#addPricingModal').modal('hide');
                showToast("Package added successfully", "success");
                loadModule('pricing');
            } else {
                showToast(res.message || "Failed to add package", "danger");
            }
        },
        complete: function() {
            btn.prop('disabled', false);
        }
    });
});
</script>