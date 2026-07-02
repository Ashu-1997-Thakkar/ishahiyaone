<?php
error_reporting(0);
session_start(); // ✅ Needed for captcha persistence

// Only generate captcha if not already set (avoid regenerating after submission)
if (!isset($_SESSION['captcha_question'])) {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);

    // choose random operator
    $operators = ['+', '-', '*'];
    $operator = $operators[array_rand($operators)];

    // calculate result & store in session
    $captcha_result = 0;
    switch ($operator) {
        case '+': $captcha_result = $num1 + $num2; break;
        case '-': $captcha_result = $num1 - $num2; break;
        case '*': $captcha_result = $num1 * $num2; break;
    }

    $_SESSION['captcha'] = $captcha_result;
    $_SESSION['captcha_question'] = "$num1 $operator $num2";
}

// Fetch wishlist and cart counts from session (or database)
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
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--dark-bg);
      color: #e6e6e6;
      margin: 0;
      padding: 0;
    }


    /* ================= CONTACT HERO ================= */
    .hero-section {
      background: #000;
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }

    .hero-section h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .hero-section p {
      font-size: 1.2rem;
      color: #ffffff;
      font-style: italic;
      opacity: 0.9;
    }

    /* ================= FORM SECTION ================= */
    .form-section {
      padding: 80px 20px;
      max-width: 700px;
      margin: auto;
    }

    .contact-card {
      background: var(--card-bg);
      padding: 40px;
      border-radius: 15px;
      border: 1px solid #222;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    label {
      font-weight: 600;
      color: var(--gold);
      margin-bottom: 8px;
      display: block;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 1px;
    }

    input, textarea {
      background: #000 !important;
      border: 1px solid #333 !important;
      color: #fff !important;
      padding: 12px 15px !important;
      border-radius: 8px !important;
      width: 100%;
      margin-bottom: 25px;
      transition: 0.3s;
    }

    input:focus, textarea:focus {
      border-color: var(--gold) !important;
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.2) !important;
      outline: none;
    }

    .btn-submit {
      background-color: var(--gold);
      color: #000;
      font-weight: 700;
      padding: 14px 30px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background-color: #fff;
      transform: translateY(-2px);
    }

    /* ================= FOOTER ================= */
    .footer {
        background: #050505;
        padding: 40px 0;
        border-top: 1px solid #222;
        text-align: center;
    }

    .footer p {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin: 0;
    }
  </style>
</head>
<body>

    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>
    <?php include 'includes/category_nav.php'; ?>

  <section class="hero-section">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you</p>
    </div>
  </section>

  <!-- Inquiry Form Section -->
  <section class="form-section">
    <div class="contact-card">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="process_inquiry.php" method="post">
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your full name" autocomplete="name" required>
                </div>
                <div class="col-md-6">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Your email address" autocomplete="email" required>
                </div>
            </div>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Your phone number" autocomplete="tel">

            <label for="message">Your Inquiry</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your inquiry here..." required></textarea>

            <!-- ✅ Fixed CAPTCHA with Refresh -->
            <label for="captcha_answer">
                Solve this: <span id="captcha-question"><?php echo $_SESSION['captcha_question']; ?></span> = ?
                <i class="fas fa-sync-alt" id="refresh-captcha" style="cursor: pointer; color: var(--gold); margin-left: 10px; font-size: 0.9rem;" title="Refresh Question"></i>
            </label>
            <input type="text" id="captcha_answer" name="captcha_answer" required autocomplete="off">

            <button type="submit" class="btn-submit">Submit Inquiry</button>
        </form>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>

  <script>
    document.getElementById('refresh-captcha').addEventListener('click', function() {
        const icon = this;
        icon.classList.add('fa-spin'); // Add spin animation

        fetch('refresh_captcha.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('captcha-question').innerText = data;
                document.getElementById('captcha_answer').value = ''; // Clear answer field
                setTimeout(() => icon.classList.remove('fa-spin'), 500); // Stop spinning
            })
            .catch(err => {
                console.error('Error refreshing captcha:', err);
                icon.classList.remove('fa-spin');
            });
    });
  </script>
</body>
</html>
