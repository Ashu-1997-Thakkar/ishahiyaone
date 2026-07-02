<div class="footer-wrapper">
    <!-- Global Premium Newsletter Component -->
    <?php include 'newsletter.php'; ?>

    <!-- Main Footer -->
    <footer class="footer">
        <div class="footer__container">
            <!-- Brand & Social -->
            <div class="footer__col brand-col">
                <a href="index.php" class="footer__logo">
                    <img src="image/logo/ishahiya-logo.png" alt="Ishahiya Logo" class="footer__logo-img">
                </a>
                <p class="footer__desc">Your premium destination for high-quality electronics, fashion, and lifestyle products with unbeatable deals.</p>
                <div class="footer__social-links">
                    <a href="https://www.facebook.com/share/1NmMBy5VP4/" target="_blank" class="footer__social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/ishahiyaone?igsh=MXYzc21reWFqMGJ6Nw==" target="_blank" class="footer__social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank" class="footer__social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" class="footer__social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer__col accordion-col">
                <h4 class="footer__title">About Us <i class="fas fa-chevron-down d-md-none float-right mt-1"></i></h4>
                <div class="footer__collapse">
                    <ul class="footer__links">
                        <li><a href="about.php">Our Story</a></li>
                        <li><a href="delivery.php">Delivery Information</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms-and-conditions.php">Terms & Conditions</a></li>
                        <li><a href="Cancellation_Return_Refund_Policy.php">Return Policy</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <!-- Account Links -->
            <div class="footer__col accordion-col">
                <h4 class="footer__title">My Account <i class="fas fa-chevron-down d-md-none float-right mt-1"></i></h4>
                <div class="footer__collapse">
                    <ul class="footer__links">
                        <li><a href="log.php">Sign In / Register</a></li>
                        <li><a href="cart.php">View Cart</a></li>
                        <li><a href="wishlist.php">My Wishlist</a></li>
                        <li><a href="track-order.php">Track My Order</a></li>
                        <li><a href="help.php">Help Center</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact & Payment -->
            <div class="footer__col accordion-col">
                <h4 class="footer__title">Contact & Security <i class="fas fa-chevron-down d-md-none float-right mt-1"></i></h4>
                <div class="footer__collapse">
                    <div class="footer__contact mb-3">
                        <p><i class="fa-solid fa-location-dot"></i> 5, Keshav Shardamani, Karamsad</p>
                        <p><i class="fa-solid fa-phone"></i> +91 99743 28904</p>
                        <p><i class="fa-solid fa-phone"></i> +91 72018 08176</p>
                    </div>
                    
                    <div class="footer__payment">
                        <p>100% Secure Checkout</p>
                        <div class="footer__payment-icons">
                            <img src="image/sm-banner/pngegg.png" alt="Payment Methods">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer__bottom">
            <p>&copy; <?php echo date("Y"); ?> IshahiyaOne.shop. Owned & operated by BR IT SOLUTION AND BR CATTLE FEED. GSTIN: 24AXGPP5413P1Z3</p>
            <div class="footer__bottom-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms-and-conditions.php">Terms of Service</a>
            </div>
        </div>
    </footer>
</div>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/917201808176?text=Hello,%20I%E2%80%99m%20interested%20in%20your%20products." class="whatsapp-float" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Mobile Nav Backdrop -->
<div class="nav__backdrop" id="nav-backdrop"></div>

<script>
    // Navigation toggle logic (global)
    document.addEventListener('DOMContentLoaded', () => {
        const navToggle  = document.getElementById("nav-toggle");
        const navMenu    = document.getElementById("nav-menu");
        const closeBtn   = document.getElementById("close-btn");
        const backdrop   = document.getElementById("nav-backdrop");

        function openMenu() {
            if (!navMenu) return;
            navMenu.classList.add("show-menu");
            if (backdrop) backdrop.classList.add("show");
            document.body.style.overflow = "hidden"; // Prevent scroll behind drawer
        }

        function closeMenu() {
            if (!navMenu) return;
            navMenu.classList.remove("show-menu");
            if (backdrop) backdrop.classList.remove("show");
            document.body.style.overflow = ""; // Restore scroll
        }

        if (navToggle) navToggle.addEventListener("click", openMenu);
        if (closeBtn)  closeBtn.addEventListener("click",  closeMenu);
        if (backdrop)  backdrop.addEventListener("click",  closeMenu);

        // Close on nav link click (single page feel)
        if (navMenu) {
            navMenu.querySelectorAll('.nav__link').forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        }

        // Close on Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMenu();
        });

        // Mobile & Tablet Footer Accordion (Realme & Android 100% Reliable Fix)
        const accordionTitles = document.querySelectorAll('.accordion-col .footer__title');
        accordionTitles.forEach(title => {
            title.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    const col = this.parentElement;
                    const collapse = col.querySelector('.footer__collapse');
                    const isActive = col.classList.toggle('active');
                    
                    // Direct style fallback forces open even if mobile browser cached old CSS
                    if (collapse) {
                        collapse.style.display = isActive ? 'block' : 'none';
                    }
                    
                    const icon = this.querySelector('i');
                    if (icon) {
                        if (isActive) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                }
            });
        });
    });
</script>
