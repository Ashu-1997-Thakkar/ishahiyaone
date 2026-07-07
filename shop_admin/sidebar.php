<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
  <div class="side-header">
    <img src="../image/logo/ishahiya-logo.png" width="80" height="80" alt="Logo" style="object-fit:contain; border-radius:10px; background:#000;">
    <h6 class="text-white mt-2 font-weight-bold">IshahiyaOne Admin Panel</h6>
  </div>

  <div class="menu-items mt-3">
    <!-- Section: Dashboard -->
    <a href="javascript:void(0)" class="active" onclick="loadModule('dashboard')">
      <i class="fa fa-home"></i> Dashboard
    </a>

    <!-- Section: User Management -->
    <div class="menu-label text-muted collapsed" onclick="toggleSection(this, 'users-menu')">
      <span><i class="fa fa-users-cog mr-2"></i> Users</span>
      <i class="fa fa-chevron-down toggle-icon"></i>
    </div>
    <div id="users-menu" class="submenu-container collapsed">
      <a href="javascript:void(0)" onclick="loadModule('customers')"><i class="fa fa-user"></i> Customers</a>
      <a href="javascript:void(0)" onclick="loadModule('mobile-clients')"><i class="fa fa-mobile-alt"></i> Mobile Clients</a>
      <a href="javascript:void(0)" onclick="loadModule('messages')"><i class="fa fa-envelope"></i> Messages</a>
      <a href="javascript:void(0)" onclick="loadModule('inquiries')"><i class="fa fa-question-circle"></i> Inquiries</a>
      <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'superadmin'])): ?>
      <a href="javascript:void(0)" onclick="loadModule('manage-admins')"><i class="fa fa-user-shield"></i> Manage Admins</a>
      <?php endif; ?>
    </div>

    <!-- Section: Inventory & Categories -->
    <div class="menu-label text-muted collapsed" onclick="toggleSection(this, 'inventory-menu')">
      <span><i class="fa fa-boxes mr-2"></i> Inventory</span>
      <i class="fa fa-chevron-down toggle-icon"></i>
    </div>
    <div id="inventory-menu" class="submenu-container collapsed">
      <a href="javascript:void(0)" onclick="loadModule('categories')"><i class="fa fa-th-large"></i> Categories</a>
      <a href="javascript:void(0)" onclick="loadModule('main-categories')"><i class="fa fa-list"></i> Main Categories</a>
      <a href="javascript:void(0)" onclick="loadModule('sub-categories')"><i class="fa fa-sitemap"></i> Sub Categories</a>
      <a href="javascript:void(0)" onclick="loadModule('sizes')"><i class="fa fa-expand"></i> Sizes</a>
      <a href="javascript:void(0)" onclick="loadModule('stock')"><i class="fa fa-warehouse"></i> Stock Mgmt</a>
    </div>

    <!-- Section: Marketing -->
    <div class="menu-label text-muted collapsed" onclick="toggleSection(this, 'marketing-menu')">
      <span><i class="fa fa-bullhorn mr-2"></i> Marketing</span>
      <i class="fa fa-chevron-down toggle-icon"></i>
    </div>
    <div id="marketing-menu" class="submenu-container collapsed">
      <a href="javascript:void(0)" onclick="loadModule('new-arrivals')"><i class="fa fa-star"></i> New Arrivals</a>
      <a href="javascript:void(0)" onclick="loadModule('bumper-offer')"><i class="fa fa-bolt"></i> Bumper Banners</a>
      <a href="javascript:void(0)" onclick="loadModule('bumper-products')"><i class="fa fa-box-open"></i> Bumper Products</a>
      <a href="javascript:void(0)" onclick="loadModule('hero-slider')"><i class="fa fa-images"></i> Hero Slider</a>
    </div>


    <!-- Section: Finance -->
    <div class="menu-label text-muted collapsed" onclick="toggleSection(this, 'finance-menu')">
      <span><i class="fa fa-wallet mr-2"></i> Finance</span>
      <i class="fa fa-chevron-down toggle-icon"></i>
    </div>
    <div id="finance-menu" class="submenu-container collapsed">
      <a href="javascript:void(0)" onclick="loadModule('payments')"><i class="fa fa-money-bill-wave"></i> Payments</a>
      <a href="javascript:void(0)" onclick="loadModule('pricing')"><i class="fa fa-tag"></i> Pricing Status</a>
    </div>

    <!-- Section: Collections -->
    <div class="menu-label text-muted collapsed" onclick="toggleSection(this, 'collections-menu')">
      <span><i class="fa fa-store mr-2"></i> Shop Collections</span>
      <i class="fa fa-chevron-down toggle-icon"></i>
    </div>
    <div id="collections-menu" class="submenu-container collapsed">
      <a href="javascript:void(0)" onclick="loadModule('our-shop')"><i class="fa fa-shopping-bag"></i> Our Shop</a>
      <a href="javascript:void(0)" onclick="loadModule('sub-shop')"><i class="fa fa-layer-group"></i> Sub Shop</a>
      <a href="javascript:void(0)" onclick="loadModule('collections')"><i class="fa fa-images"></i> Collections</a>
      <a href="javascript:void(0)" onclick="loadModule('sub-collections')"><i class="fa fa-object-group"></i> Sub Collections</a>
    </div>
  </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<script>
  function toggleSection(label, menuId) {
    const menu = document.getElementById(menuId);
    label.classList.toggle('collapsed');
    menu.classList.toggle('collapsed');
  }

  function toggleSidebar() {
    const sidebar = document.getElementById("mySidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const main = document.getElementById("main-content");
    const isMobile = window.innerWidth <= 992;

    if (isMobile) {
      sidebar.classList.toggle("mobile-open");
      if (overlay) overlay.classList.toggle("active");
    } else {
      if (sidebar.style.width === "0px" || sidebar.style.width === "0") {
        sidebar.style.width = "260px";
        main.style.marginLeft = "260px";
      } else {
        sidebar.style.width = "0";
        main.style.marginLeft = "0";
      }
    }
  }

  function closeSidebar() {
    const sidebar = document.getElementById("mySidebar");
    const overlay = document.getElementById("sidebarOverlay");
    if (sidebar) sidebar.classList.remove("mobile-open");
    if (overlay) overlay.classList.remove("active");
  }

  document.addEventListener("DOMContentLoaded", function() {
    const isMobile = window.innerWidth <= 992;
    if (!isMobile) {
      document.getElementById("mySidebar").style.width = "260px";
      document.getElementById("main-content").style.marginLeft = "260px";
    } else {
      document.getElementById("mySidebar").style.width = "";
      document.getElementById("main-content").style.marginLeft = "";
    }

    const menuLinks = document.querySelectorAll('.menu-items a');
    menuLinks.forEach(link => {
      link.addEventListener('click', function() {
        menuLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        if (window.innerWidth <= 992) {
          closeSidebar();
        }
      });
    });
  });
</script>




<!-- Subcategory Form (Initially Hidden) -->
<div id="subcategoryForm" style="display:none; margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
  <form action="/shop_admin/controller/DataUpdate.php" method="POST" enctype="multipart/form-data">
    <div class="form-group mb-3">
      <label for="category_name">Category:</label>
      <select class="form-control" id="category_name" name="category_name" required>
        <option value="" disabled selected>-- Select Category --</option>
        <?php
        include_once __DIR__ . "/config/dbconnect.php";
        $catResult = mysqli_query($conn, "SELECT * FROM category");
        while ($row = mysqli_fetch_assoc($catResult)) {
          echo "<option value='" . htmlspecialchars($row['category_name']) . "'>" . htmlspecialchars($row['category_name']) . "</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="subcategory_name" class="form-label">Subcategory Name</label>
      <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" required>
    </div>

    <div class="mb-3">
      <label for="prod_name" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="prod_name" name="name" required>
    </div>

    <div class="mb-3">
      <label for="prod_price" class="form-label">Price</label>
      <input type="text" class="form-control" id="prod_price" name="price" required>
    </div>

    <div class="mb-3">
      <label for="prod_image" class="form-label">Image (optional)</label>
      <input type="file" class="form-control" id="prod_image" name="image">
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <button type="button" class="btn btn-secondary" onclick="toggleSubcategoryForm()">Cancel</button>
  </form>
</div>

<script>
  // Function to open the Subcategory Modal (Form)
  function openSubcategoryModal() {
    const form = document.getElementById('subcategoryForm');
    form.style.display = "block"; // Show the form
  }

  // Function to toggle visibility of the Subcategory Form (Hide or Show)
  function toggleSubcategoryForm() {
    const form = document.getElementById('subcategoryForm');
    if (form.style.display === "none") {
      form.style.display = "block"; // Show the form
    } else {
      form.style.display = "none"; // Hide the form
    }
  }
</script>