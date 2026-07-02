<?php
session_start();
// about.php
error_reporting(0);

// Fetch wishlist and cart counts from session (or database)
// Using logic similar to index.php for consistency
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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

        ul.custom-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 768px) {
            ul.custom-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        ul.custom-list li {
            margin-bottom: 0;
            padding-left: 28px;
            position: relative;
        }

        ul.custom-list li::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: var(--gold);
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 0.9rem;
        }

        .promise-text {
            font-size: 1.1rem !important;
            color: #fff !important;
            font-weight: 500;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8 !important;
        }
    </style>
</head>
<body>

    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>
    <?php include 'includes/category_nav.php'; ?>

    <section class="hero-section">
        <div class="container">
            <h1>About Ishahiya</h1>
            <p>Where Tradition Meets Modern Elegance</p>
        </div>
    </section>

    <!-- Sections -->
    <section class="about-section">
        <div class="about-grid">
            
            <div class="section-card full-width">
                <h2 class="section-title">Who We Are</h2>
                <p>
                    At <strong>Ishahiya</strong>, we believe a woman can never have enough jewellery, shoes, and accessories—especially when those treasures are handcrafted with love and designed to add a touch of dazzle to everyday glamour.
                    Founded in 2022 by <strong>Bhavin Patel</strong>, Ishahiya was born with a simple vision: to bring the richness of Indian craftsmanship to women across the world.
                </p>
                <p>
                    Our elegant products reflect the diverse tastes, styles, and preferences of modern women while staying rooted in tradition.
                </p>
            </div>

            <div class="section-card">
                <h2 class="section-title">Our Journey</h2>
                <p>
                    With over a decade of hands-on experience in global trade and exports, Ishahiya has successfully bridged the gap between traditional Indian craftsmanship and international fashion demands.
                </p>
                <p>
                    Our story is one of resilience and vision. Starting with limited resources but unlimited determination, we overcame challenges and built a brand that empowers artisans, supports communities, and delights women across the globe.
                </p>
                <p>
                    Many of our clients have trusted us since our inception — a testament to the long-term relationships we’ve nurtured through quality and reliability.
                </p>
            </div>

            <div class="section-card">
                <h2 class="section-title">Our Mission</h2>
                <p>
                    We aim to be a one-stop destination for women seeking exquisite styles, trends, and unique handcrafted designs. By combining talent, quality, and style under one umbrella, Ishahiya makes fashion accessible without compromising authenticity.
                </p>
                <p>
                    Our mission goes beyond fashion—we empower artisans, support women in business, and provide global exposure to local craftsmanship.
                </p>
            </div>

            <div class="section-card full-width">
                <h2 class="section-title">Our Expertise</h2>
                <ul class="custom-list">
                    <li>Handcrafted Clothing – from traditional embroidery to contemporary designer wear.</li>
                    <li>Ethnic & Festive Wear – lehengas, sarees, kurtas, and fusion styles for every occasion.</li>
                    <li>Everyday Essentials – comfortable, stylish outfits crafted with premium fabrics.</li>
                    <li>Custom Collections – curated designs tailored for global markets and brand-specific needs.</li>
                    <li>Handcrafted Jewellery – from traditional mirror work to modern statement pieces.</li>
                    <li>Designer Footwear – unique, comfortable, and crafted by skilled Indian artisans.</li>
                    <li>Fashion Accessories – stylish additions that complete every look.</li>
                </ul>
            </div>

            <div class="section-card full-width text-center" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
                <h2 class="section-title" style="margin-bottom: 15px;">Our Promise</h2>
                <p class="promise-text">
                    <i class="fas fa-check-circle mr-2" style="color: var(--gold);"></i> At Ishahiya, we don’t just sell products—we share stories, preserve heritage, and create experiences that empower women to shine, wherever they are in the world.
                </p>
            </div>

        </div>
    </section>

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
