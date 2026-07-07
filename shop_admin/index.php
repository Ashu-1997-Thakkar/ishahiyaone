 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard | Ishahiya</title>
   <link rel="icon" type="image/png" sizes="32x32" href="../image/logo/ishahiya-logo.png">
   <link rel="icon" type="image/png" sizes="16x16" href="../image/logo/ishahiya-logo.png">
   <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">

   <!-- Google Fonts: Inter -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
   <!-- Bootstrap 4 -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <!-- SweetAlert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- Modern Admin CSS -->
   <link rel="stylesheet" href="./assets/css/admin-modern.css">

   <style>
     /* Small fixes for specific dynamic elements */
     #main-content {
       transition: all 0.3s ease;
     }

     .sidebar-collapsed #main-content {
       margin-left: 0;
     }

     .sidebar-collapsed .sidebar {
       transform: translateX(-100%);
     }

     .navbar-brand img {
       border-radius: 8px;
     }
   </style>
 </head>

 <body>

   <?php
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // ✅ Redirect if not admin
    if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin', 'superadmin'])) {
      header("Location: log.php");
      exit();
    }

    // ✅ Database
    include_once __DIR__ . '/../shop_admin/config/dbconnect.php';
    include "./adminHeader.php";
    include "./sidebar.php";
    ?>

   <div id="main-content">
     <div id="dashboardContent" style="display: none">
       <div class="d-flex justify-content-between align-items-center mb-4">
         <h2 class="font-weight-bold" style="border:none; margin:0; padding:0;">📊 Dashboard Overview</h2>
         <span class="badge badge-info p-2">Last Updated: <?= date('H:i') ?></span>
       </div>

       <div class="row">
         <!-- Total Users -->
         <div class="col-xl-3 col-md-6 mb-4">
           <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
             <div class="icon-box"><i class="fas fa-users"></i></div>
             <div>
               <h4>Total Customers</h4>
               <h2>
                 <?php
                  $sql = "SELECT * FROM user WHERE role != 'admin'";
                  $result = $conn->query($sql);
                  echo ($result->num_rows > 0) ? $result->num_rows : 0;
                  ?>
               </h2>
             </div>
             <small><i class="fas fa-arrow-up"></i> Registered Accounts</small>
           </div>
         </div>

         <!-- Total Categories -->
         <div class="col-xl-3 col-md-6 mb-4">
           <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
             <div class="icon-box"><i class="fas fa-th-large"></i></div>
             <div>
               <h4>Categories</h4>
               <h2>
                 <?php
                  $sql = "SELECT * FROM category";
                  $result = $conn->query($sql);
                  echo ($result->num_rows > 0) ? $result->num_rows : 0;
                  ?>
               </h2>
             </div>
             <small><i class="fas fa-tags"></i> Product Groups</small>
           </div>
         </div>

         <!-- Total Products -->
         <div class="col-xl-3 col-md-6 mb-4">
           <div class="stat-card" style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);">
             <div class="icon-box"><i class="fas fa-box"></i></div>
             <div>
               <h4>Total Products</h4>
               <h2>
                 <?php
                  $sql = "SELECT * FROM products";
                  $result = $conn->query($sql);
                  echo ($result->num_rows > 0) ? $result->num_rows : 0;
                  ?>
               </h2>
             </div>
             <small><i class="fas fa-inventory"></i> Active Inventory</small>
           </div>
         </div>

         <!-- Total Orders -->
         <div class="col-xl-3 col-md-6 mb-4">
           <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
             <div class="icon-box"><i class="fas fa-shopping-cart"></i></div>
             <div>
               <h4>Total Orders</h4>
               <h2>
                 <?php
                  $sql = "SELECT * FROM orders";
                  $result = $conn->query($sql);
                  echo ($result->num_rows > 0) ? $result->num_rows : 0;
                  ?>
               </h2>
             </div>
             <small><i class="fas fa-truck"></i> Orders Placed</small>
           </div>
         </div>
       </div>

       <!-- Charts Section -->
       <div class="row">
         <div class="col-lg-6">
           <div class="chart-card">
             <h5><i class="fas fa-chart-bar text-primary"></i> Engagement Metrics</h5>
             <canvas id="userOrderChart"></canvas>
           </div>
         </div>
         <div class="col-lg-6">
           <div class="chart-card">
             <h5><i class="fas fa-chart-pie text-success"></i> Inventory Split</h5>
             <canvas id="categoryChart"></canvas>
           </div>
         </div>
       </div>

       <div class="row mt-2">
         <div class="col-lg-8">
           <div class="chart-card">
             <h5><i class="fas fa-chart-line text-info"></i> Sales Velocity</h5>
             <canvas id="ordersTrendChart"></canvas>
           </div>
         </div>
         <div class="col-lg-4">
           <div class="chart-card">
             <h5><i class="fas fa-tasks text-warning"></i> Status Hub</h5>
             <canvas id="orderStatusChart"></canvas>
           </div>
         </div>
       </div>
     </div>
     <div class="allContent-section"></div>
   </div>



   <!-- Scripts -->
   <!-- Scripts are already included in adminHeader.php -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
   <script>
     document.addEventListener("DOMContentLoaded", function() {

       // ✅ Chart 1: Users vs Orders
       var ctx1 = document.getElementById('userOrderChart').getContext('2d');
       new Chart(ctx1, {
         type: 'bar',
         data: {
           labels: ['Users', 'Orders'],
           datasets: [{
             label: 'Count',
             data: [120, 80], // 🔹 Static demo values
             backgroundColor: ['#4e73df', '#f6c23e']
           }]
         },
         options: {
           responsive: true,
           plugins: {
             legend: {
               display: false
             }
           }
         }
       });

       // ✅ Chart 2: Products by Category
       var ctx2 = document.getElementById('categoryChart').getContext('2d');
       new Chart(ctx2, {
         type: 'doughnut',
         data: {
           labels: ['Electronics', 'Clothing', 'Shoes', 'Accessories', 'Home'],
           datasets: [{
             data: [40, 25, 15, 10, 10], // 🔹 Static demo values
             backgroundColor: ['#36b9cc', '#1cc88a', '#f6c23e', '#e74a3b', '#858796']
           }]
         },
         options: {
           responsive: true,
           plugins: {
             legend: {
               position: 'bottom'
             }
           }
         }
       });

       // ✅ Chart 3: Monthly Orders Trend
       var ctx3 = document.getElementById('ordersTrendChart').getContext('2d');
       new Chart(ctx3, {
         type: 'line',
         data: {
           labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
           datasets: [{
             label: 'Orders',
             data: [12, 19, 3, 5, 2, 3, 15, 20, 18, 25, 30, 40], // 🔹 Demo values
             backgroundColor: 'rgba(78,115,223,0.2)',
             borderColor: '#4e73df',
             borderWidth: 2,
             fill: true,
             tension: 0.4
           }]
         },
         options: {
           responsive: true,
           plugins: {
             legend: {
               display: false
             }
           }
         }
       });

       // ✅ Chart 4: Order Status Breakdown
       var ctx4 = document.getElementById('orderStatusChart').getContext('2d');
       new Chart(ctx4, {
         type: 'pie',
         data: {
           labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
           datasets: [{
             data: [10, 20, 30, 5], // 🔹 Demo values
             backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b']
           }]
         },
         options: {
           responsive: true,
           plugins: {
             legend: {
               position: 'bottom'
             }
           }
         }
       });

     });
   </script>

   <script>
     // Hash-based Router
     function loadModule(fullModulePath, page = 1) {
       const content = $(".allContent-section");
       content.html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

       // Parse the module argument that was passed in
       const passedParts = fullModulePath.split('?');
       const module = passedParts[0];
       const passedParams = new URLSearchParams(passedParts[1] || "");

       // Extract current hash
       const hash = window.location.hash.substring(1);
       const hashParts = hash.split('?');
       const currentModule = hashParts[0];
       const hashParams = new URLSearchParams(hashParts[1] || "");

       // Build data object
       let data = {
         record: 1,
         page: page
       };

       // Add passed params first
       for (const [key, value] of passedParams) {
         if (key !== 'page') data[key] = value;
       }

       // Only merge hash params if we are staying on the SAME module AND no explicit params were passed
       // This prevents filters from 'sub-shop' bleeding into 'bumper-products'
       if (currentModule === module && passedParams.toString() === '') {
         for (const [key, value] of hashParams) {
           if (key !== 'page') data[key] = value;
         }
       }

       let url = "";
       switch (module) {
         case 'dashboard':
           content.html($("#dashboardContent").html());
           updateCharts(); // Re-init charts if needed
           window.location.hash = "dashboard";
           return;
         case 'manage-admins':
           url = "adminView/viewAdmins.php";
           break;
         case 'customers':
           url = "adminView/viewCustomers.php";
           break;
         case 'mobile-clients':
           url = "adminView/listCustomers.php";
           break;
         case 'messages':
           url = "adminView/viewMassages.php";
           break;
         case 'inquiries':
           url = "adminView/showUserInquiries.php";
           break;
         case 'categories':
           url = "adminView/viewCategories.php";
           break;
         case 'main-categories':
           url = "adminView/showMainCategory.php";
           break;
         case 'sub-categories':
           url = "adminView/showSubCategory.php";
           break;
         case 'sizes':
           url = "adminView/viewSizes.php";
           break;
         case 'stock':
           url = "adminView/showquantity.php";
           break;
         case 'new-arrivals':
           url = "adminView/viewAllProducts.php";
           break;
         case 'festivals':
           url = "adminView/showFestivalDhamaka.php";
           break;
         case 'special-offers':
           url = "adminView/showSpecialOffer.php";
           break;
         case 'bumper-offer':
           url = "adminView/showBumperOffer.php";
           break;
         case 'bumper-products':
           url = "adminView/manageBumperProducts.php";
           break;
         case 'gif-banners':
           url = "adminView/showGifBanner.php";
           break;
         case 'hero-slider':
           url = "adminView/viewHeroSlider.php";
           break;
         case 'payments':
           url = "adminView/showPayment.php";
           break;

         case 'pricing':
           url = "adminView/pricing-status.php";
           break;
         case 'our-shop':
           url = "adminView/viewMen.php";
           break;
         case 'sub-shop':
           url = "adminView/showOurSubShopCollections.php";
           break;
         case 'collections':
           url = "adminView/viewOurCollections.php";
           break;
         case 'sub-collections':
           url = "adminView/viewOurSubCollections.php";
           break;
         default:
           content.html($("#dashboardContent").html());
           return;
       }

       $.ajax({
         url: url,
         method: "GET",
         data: data,
         cache: false,
         success: function(response) {
           content.html(response);
           // Construct hash with all params
           let hashStr = module;
           let params = new URLSearchParams(data);
           params.delete('record'); // Remove internal marker
           if (params.toString()) {
             hashStr += '?' + params.toString();
           }
           window.location.hash = hashStr;
         },
         error: function() {
           content.html('<div class="alert alert-danger">Error loading module. Please try again.</div>');
         }
       });
     }

     // Handle browser Back/Forward & initial load
     window.addEventListener('hashchange', function() {
       const hash = window.location.hash.substring(1);
       if (!hash) {
         loadModule('dashboard');
         return;
       }
       const parts = hash.split('?');
       const module = parts[0];
       const params = new URLSearchParams(parts[1] || "");
       const page = params.get('page') || 1;
       loadModule(module, page);
     });

     $(document).ready(function() {
       // Initial route
       if (window.location.hash) {
         window.dispatchEvent(new HashChangeEvent('hashchange'));
       } else {
         loadModule('dashboard');
       }
     });
   </script>



   <script>
     function showToast(message, type = 'info') {
       let container = document.getElementById('toastContainer');
       if (!container) {
         container = document.createElement('div');
         container.id = 'toastContainer';
         container.className = 'toast-container';
         document.body.appendChild(container);
       }

       const toast = document.createElement('div');
       toast.className = `custom-toast ${type}`;

       let icon = 'info-circle';
       if (type === 'success') icon = 'check-circle';
       if (type === 'danger') icon = 'exclamation-circle';

       toast.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <span>${message}</span>
    `;

       container.appendChild(toast);

       setTimeout(() => {
         toast.classList.add('hiding');
         setTimeout(() => toast.remove(), 300);
       }, 4000);
     }

     // Legacy function mappings for compatibility with various views
     function showMainCategory() {
       loadModule('main-categories');
     }

     function showSubCategory() {
       loadModule('sub-categories');
     }

     function showCategory() {
       loadModule('categories');
     }

     function showProductItems() {
       loadModule('new-arrivals');
     }

     // Custom Confirmation Logic
     function showConfirm(title, message, onConfirm, confirmText = 'Confirm', isDangerous = true) {
       const overlay = document.getElementById('confirmOverlay');
       const titleEl = document.getElementById('confirmTitle');
       const msgEl = document.getElementById('confirmMessage');
       const yesBtn = document.getElementById('btnConfirmYes');
       const noBtn = document.getElementById('btnConfirmNo');
       const iconEl = document.querySelector('.confirm-header i');

       titleEl.textContent = title;
       msgEl.textContent = message;
       overlay.style.display = 'flex';

       // Clone buttons to remove previous listeners
       const newYes = yesBtn.cloneNode(true);
       const newNo = noBtn.cloneNode(true);
       yesBtn.parentNode.replaceChild(newYes, yesBtn);
       noBtn.parentNode.replaceChild(newNo, noBtn);

       // Dynamic text and styling
       newYes.textContent = confirmText;
       if (isDangerous) {
         newYes.style.background = '#ef4444'; // Red for Delete/Ban
         iconEl.className = 'fas fa-exclamation-triangle';
         iconEl.style.color = '#f6c23e'; // Warning Yellow
       } else {
         newYes.style.background = '#10b981'; // Green for Verify/Approve
         iconEl.className = 'fas fa-check-circle';
         iconEl.style.color = '#10b981'; // Success Green
       }

       newYes.onclick = () => {
         overlay.style.display = 'none';
         onConfirm();
       };
       newNo.onclick = () => {
         overlay.style.display = 'none';
       };
     }

     function updateCharts() {
       // Chart initialization logic here if it needs to re-run
     }

     // ✅ Flash messages handled via Toasts
     <?php
      if (isset($_GET['category'])) {
        $type = ($_GET['category'] == "success") ? "success" : "danger";
        $msg = ($_GET['category'] == "success") ? "Category Successfully Added" : "Adding Category Unsuccessful";
        echo "document.addEventListener('DOMContentLoaded', () => {
    showToast('$msg', '$type');
    const url = new URL(window.location);
    url.searchParams.delete('category');
    window.history.replaceState({}, '', url);
  });";
      }
      if (isset($_GET['size'])) {
        $type = ($_GET['size'] == "success") ? "success" : "danger";
        $msg = ($_GET['size'] == "success") ? "Size Successfully Added" : "Adding Size Unsuccessful";
        echo "document.addEventListener('DOMContentLoaded', () => {
    showToast('$msg', '$type');
    const url = new URL(window.location);
    url.searchParams.delete('size');
    window.history.replaceState({}, '', url);
  });";
      }
      if (isset($_GET['variation'])) {
        $type = ($_GET['variation'] == "success") ? "success" : "danger";
        $msg = ($_GET['variation'] == "success") ? "Variation Successfully Added" : "Adding Variation Unsuccessful";
        echo "document.addEventListener('DOMContentLoaded', () => {
    showToast('$msg', '$type');
    const url = new URL(window.location);
    url.searchParams.delete('variation');
    window.history.replaceState({}, '', url);
  });";
      }
      ?>
   </script>

   <script type="text/javascript" src="./assets/js/ajaxWork.js"></script>
   <script type="text/javascript" src="./assets/js/script.js"></script>

   <div class="toast-container" id="toastContainer"></div>

   <!-- Custom Confirmation Modal -->
   <div class="custom-confirm-overlay" id="confirmOverlay">
     <div class="custom-confirm-modal">
       <div class="confirm-header">
         <i class="fas fa-exclamation-triangle"></i>
       </div>
       <div class="confirm-body">
         <h4 id="confirmTitle">Are you sure?</h4>
         <p id="confirmMessage">You won't be able to revert this!</p>
       </div>
       <div class="confirm-footer">
         <button class="btn-confirm-no" id="btnConfirmNo">Cancel</button>
         <button class="btn-confirm-yes" id="btnConfirmYes">Confirm</button>
       </div>
     </div>
   </div>

 </body>

 </html>