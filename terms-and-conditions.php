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
        <h1>Terms & Conditions</h1>
        <p>Read carefully before using our website</p>
    </section>

    <!-- Terms Content -->
    <section class="policy-section">
        <div class="about-grid">
            <div class="section-card full-width">
                <h2 class="section-title">Terms & Condition</h2>
                <p>These Terms & Conditions ("Terms") govern your access to and use of the website <strong>www.ishahiya.com</strong> ("Company", "we", "us", or "our"). By using our website, products, or services, you ("User", "Customer", or "you") agree to these Terms. If you do not agree, you must not use this website or make any purchases.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">1. General Use of Website</h2>
                <p>You must use this website lawfully and in accordance with these Terms.</p>
                <p>You are responsible for ensuring that your device, internet connection, and login credentials remain secure.</p>
                <p>We strive to provide uninterrupted access to our website but cannot guarantee this at all times due to technical, server, or network issues beyond our control.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">2. Scope of Services</h2>
                <p>We collect limited personal information for the purpose of order processing, service improvement, and personalized offers. This information will be used responsibly as per our Privacy Policy.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">3. Product Information & Accuracy</h2>
                <p>All product details, images, and specifications on our website are based on available information at the time of publication. While we make every effort to provide accurate details, actual colors, sizes, or minor specifications may vary.</p>
                <p>We reserve the right to update or correct product information without prior notice.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">4. Transactions & Payments</h2>
                <p>By placing an order, you agree to pay 100% of the transaction value including applicable shipping charges, courier fees, and taxes.</p>
                <p>Payments must only be made through secure payment options provided on our website. Cash transactions with our staff or tele-callers are strictly not allowed.</p>
                <p>Please review your order details carefully before confirming.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">5. Cancellation, Refund & Returns</h2>
                <p>Cancellations, returns, and refunds are subject to our Cancellation & Refund Policy (available separately).</p>
                <p>Certain products may have additional conditions or may not be eligible for cancellation or refund.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">6. Warranty & Liability</h2>
                <p>Products may carry a warranty as specified in their description. Warranty is limited to repair or replacement of defective parts under a "return to base" policy.</p>
                <p>Warranty does not cover damages due to mishandling, improper storage, unauthorized modifications, or natural wear and tear.</p>
                <p>No warranty or guarantee is provided for services. We are not liable for indirect, incidental, or consequential damages.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">7. Pricing & Offers</h2>
                <p>Prices displayed on our website are competitive and subject to change without prior notice.</p>
                <p>Errors in pricing, technical glitches, or misprints are not binding. If an error occurs, we reserve the right to cancel the order and issue a refund.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">8. Proprietary Use of Products</h2>
                <p>Products sold on www.ishahiya.com are intended for specific use as described. We are not responsible if products are used with incompatible machines or in ways not recommended by us.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">9. Intellectual Property</h2>
                <p>All website content including images, text, software, and designs are the property of www.ishahiya.com or its licensors. You may not copy, reproduce, resell, distribute, or use our content for commercial purposes without written permission.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">10. Dispute Resolution & Governing Law</h2>
                <p>These Terms are governed by and construed in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in Gujarat, India.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">11. Miscellaneous</h2>
                <p>Grievances or claims must be reported to our support team within 7 days of the transaction date.</p>
                <p>We reserve the right to modify, update, or replace these Terms at any time without prior notice.</p>
            </div>

            <div class="section-card full-width text-center" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
                <h2 class="section-title" style="margin-bottom: 15px;">Agreement</h2>
                <p class="promise-text">
                    <i class="fas fa-check-circle mr-2" style="color: var(--gold);"></i> By using this website, you acknowledge that you have read, understood, and agreed to these Terms & Conditions.
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
