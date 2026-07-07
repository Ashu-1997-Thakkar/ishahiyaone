<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
   include_once "./config/dbconnect.php";
/** @var mysqli $conn */
?>

<style>
/* ================= BULLETPROOF ADMIN DROPDOWN POSITIONING & VISIBILITY ================= */
.top-navbar, 
.top-navbar .container-fluid, 
.top-navbar .ml-auto, 
.top-navbar .dropdown {
    overflow: visible !important;
}

.top-navbar .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    min-width: 220px !important;
    max-width: calc(100vw - 20px) !important;
    width: auto !important;
    z-index: 99999 !important;
    overflow: visible !important;
    word-wrap: break-word !important;
    margin-top: 8px !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
}

.top-navbar .dropdown-item {
    white-space: normal !important;
    word-wrap: break-word !important;
    overflow: visible !important;
    text-overflow: clip !important;
    display: flex !important;
    align-items: center !important;
    padding: 10px 15px !important;
    font-size: 14px !important;
    color: #333 !important;
}

.top-navbar .dropdown-item:hover {
    background-color: rgba(212, 175, 55, 0.1) !important;
    color: #000 !important;
}

.top-navbar .dropdown-item.text-danger:hover {
    background-color: rgba(231, 74, 59, 0.1) !important;
    color: #e74a3b !important;
}
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg top-navbar" style="overflow: visible !important;">
    <div class="container-fluid d-flex align-items-center justify-content-between" style="overflow: visible !important;">
        <div class="d-flex align-items-center" style="max-width: 65%; overflow: hidden;">
            <button class="btn btn-dark d-lg-none mr-2 shadow-sm flex-shrink-0" type="button" id="mobileSidebarToggle" onclick="toggleSidebar()" style="border-radius: 8px; padding: 6px 12px; background: #0f172a; border: 1px solid #d4af37; color: #d4af37;">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand d-flex align-items-center text-truncate" href="./index.php" style="overflow: hidden;">
                <img src="../image/logo/ishahiya-logo.png" width="40" height="40" alt="Ishahiya" class="mr-2 flex-shrink-0" style="object-fit:contain; border-radius:8px;">
                <span class="font-weight-bold text-dark h5 mb-0 text-truncate" style="font-size: 1rem;">Ishahiya Admin</span>
            </a>
        </div>

        <div class="ml-auto d-flex align-items-center flex-shrink-0" style="overflow: visible !important;">  
            <?php if(isset($_SESSION['user_id'])) { ?>
                <div class="dropdown" style="position: relative !important; overflow: visible !important;">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center shadow-sm" 
                            type="button" 
                            id="userMenu" 
                            data-toggle="dropdown" 
                            data-display="static"
                            aria-haspopup="true" 
                            aria-expanded="false" 
                            style="border-radius: 30px; padding: 5px 15px;">
                        <i class="fa fa-user-circle mr-2" style="font-size:22px; color:var(--primary-color);"></i>
                        <span class="font-weight-600 text-dark">Admin</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2" 
                         aria-labelledby="userMenu" 
                         style="position: absolute !important; top: 100% !important; right: 0 !important; left: auto !important; transform: none !important; z-index: 99999 !important; min-width: 220px !important; max-width: calc(100vw - 20px) !important; border-radius: 12px; overflow: visible !important;">
                        <a class="dropdown-item py-2" href="./settings.php"><i class="fa fa-cog mr-2"></i> Settings</a>
                        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'superadmin'])): ?>
                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="loadModule('manage-admins')"><i class="fa fa-user-shield mr-2"></i> Manage Admins</a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger" href="./logout.php"><i class="fa fa-sign-out-alt mr-2"></i> Logout</a>
                    </div>
                </div>
            <?php } else { ?>
                <a href="./log.php" class="btn btn-primary shadow-sm" style="border-radius: 30px; padding: 8px 20px;">
                    <i class="fa fa-sign-in-alt mr-2"></i> Login
                </a>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Required JS for dropdown -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
