<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Handle delete action via AJAX
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    header('Content-Type: application/json');
    $id = $_POST['id'];

    if (!is_numeric($id)) {
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    $deleteQuery = "DELETE FROM Customer WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo json_encode(['success' => 'Customer deleted successfully!']);
    } else {
        echo json_encode(['error' => 'Error deleting customer: ' . mysqli_error($conn)]);
    }
    exit;
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total
$totalRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Customer");
$totalRows = mysqli_fetch_assoc($totalRes)['total'];

// Fetch records
$query = "SELECT * FROM Customer ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
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

    .badge-status {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-verified { background: #f0fdf4; color: #16a34a; border: 1px solid #bcf0da; }
    .status-pending { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }

    .mobile-chip {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        font-size: 0.85rem;
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

    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

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
            <h4 class="mb-0 font-weight-bold">Mobile App Clients</h4>
            <span class="text-muted small">Managing <?= $totalRows ?> registered mobile users</span>
        </div>
        <button class="btn btn-danger btn-sm" id="btnDeleteSelectedMobileClients" style="display:none;" onclick="deleteSelectedMobileClients()">
            <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountMobileClients">0</span>)
        </button>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllMobileClients" style="cursor: pointer;">
                    </th>
                    <th class="text-center">#</th>
                    <th>Customer ID</th>
                    <th>Mobile Number</th>
                    <th class="text-center">Verification</th>
                    <th class="text-center">Registration Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $isVerified = ($row['is_verified'] == 1);
                        $statusClass = $isVerified ? 'status-verified' : 'status-pending';
                        $statusLabel = $isVerified ? 'Verified' : 'Pending';
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="mobileclient-checkbox" value="<?= $row['id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $count++ ?></td>
                            <td class="font-weight-bold">#<?= $row['id'] ?></td>
                            <td><span class="mobile-chip"><?= htmlspecialchars($row['Mobile_Number']) ?></span></td>
                            <td class="text-center">
                                <span class="badge-status <?= $statusClass ?>"><?= $statusLabel ?></span>
                            </td>
                            <td class="text-center text-muted small"><?= date('M d, Y H:i', strtotime($row['Date'])) ?></td>
                            <td class="text-center">
                                <button class="btn-action btn-delete" title="Delete Client" onclick="deleteCustomer(<?= $row['id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">No mobile clients found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'mobile-clients') ?>
    </div>
</div>

<script>
function deleteCustomer(id) {
    showConfirm("Delete Client?", "Are you sure you want to remove this mobile client registration?", function() {
        $.ajax({
            url: './adminView/listCustomers.php',
            method: 'POST',
            data: { action: 'delete', id: id },
            success: function(data) {
                if (data.success) {
                    showToast(data.success, "success");
                    loadModule('mobile-clients', <?= $page ?>);
                } else {
                    showToast(data.error || "Failed to delete", "danger");
                }
            },
            error: function() { showToast("Network error", "danger"); }
        });
    });
}

// --- Select All & Bulk Delete for Mobile Clients ---
function updateBulkDeleteMobileClientsBtn() {
    let selectedCount = $('.mobileclient-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountMobileClients').text(selectedCount);
        $('#btnDeleteSelectedMobileClients').fadeIn(200);
    } else {
        $('#btnDeleteSelectedMobileClients').fadeOut(200);
    }
    let totalCount = $('.mobileclient-checkbox').length;
    $('#selectAllMobileClients').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllMobileClients').on('change', function() {
    $('.mobileclient-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteMobileClientsBtn();
});

$(document).on('change', '.mobileclient-checkbox', function() {
    updateBulkDeleteMobileClientsBtn();
});

function deleteSelectedMobileClients() {
    let selectedIds = [];
    $('.mobileclient-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Clients?",
        `Are you sure you want to permanently delete ${selectedIds.length} selected mobile clients?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedMobileClients').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './adminView/listCustomers.php',
                    method: 'POST',
                    data: { action: 'delete', id: id },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} clients!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('mobile-clients', page);
            });
        }
    );
}
</script>
