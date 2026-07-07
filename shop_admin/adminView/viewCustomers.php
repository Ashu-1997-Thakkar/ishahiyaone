<?php
session_start();
include_once dirname(__DIR__) . "/config/dbconnect.php";
include_once dirname(__DIR__) . "/config/pagination_helper.php";

// ✅ Role-based restriction
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin', 'superadmin'])) {
    echo "<div class='alert alert-danger'>Access Denied</div>";
    exit;
}

// ✅ CSRF token generate
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ✅ Search functionality
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// ✅ Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM user WHERE role != 'admin' 
          AND (name LIKE '%$search%' OR email LIKE '%$search%')
          ORDER BY created_at DESC 
          LIMIT $limit OFFSET $offset";

$result = $conn->query($query);
$count = $offset + 1;

// Get total for pagination
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM user WHERE role != 'admin' AND (name LIKE '%$search%' OR email LIKE '%$search%')");
$totalRows = $totalRes->fetch_assoc()['total'];
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

    .badge-role {
        background: #f8fafc;
        color: #64748b;
        padding: 4px 10px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-status {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 10px;
        font-weight: 600;
    }
    .status-active { background: #f0fdf4; color: #16a34a; }
    .status-banned { background: #fef2f2; color: #dc2626; }

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
    
    .btn-ban { background: #fff7ed; color: #ea580c; }
    .btn-ban:hover { background: #ea580c; color: white; }

    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    .view-header {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .search-box {
        position: relative;
        max-width: 300px;
    }
    .search-box input {
        padding-left: 35px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        font-size: 0.85rem;
    }
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
</style>

<div class="container-fluid py-3">
    <div class="view-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 font-weight-bold">Registered Customers</h4>
            <span class="text-muted small">Managing <?= $totalRows ?> active user accounts</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 15px;">
            <button class="btn btn-danger btn-sm" id="btnDeleteSelectedCustomers" style="display:none;" onclick="deleteSelectedCustomers()">
                <i class="fas fa-trash-alt mr-1"></i> Delete (<span id="selectedCountCustomers">0</span>)
            </button>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Search name or email..." 
                       id="userSearchInput" value="<?= htmlspecialchars($search) ?>"
                       onkeyup="if(event.key === 'Enter') performSearch()">
            </div>
        </div>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">
                        <input type="checkbox" id="selectAllCustomers" style="cursor: pointer;">
                    </th>
                    <th class="text-center">#</th>
                    <th>User</th>
                    <th>Email Address</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Joined Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $statusClass = ($row['status'] == 'active') ? 'status-active' : 'status-banned';
                        $initial = strtoupper(substr($row['name'], 0, 1));
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="customer-checkbox" value="<?= $row['id'] ?>" style="cursor: pointer;">
                            </td>
                            <td class="text-center text-muted small"><?= $count++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar"><?= $initial ?></div>
                                    <span class="font-weight-600"><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?php 
                                        $email = $row["email"];
                                        $masked = preg_replace('/(?<=.{3}).(?=.*@)/', '*', $email);
                                        echo htmlspecialchars($masked);
                                    ?>
                                </span>
                            </td>
                            <td class="text-center"><span class="badge-role"><?= htmlspecialchars($row['role']) ?></span></td>
                            <td class="text-center small text-muted"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td class="text-center">
                                <span class="badge-status <?= $statusClass ?>"><?= ucfirst($row['status']) ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action btn-edit" title="Edit User" 
                                        data-id="<?=$row['id']?>"
                                        data-name="<?=htmlspecialchars($row['name'])?>"
                                        data-email="<?=htmlspecialchars($row['email'])?>"
                                        data-role="<?=$row['role']?>"
                                        onclick="openEditModal(this)">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn-action btn-ban" title="<?= ($row['status'] == 'active') ? 'Ban User' : 'Unban User' ?>"
                                        onclick="handleUserAction('./controller/banUser.php?id=<?=$row['id']?>&csrf=<?=$_SESSION['csrf_token']?>', '<?= ($row['status'] == 'active') ? 'Ban' : 'Unban' ?> User?', 'Confirm changing status for this user?', '<?= ($row['status'] == 'active') ? 'Ban' : 'Unban' ?>', <?= ($row['status'] == 'active') ? 'true' : 'false' ?>)">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Delete User"
                                        onclick="handleUserAction('./controller/deleteUser.php?id=<?=$row['id']?>&csrf=<?=$_SESSION['csrf_token']?>', 'Delete User?', 'This action cannot be undone. Permanent delete?', 'Delete', true)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">No customers found matching your search.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing page <?= $page ?></div>
        <?= renderPagination($totalRows, $limit, $page, 'customers', $search) ?>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="./controller/updateUser.php" method="POST" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit mr-2" style="color: #c59d2f;"></i>Edit User Account</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf_token']?>">

                <div class="form-group mb-3">
                    <label for="edit_name" class="small font-weight-bold">Full Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" autocomplete="name" required>
                </div>

                <div class="form-group mb-3">
                    <label for="edit_email" class="small font-weight-bold">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="form-control" autocomplete="email" required>
                </div>

                <div class="form-group mb-0">
                    <label for="edit_role" class="small font-weight-bold">System Role</label>
                    <select name="role" id="edit_role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function performSearch() {
    const val = document.getElementById('userSearchInput').value;
    loadModule('customers', 1, val);
}

function openEditModal(button) {
    document.getElementById('edit_id').value = button.getAttribute('data-id');
    document.getElementById('edit_name').value = button.getAttribute('data-name');
    document.getElementById('edit_email').value = button.getAttribute('data-email');
    document.getElementById('edit_role').value = button.getAttribute('data-role');
    $('#editUserModal').modal('show');
}

function handleUserAction(url, title, message, btnText = 'Confirm', isDangerous = true) {
    showConfirm(title, message, function() {
        window.location.href = url;
    }, btnText, isDangerous);
}

// --- Select All & Bulk Delete for Customers ---
function updateBulkDeleteCustomersBtn() {
    let selectedCount = $('.customer-checkbox:checked').length;
    if (selectedCount > 0) {
        $('#selectedCountCustomers').text(selectedCount);
        $('#btnDeleteSelectedCustomers').fadeIn(200);
    } else {
        $('#btnDeleteSelectedCustomers').fadeOut(200);
    }
    let totalCount = $('.customer-checkbox').length;
    $('#selectAllCustomers').prop('checked', totalCount > 0 && selectedCount === totalCount);
}

$('#selectAllCustomers').on('change', function() {
    $('.customer-checkbox').prop('checked', $(this).prop('checked'));
    updateBulkDeleteCustomersBtn();
});

$(document).on('change', '.customer-checkbox', function() {
    updateBulkDeleteCustomersBtn();
});

function deleteSelectedCustomers() {
    let selectedIds = [];
    $('.customer-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length === 0) return;

    showConfirm(
        "Delete Multiple Customers?",
        `Are you sure you want to permanently delete ${selectedIds.length} selected users?`,
        function() {
            let errors = 0;
            $('#btnDeleteSelectedCustomers').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');
            const csrf = "<?=$_SESSION['csrf_token']?>";

            let requests = selectedIds.map(id =>
                $.ajax({
                    url: './controller/deleteUser.php',
                    method: 'GET',
                    data: { id: id, csrf: csrf, ajax: 1 },
                    error: function() { errors++; }
                })
            );

            $.when.apply($, requests).always(function() {
                if (errors === 0) {
                    showToast(`Successfully deleted ${selectedIds.length} users!`, 'success');
                } else {
                    showToast(`Deleted with some errors.`, 'warning');
                }
                const val = document.getElementById('userSearchInput').value;
                const params = new URLSearchParams(window.location.hash.split('?')[1]);
                const page = params.get('page') || 1;
                loadModule('customers', page, val);
            });
        },
        "Delete", true
    );
}
</script>
