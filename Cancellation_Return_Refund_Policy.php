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

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Cancellation, Return & Refund Policy</h1>
        <p>Customer Satisfaction is Our Priority</p>
    </section>

    <!-- Policy Sections -->
    <section class="policy-section">
        <div class="about-grid">
            <div class="section-card full-width">
                <h2 class="section-title">1. Product Return & Replacement</h2>
                <p><strong>Accepted Reasons for Return/Replacement:</strong></p>
                <ul class="custom-list mb-3">
                    <li>Damaged or Defective Product</li>
                    <li>Wrong Product Delivered</li>
                    <li>Missing Parts</li>
                </ul>
                <p><strong>Non-Acceptable Reasons for Return/Replacement:</strong></p>
                <ul class="custom-list">
                    <li>Altered or Worn Product</li>
                    <li>Incomplete Returns</li>
                    <li>Personal Preference or Dislike</li>
                    <li>Customer-Caused Damage</li>
                </ul>
                <p class="mt-3" style="color:var(--gold);"><em>Note:</em> Replacements are subject to product availability.</p>
            </div>

            <div class="section-card">
                <h2 class="section-title">2. Refund Policy</h2>
                <ul class="custom-list mb-3">
                    <li>Refunds are processed after inspection of the returned product.</li>
                    <li>For COD orders, refunds will be processed via NEFT/IMPS/UPI after bank details or UPI ID are submitted.</li>
                    <li>Processing time: 7-10 working days.</li>
                    <li>If pickup service is unavailable, customers must return products to our warehouse. Courier charges will be deducted from refunds.</li>
                </ul>
                <p>For damaged or incorrect products, please contact us:</p>
                <p><i class="fa fa-phone" style="color:var(--gold); margin-right:8px;"></i> +91 99743 28904<br><i class="fa fa-envelope" style="color:var(--gold); margin-right:8px; margin-top:8px;"></i> <a href="mailto:support@ishahiyaone.shop" style="color: #fff; text-decoration: none;">support@ishahiyaone.shop</a></p>
            </div>

            <div class="section-card">
                <h2 class="section-title">3. Shipping Policy</h2>
                <ul class="custom-list">
                    <li>Orders are dispatched Monday to Saturday, excluding Sundays and public holidays.</li>
                    <li>Delivery within India: 4-10 working days via trusted courier partners (Delhivery, DTDC, XpressBees, Blue Dart, India Post).</li>
                    <li>If a package appears tampered or damaged, refuse delivery and report within 24 hours with unboxing video proof.</li>
                    <li>International shipping may include additional customs duties and taxes based on the destination country.</li>
                    <li>Customers are responsible for all import duties, customs fees, and local taxes.</li>
                    <li>Delivery delays may occur due to customs or weather conditions.</li>
                </ul>
            </div>

            <div class="section-card">
                <h2 class="section-title">4. Cancellation Policy</h2>
                <ul class="custom-list mb-3">
                    <li>Orders can be canceled before dispatch by contacting us at +91 99743 28904 or <a href="mailto:support@ishahiyaone.shop" style="color:#fff;">support@ishahiyaone.shop</a>.</li>
                    <li>Full refunds are available for cancellations made before shipment.</li>
                    <li>You can track your order status anytime by logging into your account under "My Account."</li>
                </ul>
            </div>

            <div class="section-card full-width text-center" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
                <h2 class="section-title" style="margin-bottom: 15px;">Our Commitment</h2>
                <p class="promise-text">
                    <i class="fas fa-check-circle mr-2" style="color: var(--gold);"></i> We strive to make your shopping experience seamless, transparent, and trustworthy.<br>
                    Every Ishahiya product reflects our promise of quality, authenticity, and care.
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
