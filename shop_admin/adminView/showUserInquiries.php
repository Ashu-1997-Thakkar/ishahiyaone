<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$totalSql = "SELECT COUNT(*) AS total FROM inquiries";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_assoc($totalResult)['total'];

// Fetch inquiries
$sql = "SELECT * FROM inquiries ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
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

    .user-avatar {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.75rem;
        margin-right: 10px;
    }

    .inquiry-text {
        max-width: 350px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        color: #475569;
        line-height: 1.4;
    }

    .phone-badge {
        background: #f8fafc;
        color: #475569;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.8rem;
    }

    .btn-verify { background: #f0fdf4; color: #16a34a; }
    .btn-verify:hover { background: #16a34a; color: white; }

    .btn-view { background: #f0f9ff; color: #0369a1; }
    .btn-view:hover { background: #0369a1; color: white; }

    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

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

    .view-header {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .date-text {
        font-size: 0.75rem;
        color: #94a3b8;
    }
</style>

<div class="container-fluid py-3">
    <div class="view-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 font-weight-bold">User Inquiries</h4>
            <span class="text-muted small">Managing customer inquiries (Verified & Pending)</span>
        </div>
        <button class="btn btn-danger btn-sm" id="btnDeleteSelectedInquiries" style="display:none;" onclick="deleteSelectedInquiries()">
            <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountInquiries">0</span>)
        </button>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllInquiries" style="cursor: pointer;">
                    </th>
                    <th class="text-center">ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Inquiry</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $initial = strtoupper(substr($row['name'], 0, 1));
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="inquiry-checkbox" value="<?= $row['id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $row['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar"><?= $initial ?></div>
                                    <span class="font-weight-600"><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="small font-weight-500"><?= htmlspecialchars($row['email']) ?></span>
                            </td>
                            <td>
                                <span class="phone-badge"><?= htmlspecialchars($row['phone']) ?></span>
                            </td>
                            <td>
                                <div class="inquiry-text" title="<?= htmlspecialchars($row['message']) ?>">
                                    <?= htmlspecialchars($row['message']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($row['is_verified'] == 1): ?>
                                    <span class="badge badge-success" style="background: #f0fdf4; color: #16a34a; border-radius: 10px; padding: 4px 10px; font-size: 0.7rem;">Verified</span>
                                <?php else: ?>
                                    <span class="badge badge-warning" style="background: #fffbeb; color: #d97706; border-radius: 10px; padding: 4px 10px; font-size: 0.7rem;">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="date-text">
                                    <?= date('Y-m-d', strtotime($row['created_at'])) ?><br>
                                    <small class="opacity-75"><?= date('H:i:s', strtotime($row['created_at'])) ?></small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <button class="btn-action btn-view" title="View Details" 
                                            onclick="viewInquiryDetail('<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars($row['message']) ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($row['is_verified'] == 0): ?>
                                        <button class="btn-action btn-verify" title="Verify Manually"
                                                onclick="handleInquiryAction('./controller/manageInquiry.php?id=<?=$row['id']?>&action=verify', 'Verify Inquiry?', 'Manually mark this inquiry as verified?', 'Verify', false)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>

                                    <button class="btn-action btn-delete" title="Delete Inquiry"
                                            onclick="handleInquiryAction('./controller/manageInquiry.php?id=<?=$row['id']?>&action=delete', 'Delete Inquiry?', 'Are you sure you want to remove this inquiry?', 'Delete', true)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">No verified inquiries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'inquiries') ?>
    </div>
</div>

<!-- View Inquiry Modal -->
<div class="modal fade" id="viewInquiryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-envelope-open-text mr-2" style="color: #c59d2f;"></i>Inquiry Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <h6 class="font-weight-bold text-muted mb-1">From:</h6>
                <p id="detailName" class="h5 mb-4"></p>
                
                <h6 class="font-weight-bold text-muted mb-1">Message:</h6>
                <div class="p-3 bg-light rounded" id="detailMessage" style="white-space: pre-wrap; line-height: 1.6; color: #334155;"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewInquiryDetail(name, message) {
    document.getElementById('detailName').innerText = name;
    document.getElementById('detailMessage').innerText = message;
    $('#viewInquiryModal').modal('show');
}

function handleInquiryAction(url, title, message, btnText = 'Confirm', isDangerous = true) {
    showConfirm(title, message, function() {
        $.ajax({
            url: url + '&ajax=1',
            method: 'GET',
            success: function(res) {
                // If it's returning JSON directly or text
                try {
                    let data = typeof res === 'string' ? JSON.parse(res) : res;
                    if(data.success) {
                        showToast(title + " successful", "success");
                        const params = new URLSearchParams(window.location.hash.split('?')[1]);
                        const page = params.get('page') || 1;
                        loadModule('inquiries', page);
                    } else {
                        showToast(data.error || "Action failed", "danger");
                    }
                } catch(e) {
                    showToast("Action successful", "success");
                    const params = new URLSearchParams(window.location.hash.split('?')[1]);
                    const page = params.get('page') || 1;
                    loadModule('inquiries', page);
                }
            },
            error: function() { showToast("Network error", "danger"); }
        });
    }, btnText, isDangerous);
}

// --- Select All & Bulk Delete for Inquiries ---
function updateBulkDeleteInquiriesBtn() {
    let selectedCount = $('.inquiry-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountInquiries').text(selectedCount);
        $('#btnDeleteSelectedInquiries').fadeIn(200);
    } else {
        $('#btnDeleteSelectedInquiries').fadeOut(200);
    }
    let totalCount = $('.inquiry-checkbox').length;
    $('#selectAllInquiries').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllInquiries').on('change', function() {
    $('.inquiry-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteInquiriesBtn();
});

$(document).on('change', '.inquiry-checkbox', function() {
    updateBulkDeleteInquiriesBtn();
});

function deleteSelectedInquiries() {
    let selectedIds = [];
    $('.inquiry-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Inquiries?",
        `Are you sure you want to permanently delete ${selectedIds.length} selected inquiries?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedInquiries').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/manageInquiry.php',
                    method: 'POST',
                    data: { action: 'delete', id: id, ajax: 1 },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} inquiries!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('inquiries', page);
            });
        }
    );
}
</script>
