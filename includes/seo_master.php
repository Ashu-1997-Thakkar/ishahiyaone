<?php
/**
 * IshahiyaOne SEO Master Helper
 * Dynamically generates meta tags, social share tags, and schema markup.
 */

$current_page = basename($_SERVER['PHP_SELF']);
$site_name = "IshahiyaOne";
$domain = "https://ishahiyaone.shop"; // Update with actual domain if different

// Default SEO values
$seo_title = "IshahiyaOne - Premium Online Fashion Store | Navratri & Festival Collections";
$seo_desc = "Discover high-end fashion, Navratri specials, and exclusive festival offers at IshahiyaOne. Shop the latest trends in men's and women's ethnic and modern wear.";
$seo_keywords = "IshahiyaOne, online shopping India, Navratri collection, festival fashion, men's ethnic wear, women's designer wear, trending outfits";
$canonical_url = $domain . "/" . $current_page;
$og_image = $domain . "/image/logo/ishahiya-logo.png";
$seo_robots = "index, follow";

// Page-specific overrides
switch ($current_page) {
    case 'index.php':
        $seo_title = "IshahiyaOne | Best Online Fashion Store - Navratri Specials & Deals";
        break;
    case 'shop.php':
        $seo_title = "Shop Latest Collections - IshahiyaOne Online Store";
        $seo_desc = "Browse our full range of products. From traditional Navratri outfits to modern essentials, find everything at IshahiyaOne.";
        break;
    case 'about.php':
    case 'About.php':
        $seo_title = "About Us | The Story of IshahiyaOne Fashion";
        $seo_desc = "Learn about IshahiyaOne's journey in bringing premium fashion to your doorstep. Our commitment to quality and style.";
        break;
    case 'contact.php':
        $seo_title = "Contact IshahiyaOne - Customer Support & Inquiries";
        $seo_desc = "Get in touch with IshahiyaOne for any orders, inquiries, or support. We are here to help you 24/7.";
        break;
    case 'cart.php':
        $seo_title = "Your Shopping Cart | IshahiyaOne";
        $seo_desc = "Review your selected fashion items and proceed to a secure checkout at IshahiyaOne.";
        break;
    case 'checkout.php':
        $seo_title = "Secure Checkout | IshahiyaOne - Fashion Delivered";
        $seo_desc = "Enter your delivery details and choose your preferred payment method for a seamless shopping experience.";
        break;
    case 'collections.php':
        $seo_title = "Our Exclusive Collections | IshahiyaOne Premium Wear";
        $seo_desc = "Explore curated collections of the finest ethnic and contemporary wear at IshahiyaOne.";
        break;
    case 'track-order.php':
        $seo_title = "Track Your Order | IshahiyaOne";
        $seo_desc = "Track your order status securely on IshahiyaOne.";
        $seo_robots = "noindex, nofollow";
        break;
    case 'drt.php':
        // Product Detail page - title is usually set dynamically in the page
        if (isset($product_name)) {
            $seo_title = htmlspecialchars($product_name) . " | IshahiyaOne Fashion";
            $seo_desc = "Buy " . htmlspecialchars($product_name) . " online at IshahiyaOne. High-quality fabric and stunning design.";
        }
        break;
}

// Ensure title is set
if (!isset($seo_title)) {
    $seo_title = "IshahiyaOne - Style & Elegance";
}

?>

<!-- ✅ Primary Meta Tags -->
<title><?php echo $seo_title; ?></title>
<meta name="title" content="<?php echo $seo_title; ?>">
<meta name="description" content="<?php echo $seo_desc; ?>">
<meta name="keywords" content="<?php echo $seo_keywords; ?>">
<meta name="author" content="IshahiyaOne">
<link rel="canonical" href="<?php echo $canonical_url; ?>">
<link rel="icon" type="image/png" sizes="32x32" href="image/logo/ishahiya-logo.png">
<link rel="icon" type="image/png" sizes="16x16" href="image/logo/ishahiya-logo.png">
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

<!-- ✅ Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $canonical_url; ?>">
<meta property="og:title" content="<?php echo $seo_title; ?>">
<meta property="og:description" content="<?php echo $seo_desc; ?>">
<meta property="og:image" content="<?php echo $og_image; ?>">

<!-- ✅ Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo $canonical_url; ?>">
<meta property="twitter:title" content="<?php echo $seo_title; ?>">
<meta property="twitter:description" content="<?php echo $seo_desc; ?>">
<meta property="twitter:image" content="<?php echo $og_image; ?>">

<!-- ✅ General Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<meta name="robots" content="<?php echo $seo_robots; ?>">
<meta name="theme-color" content="#d4af37">

<!-- ✅ Schema.org JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "IshahiyaOne",
  "url": "<?php echo $domain; ?>",
  "logo": "<?php echo $domain; ?>/logo.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91-9974328904",
    "contactType": "customer service",
    "areaServed": "IN",
    "availableLanguage": "en"
  },
  "sameAs": [
    "https://facebook.com/ishahiyaone",
    "https://instagram.com/ishahiyaone"
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "url": "<?php echo $domain; ?>",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?php echo $domain; ?>/search.php?s={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

<!-- ✅ Base URL for Clean URL Asset Resolution -->
<?php
$base_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($base_dir === '/' || $base_dir === '\\') {
    $base_dir = '/';
} else {
    $base_dir = rtrim($base_dir, '/') . '/';
}
?>
<base href="<?php echo htmlspecialchars($base_dir); ?>">
