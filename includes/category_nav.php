<?php
// ✅ Shared Category Navigation Component (Swiper-based) - Minimalist Meesho Style
if (!isset($conn)) {
    include_once __DIR__ . "/../shop_admin/config/dbconnect.php";
}
?>

<!-- ✅ CATEGORY MENU BAR -->
<div class="category-menu-container">
  <div class="category-menu-wrapper">
    <div class="category-menu swiper" id="categorySwiper">

      <div class="swiper-wrapper">
        <?php
        $mainCatResult = $conn->query("SELECT * FROM main_category ORDER BY id ASC");
        if ($mainCatResult && $mainCatResult->num_rows > 0) {
            while ($mainCat = $mainCatResult->fetch_assoc()) {
                $mainCatId = (int)$mainCat['id'];
                $mainCatName = htmlspecialchars($mainCat['main_category_name']);
                $mainCatSlug = htmlspecialchars($mainCat['slug']);
                
                $hasSubCat = $conn->query("SELECT COUNT(*) as count FROM sub_category WHERE main_category_id='$mainCatId'")->fetch_assoc();
                $hasSubCategories = $hasSubCat['count'] > 0;
                $specialMainCats = ['bulk - sms - services', 'cattle - feed'];
                $isSpecialMain = in_array(trim($mainCatSlug), $specialMainCats);
                ?>
                <div class="swiper-slide <?= $hasSubCategories ? 'dropdown' : '' ?>">
                    <a href="<?= $isSpecialMain ? 'drt.php?main='.$mainCatSlug : 'shop.php?main='.$mainCatSlug ?>" class="cat-link <?= $hasSubCategories ? 'has-dropdown' : '' ?>">
                        <?php
                        // Generic mapping for icons based on category name
                        $iconClass = "fa-solid fa-list-ul"; // Default
                        $lowerName = strtolower($mainCatName);
                        if (strpos($lowerName, 'elect') !== false) $iconClass = "fa-solid fa-bolt-lightning";
                        elseif (strpos($lowerName, 'fash') !== false || strpos($lowerName, 'wear') !== false) $iconClass = "fa-solid fa-shirt";
                        elseif (strpos($lowerName, 'network') !== false) $iconClass = "fa-solid fa-network-wired";
                        elseif (strpos($lowerName, 'home') !== false) $iconClass = "fa-solid fa-house-chimney";
                        elseif (strpos($lowerName, 'living') !== false) $iconClass = "fa-solid fa-couch";
                        elseif (strpos($lowerName, 'offer') !== false) $iconClass = "fa-solid fa-tags";
                        elseif (strpos($lowerName, 'sms') !== false) $iconClass = "fa-solid fa-comment-sms";
                        elseif (strpos($lowerName, 'cattle') !== false || strpos($lowerName, 'feed') !== false) $iconClass = "fa-solid fa-cow";
                        ?>
                        <i class="<?= $iconClass ?> cat-icon"></i>
                        <span><?= $mainCatName ?></span>
                        <?php if ($hasSubCategories): ?>
                            <i class="fa-solid fa-chevron-down arrow-down"></i>
                        <?php endif; ?>
                    </a>
                    <?php if ($hasSubCategories): ?>
                    <ul class="dropdown-menu">
                        <?php
                        $subCatResult = $conn->query("SELECT * FROM sub_category WHERE main_category_id='$mainCatId' ORDER BY id ASC");
                        while ($subCat = $subCatResult->fetch_assoc()) {
                            $subCatName = htmlspecialchars($subCat['sub_category_name']);
                            $subCatSlug = htmlspecialchars($subCat['slug']);
                            
                            // Comprehensive subcategory icons mapping
                            $subIcon = "fa-circle-dot"; // Default
                            $lowSub = strtolower($subCatName);
                            
                            // Electronics & Tech
                            if (strpos($lowSub, 'mobile') !== false) $subIcon = "fa-mobile-screen-button";
                            elseif (strpos($lowSub, 'laptop') !== false) $subIcon = "fa-laptop";
                            elseif (strpos($lowSub, 'desktop') !== false || strpos($lowSub, 'computer') !== false) $subIcon = "fa-desktop";
                            elseif (strpos($lowSub, 'drive') !== false || strpos($lowSub, 'storage') !== false) $subIcon = "fa-hard-drive";
                            elseif (strpos($lowSub, 'print') !== false || strpos($lowSub, 'scan') !== false) $subIcon = "fa-print";
                            elseif (strpos($lowSub, 'monitor') !== false || strpos($lowSub, 'display') !== false) $subIcon = "fa-display";
                            elseif (strpos($lowSub, 'server') !== false) $subIcon = "fa-server";
                            elseif (strpos($lowSub, 'tablet') !== false) $subIcon = "fa-tablet-screen-button";
                            elseif (strpos($lowSub, 'cctv') !== false || strpos($lowSub, 'camera') !== false || strpos($lowSub, 'security') !== false) $subIcon = "fa-shield-halved";
                            elseif (strpos($lowSub, 'spy') !== false) $subIcon = "fa-user-secret";
                            elseif (strpos($lowSub, 'cable') !== false || strpos($lowSub, 'wire') !== false) $subIcon = "fa-link";
                            elseif (strpos($lowSub, 'router') !== false || strpos($lowSub, 'wifi') !== false || strpos($lowSub, 'point') !== false) $subIcon = "fa-wifi";
                            elseif (strpos($lowSub, 'adapter') !== false) $subIcon = "fa-plug";
                            elseif (strpos($lowSub, 'switch') !== false) $subIcon = "fa-toggle-on";
                            
                            // Fashion & Wear
                            elseif (strpos($lowSub, 'men') !== false && strpos($lowSub, 'women') === false) $subIcon = "fa-person";
                            elseif (strpos($lowSub, 'women') !== false) $subIcon = "fa-person-dress";
                            elseif (strpos($lowSub, 'kid') !== false || strpos($lowSub, 'child') !== false) $subIcon = "fa-child-reaching";
                            elseif (strpos($lowSub, 'couple') !== false || strpos($lowSub, 'combo') !== false) $subIcon = "fa-people-pulling";
                            elseif (strpos($lowSub, 'family') !== false) $subIcon = "fa-people-group";
                            elseif (strpos($lowSub, 'jewellery') !== false || strpos($lowSub, 'gem') !== false) $subIcon = "fa-gem";
                            
                            // Smart Home & Living
                            elseif (strpos($lowSub, 'wearable') !== false || strpos($lowSub, 'watch') !== false) $subIcon = "fa-stopwatch-20";
                            elseif (strpos($lowSub, 'energy') !== false || strpos($lowSub, 'power') !== false) $subIcon = "fa-bolt-lightning";
                            elseif (strpos($lowSub, 'light') !== false) $subIcon = "fa-lightbulb";
                            elseif (strpos($lowSub, 'gps') !== false || strpos($lowSub, 'track') !== false) $subIcon = "fa-location-crosshairs";
                            
                            // Offers & Others
                            elseif (strpos($lowSub, 'offer') !== false || strpos($lowSub, 'bumper') !== false || strpos($lowSub, 'sale') !== false) $subIcon = "fa-fire-flame-curved";
                            elseif (strpos($lowSub, 'feed') !== false || strpos($lowSub, 'calf') !== false || strpos($lowSub, 'buffalo') !== false || strpos($lowSub, 'khor') !== false || strpos($lowSub, 'dan') !== false) $subIcon = "fa-cow";
                            ?>
                             <li>
                                <a href="subshop1.php?subcategory=<?= $subCatSlug ?>">
                                    <div class="sub-link-content">
                                        <i class="fa-solid <?= $subIcon ?> sub-icon"></i>
                                        <span><?= $subCatName ?></span>
                                    </div>
                                    <i class="fa-solid fa-angle-right"></i>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="view-all">
                            <a href="shop.php?main=<?= $mainCatSlug ?>">
                                View All <?= $mainCatName ?>
                                <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </li>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php
            }
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Category Swiper & Dropdown JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof Swiper === 'undefined') return;

  // Initialize Category Swiper
  new Swiper('#categorySwiper', {
    slidesPerView: 'auto',
    spaceBetween: 0,
    freeMode: true,
    watchOverflow: true,
    centerInsufficientSlides: false,
    grabCursor: false,
    slidesOffsetBefore: 0,
    slidesOffsetAfter: 0,
    scrollbar: false,
    breakpoints: {
      1025: { slidesOffsetBefore: 0, slidesOffsetAfter: 20, freeMode: false },
      768:  { slidesOffsetBefore: 0, slidesOffsetAfter: 10 }
    }
  });

  // ── Mobile & Tablet touch dropdown toggle & Arrow toggle ──────────────────────────────────
  document.querySelectorAll('.swiper-slide.dropdown > a').forEach(function(link) {
    link.addEventListener('click', function(e) {
      var isArrowClick = e.target.classList && (e.target.classList.contains('arrow-down') || e.target.closest('.arrow-down'));
      var isMobileOrTouch = window.innerWidth <= 1024 || ('ontouchstart' in window) || navigator.maxTouchPoints > 0;

      if (isArrowClick || isMobileOrTouch) {
        e.preventDefault();
        var slide = this.parentElement;
        var wasActive = slide.classList.contains('active');
        
        // Close all open dropdowns first
        document.querySelectorAll('.swiper-slide.dropdown.active')
                .forEach(function(el) { el.classList.remove('active'); });
                
        if (!wasActive) {
          slide.classList.add('active');
        } else {
          slide.classList.remove('active');
        }
      }
    });
  });

  // Close mobile dropdown on outside click
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.swiper-slide.dropdown')) {
      document.querySelectorAll('.swiper-slide.dropdown.active')
              .forEach(function(el) { el.classList.remove('active'); });
    }
  });
});
</script>
