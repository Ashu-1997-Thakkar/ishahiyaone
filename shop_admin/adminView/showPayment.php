<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRes    = $conn->query("SELECT COUNT(*) AS total FROM billing_details");
$totalRows   = $totalRes->fetch_assoc()['total'];

$sql = "SELECT * FROM billing_details ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
$count = $offset + 1;
?>

<style>
    .compressed-table td, .compressed-table th {
        padding: 6px 8px !important;
        font-size: 0.78rem;
        vertical-align: middle !important;
        white-space: nowrap;
    }
    .status-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    .status-success { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-failed { background: #fee2e2; color: #991b1b; }
    
    .date-text { font-size: 0.72rem; color: #64748b; }
    .amount-text { font-weight: 700; color: #059669; }
    
    /* Scrollable container for the very wide table */
    .table-responsive-payments {
        overflow-x: auto;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background: white;
    }
</style>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Payment Records</h3>
        <span class="badge badge-dark">Total: <?= $totalRows ?></span>
    </div>

    <div class="table-responsive-payments">
        <table class="custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center">S.N.</th>
                    <th>Ord No</th>
                    <th>Customer</th>
                    <th class="text-center">Products</th>
                    <th>Email / Mobile</th>
                    <th>Location</th>
                    <th>Amount</th>
                    <th>Transaction</th>
                    <th>Status</th>
                    <th>Mode</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $count++ ?></td>
                            <td class="font-weight-bold">#<?= htmlspecialchars($row['id'] ?? '') ?></td>
                            <td>
                                <div class="font-weight-600 text-dark"><?= htmlspecialchars($row['fullname'] ?? '') ?></div>
                                <div class="text-muted" style="font-size: 0.7rem;">SKU: <?= htmlspecialchars($row['sku_no'] ?? 'N/A') ?></div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.7rem;" onclick="showPaymentProducts('<?= htmlspecialchars(($row['order_id'] ?? $row['id']) ?? '') ?>')">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </td>
                            <td>
                                <div class="date-text"><?= htmlspecialchars($row['email'] ?? '') ?></div>
                                <div class="date-text"><?= htmlspecialchars($row['mobile'] ?? '') ?></div>
                            </td>
                            <td>
                                <div class="date-text"><?= htmlspecialchars($row['city'] ?? '') ?>, <?= htmlspecialchars($row['state'] ?? '') ?></div>
                                <div class="text-muted" style="font-size: 0.65rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($row['address'] ?? '') ?></div>
                            </td>
                            <td>
                                <span class="amount-text">₹<?= number_format((float)($row['total_amount'] ?? 0), 2) ?></span>
                            </td>
                            <td>
                                <div class="date-text">TXN: <?= htmlspecialchars($row['TXNID'] ?? 'N/A') ?></div>
                                <div class="date-text">REF: <?= htmlspecialchars($row['RefNo'] ?? 'N/A') ?></div>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $rawStatus = $row['payment_status'] ?? '';
                                    $status = strtolower($rawStatus);
                                    $class = 'status-pending';
                                    if(strpos($status, 'success') !== false) $class = 'status-success';
                                    if(strpos($status, 'fail') !== false) $class = 'status-failed';
                                    $displayText = !empty($rawStatus) ? $rawStatus : 'PENDING';
                                ?>
                                <span class="status-badge <?= $class ?>"><?= htmlspecialchars($displayText) ?></span>
                            </td>
                            <td><span class="badge badge-light border"><?= htmlspecialchars($row['Mode'] ?? 'N/A') ?></span></td>
                            <td class="text-center date-text">
                                <?= date("d M Y", strtotime($row['created_at'] ?? 'now')) ?><br>
                                <?= date("h:i A", strtotime($row['created_at'] ?? 'now')) ?>
                            </td>
                            <td>
                                <div class="action-btn-group justify-content-center">
                                    <?php $actId = htmlspecialchars($row['order_id'] ?? $row['id']); ?>
                                    <a href="generate_pdf.php?id=<?= $actId ?>" class="btn-action" style="background: #eff6ff; color: #2563eb;" title="Print PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button class="btn-action" style="background: #ecfdf5; color: #059669;" title="Dispatch" onclick="openPaymentDispatchModal('<?= $actId ?>')">
                                        <i class="fas fa-truck-loading"></i>
                                    </button>
                                    <button class="btn-action btn-action-delete" title="Delete" onclick="openPaymentDeleteModal('<?= $actId ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="12" class="text-center py-5 text-muted">No payment records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> records</small>
        <?= renderPagination($totalRows, $limit, $page, 'payments') ?>
    </div>
</div>

<!-- ===================== MODALS ===================== -->

<!-- Dispatch Modal -->
<div class="modal fade" id="payDispatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="dispatchPaymentForm" class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-shipping-fast mr-2"></i>Dispatch Order</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="order_id" id="pay_dispatch_order_id" />
                <div class="form-group mb-3">
                    <label class="font-weight-600">Dispatch Date</label>
                    <input type="date" class="form-control" name="dispatch_date" value="<?= date('Y-m-d') ?>" required />
                </div>
                <div class="form-group mb-0">
                    <label class="font-weight-600">Courier Name / Service</label>
                    <input type="text" class="form-control" name="courier_name" placeholder="e.g. Delhivery, BlueDart" required />
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm px-4">Confirm Dispatch</button>
            </div>
        </form>
    </div>
</div>

<!-- Products Modal -->
<div class="modal fade" id="payProductsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-shopping-bag mr-2" style="color: #c59d2f;"></i>Ordered Items</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div id="payProductList" class="text-dark"></div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="payDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deletePaymentForm" class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">Are you sure?</h4>
                <p class="text-muted mb-4">You are about to delete this payment record. This action cannot be undone.</p>
                <input type="hidden" name="order_id" id="pay_delete_order_id" />
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-light btn-lg mr-2 px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">Delete Record</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentDispatchModal(orderId) {
    $('#pay_dispatch_order_id').val(orderId);
    $('#payDispatchModal').modal('show');
}

function openPaymentDeleteModal(orderId) {
    $('#pay_delete_order_id').val(orderId);
    $('#payDeleteModal').modal('show');
}

function showPaymentProducts(orderId) {
    const listEl = $('#payProductList');
    listEl.html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
    $('#payProductsModal').modal('show');

    $.ajax({
        url: `/shop_admin/adminView/get_products.php`,
        method: 'GET',
        data: { order_id: orderId },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                let html = '<ul class="list-group list-group-flush">';
                data.products.forEach(p => {
                    html += `<li class="list-group-item d-flex align-items-center py-3">
                        <i class="fas fa-check-circle text-success mr-3"></i>
                        <span class="font-weight-600">${p}</span>
                    </li>`;
                });
                html += '</ul>';
                if(data.sku_no) {
                    html += `<div class="mt-3 p-2 bg-light rounded text-center small text-muted"><strong>SKU:</strong> ${data.sku_no}</div>`;
                }
                listEl.html(html);
            } else {
                listEl.html(`<div class="alert alert-warning">${data.message}</div>`);
            }
        },
        error: function() {
            listEl.html('<div class="alert alert-danger">Failed to load product data.</div>');
        }
    });
}

$(document).ready(function() {
    // Check hash for legacy redirects (fallback)
    const urlParams = new URLSearchParams(window.location.hash.split('?')[1] || "");
    const status = urlParams.get('status');
    if (status === 'deleted') {
        showToast("Order deleted successfully", "success");
    } else if (status === 'sms_sent') {
        showToast("Order dispatched and SMS sent", "success");
    }

    // AJAX Delete Handling
    $('#deletePaymentForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        
        $.ajax({
            url: './adminView/delete_order.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#payDeleteModal').modal('hide');
                    showToast("Order deleted successfully", "success");
                    if(typeof loadModule === 'function') loadModule('payments');
                } else {
                    showToast(res.message || "Failed to delete order", "error");
                    btn.prop('disabled', false).html('Delete Record');
                }
            },
            error: function() {
                showToast("Server error deleting order", "error");
                btn.prop('disabled', false).html('Delete Record');
            }
        });
    });

    // AJAX Dispatch Handling
    $('#dispatchPaymentForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Dispatching...');
        
        $.ajax({
            url: './adminView/dispatch_order.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#payDispatchModal').modal('hide');
                    showToast("Order dispatched and SMS sent", "success");
                    if(typeof loadModule === 'function') loadModule('payments');
                } else {
                    showToast(res.message || "Failed to dispatch order", "error");
                    btn.prop('disabled', false).html('Confirm Dispatch');
                }
            },
            error: function() {
                showToast("Server error dispatching order", "error");
                btn.prop('disabled', false).html('Confirm Dispatch');
            }
        });
    });
});
</script>
