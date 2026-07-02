<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$totalSql = "SELECT COUNT(*) AS total FROM messages";
$totalResult = mysqli_query($conn, $totalSql);
$totalRows = mysqli_fetch_assoc($totalResult)['total'];

// Fetch messages
$sql = "SELECT * FROM messages ORDER BY submitted_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
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

    .msg-preview {
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        color: #475569;
        line-height: 1.4;
    }

    .view-header {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .date-badge {
        font-size: 0.7rem;
        color: #94a3b8;
        background: #f8fafc;
        padding: 2px 8px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
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
</style>

<div class="container-fluid py-3">
    <div class="view-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 font-weight-bold">Customer Inquiries</h4>
            <span class="text-muted small">Viewing and managing messages from website users</span>
        </div>
        <button class="btn btn-danger btn-sm" id="btnDeleteSelectedMessages" style="display:none;" onclick="deleteSelectedMessages()">
            <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountMessages">0</span>)
        </button>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllMessages" style="cursor: pointer;">
                    </th>
                    <th class="text-center">#</th>
                    <th>From Customer</th>
                    <th>Contact Info</th>
                    <th>Message Content</th>
                    <th class="text-center">Received Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $initial = strtoupper(substr($row['name'], 0, 1));
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="message-checkbox" value="<?= $row['id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $count++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar"><?= $initial ?></div>
                                    <span class="font-weight-600"><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="small font-weight-500"><?= htmlspecialchars($row['email']) ?></div>
                            </td>
                            <td>
                                <div class="msg-preview" title="<?= htmlspecialchars($row['message']) ?>">
                                    <?= htmlspecialchars($row['message']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="date-badge">
                                    <?= date('M d, Y', strtotime($row['submitted_at'])) ?>
                                    <span class="ml-1 opacity-50"><?= date('H:i', strtotime($row['submitted_at'])) ?></span>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action btn-delete" title="Delete Message" onclick="deleteMessage(<?= $row['id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">No customer messages found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'messages') ?>
    </div>
</div>

<script>
function deleteMessage(id) {
    showConfirm(
        "Delete Message?",
        "Are you sure you want to permanently delete this customer inquiry?",
        function() {
            $.post("./controller/deleteMessage.php", { id: id }, function(res) {
                if(res.success) {
                    showToast(res.message, 'success');
                    const params = new URLSearchParams(window.location.hash.split('?')[1]);
                    const page = params.get('page') || 1;
                    loadModule('messages', page);
                } else {
                    showToast(res.message || "Failed to delete", 'danger');
                }
            });
        }
    );
}

// --- Select All & Bulk Delete for Messages ---
function updateBulkDeleteMessagesBtn() {
    let selectedCount = $('.message-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountMessages').text(selectedCount);
        $('#btnDeleteSelectedMessages').fadeIn(200);
    } else {
        $('#btnDeleteSelectedMessages').fadeOut(200);
    }
    let totalCount = $('.message-checkbox').length;
    $('#selectAllMessages').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllMessages').on('change', function() {
    $('.message-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteMessagesBtn();
});

$(document).on('change', '.message-checkbox', function() {
    updateBulkDeleteMessagesBtn();
});

function deleteSelectedMessages() {
    let selectedIds = [];
    $('.message-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Messages?",
        `Are you sure you want to permanently delete ${selectedIds.length} selected inquiries?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedMessages').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/deleteMessage.php',
                    method: 'POST',
                    data: { id: id },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} messages!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('messages', page);
            });
        }
    );
}
</script>
