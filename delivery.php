<?php 
session_start();
// delivery.php
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

        /* ================= GLOBALLY SHARED POLICY CSS IN style.css ================= */

  </style>
</head>
<body>

  <!-- Header & Navigation -->
  <?php include 'includes/header_nav.php'; ?>
  <?php include 'includes/category_nav.php'; ?>

  <!-- Hero -->
  <section class="hero-section">
    <h1>Shipping & Delivery Policy</h1>
    <p>Fast and secure delivery for your orders</p>
  </section>

  <!-- Delivery Policy Content -->
  <section class="policy-section">
    <div class="about-grid">

      <div class="section-card full-width">
        <h2 class="section-title">Shipping & Delivery Policy</h2>
        <p>At Ishahiya, we aim to deliver your orders quickly, safely, and efficiently. This Shipping & Delivery Policy explains how we handle order processing, shipping, and delivery timelines.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">1. Order Processing</h2>
        <p>Orders are processed within 1-3 business days after payment confirmation.</p>
        <p>Orders placed on weekends or public holidays will be processed on the next working day.</p>
        <p>You will receive an email/SMS confirmation once your order has been shipped, along with tracking details.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">2. Shipping Methods & Charges</h2>
        <p>We ship orders through trusted courier partners across India.</p>
        <p>Shipping charges (if applicable) will be calculated and displayed at checkout before you confirm payment.</p>
        <p>Free shipping may be offered on select products or promotions, which will be clearly mentioned on the product page.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">3. Delivery Timelines</h2>
        <p>Standard delivery time is 5-10 business days depending on your location.</p>
        <p>Delivery to remote or rural areas may take longer than usual.</p>
        <p>We are not responsible for delays caused by courier companies, weather conditions, strikes, or events beyond our control.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">4. Tracking Your Order</h2>
        <p>Once shipped, you will receive a tracking ID and link to track your shipment online.</p>
        <p>Customers are responsible for providing accurate shipping details at the time of checkout. Incorrect or incomplete addresses may result in delays or failed deliveries.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">5. Failed Deliveries</h2>
        <p>If the courier is unable to deliver due to incorrect address, non-availability, or refusal to accept, the package may be returned to us.</p>
        <p>In such cases, re-delivery charges may apply and must be paid by the customer.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">6. International Shipping</h2>
        <p>Currently, we ship only within India. International shipping options will be updated in the future.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">7. Damaged or Missing Items</h2>
        <p>If your package arrives damaged, tampered, or with missing items, please notify us within 48 hours of delivery with photos/videos as proof.</p>
        <p>We will investigate and arrange for a replacement, refund, or compensation as applicable.</p>
      </div>

      <div class="section-card">
        <h2 class="section-title">8. Contact for Shipping Support</h2>
        <p>For queries related to shipping or delivery, please contact:</p>
        <p><i class="fa fa-envelope" style="color:var(--gold); margin-right:8px;"></i> Email: info@ishahiya.com<br><i class="fa fa-phone" style="color:var(--gold); margin-right:8px; margin-top:8px;"></i> Phone/WhatsApp: +91 997438904</p>
      </div>

      <div class="section-card full-width text-center" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
        <h2 class="section-title" style="margin-bottom: 15px;">Agreement</h2>
        <p class="promise-text" style="font-size: 1.1rem !important; color: #fff !important; font-weight: 500; max-width: 800px; margin: 0 auto; line-height: 1.8 !important;">
          <i class="fas fa-check-circle mr-2" style="color: var(--gold);"></i> By placing an order on www.ishahiya.com, you agree to this Shipping & Delivery Policy.
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
