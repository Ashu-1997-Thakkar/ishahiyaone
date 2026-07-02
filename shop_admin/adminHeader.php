<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
   include_once "./config/dbconnect.php";
/** @var mysqli $conn */
?>
       
<!-- Navbar -->
<nav class="navbar navbar-expand-lg top-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="./index.php">
            <img src="../image/logo/ishahiya-logo.png" width="50" height="50" alt="Ishahiya" class="mr-2" style="object-fit:contain; border-radius:8px;">
            <span class="font-weight-bold text-dark h5 mb-0">Ishahiya Admin</span>
        </a>

        <div class="ml-auto d-flex align-items-center">  
            <?php if(isset($_SESSION['user_id'])) { ?>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center shadow-sm" 
                            type="button" 
                            id="userMenu" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false" 
                            style="border-radius: 30px; padding: 5px 15px;">
                        <i class="fa fa-user-circle mr-2" style="font-size:24px; color:var(--primary-color);"></i>
                        <span class="font-weight-600 text-dark">Admin</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2" aria-labelledby="userMenu" style="border-radius: 12px;">
                        <a class="dropdown-item py-2" href="./settings.php"><i class="fa fa-cog mr-2"></i> Settings</a>
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

