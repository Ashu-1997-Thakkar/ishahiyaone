<?php 
session_start();
error_reporting(0);

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += (int)$item['quantity'];
    }
}
$wishlist_count = isset($_SESSION["wishlist"]) ? count($_SESSION["wishlist"]) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/seo_master.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --gold: #d4af37;
            --gold-hover: #c49a2e;
            --dark-bg: #000000;
            --card-bg: #111111;
            --text-muted: #aaaaaa;
        }

        body {
            background-color: var(--dark-bg);
            color: #e6e6e6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>
    <?php include 'includes/category_nav.php'; ?>

    <!-- Hero -->
    <section class="hero-section">
        <h1>Privacy Policy</h1>
        <p>Your privacy matters to us</p>
    </section>

    <!-- Privacy Policy Content -->
    <section class="policy-section">
        <div class="about-grid">
            <div class="section-card full-width">
                <h2 class="section-title">Privacy Policy</h2>
                <p>At Ishahiya, we respect your privacy and are committed to protecting your personal information. This Privacy Policy describes how we collect, use, store, and protect your data when you visit or make a purchase from our website.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">1. Information We Collect</h2>
                <p><strong>a) Personal Information</strong></p>
                <ul class="custom-list mb-3">
                    <li>Name</li>
                    <li>Email address</li>
                    <li>Phone number</li>
                    <li>Billing and shipping address</li>
                    <li>Payment details (processed securely)</li>
                </ul>
                <p><strong>b) Non-Personal Information</strong></p>
                <ul class="custom-list">
                    <li>IP address, browser type, device information</li>
                    <li>Cookies and browsing behavior</li>
                </ul>
            </div>

            <div class="section-card">
                <h2 class="section-title">2. How We Use Your Information</h2>
                <ul class="custom-list">
                    <li>Process and fulfill your orders</li>
                    <li>Communicate order updates and offers</li>
                    <li>Improve our website, products, and customer experience</li>
                    <li>Detect fraud and enhance security</li>
                    <li>Comply with legal and regulatory requirements</li>
                </ul>
            </div>

            <div class="section-card">
                <h2 class="section-title">3. Sharing of Information</h2>
                <p>We do not sell or rent your personal information to third parties. We may share information only with:</p>
                <ul class="custom-list">
                    <li>Courier & logistics partners (to deliver your orders)</li>
                    <li>Payment gateway providers (to process secure payments)</li>
                    <li>Service providers (for website hosting, analytics)</li>
                    <li>Government or law enforcement authorities (if legally required)</li>
                </ul>
            </div>

            <div class="section-card">
                <h2 class="section-title">4. Data Security</h2>
                <p>We use SSL encryption and secure servers to protect your personal data. Access to personal information is restricted to authorized personnel only. However, no online transmission is 100% secure. We cannot guarantee absolute security of data.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">5. Cookies & Tracking</h2>
                <p>Our website uses cookies to improve user experience, remember your preferences, and analyze traffic. You may disable cookies in your browser, but certain features of the site may not function properly.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">6. Your Rights</h2>
                <p>As a customer, you have the right to:</p>
                <ul class="custom-list">
                    <li>Access, correct, or update your personal information</li>
                    <li>Request deletion of your account/data</li>
                    <li>Opt-out of marketing emails and SMS by unsubscribing at any time</li>
                </ul>
            </div>

            <div class="section-card">
                <h2 class="section-title">7. Third-Party Links</h2>
                <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of such external sites.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">8. Children's Privacy</h2>
                <p>Our website is not intended for use by children under the age of 18 years. We do not knowingly collect personal data from minors.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">9. Policy Updates</h2>
                <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with the updated date.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">10. Contact Us</h2>
                <p>If you have any questions or concerns about this Privacy Policy or how your information is handled, please contact us at:</p>
                <p><i class="fa fa-envelope" style="color:var(--gold); margin-right:8px;"></i> Email: info@ishahiya.com<br><i class="fa fa-phone" style="color:var(--gold); margin-right:8px; margin-top:8px;"></i> Phone/WhatsApp: +91 99743 28904</p>
            </div>

            <div class="section-card full-width text-center" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
                <h2 class="section-title" style="margin-bottom: 15px;">Consent</h2>
                <p class="promise-text">
                    <i class="fas fa-check-circle mr-2" style="color: var(--gold);"></i> By using www.ishahiya.com, you consent to the collection and use of your information as described in this Privacy Policy.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
  
    <script>
        // Reveal on scroll logic with staggered delay
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.section-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                            entry.target.classList.add('visible');
                        }, entry.target.dataset.delay * 150); // increased delay for better visual effect
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: "0px 0px -20px 0px" });

            cards.forEach((card, i) => {
                card.dataset.delay = i; // Sequential waterfall stagger
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
