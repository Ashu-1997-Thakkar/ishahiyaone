<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once dirname(__DIR__) . "/config/dbconnect.php";

/** @var mysqli $conn */

// Verify admin session and Super Admin role
if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['super_admin', 'superadmin'])) {
    echo "<div class='alert alert-danger m-3'>Access Denied: Super Administrative privileges required.</div>";
    exit;
}

// Ensure status column exists in admin table
$colCheck = $conn->query("SHOW COLUMNS FROM admin LIKE 'status'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE admin ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'approved'");
}

$query = "SELECT id, full_name, username, email, role, COALESCE(status, 'approved') as status FROM admin ORDER BY id DESC";
$result = $conn->query($query);

$totalAdmins = 0;
$approvedCount = 0;
$pendingCount = 0;
$revokedCount = 0;

$admins = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
        $totalAdmins++;
        $st = strtolower(trim($row['status']));
        if ($st === 'approved') {
            $approvedCount++;
        } elseif ($st === 'pending') {
            $pendingCount++;
        } elseif ($st === 'revoked' || $st === 'inactive' || $st === 'disabled') {
            $revokedCount++;
        } else {
            $approvedCount++;
        }
    }
}
?>

<style>
    .compressed-table td,
    .compressed-table th {
        padding: 12px 14px !important;
        font-size: 0.88rem;
        vertical-align: middle !important;
        border-color: #f1f5f9 !important;
    }

    .custom-table thead {
        background-color: #0f172a;
        color: #d4af37;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 2px solid #d4af37;
    }

    .admin-avatar {
        width: 36px;
        height: 36px;
        background: #0f172a;
        color: #d4af37;
        border: 1px solid #d4af37;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
        margin-right: 12px;
    }

    .badge-role-super {
        background: #fef9c3;
        color: #854d0e;
        padding: 5px 12px;
        border-radius: 12px;
        border: 1px solid #fde047;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-role-admin {
        background: #f1f5f9;
        color: #334155;
        padding: 5px 12px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-rbac {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .rbac-approved { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .rbac-pending { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
    .rbac-revoked { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .btn-action-rbac {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        cursor: pointer;
    }
    .btn-approve { background: #15803d; color: #fff; }
    .btn-approve:hover { background: #166534; color: #fff; box-shadow: 0 4px 10px rgba(21, 128, 61, 0.3); }
    
    .btn-revoke { background: #ea580c; color: #fff; }
    .btn-revoke:hover { background: #c2410c; color: #fff; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.3); }

    .btn-del-admin { background: #fee2e2; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; }
    .btn-del-admin:hover { background: #dc2626; color: #fff; }

    .stats-card-rbac {
        background: #fff;
        border-radius: 12px;
        padding: 18px 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-left: 4px solid #d4af37;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .view-header-rbac {
        background: #0f172a;
        color: #fff;
        padding: 24px 28px;
        border-radius: 14px;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        border: 1px solid #d4af37;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="container-fluid py-3">
    <!-- Header Banner -->
    <div class="view-header-rbac">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #d4af37;"><i class="fa fa-user-shield mr-2"></i>RBAC & Admin Permission Manager</h4>
            <p class="mb-0 text-light small" style="opacity: 0.85;">Strict Role-Based Access Control: Only approved administrators can access dashboard data and end-user statistics.</p>
        </div>
        <div>
            <button class="btn btn-warning font-weight-bold shadow-sm px-4 py-2" style="border-radius: 30px; background: #d4af37; border: none; color: #000;" onclick="$('#addAdminModal').modal('show')">
                <i class="fa fa-user-plus mr-2"></i> Add New Admin
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="stats-card-rbac" style="border-left-color: #0f172a;">
                <div>
                    <h6 class="text-muted mb-1 small font-weight-bold">TOTAL ADMINS</h6>
                    <h3 class="mb-0 font-weight-bold text-dark"><?= $totalAdmins ?></h3>
                </div>
                <i class="fa fa-users fa-2x text-muted" style="opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card-rbac" style="border-left-color: #16a34a;">
                <div>
                    <h6 class="text-muted mb-1 small font-weight-bold">APPROVED ACCESS</h6>
                    <h3 class="mb-0 font-weight-bold" style="color: #16a34a;"><?= $approvedCount ?></h3>
                </div>
                <i class="fa fa-check-circle fa-2x" style="color: #16a34a; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card-rbac" style="border-left-color: #eab308;">
                <div>
                    <h6 class="text-muted mb-1 small font-weight-bold">PENDING APPROVAL</h6>
                    <h3 class="mb-0 font-weight-bold" style="color: #ca8a04;"><?= $pendingCount ?></h3>
                </div>
                <i class="fa fa-clock fa-2x" style="color: #ca8a04; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card-rbac" style="border-left-color: #dc2626;">
                <div>
                    <h6 class="text-muted mb-1 small font-weight-bold">REVOKED ACCESS</h6>
                    <h3 class="mb-0 font-weight-bold" style="color: #dc2626;"><?= $revokedCount ?></h3>
                </div>
                <i class="fa fa-ban fa-2x" style="color: #dc2626; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="table-responsive bg-white rounded shadow-sm border">
        <table class="table custom-table compressed-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 60px;"># ID</th>
                    <th>Administrator</th>
                    <th>Username / Email</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">RBAC Permission Status</th>
                    <th class="text-center" style="width: 250px;">Access Controls & Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $row): 
                        $st = strtolower(trim($row['status']));
                        $statusClass = 'rbac-approved';
                        $statusText = 'Approved';
                        $statusIcon = 'fa-check-circle';

                        if ($st === 'pending') {
                            $statusClass = 'rbac-pending';
                            $statusText = 'Pending Approval';
                            $statusIcon = 'fa-clock';
                        } elseif ($st === 'revoked' || $st === 'inactive' || $st === 'disabled') {
                            $statusClass = 'rbac-revoked';
                            $statusText = 'Access Revoked';
                            $statusIcon = 'fa-ban';
                        }

                        $roleClass = ($row['role'] === 'super_admin' || $row['role'] === 'superadmin') ? 'badge-role-super' : 'badge-role-admin';
                        $initial = strtoupper(substr($row['full_name'] ?: $row['username'], 0, 1));
                        $isSelf = (intval($row['id']) === intval($_SESSION['user_id'] ?? 0));
                    ?>
                        <tr>
                            <td class="text-center font-weight-bold text-muted"><?= $row['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="admin-avatar"><?= $initial ?></div>
                                    <div>
                                        <span class="font-weight-600 text-dark d-block"><?= htmlspecialchars($row['full_name'] ?: 'N/A') ?></span>
                                        <?php if ($isSelf): ?>
                                            <span class="badge badge-info small" style="font-size: 10px;">You (Current Session)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark font-weight-500 d-block"><?= htmlspecialchars($row['email']) ?></span>
                                <span class="text-muted small">@<?= htmlspecialchars($row['username']) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="<?= $roleClass ?>"><?= str_replace('_', ' ', htmlspecialchars($row['role'])) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge-rbac <?= $statusClass ?>">
                                    <i class="fa <?= $statusIcon ?> mr-1"></i> <?= $statusText ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                                    <?php if ($st === 'pending' || $st === 'revoked'): ?>
                                        <button class="btn-action-rbac btn-approve shadow-sm" onclick="updateAdminRbacStatus(<?= $row['id'] ?>, 'approved', '<?= htmlspecialchars(addslashes($row['full_name'] ?: $row['username'])) ?>')" title="Grant Permission">
                                            <i class="fa fa-check"></i> Grant Access
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($st === 'approved' && !$isSelf): ?>
                                        <button class="btn-action-rbac btn-revoke shadow-sm" onclick="updateAdminRbacStatus(<?= $row['id'] ?>, 'revoked', '<?= htmlspecialchars(addslashes($row['full_name'] ?: $row['username'])) ?>')" title="Revoke Permission">
                                            <i class="fa fa-ban"></i> Revoke Access
                                        </button>
                                    <?php endif; ?>

                                    <?php if (!$isSelf && intval($row['id']) !== 1): ?>
                                        <button class="btn-del-admin shadow-sm" onclick="deleteAdminAccount(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['full_name'] ?: $row['username'])) ?>')" title="Delete Admin Account">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">No administrative accounts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add New Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="addAdminForm" class="modal-content border-0 shadow-lg" onsubmit="submitNewAdminForm(event)">
            <div class="modal-header bg-dark text-white" style="border-bottom: 2px solid #d4af37;">
                <h5 class="modal-title font-weight-bold" style="color: #d4af37;"><i class="fa fa-user-shield mr-2"></i>Create New Admin Account</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" placeholder="e.g. Rahul Sharma" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@ishahiyaone.shop" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Username (Optional)</label>
                    <input type="text" name="username" class="form-control" placeholder="Leave blank to use email" style="border-radius: 8px;">
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter secure password" required minlength="6" style="border-radius: 8px;">
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-0">
                        <label class="small font-weight-bold text-dark">System Role</label>
                        <select name="role" class="form-control" style="border-radius: 8px;">
                            <option value="admin" selected>Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-0">
                        <label class="small font-weight-bold text-dark">Initial Permission</label>
                        <select name="status" class="form-control" style="border-radius: 8px;">
                            <option value="approved" selected>Approved (Active)</option>
                            <option value="pending">Pending Approval</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                <button type="submit" class="btn btn-warning btn-sm px-4 font-weight-bold" style="border-radius: 20px; background: #0f172a; color: #d4af37; border: 1px solid #d4af37;">
                    <i class="fa fa-check mr-1"></i> Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateAdminRbacStatus(adminId, newStatus, adminName) {
    const actionText = newStatus === 'approved' ? 'Grant Access & Approve' : 'Revoke Access for';
    const confirmMsg = newStatus === 'approved' 
        ? `Are you sure you want to grant full administrative permission to "${adminName}"? They will be able to access all admin dashboard data.`
        : `Are you sure you want to revoke access for "${adminName}"? They will be immediately blocked from logging into the admin panel.`;

    if (!confirm(confirmMsg)) return;

    $.ajax({
        url: './controller/adminController.php',
        method: 'POST',
        data: { action: 'update_status', id: adminId, status: newStatus },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('✅ ' + response.message);
                loadModule('manage-admins');
            } else {
                alert('❌ Error: ' + response.message);
            }
        },
        error: function() {
            alert('❌ Failed to update admin permission. Please try again.');
        }
    });
}

function deleteAdminAccount(adminId, adminName) {
    if (!confirm(`⚠️ PERMANENT DELETE:\nAre you sure you want to permanently delete admin account "${adminName}"? This action cannot be undone.`)) return;

    $.ajax({
        url: './controller/adminController.php',
        method: 'POST',
        data: { action: 'delete', id: adminId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('✅ ' + response.message);
                loadModule('manage-admins');
            } else {
                alert('❌ Error: ' + response.message);
            }
        },
        error: function() {
            alert('❌ Failed to delete admin account.');
        }
    });
}

function submitNewAdminForm(event) {
    event.preventDefault();
    const form = $('#addAdminForm');
    const formData = form.serialize() + '&action=add';

    $.ajax({
        url: './controller/adminController.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('✅ ' + response.message);
                $('#addAdminModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                loadModule('manage-admins');
            } else {
                alert('❌ Error: ' + response.message);
            }
        },
        error: function() {
            alert('❌ Failed to create admin account.');
        }
    });
}
</script>
