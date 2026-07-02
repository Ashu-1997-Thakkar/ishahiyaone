-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 28, 2025 at 03:41 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `velvetvougedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `role`) VALUES
(7, 'admin', 'GopaL@1437', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `all_category`
--

DROP TABLE IF EXISTS `all_category`;
CREATE TABLE IF NOT EXISTS `all_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `Image1` varchar(100) DEFAULT NULL,
  `Image2` varchar(100) DEFAULT NULL,
  `Image3` varchar(100) DEFAULT NULL,
  `Image4` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `all_category`
--

INSERT INTO `all_category` (`id`, `name`, `description`, `price`, `category`, `brand`, `Image1`, `Image2`, `Image3`, `Image4`, `created_at`, `updated_at`) VALUES
(1, 'Ashutosh', 'sdfsdf', 5877.00, 'men', 'IshaHiya', '1753706693_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', '1753706693_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-3.jpg', '1753706693_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', '1753706693_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-1(760).jpg', '2025-07-28 12:44:53', '2025-07-28 12:44:53'),
(2, 'asdsad', 'sdfsdf', 34353.00, 'Women', 'IshaHiya', '1753706861_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', '1753706861_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-3.jpg', '1753706861_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-2.jpg', '1753706861_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', '2025-07-28 12:47:41', '2025-07-28 12:47:41'),
(3, 'fsdf', 'sdfsdf', 0.00, 'Kids', 'sdfsdf', '1753706889_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-1(760).jpg', '1753706889_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', '1753706889_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-3.jpg', '1753706889_Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-2.jpg', '2025-07-28 12:48:09', '2025-07-28 12:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `best_products`
--

DROP TABLE IF EXISTS `best_products`;
CREATE TABLE IF NOT EXISTS `best_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(50, 'Kids-Kurtis'),
(52, 'Women-Kurtis'),
(53, 'Navratri Couple Kediya Dhoti Set'),
(54, 'Sarees'),
(56, 'Women-Salwar Suits'),
(57, 'Kids-Salwar Suits'),
(58, 'Women-lehenga Choli'),
(59, 'kids-lehenga Choli'),
(61, 'Women-Gown Dress'),
(63, 'women-A-Line Dress'),
(64, 'wedding special'),
(67, 'Kids-t-shirt'),
(68, 'women-t-shirt'),
(69, 'Women-Gown'),
(72, 'Women-Jumpsuit'),
(73, 'Girl-Jumpsuit'),
(74, 'Palazzo Dress Indo Western Style'),
(75, 'Women-Kurti Palazo'),
(76, 'Girls-Kurti Palazo'),
(77, 'Girls-Kurtis'),
(78, 'Girls-Salwar Suits'),
(79, 'Girls-lehenga Choli'),
(80, 'Girls-Gown Dress'),
(81, 'Boys-t-shirt'),
(82, 'Girls-Gown'),
(83, 'Boys-Kurta Pajama'),
(84, 'Women-Gown Style Salwar Suits'),
(85, 'Boys-Shirt'),
(86, 'Girls-Shirt'),
(87, 'women-Co-Ord Set'),
(88, 'Girls-Co-Ord Set'),
(89, 'Boys-Denim Jeans'),
(90, 'Girls-Denim Jeans'),
(92, 'Women-Floral Print Tiered Dress'),
(93, 'Girls-Floral Print Tiered Dress'),
(94, 'Dandiya Couple Set Combo'),
(95, 'Ethnic Festival Couple Dress Set Combo'),
(97, 'Men and Women Couple Combo'),
(98, 'Couple Combo Lehenga Choli Collection'),
(99, 'Cotton Couple Set for Work, Travel & Beyond');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_name` varchar(150) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `image`, `product_name`, `category_name`, `category_id`) VALUES
(99, 'Royal Purple Roman Silk Gown-1(1599).jpg', 'Gown', 'Women-Gown', NULL),
(101, 'img_6881389e3cf909.61370063.jpg', 'Traditional Garba Lehenga Choli', 'Women-lehenga Choli', NULL),
(102, 'Printed Rayon Calf-Length Asymmetrical Sleeveless Round-Neck Co-ord Set-1(1000).jpg', 'Sleeveless Round-Neck Co-ord Set', 'women-Co-Ord Set', NULL),
(103, 'Palazzo Dress Indo Western Style-1(1349).jpg', 'Palazzo Dress Indo Western Style', 'Palazzo Dress Indo Western Style', NULL),
(105, 'Couples Mirror Work Kediya and Kurta Combo-1(2000).jpg', 'Dandiya Couple Set Lehenga & Kurta Combo', 'Dandiya Couple Set Combo', NULL),
(107, 'Gamthi Embroidery Lehenga & Contrast Kurta Combo-1(2500).jpg', 'Gamthi Embroidery Lehenga & Contrast Kurta Combo', 'Ethnic Festival Couple Dress Set Combo', NULL),
(108, 'Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree .jpg', 'Saree Kurta and Jacket Combo', 'Men and Women Couple Combo', NULL),
(109, 'Heavy Georgette Fabric Couple Combo Lehenga Choli Collection-1.jpg', 'Heavy Georgette Fabric Couple Combo', 'Couple Combo Lehenga Choli Collection', NULL),
(111, 'Sky Blue Cotton Couple Set.jpg', 'Cotton Couple Set', 'Cotton Couple Set for Work, Travel & Beyond', NULL),
(112, 'Gamthi Work Navratri Wear Nayra Cut Kurti-3.jpg', 'Gamthi Work Navratri Wear Nayra Cut Kurti', 'Girls-Kurtis', NULL),
(118, 'img_688138d3d62e64.07374484.jpg', 'Sarees Collection', 'Sarees', 54),
(127, 'WhatsApp Image 2025-07-25 at 12.09.58 PM (1).jpeg', 'Pure roman silk chaniya choli with heavy vintage duptta', 'Women-lehenga Choli', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(6, 'anone', 'admin@gmail.com', 'good site ', '2024-11-28 12:13:38'),
(7, 'Bhav', 'bhavinpatel323@yahoo.com', 'Test ', '2025-07-13 06:16:13'),
(8, 'Bhav', 'bhavinpatel323@yahoo.com', 'Test ', '2025-07-13 06:44:59'),
(9, 'Nishant Sharma', 'nishant.developer22@gmail.com', 'Hello team, \"ishahiya.com\"\r\n\r\nI have almost a decade of experience & specialize in content strategy planning, link building & SEO strategy.\r\n\r\nOur main focus will be to help generate more sales & online traffic.\r\n\r\nWe can place your website on Google\'s 1st page.\r\n\r\nPlease respond with your phone number, so we can schedule a follow-up call for you within 24 hours. I\'d be glad to go over our plan with you.\r\n\r\n\r\nThank you,\r\n\r\nNishant Sharma\r\n\r\n', '2025-07-28 08:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Contact` int NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` int NOT NULL DEFAULT '0',
  `pay_status` int NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `address`, `Contact`, `order_date`, `order_status`, `pay_status`, `total_price`) VALUES
(20, 'hahios', 'dasdxazc', 213123, '2024-11-29 11:37:09', 0, 0, 23.00),
(21, 'hahios', 'adsfasd', 12345678, '2024-11-29 11:39:16', 0, 0, 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `size`, `quantity`, `price`, `image`) VALUES
(29, 20, 'shoe', 'XS', 2, 23.00, 'product-2-1.jpg'),
(30, 21, 'shoe', 'XS', 1, 23.00, 'product-2-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rating` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category_id` int DEFAULT NULL,
  `image2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_best_product` tinyint(1) DEFAULT '0',
  `is_best` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `brand`, `description`, `price`, `image`, `rating`, `created_at`, `category_id`, `image2`, `image3`, `image4`, `is_best_product`, `is_best`) VALUES
(47, 'Pure Cotton Printed Lehenga Choli with Gota Kodi Work', 'IshaHiya', 'Product Description – IshaHiya5121 Step into elegance with our beautifully crafted Pure Cotton Lehenga Choli Set — a perfect blend of traditional charm and modern comfort. Designed for festive moments, Garba nights, and cultural celebrations, this set features intricate gota and cowrie (kodi) work, vibrant prints, and a grand 6-meter flair to make every twirl unforgettable.Structured Product DetailsLehenga (Stitched)Fabric: Pure CottonWork: Plain & Printed DesignWaist Size: Supports up to 42 InchesClosure: Side Zip with DrawstringLength: 42 InchesFlair: 6 Meters for a Voluminous LookInner: Full Micro Inner with Canvas Support for Shape & ComfortBlouse (Stitched)Fabric: Pure Cotton (Fully Stitched)Work: Printed & Plain Pattern with Gota and Kodi (Cowrie) Touch-upSize: 38 Inches (Alterable up to 42 Inches)Length: 15 InchesDupattaFabric: Pure CottonWork: Printed Patch with Gota & Cowrie (Kodi) Work on BordersLength: 2.35 MetersThis set is thoughtfully designed for women who love grace and tradition wrapped in breathable, soft cotton. Pair it with ethnic jewelry and comfortable juttis to complete the festive look.', 3200.00, '668994.jpg', 0, '2025-07-20 20:14:59', 58, NULL, NULL, NULL, 0, 1),
(48, 'Navratri Special Rayon Chaniya Choli with Latkan Dupatta ', 'IshaHiya', 'Product Details\r\nIshaHiya5104\r\n\r\nStep into elegance with this beautifully crafted Reyon Cotton Lehenga Choli Set, featuring vibrant digital prints and premium detailing. The lehenga comes fully stitched with 3.2 meters flair, canvas and cancan for structure, and is paired with an unstitched matching choli and stylish dupatta. A perfect festive wear choice for Garba, Navratri, and cultural celebrations.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Stitched)\r\n\r\nFabric: Heavy Rayon Cotton\r\n\r\nWork: Fancy Digital Print\r\n\r\nInner: Micro Cotton\r\n\r\nLength & Waist: 42 Inches\r\n\r\nFlair: 3.2 Meters\r\n\r\nSupport: 6-Inch Cancan & 3-Inch Canvas for Voluminous Look\r\n\r\nCholi (Unstitched)\r\n\r\nFabric: Heavy Rayon Cotton\r\n\r\nWork: Matching Fancy Digital Print\r\n\r\nSize: 1 Meter Fabric\r\n\r\nType: Unstitched (Tailorable to All Sizes)\r\n\r\nDupatta\r\n\r\nFabric: Heavy Rayon Cotton\r\n\r\nWork: Fancy Digital Print with Heavy Thread Latkans on Both Sides and Samosa Lace Borders\r\n\r\nLength: 2.20 Meters\r\n\r\nWidth: 42 Inches', 1200.00, 'Navratri Special Rayon Chaniya Choli-1(949).jpg', 0, '2025-07-20 20:35:11', 58, NULL, NULL, NULL, 0, 1),
(49, 'Green Printed Cotton Navratri Lehenga Choli With Dupatta', 'IshaHiya', ' IshaHiya5275 – Pure Cotton Lehenga Choli Set💰 Price: ₹3,500/-🌀 Flair: 6 Meters📦 GST: 12% Extra🚚 Shipping: Extra✔️ Availability: Single Piece AvailableNew Arrivals Are Live – Best Designs, Ready to Book!✨ Product DescriptionCelebrate tradition with elegance in our IshaHiya5275 Lehenga Choli Set, crafted in pure cotton for breathability, comfort, and festive flair. Designed with gota and cowrie (kodi) accents, this vibrant set brings a timeless ethnic look to life — perfect for Navratri, weddings, cultural functions, and festive gatherings.📝 Product DetailsLehenga (Stitched)Fabric: Pure CottonWork: Plain & Printed DesignWaist Size: Supports up to 42 InchesClosure: Drawstring with Side ZipLength: 42 InchesFlair: 6 Meters (Grand Volume)Inner: Full Micro Inner with Canvas SupportStitching: Stitched and Ready to WearBlouse (Stitched)Fabric: Pure Cotton (Fully Stitched)Work: Plain and Printed with Gota & Cowrie (Kodi) Touch-upSize: 38 Inches (with extra margin to alter up to 42 Inches)Length: 15 InchesDupattaFabric: Pure CottonWork: Plain with Printed Patch, Gota & Cowrie (Kodi) Touch-upLength: 2.35 Meters📦 Package Contains1 Lehenga, 1 Blouse, 1 Dupatta⚖️ Weight:2.00 Kg (Approx.)Add grace to your wardrobe with this beautifully designed lehenga choli set. Book yours now — limited stock available!', 3500.00, 'reen Printed Cotton Navratri Lehenga Choli With Dupatta-1.jpg', 0, '2025-07-20 20:44:28', 58, NULL, NULL, NULL, 0, 0),
(50, 'Tissue Silk Lehenga Choli Set', 'IshaHiya', 'IshaHiya5425 – Designer Tissue Silk Lehenga Choli Set💰 Price: ₹2,000/- + GST🚚 Shipping: Extra🎨 Colors Available: 6 Stunning Options📢 Exclusive Bulk Rates for Resellers & Exporters👗 Choli (Blouse)Fabric: Pure Tissue SilkWork: Heavy Embroidery with Sequins & Thread WorkInner: Micro CottonSize Options:M (with margin up to L)XL (with margin up to 2XL)(Fully Stitched & Adjustable)👗 Lehenga (Skirt)Fabric: Pure Tissue SilkInner: Micro CottonWork: Heavy Embroidery with Sequins & Thread WorkFlair: 3.5 MetersFeatures:Attached Canvas & Cancan for volumeAdjustable Drawstring (Dori)Size: Supports up to 44 Inches(Fully Stitched and Ready to Wear)👗 DupattaFabric: Pure Bandhej GeorgetteSize: 2.30 MetersWork: Heavy Embroidery with 7mm Sequins & Thread Work📦 Package Includes1 Stitched Lehenga, 1 Stitched Choli, 1 Embroidered Dupatta⚖️ Weight: Approx. 1.50 Kg✨ Perfect for weddings, festive events, and traditional occasions.📦 Single orders welcome – Bulk inquiries encouraged!🛒 “New Looks, Premium Feels – Reserve Your Favorite Color Today!”', 2000.00, 'WhatsApp Image 2025-07-19 at 2.14.10 PM (1).jpeg', 0, '2025-07-20 20:52:26', 58, NULL, NULL, NULL, 0, 1),
(51, 'Vichitra Silk Lehenga with Patchwork Border', 'IshaHiya', ' IshaHiya5426 – Vichitra Silk Lehenga Choli Set\r\nStep into timeless elegance with this luxurious 3-piece Lehenga Choli set crafted from rich Vichitra Silk and adorned with exquisite coding embroidery and sequin work. Ideal for weddings, receptions, festive occasions, and ethnic parties, this ensemble offers a complete look that’s stylish, graceful, and sophisticated.\r\n\r\n📝 Product Details\r\n👗 Blouse (Fully Stitched)\r\nFabric: Vichitra Silk\r\n\r\nWork: Intricate Coding Embroidery with Sequin Detailing\r\n\r\nFeatures: Built-in Cups | Zipper Closure\r\n\r\nNeckline & Sleeves: Round Neck | Full Sleeves\r\n\r\nLength: 15 Inches\r\n\r\nSizes Available: M (38), L (40), XL (42), XXL (44)\r\n\r\n🌀 Lehenga\r\nFabric: Vichitra Silk\r\n\r\nWork: Coordinated Coding Embroidery with Sequin Highlights\r\n\r\nWaist Size: Supports up to 42 Inches\r\n\r\nLength: 42 Inches\r\n\r\nFlair: 3 Meters\r\n\r\nStitching: Semi-Stitched with Margin for Adjustment\r\n\r\n🧣 Dupatta\r\nFabric: Vichitra Silk\r\n\r\nWork: Matching Sequins Embroidery with Coding Accents\r\n\r\nLength: 2.2 Meters\r\n\r\n📦 Package Includes\r\n1 Fully Stitched Blouse, 1 Semi-Stitched Lehenga, 1 Embroidered Dupatta\r\n\r\nMake every celebration a statement in style. With a graceful drape, beautiful craftsmanship, and easy-to-alter fit, IshaHiya5426 is the perfect blend of tradition and modern flair.\r\n\r\n🛍️ Limited stock — book yours now!\r\n\r\n', 1550.00, 'Vichitra Silk Lehenga with Patchwork-4.jpg', 0, '2025-07-20 21:00:45', 58, NULL, NULL, NULL, 0, 1),
(52, 'Pure roman silk chaniya choli with heavy vintage duptta', 'IshaHiya', 'IshaHiya6130  Pure roman silk chaniya choli with heavy vintage duptta 🔥  Dno :- Chandrakala   6 MTR flare L size  41 waist  41 length   L size blouse with margin Blouse with heavy pads', 1850.00, '14993.jpeg', 0, '2025-07-20 21:21:43', 58, NULL, NULL, NULL, 0, 0),
(54, 'Gamthi Work Navratri Lehenga Choli Multi Color Cotton', 'IshaHiya', 'Product Details\r\nIshaHiya5763\r\n\r\nStep into timeless elegance with this Pure Cotton Lehenga Choli Set, highlighted by traditional Gamathi mirror work on the choli and a 6-meter flair lehenga with rich ribbon work and lumpi lace borders. A fully stitched ensemble designed for festive occasions and cultural celebrations.\r\n\r\nProduct Details:\r\n\r\nCholi (Fully Stitched):\r\n\r\nFabric: Pure Cotton\r\n\r\nWork: Heavy Gamathi Embroidery with Real Mirror Work\r\n\r\nInner: Micro Cotton\r\n\r\nSize:\r\n\r\nM (up to L margin)\r\n\r\nXL (up to 2XL margin)\r\n\r\nLehenga (Fully Stitched):\r\n\r\nFabric: Pure Cotton\r\n\r\nWork: Heavy Ribbon Embroidery with Lumpi Lace Border\r\n\r\nInner: Micro Cotton\r\n\r\nFlair: 6 Meters\r\n\r\nSize: Up to 44', 2250.00, 'Multi-Color Cotton Gamthi Work Navratri Wear Lehenga Choli-2.jpg', 0, '2025-07-23 01:48:28', 58, NULL, NULL, NULL, 0, 1),
(55, 'Muslin Mirror Work Lehenga Choli', 'IshaHiya', 'Product Details\r\nIshaHiya6025\r\n\r\nElevate your festive collection with this premium Heavy Muslin Cotton Lehenga Set, crafted with rich digital prints and real mirror work. The lehenga features a grand 4-meter flair, micro inner lining, and a canvas patta border for a voluminous silhouette. Paired with an unstitched muslin choli and a fancy digital print dupatta, this ensemble is ideal for weddings, functions, and festive occasions.\r\n\r\nLehenga Details\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Beautiful Digital Print & Real Mirror Work\r\n\r\nInner: Micro Cotton\r\n\r\nLength: 42-44 Inches\r\n\r\nFlair: 4 Meters\r\n\r\nSize: Fully Stitched Free Size Up to XXL (44) with Canvas Patta\r\n\r\nCholi Details\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print & Real Mirror Work\r\n\r\nType: Unstitched (1 Meter Fabric)\r\n\r\nDupatta Details\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Fancy Digital Print with Real Mirror Work\r\n\r\nLength: 2.20 Meters', 1799.00, 'Muslin Mirror Work Lehenga Choli With Embroidered Blouse-1(1449).jpg', 0, '2025-07-24 20:16:38', 58, NULL, NULL, NULL, 0, 0),
(57, 'Ashutosh', NULL, 'dqwd', 5877.00, 'Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', 5, '2025-07-28 11:53:48', 0, NULL, NULL, NULL, 0, 0),
(58, 'Ashutosh', NULL, 'dqwd', 5877.00, 'Cotton Calf Length Printed Flared Sleeveless Sweetheart Neck Dress-4.jpg', 5, '2025-07-28 11:53:55', 0, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_size_variation`
--

DROP TABLE IF EXISTS `product_size_variation`;
CREATE TABLE IF NOT EXISTS `product_size_variation` (
  `variation_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `size_id` int NOT NULL,
  `quantity_in_stock` int NOT NULL,
  PRIMARY KEY (`variation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_size_variation`
--

INSERT INTO `product_size_variation` (`variation_id`, `product_id`, `size_id`, `quantity_in_stock`) VALUES
(28, 0, 1, 13),
(29, 7, 0, 4),
(30, 6, 1, 5),
(31, 7, 2, 4),
(32, 9, 3, 3),
(35, 17, 1, 1),
(37, 17, 2, 1),
(38, 17, 3, 1),
(43, 18, 0, 1),
(44, 47, 7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `size_id` int NOT NULL AUTO_INCREMENT,
  `size_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`) VALUES
(1, 's'),
(2, 'L'),
(3, 'M'),
(7, 'FREE SIZE');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS `subcategory`;
CREATE TABLE IF NOT EXISTS `subcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `collection_id` int DEFAULT NULL,
  `subcategory_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `brand` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(225) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_id` (`collection_id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`id`, `collection_id`, `subcategory_name`, `brand`, `price`, `description`, `image1`, `image2`, `image3`, `image4`, `category_name`) VALUES
(23, 102, 'Printed 2-Piece Co-Ord Set', 'IshaHiya', 350.00, 'Product Description\r\nStep into timeless elegance with our beautifully crafted Kurta Pant Set, designed to deliver style, comfort, and sophistication in one perfect package. Whether for festive occasions or casual outings, this outfit is a must-have in every modern wardrobe.\r\n\r\nProduct Details\r\nPackage Contains:\r\n1 Kurta & 1 Pants\r\n\r\nFabric:\r\nPremium Fabric (Recommended: Dry Clean Only)\r\n\r\nWash Care:\r\nDry Clean for best results and fabric longevity\r\n\r\nThis set is perfect for those who appreciate refined fashion with minimal effort. Pair it with traditional footwear and accessories to complete your look.', 'uploads/subcategories/1753122101_Printed_2-Piece_Co-Ord_Set-1(599).jpg', 'uploads/subcategories/1752958508_Printed 2-Piece Co-Ord Set-1(599).jpg', 'uploads/subcategories/1752958508_Printed 2-Piece Co-Ord Set-2.jpg', 'uploads/subcategories/1752958508_Printed 2-Piece Co-Ord Set-4.jpg', NULL),
(45, 107, 'Gamthi Embroidery Lehenga & Contrast Kurta Combo', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya3567\r\n\r\nElevate your ethnic collection with this Designer Gamthi Work Lehenga Choli Set, crafted in premium Maslin and Rayon fabrics. Featuring a full-stitched lehenga with 5-meter flair, a beautifully embroidered Rayon blouse with padding, and a printed Georgette dupatta with mirror lace. The set includes an off-white digital print Kurta, making it a versatile combo for festive and wedding wear.\r\n\r\nComplete Product Specification\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Maslin / Rayon\r\n\r\nInner: Crape\r\n\r\nColor: Maroon\r\n\r\nWork: Heavy Print + Gamthi Work Lace + Patch\r\n\r\nLength: 42\"\r\n\r\nSize: 42\"\r\n\r\nFlair: 5 Meters\r\n\r\nOpening: Side Zipper + Dori (Naadi)\r\n\r\nBlouse (Fully Stitched & Padded)\r\n\r\nFabric: Rayon\r\n\r\nInner: Cotton\r\n\r\nSize: L (Chest 40\") – With Extra Margin\r\n\r\nLength: 15\"\r\n\r\nSleeve Length: 10\"\r\n\r\nWork: Gamthi Patch + Thread & Sequence Work\r\n\r\nBack Style: Dori Tie-Up\r\n\r\nColor: Black\r\n\r\nDupatta\r\n\r\nFabric: Heavy Georgette\r\n\r\nLength: 2.25 Meters\r\n\r\nWork: Heavy Print + Mirror Lace Border\r\n\r\nColor: Maroon\r\n\r\nKurta\r\n\r\nFabric: Maslin\r\n\r\nColor: Off White\r\n\r\nWork: Digital Print\r\n\r\nSize: M, XL (With Extra Loosening)\r\n\r\nLength: 40\"\r\n\r\nSleeve Length: 25\"', 'uploads/subcategories/1753122905_Gamthi_Embroidery_Lehenga_&_Contrast_Kurta_Combo-1-4.jpg', 'uploads/subcategories/1753008014_Gamthi Embroidery Lehenga & Contrast Kurta Combo-004.jpg', 'uploads/subcategories/1753008014_Gamthi Embroidery Lehenga & Contrast Kurta Combo-002.jpg', 'uploads/subcategories/1753008014_Gamthi Embroidery Lehenga & Contrast Kurta Combo-1-3.jpg', NULL),
(22, 102, 'Sleeveless Round-Neck Co-ord Set', 'IshaHiya', 600.00, 'PProduct Description\r\nRefresh your everyday wardrobe with this effortlessly stylish Top & Bottom Set, crafted from 100% rayon for a soft, breathable feel and easy movement. Designed for a relaxed yet polished look, this outfit is perfect for casual days where comfort meets charm.\r\n\r\nProduct Details\r\nPackage Contains:\r\n1 Top & 1 Bottom\r\n\r\nFabric:\r\n100% Rayon – lightweight, smooth, and ideal for all-day wear\r\n\r\nWash Care:\r\nMachine Wash – easy to clean and maintain\r\n\r\nSize Worn by Model:\r\nSmall (S)\r\n\r\nMood:\r\nCasual – perfect for everyday outings, meet-ups, or lounging in style\r\n\r\nDisclaimer:\r\nThere might be slight variation in the actual color of the product due to different screen resolutions.\r\n\r\nThis set is designed to blend comfort with understated elegance. Pair it with your favorite sneakers or flats and step out with confidence.', 'uploads/subcategories/1752958017_Printed Rayon Calf-Length Asymmetrical Sleeveless Round-Neck Co-ord Set-1(1000).jpg', 'uploads/subcategories/1752958017_Printed Rayon Calf-Length Asymmetrical Sleeveless Round-Neck Co-ord Set-2.jpg', 'uploads/subcategories/1752958017_Printed Rayon Calf-Length Asymmetrical Sleeveless Round-Neck Co-ord Set-3.jpg', 'uploads/subcategories/1752958017_Printed Rayon Calf-Length Asymmetrical Sleeveless Round-Neck Co-ord Set-4.jpg', NULL),
(11, 99, 'Orange Chinnon Silk Party Gown ', 'IshaHiya', 3000.00, 'Fabric & Product Details\r\n\r\nTop Fabric: Chinnon Silk (Free Size Stitch – XL)\r\n\r\nDupatta Fabric: Matching Chinnon Silk\r\n\r\nStitching: Ready-to-Wear, Free Size (Fits XL)\r\n\r\nSize: XL\r\n\r\nAvailability: Single Single Available in All Designs', 'uploads/subcategories/1753123420_Aashirwad_Orange_Chinnon_Silk_Party_Gown(1700).jpg', 'uploads/subcategories/1752950728_Aashirwad Orange Chinnon Silk Party Gown-2.jpg', 'uploads/subcategories/1752950728_Aashirwad Orange Chinnon Silk Party Gown-3.jpg', 'uploads/subcategories/1753123554_Aashirwad_Orange_Chinnon_Silk_Party_Gown-4.jpg', NULL),
(12, 99, 'Gulnaaz Skirt Style Suit with Dupatta ', 'IshaHiya', 3000.00, 'IshaHiya5710\r\n\r\nIntroducing our latest free-size stitched collection, designed for effortless elegance and comfort in XL size. Made from premium real georgette, this stylish 3-piece set includes a flowy stitched skirt, a matching top, and a graceful dupatta. Ideal for daily wear, parties, and boutique reselling — now available at wholesale and manufacturer prices.\r\n\r\nFull Product Description :-\r\n\r\nCollection Type: Free Size Stitched Set (XL)\r\n\r\nTop Fabric: Real Georgette – Free Size Stitched\r\n\r\nBottom Fabric: Real Georgette Skirt – Free Size Stitched\r\n\r\nDupatta: Real Georgette – Lightweight & Elegant\r\n\r\nStyle: Ready to Wear, Comfortable Fit\r\n\r\nIdeal For: Casual Wear, Festive Wear, Boutique Reselling\r\n\r\nAvailable Size: XL (Free Size Stitch)\r\n\r\nPrice: Competitive – Wholesale & Manufacturer Rate Available', 'uploads/subcategories/1752951125_Aashirwad Gulnaaz Skirt Style Suit with Dupatta-1(3000).jpg', 'uploads/subcategories/1752951125_Aashirwad Gulnaaz Skirt Style Suit with Dupatta-2.jpg', 'uploads/subcategories/1752951125_Aashirwad Gulnaaz Skirt Style Suit with Dupatta-3.jpg', 'uploads/subcategories/1752951125_Aashirwad Gulnaaz Skirt Style Suit with Dupatta-4.jpg', NULL),
(13, 99, 'Real Chinon Skirt Style Suits', 'IshaHiya', 3500.00, 'Fabric Detail\r\nTop - Real Chinon\r\nDupatta- Real Chinon\r\nSkirt - Real Chinon\r\n\r\nSINGLE SINGLE AVAILABLE\r\nNote - Embroidery Work & Mirror Work', 'uploads/subcategories/1752951736_Gulkayra Designer Teal Blue Real Chinon Skirt Style Suits-1(3500).jpg', 'uploads/subcategories/1752951736_Gulkayra Designer Teal Blue Real Chinon Skirt Style Suits-2.jpg', 'uploads/subcategories/1752951736_Gulkayra Designer Teal Blue Real Chinon Skirt Style Suits-3.jpg', 'uploads/subcategories/1752951736_Gulkayra Designer Teal Blue Real Chinon Skirt Style Suits-4.jpg', NULL),
(14, 101, 'Traditional Garba Lehenga Choli in Multi Colors', 'IshaHiya', 4500.00, 'IshaHiya5008\r\n\r\nStep into grandeur with this premium lehenga choli set, crafted with a massive 9-meter flair and elegant stitched tailoring. Designed for comfort and royal appeal, it includes a customisable blouse and a 2.4-meter dupatta—perfect for weddings, sangeet, and festive occasions. Available in ready stock with reseller-friendly wholesale pricing.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Stitched)\r\n\r\nLength: 40 Inches\r\n\r\nWaist Size: Up to 42 Inches (Adjustable with drawstring)\r\n\r\nFlair: 9 Meters – Grand Ghera for Bridal & Partywear Look\r\n\r\nInner Lining: Included\r\n\r\nType: Fully Stitched\r\n\r\nCholi (Blouse – Stitched)\r\n\r\nSize: M (38 Inches)\r\n\r\nCustomisation: Alterable up to 44 Inches\r\n\r\nLength: 15 Inches\r\n\r\nType: Stitched with margin for adjustment\r\n\r\nDupatta\r\n\r\nLength: 2.40 Meters\r\n\r\nDesign: Coordinated to match lehenga set with festive detailing', 'uploads/subcategories/1752952923_Traditional Garba Lehenga Choli in Multi Colors-1(4299).jpg', 'uploads/subcategories/1752952923_Traditional Garba Lehenga Choli in Multi Colors-2.jpg', 'uploads/subcategories/1752952923_Traditional Garba Lehenga Choli in Multi Colors-3.jpg', 'uploads/subcategories/1752952923_Traditional Garba Lehenga Choli in Multi Colors-4.jpg', NULL),
(15, 101, 'Navratri Special Multicolor Muslin Lehenga with Real Mirror Touch', 'IshaHiya', 1700.00, 'IshaHiya3594\r\n\r\nGrace your festive occasions with this stunning Muslin Cotton Lehenga Set, featuring vibrant digital prints and real mirror work. The fully stitched lehenga comes with a canvas patta border and is paired with an unstitched choli and a beautiful matching dupatta. Ideal for parties and ethnic functions – now available at manufacturer and wholesale prices.\r\n\r\nComplete Fabric & Design Details\r\n\r\nLehenga:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work\r\n\r\nInner: Soft Micro Cotton\r\n\r\nFlair: 4 Meters\r\n\r\nLength: 43 Inches\r\n\r\nSize: Fully Stitched to XXL (44) with Canvas Patta Border\r\n\r\nCholi:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work & Lace Detailing\r\n\r\nSize: Unstitched (Can be tailored up to XXL+) with extra lace fabric\r\n\r\nDupatta:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work & Fancy Lace Borders\r\n\r\nLength: 2 Meters', 'uploads/subcategories/1752953413_Navratri Special Multicolor Muslin Lehenga-1(1499).jpg', 'uploads/subcategories/1752953413_Navratri Special Multicolor Muslin Lehenga-2.jpg', 'uploads/subcategories/1752953413_Navratri Special Multicolor Muslin Lehenga-3.jpg', 'uploads/subcategories/1752953413_Navratri Special Multicolor Muslin Lehenga-4.jpg', NULL),
(17, 101, 'Pure Cotton Printed Lehenga Choli with Gota Kodi Work ', 'IshaHiya', 3000.00, 'IshaHiya5121\r\n\r\nPresenting a charming Pure Cotton Lehenga Choli Set crafted with soft fabric, elegant print, and subtle traditional touch-ups. This stitched lehenga features a grand 6-meter flair, full inner lining, and a zip with drawstring closure. Accented with gota and cowrie (kodi) work, it\'s perfect for festive occasions, Garba nights, and cultural gatherings.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Stitched)\r\n\r\nFabric: Pure Cotton\r\n\r\nWork: Plain & Printed Design\r\n\r\nWaist Size: Supports up to 42 Inches\r\n\r\nClosure: Side Zip with Drawstring\r\n\r\nLength: 42 Inches\r\n\r\nFlair: 6 Meters for Full Volume\r\n\r\nInner: Full Micro Inner with Canvas Support\r\n\r\nBlouse (Stitched)\r\n\r\nFabric: Pure Cotton (Fully Stitched)\r\n\r\nWork: Printed & Plain Pattern with Gota and Kodi (Cowrie) Work Touchup\r\n\r\nSize: 38 Inches (Alterable up to 42 Inches)\r\n\r\nLength: 15 Inches\r\n\r\nDupatta\r\n\r\nFabric: Pure Cotton\r\n\r\nWork: Printed Patch with Gota & Cowrie (Kodi) Work Borders\r\n\r\nLength: 2.35 Meters', 'uploads/subcategories/1752953813_Cotton Printed Lehenga Choli -1(3000).jpg', 'uploads/subcategories/1752953813_Cotton Printed Lehenga Choli -2.jpg', 'uploads/subcategories/1752953813_Cotton Printed Lehenga Choli -3.jpg', 'uploads/subcategories/1752953813_Cotton Printed Lehenga Choli -4.jpg', NULL),
(18, 101, 'Muslin Mirror Work Lehenga Choli with Digital Prints ', 'IshaHiya', 2000.00, 'IshaHiya5091\r\n\r\nAdd elegance to your festive wardrobe with this stunning Muslin Cotton Lehenga Choli Set, featuring vibrant digital prints and detailed real mirror work. Designed with a 4-meter flair and stitched to fit up to XXL, it includes a canvas border finish and latkan-detailed dupatta. Ideal for Navratri, wedding functions, or boutique collections.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work & Gota Zari Lace Border\r\n\r\nInner: Micro Cotton\r\n\r\nFlair: 4 Meters\r\n\r\nLength: 43 Inches\r\n\r\nSize: Free Size – Stitched to Fit Up to XXL (44\")\r\n\r\nFinish: Canvas Patta Border for Enhanced Flair\r\n\r\nCholi (Unstitched)\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work\r\n\r\nSize: 1.2 Meter Fabric (Unstitched – Tailorable to XXL+)\r\n\r\nDupatta\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work and Fancy Latkan Lace Border\r\n\r\nLength: 2+ Meters', 'uploads/subcategories/1752954024_Muslin Mirror Work Lehenga Choli-1(1599).jpg', 'uploads/subcategories/1752954024_Muslin Mirror Work Lehenga Choli-2.jpg', 'uploads/subcategories/1752954024_Muslin Mirror Work Lehenga Choli-3.jpg', 'uploads/subcategories/1752954024_Muslin Mirror Work Lehenga Choli-4.jpg', NULL),
(19, 101, 'Butter Silk Lehenga Choli with Mirror Work ', 'IshaHiya', 1650.00, 'IshaHiya4974\r\n\r\nMake a bold festive statement with this fully stitched butter silk lehenga, designed with rich digital print and embellished with real mirror work. Featuring a 4-meter flair and 44-inch length, this lehenga comes with an unstitched blouse and a beautifully finished dupatta with lace and mirror detailing. Ideal for weddings, festive occasions, and wholesale resale – now available in free size.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Heavy Butter Silk\r\n\r\nWork: Digital Print with Real Mirror Work\r\n\r\nFlair: 4 Meters\r\n\r\nLength: 44 Inches\r\n\r\nInner: Silk\r\n\r\nType: Free Size, Fully Stitched\r\n\r\nCholi (Unstitched)\r\n\r\nFabric: Soft Butter Silk\r\n\r\nWork: Real Mirror Work\r\n\r\nLength: 1.20 Meters (Unstitched)\r\n\r\nType: Custom Tailorable\r\n\r\nDupatta\r\n\r\nFabric: Heavy Butter Silk\r\n\r\nWork: Real Mirror Work with Hanging Lace Border\r\n\r\nLength: Standard festive length\r\n\r\nLook: Premium and Elegant', 'uploads/subcategories/1752954228_Butter Silk Lehenga Choli-1(1199).jpg', 'uploads/subcategories/1752954228_Butter Silk Lehenga Choli-2.jpg', 'uploads/subcategories/1752954228_Butter Silk Lehenga Choli-3.jpg', 'uploads/subcategories/1752954228_Butter Silk Lehenga Choli-4.jpg', NULL),
(20, 101, 'Paithani Silk Stitched Saree Lehenga Choli ', 'IshaHiya6288-PEC Peach', 3500.00, 'Product Details\r\nIshaHiya2021L\r\n\r\nMake a statement with this exquisite Paithani Jacquard Silk Lehenga Choli, featuring traditional Zari Weaving Work for a regal look. The stitched lehenga comes with a grand 3.5-meter flair, canvas and can-can for added volume, and a comfortable Micro Cotton inner. The unstitched blouse allows for customization, while the Paithani Jacquard Dupatta with Tassels adds elegance. A matching woven Batwa (purse) completes this luxurious set, making it perfect for weddings, festive occasions, and traditional celebrations.\r\n\r\n\r\n\r\nLehenga Fabric: Rich Jacquard Silk with Intricate Zari Weaving\r\n\r\nBlouse Fabric: Jacquard Silk (Unstitched) for Custom Tailoring\r\n\r\nDupatta Fabric: Jacquard Paithani with Both-Side Tassels\r\n\r\nLehenga Flair: 3.5 Meters for a Royal Look\r\n\r\nInner Fabric: Soft Micro Cotton for Comfort\r\n\r\nClosure: Drawstring with Handmade Tassels for a Traditional Touch\r\n\r\nBonus Accessory: Comes with a Matching Batwa (Handbag)\r\n\r\nStitching: Stitched with Canvas & Can-Can for Structure and Elegance\r\nINNER :- Micro Cotton\r\n\r\nCHOLI \r\nFABRICE :- Soft Chinon Silk (Unstitched)\r\nWORK :- Fancy Digital Print Work & REAL MIRROR WORK\r\n\r\nDUPATTA\r\nFABRICE :- Soft Chinon Silk\r\nWORK :- Fancy Digital Print Work & REAL MIRROR WORK WITH FANCY LATKAN LACE', 'uploads/subcategories/1752954939_Paithani Silk Stitched Saree Lehenga -1(2999).jpg', 'uploads/subcategories/1752954939_Paithani Silk Stitched Saree Lehenga -2.jpg', 'uploads/subcategories/1752954939_Paithani Silk Stitched Saree Lehenga -3.jpg', 'uploads/subcategories/1752954939_Paithani Silk Stitched Saree Lehenga -4.jpg', NULL),
(21, 101, 'Muslin Lehenga Choli Set with Mirror Digital Print', 'IshaHiya', 1750.00, 'Product Details\r\nIshaHiya5100\r\n\r\nStep into festive elegance with this Heavy Muslin Cotton Lehenga Choli Set, crafted with vibrant digital prints and hand-finished real mirror work. The 4-meter flair lehenga is fully stitched and tailored for a flattering fall, enhanced with canvas patta border. Paired with an unstitched choli and latkan-detailed dupatta, this set is perfect for weddings, Navratri, and ethnic events.\r\n\r\nStructured Product Details:\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Beautiful Digital Print with Real Mirror Work\r\n\r\nInner: Micro Cotton\r\n\r\nLength: 42–44 Inches\r\n\r\nFlair: 4 Meters\r\n\r\nSize: Free Size – Stitched to Fit up to XXL (44\")\r\n\r\nFinish: Canvas Patta Border for Extra Volume\r\n\r\nCholi (Unstitched)\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print with Real Mirror Work\r\n\r\nSize: 1 Meter Fabric – Unstitched (Can Be Tailored Up to XXL+)\r\n\r\nType: Custom-Stitch\r\n\r\nDupatta\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Matching Digital Print with Real Mirror Work and Fancy Latkan Lace\r\n\r\nLength: 2.20 Meters', 'uploads/subcategories/1752955330_Navratri Special Lehenga Choli -1(1499).jpg', 'uploads/subcategories/1752955330_Navratri Special Lehenga Choli-2.jpg', 'uploads/subcategories/1752955330_Navratri Special Lehenga Choli-3.jpg', 'uploads/subcategories/1752955330_Navratri Special Lehenga Choli-4.jpg', NULL),
(27, 103, 'Chinon Palazzo Dress Indo Western Style', 'IshaHiya', 1550.00, 'Product Details\r\nIshaHiya4783\r\n\r\nUpgrade your ethnic wardrobe with this elegant Air Tex Chinon Plazzo Set, beautifully designed with sequin embroidery and Kodi latkan detail. This 3-piece set includes a fully stitched blouse, a flattering top, and wide-legged plazzo pants—perfect for festive wear, parties, or boutique collections. Crafted in rich Chinon fabric, it\'s available in ready stock with free-size flexibility up to XXL.\r\n\r\nStructured Product Details:\r\n\r\nBlouse (Fully Stitched)\r\n\r\nFabric: Air Tex Chinon\r\n\r\nInner: Micro\r\n\r\nLength: 15 inches\r\n\r\nSize: Free Size (Stitched)\r\n\r\nTop\r\n\r\nFabric: Air Tex Chinon\r\n\r\nWork: Sequin Embroidered Work with Kodi Latkan\r\n\r\nSize: Free Size (Fits up to XXL)\r\n\r\nLength: 31 inches\r\n\r\nPlazzo (Fully Stitched)\r\n\r\nFabric: Air Tex Chinon\r\n\r\nLength: 40 inches\r\n\r\nFit: Ready-to-Wear Wide Leg Style\r\n\r\nComments/Reviews Of Product\r\n', 'uploads/subcategories/1752960554_Palazzo Dress Indo Western Style-1(1349).jpg', 'uploads/subcategories/1752960554_Palazzo Dress Indo Western Style-1-up.jpg', 'uploads/subcategories/1752960554_Palazzo Dress Indo Western Style-3.jpg', 'uploads/subcategories/1752960554_Palazzo Dress Indo Western Style-4.jpg', NULL),
(29, 101, 'Designer Vichitra Silk Lehenga with Patchwork ', 'IshaHiya', 2000.00, 'Product Details\r\nIshaHiya5426\r\n\r\nStep into elegance with this beautifully crafted 3-piece Lehenga Set, featuring rich Vichitra Silk and all-over coding embroidery with sequin detailing. A complete look for weddings, receptions, and ethnic parties.\r\n\r\nPRODUCT DETAILS ?\r\n\r\nBlouse (Fully Stitched)\r\n\r\nFabric: Vichitra Silk\r\n\r\nWork: Coding Embroidery with Sequins\r\n\r\nFeatures: Built-in Cups | Zipper Closure\r\n\r\nNeck: Round Neck | Full Sleeves\r\n\r\nLength: 15 inches\r\n\r\nSizes Available: M(38), L(40), XL(42), XXL(44)\r\n\r\nLehenga\r\n\r\nFabric: Vichitra Silk\r\n\r\nWork: Matching Coding Embroidery with Sequins\r\n\r\nWaist Size: Upto 42 inches\r\n\r\nLength: 42 inches\r\n\r\nFlair: 3 meters\r\n\r\nStitching: Semi-stitched with margin\r\n\r\nDupatta\r\n\r\nFabric: Vichitra Silk\r\n\r\nWork: Matching Sequins Embroidery & Coding Work\r\n\r\nLength: 2.2 meters', 'uploads/subcategories/1752990996_Vichitra Silk Lehenga with Patchwork-4.jpg', 'uploads/subcategories/1752990996_Vichitra Silk Lehenga with Patchwork-1(2000).jpg', 'uploads/subcategories/1752990996_Vichitra Silk Lehenga with Patchwork-2.jpg', 'uploads/subcategories/1752990996_Vichitra Silk Lehenga with Patchwork-3.jpg', NULL),
(30, 101, 'Morden Florel Kanjivaram Garden', 'IshaHiya', 4000.00, 'Product Details\r\nIshaHiya5352\r\n\r\nLehenga (Stitched)\r\nLehenga Fabric : Khadi cotton\r\nLehenga Work : Sequins And Thread Embroidery Work \r\nLehenga Waist : Support Up To 42 \r\nLehenga Closer : Drawstring With Zip\r\nStitching : Stitched With Canvas And Full Inner\r\nLength : 41\r\nFlair : 3.80 Meter \r\nInner : Micro Cotton\r\n\r\nBlouse (Unstitched)\r\nBlouse fabric : Khadi cotton\r\nBlouse Work : Sequins And Thread Embroidery Work \r\nBlouse Length : 1 Meter\r\n\r\nDupatta\r\nDupatta Fabric : Gaji Silk\r\nDupatta Work : Printed With Lagadi Patta Also Comes With Both Side Tassels \r\nDupatta Length : 2.5 Meter\r\n\r\nPackage Contain : Lehenga, Blouse, Dupatta, Drawstring with Zip\r\n\r\nWeight : 1.450 KG', 'uploads/subcategories/1752993097_WhatsApp Image 2025-07-19 at 2.17.51 PM (1).jpeg', 'uploads/subcategories/1752993097_WhatsApp Image 2025-07-19 at 2.17.51 PM.jpeg', 'uploads/subcategories/1752993097_WhatsApp Image 2025-07-19 at 2.17.52 PM (1).jpeg', 'uploads/subcategories/1752993097_WhatsApp Image 2025-07-19 at 2.17.52 PM.jpeg', NULL),
(31, 101, 'Royal Mayur Flow Lehenga', 'IshaHiya', 2150.00, 'Full Product Description\r\ncholi👗\r\nFabric:- Pure Tissue silk\r\nWork:-  Heavy  Embroidery Sequence & Thread Work\r\nInner:- Micro Cotton\r\n *Size :-*M upto L margin\r\n            XL upto 2xl margin\r\n              (Fully Stitched)\r\n\r\n  👗Lehenga👗\r\nFabric:- Pure Tissue silk\r\nInner:- Micro Cotton\r\nWork:-  Heavy  Embroidery Sequence & Thred Work \r\nFlair:- 3.50 Meter\r\nAttached Canvas and Cancan\r\n          With DORI\r\nSize:- 44 \r\n   (Full-Stitched)\r\n\r\n     👗Dupatta👗\r\nFabric:- pure bandhrej Georgette\r\nSize:- 2.30 Meter\r\nWork:- Heavy  Embroidery 7mm Sequence & Thread Work\r\nWeight:-1.50 kg\r\n\r\n   (*Color:-6 available*)\r\n', 'uploads/subcategories/1752993844_Royal Mayur Flow Lehenga-2 (3).jpeg', 'uploads/subcategories/1752993844_Royal Mayur Flow Lehenga-2 (2).jpeg', 'uploads/subcategories/1752993844_Royal Mayur Flow Lehenga-2 (1).jpeg', 'uploads/subcategories/1752993844_Royal Mayur Flow Lehenga-2 (4).jpeg', NULL),
(33, 0, '', 'IshaHiya', 2100.00, 'Product Details\r\nIshaHiya5527\r\n\r\nCelebrate the spirit of RAS in vibrant traditional style with our ready-to-dispatch men’s kurta-dhoti and women’s kediya-dhoti sets. Made with breathable cotton and traditional Jaipuri work, these sets are perfect for Navratri, Garba nights, and festive collections. Available in 3 beautiful colors and all sizes — ideal for resellers at wholesale and manufacturer rates.\r\n\r\nFull Product Description :-\r\n\r\nMen’s Kurta with Dhoti Set\r\n\r\nKurta Fabric: Cotton\r\n\r\nKurta Length: 42 inches\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size with Elastic Waist\r\n\r\nDhoti Length: 40 to 42 inches\r\n\r\nWomen’s Kediya with Dhoti & Belt\r\n\r\nKediya Fabric: Cotton\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size Elastic Waist (Fits M to XXL)\r\n\r\nDesign: Jaipuri Traditional Work\r\n\r\nAdditional: Comes with Matching Belt\r\n\r\nColors Available: Beautiful 3 Color Options', '../uploads/subcategories/Couples Mirror Work Kediya and Kurta Combo-1(2000).jpg', '../uploads/subcategories/Couples Mirror Work Kediya and Kurta Combo-2.jpg', '../uploads/subcategories/Couples Mirror Work Kediya and Kurta Combo-3.jpg', '../uploads/subcategories/Couples Mirror Work Kediya and Kurta Combo-4.jpg', NULL),
(35, 105, 'Couples Mirror Work Kediya and Kurta Combo', 'IshaHiya', 2100.00, 'Product Details\r\nIshaHiya5527\r\n\r\nCelebrate the spirit of RAS in vibrant traditional style with our ready-to-dispatch men’s kurta-dhoti and women’s kediya-dhoti sets. Made with breathable cotton and traditional Jaipuri work, these sets are perfect for Navratri, Garba nights, and festive collections. Available in 3 beautiful colors and all sizes — ideal for resellers at wholesale and manufacturer rates.\r\n\r\nFull Product Description :-\r\n\r\nMen’s Kurta with Dhoti Set\r\n\r\nKurta Fabric: Cotton\r\n\r\nKurta Length: 42 inches\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size with Elastic Waist\r\n\r\nDhoti Length: 40 to 42 inches\r\n\r\nWomen’s Kediya with Dhoti & Belt\r\n\r\nKediya Fabric: Cotton\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size Elastic Waist (Fits M to XXL)\r\n\r\nDesign: Jaipuri Traditional Work\r\n\r\nAdditional: Comes with Matching Belt\r\n\r\nColors Available: Beautiful 3 Color Options', 'uploads/subcategories/1752997358_Couples Mirror Work Kediya and Kurta Combo-1(2000).jpg', 'uploads/subcategories/1752997358_Couples Mirror Work Kediya and Kurta Combo-2.jpg', 'uploads/subcategories/1752997358_Couples Mirror Work Kediya and Kurta Combo-3.jpg', 'uploads/subcategories/1752997358_Couples Mirror Work Kediya and Kurta Combo-4.jpg', NULL),
(34, 105, 'Couple Set Mirror Embroidered Kurta and Kediya Dhoti ', 'IshaHiya', 2100.00, 'Product Details\r\nIshaHiya5527\r\n\r\nCelebrate the spirit of RAS in vibrant traditional style with our ready-to-dispatch men’s kurta-dhoti and women’s kediya-dhoti sets. Made with breathable cotton and traditional Jaipuri work, these sets are perfect for Navratri, Garba nights, and festive collections. Available in 3 beautiful colors and all sizes — ideal for resellers at wholesale and manufacturer rates.\r\n\r\nFull Product Description :-\r\n\r\nMen’s Kurta with Dhoti Set\r\n\r\nKurta Fabric: Cotton\r\n\r\nKurta Length: 42 inches\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size with Elastic Waist\r\n\r\nDhoti Length: 40 to 42 inches\r\n\r\nWomen’s Kediya with Dhoti & Belt\r\n\r\nKediya Fabric: Cotton\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size Elastic Waist (Fits M to XXL)\r\n\r\nDesign: Jaipuri Traditional Work\r\n\r\nAdditional: Comes with Matching Belt\r\n\r\nColors Available: Beautiful 3 Color Options', 'uploads/subcategories/1752997062_Matching Navratri Cotton Couple Set.jpg', 'uploads/subcategories/1752997062_Matching Navratri Cotton Couple Set-2.jpg', 'uploads/subcategories/1752997062_Matching Navratri Cotton Couple Set-3.jpg', 'uploads/subcategories/1752997062_Matching Navratri Cotton Couple Set-4.jpg', NULL),
(36, 105, ' Couple Kediya Dhoti Set with Mirror Work', 'IshaHiya', 2100.00, 'Product Details\r\nIshaHiya5527\r\n\r\nCelebrate the spirit of RAS in vibrant traditional style with our ready-to-dispatch men’s kurta-dhoti and women’s kediya-dhoti sets. Made with breathable cotton and traditional Jaipuri work, these sets are perfect for Navratri, Garba nights, and festive collections. Available in 3 beautiful colors and all sizes — ideal for resellers at wholesale and manufacturer rates.\r\n\r\nFull Product Description :-\r\n\r\nMen’s Kurta with Dhoti Set\r\n\r\nKurta Fabric: Cotton\r\n\r\nKurta Length: 42 inches\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size with Elastic Waist\r\n\r\nDhoti Length: 40 to 42 inches\r\n\r\nWomen’s Kediya with Dhoti & Belt\r\n\r\nKediya Fabric: Cotton\r\n\r\nSizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nDhoti Fabric: Crape\r\n\r\nDhoti Type: Free Size Elastic Waist (Fits M to XXL)\r\n\r\nDesign: Jaipuri Traditional Work\r\n\r\nAdditional: Comes with Matching Belt\r\n\r\nColors Available: Beautiful 3 Color Options', 'uploads/subcategories/1752997679_Matching Navratri Cotton Couple Set-8.jpg', 'uploads/subcategories/1752997679_Matching Navratri Cotton Couple Set-7.jpg', 'uploads/subcategories/1752997679_Matching Navratri Cotton Couple Set-6.jpg', 'uploads/subcategories/1752997679_Matching Navratri Cotton Couple Set-5.jpg', NULL),
(39, 107, 'Gamthi Lehenga with Matching Kurta Combo', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya3567\r\n\r\nElevate your ethnic collection with this Designer Gamthi Work Lehenga Choli Set, crafted in premium Maslin and Rayon fabrics. Featuring a full-stitched lehenga with 5-meter flair, a beautifully embroidered Rayon blouse with padding, and a printed Georgette dupatta with mirror lace. The set includes an off-white digital print Kurta, making it a versatile combo for festive and wedding wear.\r\n\r\nComplete Product Specification\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Maslin / Rayon\r\n\r\nInner: Crape\r\n\r\nColor: Maroon\r\n\r\nWork: Heavy Print + Gamthi Work Lace + Patch\r\n\r\nLength: 42\"\r\n\r\nSize: 42\"\r\n\r\nFlair: 5 Meters\r\n\r\nOpening: Side Zipper + Dori (Naadi)\r\n\r\nBlouse (Fully Stitched & Padded)\r\n\r\nFabric: Rayon\r\n\r\nInner: Cotton\r\n\r\nSize: L (Chest 40\") – With Extra Margin\r\n\r\nLength: 15\"\r\n\r\nSleeve Length: 10\"\r\n\r\nWork: Gamthi Patch + Thread & Sequence Work\r\n\r\nBack Style: Dori Tie-Up\r\n\r\nColor: Black\r\n\r\nDupatta\r\n\r\nFabric: Heavy Georgette\r\n\r\nLength: 2.25 Meters\r\n\r\nWork: Heavy Print + Mirror Lace Border\r\n\r\nColor: Maroon\r\n\r\nKurta\r\n\r\nFabric: Maslin\r\n\r\nColor: Off White\r\n\r\nWork: Digital Print\r\n\r\nSize: M, XL (With Extra Loosening)\r\n\r\nLength: 40\"\r\n\r\nSleeve Length: 25\"', 'uploads/subcategories/1753000238_Gamthi Lehenga with Matching Kurta Festive Combo-1(2500).jpg', 'uploads/subcategories/1753000238_Gamthi Lehenga with Matching Kurta Festive Combo-1-1.jpg', 'uploads/subcategories/1753000238_Gamthi Lehenga with Matching Kurta Festive Combo-1-5.jpg', 'uploads/subcategories/1753000238_Gamthi Lehenga with Matching Kurta Festive Combo-1-2.jpg', NULL),
(43, 107, 'Matching Mirror Work Couple Outfit ', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya3565\r\n\r\nMake a statement with this vibrant Rani Pink Lehenga Choli Set, featuring multi-colour samosa lace work and paired with a beautifully printed Gamthi work dupatta. The fully stitched rayon lehenga and padded mirror-work blouse offer elegant comfort. Comes with a stylish white Maslin kurta featuring digital print and multi-lining design – perfect for festive and ethnic occasions.\r\n\r\nFull Product Specifications\r\n\r\nLehenga (Fully Stitched)\r\n\r\nFabric: Premium Rayon\r\n\r\nInner: Crape\r\n\r\nColour: Rani\r\n\r\nWork: Multi-Colour Samosa Lace\r\n\r\nLength: 42\"\r\n\r\nSize: 42\"\r\n\r\nFlair: 4.75+ Meters\r\n\r\nOpening: Side Zipper + Dori (Nadi)\r\n\r\nBlouse (Fully Stitched & Padded)\r\n\r\nFabric: Rayon with Cotton Inner\r\n\r\nSize: L (Chest 40\") with Extra Margin\r\n\r\nBlouse Length: 15\"\r\n\r\nSleeve Length: 10\"\r\n\r\nWork: Traditional Gamthi Work + Mirror Work\r\n\r\nBack Style: Dori\r\n\r\nColour: Rani\r\n\r\nDupatta\r\n\r\nFabric: Heavy Georgette\r\n\r\nLength: 2.25 Meters\r\n\r\nWork: Heavy Print + Gamthi Work Pallu Lace + Mirror Work Lace\r\n\r\nColour: Multi\r\n\r\nKurta\r\n\r\nFabric: Maslin\r\n\r\nColour: White with Multi-Lining Print\r\n\r\nWork: Digital Print\r\n\r\nSizes Available: M, XL (with extra loosening)\r\n\r\nLength: 40”\r\n\r\nSleeve Length: 25”', 'uploads/subcategories/1753002244_Gamthi Embroidery Lehenga & Contrast Kurta Combo-2.jpg', 'uploads/subcategories/1753002244_Gamthi Embroidery Lehenga & Contrast Kurta Combo-2-1.jpg', 'uploads/subcategories/1753002244_Gamthi Embroidery Lehenga & Contrast Kurta Combo-2-3.jpg', 'uploads/subcategories/1753002244_Gamthi Embroidery Lehenga & Contrast Kurta Combo-2-2.jpg', NULL),
(46, 108, 'Orange Couple Combo Set ', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya2732\r\n\r\nIntroducing our exclusive Couple Combo set featuring Men’s Kurta with Cotton fabric, paired with a stylish Jaccard Weaving Silk Koti (jacket) and comfortable cotton pajama. The combo also includes a luxurious Women’s Saree made from premium Jaccard Weaving Silk, perfect for festive and special occasions.\r\n\r\nFull Product Details\r\n\r\nMen’s Koti Fabric: Elegant Jaccard Weaving Silk with rich texture and intricate design\r\n\r\nMen’s Kurta Fabric: Soft, breathable Cotton fabric for comfort\r\n\r\nMen’s Pajama Fabric: Pure Cotton for ease and durability\r\n\r\nWomen’s Saree Fabric: Premium Jaccard Weaving Silk, offering a graceful drape and festive shine\r\n\r\nMen’s Sizes Available: M, L, XL, 2XL\r\n\r\nCombo Includes:\r\n\r\nMen’s Koti (Jaccard Weaving Silk Jacket)\r\n\r\nMen’s Kurta (Cotton Fabric) + Pajama (Cotton Fabric)\r\n\r\nWomen’s Saree (Jaccard Weaving Silk)', 'uploads/subcategories/1753010089_Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree .jpg', 'uploads/subcategories/1753010089_Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree -3.jpg', NULL, NULL, NULL),
(48, 108, 'Ethnic Indian Couple Wear ', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya2732\r\n\r\nIntroducing our exclusive Couple Combo set featuring Men’s Kurta with Cotton fabric, paired with a stylish Jaccard Weaving Silk Koti (jacket) and comfortable cotton pajama. The combo also includes a luxurious Women’s Saree made from premium Jaccard Weaving Silk, perfect for festive and special occasions.\r\n\r\nFull Product Details\r\n\r\nMen’s Koti Fabric: Elegant Jaccard Weaving Silk with rich texture and intricate design\r\n\r\nMen’s Kurta Fabric: Soft, breathable Cotton fabric for comfort\r\n\r\nMen’s Pajama Fabric: Pure Cotton for ease and durability\r\n\r\nWomen’s Saree Fabric: Premium Jaccard Weaving Silk, offering a graceful drape and festive shine\r\n\r\nMen’s Sizes Available: M, L, XL, 2XL\r\n\r\nCombo Includes:\r\n\r\nMen’s Koti (Jaccard Weaving Silk Jacket)\r\n\r\nMen’s Kurta (Cotton Fabric) + Pajama (Cotton Fabric)\r\n\r\nWomen’s Saree (Jaccard Weaving Silk)', 'uploads/subcategories/1753010569_Couple Matching Ethnic Set Jacquard Silk Saree for Women Koti Kurta Pajama for Men.jpg', 'uploads/subcategories/1753010569_Couple Matching Ethnic Set Jacquard Silk Saree for Women Koti Kurta Pajama for Men-2.jpg', 'uploads/subcategories/1753010569_Couple Matching Ethnic Set Jacquard Silk Saree for Women Koti Kurta Pajama for Men-3.jpg', NULL, NULL),
(49, 108, 'Ethnic Indian Couple Wear ', 'IshaHiya', 2500.00, '\r\nProduct Details\r\nIshaHiya2732\r\n\r\nIntroducing our exclusive Couple Combo set featuring Men’s Kurta with Cotton fabric, paired with a stylish Jaccard Weaving Silk Koti (jacket) and comfortable cotton pajama. The combo also includes a luxurious Women’s Saree made from premium Jaccard Weaving Silk, perfect for festive and special occasions.\r\n\r\nFull Product Details\r\n\r\nMen’s Koti Fabric: Elegant Jaccard Weaving Silk with rich texture and intricate design\r\n\r\nMen’s Kurta Fabric: Soft, breathable Cotton fabric for comfort\r\n\r\nMen’s Pajama Fabric: Pure Cotton for ease and durability\r\n\r\nWomen’s Saree Fabric: Premium Jaccard Weaving Silk, offering a graceful drape and festive shine\r\n\r\nMen’s Sizes Available: M, L, XL, 2XL\r\n\r\nCombo Includes:\r\n\r\nMen’s Koti (Jaccard Weaving Silk Jacket)\r\n\r\nMen’s Kurta (Cotton Fabric) + Pajama (Cotton Fabric)\r\n\r\nWomen’s Saree (Jaccard Weaving Silk)', 'uploads/subcategories/1753010773_Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree -4.jpg', 'uploads/subcategories/1753010773_Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree -5.jpg', 'uploads/subcategories/1753010773_Cotton Kurta with Jacquard Silk Koti Womens Jacquard Silk Saree -6.jpg', NULL, NULL),
(50, 108, 'Yellow Haldi Couple Outfit Set', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya2732\r\n\r\nIntroducing our exclusive Couple Combo set featuring Men’s Kurta with Cotton fabric, paired with a stylish Jaccard Weaving Silk Koti (jacket) and comfortable cotton pajama. The combo also includes a luxurious Women’s Saree made from premium Jaccard Weaving Silk, perfect for festive and special occasions.\r\n\r\nFull Product Details\r\n\r\nMen’s Koti Fabric: Elegant Jaccard Weaving Silk with rich texture and intricate design\r\n\r\nMen’s Kurta Fabric: Soft, breathable Cotton fabric for comfort\r\n\r\nMen’s Pajama Fabric: Pure Cotton for ease and durability\r\n\r\nWomen’s Saree Fabric: Premium Jaccard Weaving Silk, offering a graceful drape and festive shine\r\n\r\nMen’s Sizes Available: M, L, XL, 2XL\r\n\r\nCombo Includes:\r\n\r\nMen’s Koti (Jaccard Weaving Silk Jacket)\r\n\r\nMen’s Kurta (Cotton Fabric) + Pajama (Cotton Fabric)\r\n\r\nWomen’s Saree (Jaccard Weaving Silk)', 'uploads/subcategories/1753011048_Yellow Haldi Couple Outfit Set-1(2500).jpg', 'uploads/subcategories/1753011048_Yellow Haldi Couple Outfit Set-2.jpg', 'uploads/subcategories/1753011048_Yellow Haldi Couple Outfit Set-1(2500).jpg', 'uploads/subcategories/1753011048_Yellow Haldi Couple Outfit Set-2.jpg', NULL),
(51, 108, 'Purple Festive Couple Dress Set', 'IshaHiya', 2500.00, 'Product Details\r\nIshaHiya2732\r\n\r\nIntroducing our exclusive Couple Combo set featuring Men’s Kurta with Cotton fabric, paired with a stylish Jaccard Weaving Silk Koti (jacket) and comfortable cotton pajama. The combo also includes a luxurious Women’s Saree made from premium Jaccard Weaving Silk, perfect for festive and special occasions.\r\n\r\nFull Product Details\r\n\r\nMen’s Koti Fabric: Elegant Jaccard Weaving Silk with rich texture and intricate design\r\n\r\nMen’s Kurta Fabric: Soft, breathable Cotton fabric for comfort\r\n\r\nMen’s Pajama Fabric: Pure Cotton for ease and durability\r\n\r\nWomen’s Saree Fabric: Premium Jaccard Weaving Silk, offering a graceful drape and festive shine\r\n\r\nMen’s Sizes Available: M, L, XL, 2XL\r\n\r\nCombo Includes:\r\n\r\nMen’s Koti (Jaccard Weaving Silk Jacket)\r\n\r\nMen’s Kurta (Cotton Fabric) + Pajama (Cotton Fabric)\r\n\r\nWomen’s Saree (Jaccard Weaving Silk)', 'uploads/subcategories/1753011358_Purple Festive Couple Dress Set -1(2500).jpg', 'uploads/subcategories/1753011358_Purple Festive Couple Dress Set -2.jpg', 'uploads/subcategories/1753011358_Purple Festive Couple Dress Set -1(2500).jpg', 'uploads/subcategories/1753011358_Purple Festive Couple Dress Set -2.jpg', NULL),
(52, 109, 'Heavy Georgette Fabric Couple Combo', 'IshaHiya', 3500.00, 'Product Details\r\n*Lahenga:- (Full Stitched)*\r\nFabric :- Heavy Georgette Fabric\r\nInner :- Duble Crape Inner + Can-Can Net\r\nColour :- Wine\r\nWork :- Heavy Foil Print + Embroidery Work Belt\r\nLength :- 42\"\r\nSize :- 40\"\r\nFlair :- 4.25 Mtr\r\n*(Side Imported Zipper)*\r\n\r\n*Blouse :- (Full Stitched)*\r\nFabric :- Heavy Georgette With Fussing\r\nInner :- Cotton\r\nSize :- S (Chest 36) & L(Chest 40) (Extra Margin)\r\nBlouse Length :- 15”\r\nWork :- Heavy Embroidery + Sequence + Zari Work +Ready Lace\r\nBack Side :- Dori (Opening With Hook)\r\nBlouse Colour :- Wine\r\n(With Padded)\r\n\r\n*Dupatta* :-\r\nFabric :- Pure Heavy Tb Organza (2.25 Mtr)\r\nWork :- Heavy Embroidery + Thread Sequence + Zari Tarred + Ready Lace\r\nColour :- Light Wine\r\nWeight :- 1.670 Gm\r\n\r\n\r\n*Kurta Bottom Set* :-\r\nFabric :- Mo Satin\r\nInner:- Cotton\r\nSize :-M,XL ( Extra Loosing )\r\nLength :- 39”\r\nSleeve Length :- 25”\r\nWork :- Embroidery + Sequence', 'uploads/subcategories/1753012204_Heavy Georgette Fabric Couple Combo Lehenga Choli Collection-1.jpg', 'uploads/subcategories/1753012204_Heavy Georgette Fabric Couple Combo Lehenga Choli Collection-3.jpg', 'uploads/subcategories/1753012204_Heavy Georgette Fabric Couple Combo Lehenga Choli Collection-5.jpg', 'uploads/subcategories/1753012204_Heavy Georgette Fabric Couple Combo Lehenga Choli Collection-6.jpg', NULL),
(53, 109, 'Heavy Muslin Lehenag Choli', 'IshaHiya', 3500.00, 'Product Details\r\nLehenga:- (Full stitched)\r\nFabric :- Heavy muslin\r\nInner :- Crape\r\nColour :- Blue-White\r\nWork :- Heavy Digital Print + Ready Kutchi Mirror Lace And Patch\r\nLength :- 40\"\r\nSize :- 40” Reddy\r\nFlair :- 5 MTR\r\n\r\nBlouse :- (Full stitched)\r\nFabric :- Heavy Slub Cotton (With Padded)\r\nInner :- Cotton\r\nSize :- L(Chest 40) (Extra Margin))\r\nBlouse Length :- 15”\r\nWork :- Heavy Digital Print + Ready Kutchi Mirror Lace And Patch\r\nBack Side :- Dori\r\nColour :- White\r\n\r\nDupatta :-\r\nFabric :- Heavy Georgette (2.25 MTR)\r\nWork :- Heavy Kutchi Mirror lace + Digital Print\r\nColour :- Blue\r\n\r\n\r\nKurta Bottom Set:-\r\n\r\nFabric :- Heavy Muslin Digital Print + Kutchi Lace And Patch\r\nSize :-M,XL ( extra loosing )\r\nLength :- 38”\r\nSleeve Length :- 25”\r\nBottom Fabric :- Slub Cotton\r\nSize :- Free', 'uploads/subcategories/1753012606_Couple Special Heavy Muslin Lehenag Choli-1(3500).jpg', 'uploads/subcategories/1753012606_Couple Special Heavy Muslin Lehenag Choli-4.jpg', 'uploads/subcategories/1753012606_Couple Special Heavy Muslin Lehenag Choli-2.jpg', 'uploads/subcategories/1753012606_Couple Special Heavy Muslin Lehenag Choli-3.jpg', NULL),
(54, 109, 'Western Flower Couple Combo ', 'IshaHiya', 3750.00, 'Product Details\r\n*Lahenga:- (Full Stitched)*\r\n *Fabric* :- Jimmy Choo\r\n *Inner* :- Crape + Can-Can Net\r\n *Colour* :- Rama\r\n *Work* :- Heavy Embroidery Zari + Thread + Sequence Work\r\nLength :- 42\"\r\nSize :- 40\"\r\nFlair :- 4 Mtr\r\n*(Side Imported Zipper)*\r\n\r\n*Blouse :- (Full Stitched)*\r\n *Fabric* :- Heavy Satin\r\n *Inner* :- Cotton *(with Padded)*\r\n *Size* :- S (Chest 36) & L(Chest 40) (Extra Margin)\r\n *Blouse* Length :- 15”\r\n *Work* :- *Hand Work* + Heavy Embroidery Sequence + Thread +Zari *3D Flower*\r\n *Back Side* :- Dori (Opening With Hook)\r\n *Blouse Colour* :- Rama\r\n*Extra sleeve* Inside\r\n\r\n*Dupatta* :-\r\n *Fabric* :- Jimmy Choo (2.25 Mtr)\r\n *Work* :- Wire Stitch\r\n *Colour* :- Rama\r\n\r\n *Weight* :- 1.400 KG\r\n\r\n*Kurta Bottom Set* :-\r\n *Fabric* :- Rayon\r\n *Kurta colour* :- Black + Rama Thread Work\r\n *Size* :-M,38(42),XL,42(46) (Extra Loosing)\r\nLength :- 40”\r\n *Sleeve Length* :- M - 23.5 XL - 25.5”\r\n *Work* :- Heavy Embroidery Sequence + Thread Work\r\n *Bottom* Fabric :- Magic Slub\r\n *Colour* :- White\r\n *Length* :- 38”\r\n *Size* :- Free Size', 'uploads/subcategories/1753013204_Rama Western Flower Couple Combo Lahenga Choli-1(3750).jpg', 'uploads/subcategories/1753013204_Rama Western Flower Couple Combo Lahenga Choli-2.jpg', 'uploads/subcategories/1753013204_Rama Western Flower Couple Combo Lahenga Choli-4.jpg', 'uploads/subcategories/1753013204_Rama Western Flower Couple Combo Lahenga Choli-5.jpg', NULL),
(57, 111, 'Pure Cotton Pattern Weaving Couple Combo', 'IshaHiya', 1350.00, 'Product Details\r\nNew Launch Couple Combo (Shirt & Tunic)\r\nPresenting Couple Mens Shirt & Female Tunic Combo for Festival Season\r\n\r\nFabric - Pure Cotton Pattern Weaving\r\nAn Exclusive Collection From Couple Combo Manufacturer', 'uploads/subcategories/1753016253_Festival Couple Combo Shirt and Tunic -1.jpg', 'uploads/subcategories/1753016253_New Launch Black Color Pure Cotton Pattern Weaving Couple Combo Collection-1(1350).jpg', 'uploads/subcategories/1753016253_Festival Couple Combo Shirt and Tunic With Beautiful Color and Cotton Bubble Pattern Weaving-2.jpg', 'uploads/subcategories/1753016253_Festival Couple Combo Shirt and Tunic With Beautiful Color and Cotton Bubble Pattern Weaving-3.jpg', NULL),
(58, 111, 'linen cotton Couple collection', 'IshaHiya', 950.00, 'Product Details\r\nPresenting for festivals and special occasions\r\n\r\nTunic top& Short Kurta Fabric- Premium linen cotton fabric with colourful weaving threads -Comfort feel\r\n\r\nMen Size: . M(38),L(40),XL(42),2XL(44),3XL(46)\r\n\r\nFemale Size:. S(36),M(38),L(40),XL(42),2XL(44),', 'uploads/subcategories/1753017047_Presenting for festivals and special occasions linen cotton Couple collection-1.jpg', 'uploads/subcategories/1753017047_Presenting for festivals and special occasions linen cotton Couple collection-4.jpg', 'uploads/subcategories/1753017047_Presenting for festivals and special occasions linen cotton Couple collection-3.jpg', 'uploads/subcategories/1753017047_Presenting for festivals and special occasions linen cotton Couple collection-2.jpg', NULL),
(59, 111, 'Matching Shirt & Tunic Set for Celebration', 'IshaHiya', 1200.00, 'Product Details\r\nNew Launch: Couple Combo (Shirt & Tunic)\r\n\r\nCelebrate the festive season in perfect harmony with our Couple Combo—a stylish blend of traditional charm and contemporary design. This matching set is crafted for couples who love to make a statement together.\r\n\r\nFabric:\r\n\r\nPure Cotton Bubble with Pattern Weaving for a luxurious feel and look.\r\n\r\nMen’s Shirt:\r\n\r\nSizes Available: M (38), L (40), XL (42), 2XL (44).\r\n\r\nDesigned for comfort and style, perfect for casual or festive wear.\r\n\r\nFemale Tunic:\r\n\r\nSizes Available: M (38), L (40), XL (42), 2XL (44).\r\n\r\nA graceful tunic crafted to complement the men’s shirt, offering elegance and comfort.\r\n\r\nWhy Choose This Combo?\r\n\r\n1) Perfect for festive gatherings, couple photoshoots, and special occasions.\r\n\r\n2) Crafted with high-quality cotton for a breathable and soft feel.\r\n\r\n3) Unique Bubble Pattern Weaving adds a touch of sophistication.\r\n\r\n4) Thoughtfully designed to ensure both partners look their best.\r\n\r\nPackaging Details:\r\n\r\n1 Men\'s Shirt\r\n\r\n1 Female Tunic\r\n\r\n', 'uploads/subcategories/1753017933_Matching Shirt & Tunic Set for Celebration-2.jpg', 'uploads/subcategories/1753017933_Matching Shirt & Tunic Set for Celebration-3.jpg', 'uploads/subcategories/1753017933_Matching Shirt & Tunic Set for Celebration-1.jpg', 'uploads/subcategories/1753017933_Matching Shirt & Tunic Set for Celebration-4.jpg', NULL),
(60, 111, 'Couple Combo in Soft Linen Cotton', 'IshaHiya', 1250.00, 'Product Details\r\nIshaHiya4608\r\n\r\nCelebrate festivals in style with our Premium Linen Cotton Combo Set for couples. This set features a Men’s Short Kurta and Women’s Tunic Top, both crafted from soft and colorful woven linen cotton. Designed for comfort and elegance – perfect for festive occasions and special gatherings.\r\n\r\nFabric & Size Details\r\n\r\nFabric: Premium Linen Cotton with Multicolor Thread Weaving\r\n\r\nDesign: Light and breathable feel with traditional festive aesthetics\r\n\r\nMen’s Sizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nWomen’s Sizes: M (38), L (40), XL (42), XXL (44)\r\n\r\nSet Includes: 1 Men’s Short Kurta + 1 Women’s Tunic Top\r\n\r\nCombo Set Ideal For: Festivals, Family Functions, Couple Wear, Ethnic Themes', 'uploads/subcategories/1753018777_Couple Combo in Soft Linen Cotton.jpg', 'uploads/subcategories/1753018777_Couple Combo in Soft Linen Cotton-2.jpg', 'uploads/subcategories/1753018777_Couple Combo in Soft Linen Cotton-3.jpg', 'uploads/subcategories/1753018777_Couple Combo in Soft Linen Cotton-5.jpg', NULL),
(62, 112, 'Gamthi Work Navratri Wear Nayra Cut Kurti', 'IshaHiya', 980.00, 'Product Name: Red Nayra-Cut Pleated Kurti with Kutchi Gamthi Embroidery\r\n\r\nProduct Description:\r\n\r\nElevate your festive wardrobe with this stunning Red Pleated Nayra-Cut Kurti, crafted from soft, breathable rayon for all-day comfort. Designed with exquisite Kutchi Gamthi hand embroidery, this kurti features traditional motifs in vibrant hues, beautifully adorning the bodice, sleeves, and back—perfect for Navratri and festive celebrations.\r\n\r\nDesign & Detailing:\r\n\r\nElegant round neckline\r\n\r\nGraceful full sleeves\r\n\r\nDelicate shell lace detailing on the back\r\n\r\nFlowing pleated Nayra-cut silhouette for a flattering fit and easy movement\r\n\r\nComfort & Fit:\r\n\r\nStitched in Size L (40\"), adjustable from 38” to 44” for a customizable fit\r\n\r\n52” length offers a flowy, graceful drape\r\n\r\nCotton lining on the upper body ensures breathable comfort\r\n\r\nStyling Tip:\r\nPair this versatile kurti with jeans, palazzos, or skirts for a chic blend of traditional and modern style.\r\n\r\nIdeal For: Navratri, Garba nights, festive gatherings, and ethnic events.\r\n\r\nMaterial: Rayon with cotton lining\r\nColor: Red\r\nEmbroidery: Kutchi Gamthi handwork\r\nNeckline: Round\r\nSleeves: Full\r\nLength: 52”\r\nFit: Adjustable (38” – 44”)\r\n\r\nDisclaimer: Slight color variation may occur due to photographic lighting.', 'uploads/subcategories/1753036530_Gamthi Work Navratri Wear Nayra Cut Kurti-1(720).jpg', 'uploads/subcategories/1753036530_Gamthi Work Navratri Wear Nayra Cut Kurti-2.jpg', 'uploads/subcategories/1753036530_Gamthi Work Navratri Wear Nayra Cut Kurti-3.jpg', 'uploads/subcategories/1753036530_Gamthi Work Navratri Wear Nayra Cut Kurti-4.jpg', NULL);
INSERT INTO `subcategory` (`id`, `collection_id`, `subcategory_name`, `brand`, `price`, `description`, `image1`, `image2`, `image3`, `image4`, `category_name`) VALUES
(63, 112, 'Kutchi Work Traditional Kediya Style Kurti', 'IshaHiya', 850.00, 'Product Name: Black Kediya-Style Cotton Kurti with Kutchi Gamthi Embroidery\r\n\r\nProduct Description:\r\n\r\nCelebrate the fusion of tradition and trend with this Black Kediya-Style Kurti, beautifully crafted from soft, breathable cotton for unmatched comfort and grace. Featuring vibrant Kutchi Gamthi embroidery on the front panel, this kurti brings alive the colors and spirit of Indian craftsmanship.\r\n\r\nDesign & Detailing:\r\n\r\nKediya-style peplum silhouette for a modern ethnic appeal\r\n\r\nIntricate Kutchi embroidery on the yoke and cuff detailing on full sleeves\r\n\r\nDelicate shell embellishments hanging from the embroidered yoke add a festive, playful touch\r\n\r\nComfort & Fit:\r\n\r\nMade from pure cotton fabric, ideal for long wear\r\n\r\nDesigned for both casual elegance and festive charm\r\n\r\nStyling Tip:\r\nStyle it with jeans, palazzos, or skirts to create effortlessly chic looks for festive events or everyday outings.\r\n\r\nIdeal For: Navratri, cultural functions, festive get-togethers, and casual ethnic wear.\r\n\r\nMaterial: 100% Cotton\r\nColor: Black\r\nEmbroidery: Kutchi Gamthi\r\nNeckline: Round\r\nSleeves: Full with embroidered cuffs\r\nSilhouette: Kediya peplum style\r\n\r\nDisclaimer: Slight color variation may occur due to photographic lighting.', 'uploads/subcategories/1753036922_Kutchi Work Traditional Kediya Style Kurti-1.jpg', 'uploads/subcategories/1753036922_Kutchi Work Traditional Kediya Style Kurti-2.jpg', 'uploads/subcategories/1753036922_Kutchi Work Traditional Kediya Style Kurti-3.jpg', 'uploads/subcategories/1753036922_Kutchi Work Traditional Kediya Style Kurti-4.jpg', NULL),
(64, 102, 'Cotton 2-Piece Sets', 'IshaHiya', 350.00, 'Product Description\r\nElevate your everyday style with this elegant Kurta Pant Set – a perfect blend of comfort, class, and versatility. Crafted from premium cotton fabric, this set ensures all-day ease while keeping you effortlessly stylish.\r\n\r\nProduct Details\r\nPackage Contains:\r\n1 Kurta & 1 Pant\r\n\r\nFabric:\r\n100% Cotton – soft, breathable, and perfect for all seasons\r\n\r\nWash Care:\r\nMachine washable – easy to maintain and long-lasting\r\n\r\nWhether you\'re dressing for a casual day out or a cozy evening gathering, this kurta set is your go-to choice for timeless comfort and charm.', 'uploads/subcategories/1753041119_Cotton 2-Piece Sets-3.jpg', 'uploads/subcategories/1753041119_Cotton 2-Piece Sets-1.jpg', 'uploads/subcategories/1753041119_Cotton 2-Piece Sets-2.jpg', 'uploads/subcategories/1753041119_Cotton 2-Piece Sets-4.jpg', NULL),
(68, 114, 'Blush Pink Frill Saree', 'IshaHiya', 1750.00, 'Product Details\r\nIshaHiya9262\r\n\r\nIntroducing the latest launch – a ready-to-wear georgette frill saree with a designer multi-layer ruffle drape and attached dupatta. Crafted in faux georgette with multi-thread embroidery and sequence work, this piece blends elegance with convenience. Comes with an unstitched blouse and fits up to size 42.\r\n\r\nFull Product Description\r\n\r\nDesign: Draped Lehenga Style Saree – Ready to Wear\r\n\r\nColors Available: 3 Elegant Shades (As Shown in Picture)\r\n\r\nSaree / Lehenga Fabric:\r\n\r\nMaterial: Premium Faux Georgette\r\n\r\nDesign: Multi-Thread Embroidery with 3-Layer Ruffle & Attached Dupatta\r\n\r\nWork: Multi Thread Embroidery + Sequence + Fancy Lace Border\r\n\r\nInner: Micro Cotton for Comfortable Fit\r\n\r\nStitching: Ready-to-Wear – Fits up to Size 42\r\n\r\nBlouse:\r\n\r\nFabric: Faux Georgette\r\n\r\nWork: Matching Multi-Thread Embroidery with Sequence\r\n\r\nType: Unstitched – Can Be Tailored Up to Size 44\r\n\r\nIdeal For: Wedding Functions | Bridesmaids | Festive Wear | Boutique Displays\r\n\r\nComments/Reviews Of Product', 'uploads/subcategories/1753205238_Blush_Pink_Frill_Saree-1(1499).jpg', 'uploads/subcategories/1753205432_Blush_Pink_Frill_Saree-1-1.jpg', 'uploads/subcategories/1753205432_Blush_Pink_Frill_Saree-1-2.jpg', 'uploads/subcategories/1753205432_Blush_Pink_Frill_Saree-1(1499).jpg', 'Sarees'),
(69, 118, 'Blush Pink Frill Saree', 'IshaHiya', 1750.00, 'Product Details\r\nIshaHiya9262\r\n\r\nIntroducing the latest launch – a ready-to-wear georgette frill saree with a designer multi-layer ruffle drape and attached dupatta. Crafted in faux georgette with multi-thread embroidery and sequence work, this piece blends elegance with convenience. Comes with an unstitched blouse and fits up to size 42.\r\n\r\nFull Product Description\r\n\r\nDesign: Draped Lehenga Style Saree – Ready to Wear\r\n\r\nColors Available: 3 Elegant Shades (As Shown in Picture)\r\n\r\nSaree / Lehenga Fabric:\r\n\r\nMaterial: Premium Faux Georgette\r\n\r\nDesign: Multi-Thread Embroidery with 3-Layer Ruffle & Attached Dupatta\r\n\r\nWork: Multi Thread Embroidery + Sequence + Fancy Lace Border\r\n\r\nInner: Micro Cotton for Comfortable Fit\r\n\r\nStitching: Ready-to-Wear – Fits up to Size 42\r\n\r\nBlouse:\r\n\r\nFabric: Faux Georgette\r\n\r\nWork: Matching Multi-Thread Embroidery with Sequence\r\n\r\nType: Unstitched – Can Be Tailored Up to Size 44\r\n\r\nIdeal For: Wedding Functions | Bridesmaids | Festive Wear | Boutique Displays', 'uploads/subcategories/1753205771_Blush_Pink_Frill_Saree-1(1499).jpg', 'uploads/subcategories/1753205771_Blush_Pink_Frill_Saree-1-1.jpg', 'uploads/subcategories/1753205771_Blush_Pink_Frill_Saree-1-2.jpg', 'uploads/subcategories/1753205771_Blush_Pink_Frill_Saree-1(1499).jpg', 'Sarees'),
(70, 118, 'Luxury Frill Saree in Faux Georgette with Embroidered Blouse', 'IshaHiya', 1750.00, 'Product Details\r\nIshaHiya9262\r\n\r\nIntroducing the latest launch – a ready-to-wear georgette frill saree with a designer multi-layer ruffle drape and attached dupatta. Crafted in faux georgette with multi-thread embroidery and sequence work, this piece blends elegance with convenience. Comes with an unstitched blouse and fits up to size 42.\r\n\r\nFull Product Description\r\n\r\nDesign: Draped Lehenga Style Saree – Ready to Wear\r\n\r\nColors Available: 3 Elegant Shades (As Shown in Picture)\r\n\r\nSaree / Lehenga Fabric:\r\n\r\nMaterial: Premium Faux Georgette\r\n\r\nDesign: Multi-Thread Embroidery with 3-Layer Ruffle & Attached Dupatta\r\n\r\nWork: Multi Thread Embroidery + Sequence + Fancy Lace Border\r\n\r\nInner: Micro Cotton for Comfortable Fit\r\n\r\nStitching: Ready-to-Wear – Fits up to Size 42\r\n\r\nBlouse:\r\n\r\nFabric: Faux Georgette\r\n\r\nWork: Matching Multi-Thread Embroidery with Sequence\r\n\r\nType: Unstitched – Can Be Tailored Up to Size 44\r\n\r\nIdeal For: Wedding Functions | Bridesmaids | Festive Wear | Boutique Displays', 'uploads/subcategories/1753206074_Blush_Pink_Frill_Saree-4.jpg', 'uploads/subcategories/1753206074_Blush_Pink_Frill_Saree-5.jpg', 'uploads/subcategories/1753206074_Blush_Pink_Frill_Saree-5-1.jpg', 'uploads/subcategories/1753206074_Blush_Pink_Frill_Saree-5-2.jpg', 'Sarees'),
(71, 118, 'Elegant Black Partywear Frill Saree Multi Layer Style', 'IshaHiya', 1750.00, 'Product Details\r\nIshaHiya9262\r\n\r\nIntroducing the latest launch – a ready-to-wear georgette frill saree with a designer multi-layer ruffle drape and attached dupatta. Crafted in faux georgette with multi-thread embroidery and sequence work, this piece blends elegance with convenience. Comes with an unstitched blouse and fits up to size 42.\r\n\r\nFull Product Description\r\n\r\nDesign: Draped Lehenga Style Saree – Ready to Wear\r\n\r\nColors Available: 3 Elegant Shades (As Shown in Picture)\r\n\r\nSaree / Lehenga Fabric:\r\n\r\nMaterial: Premium Faux Georgette\r\n\r\nDesign: Multi-Thread Embroidery with 3-Layer Ruffle & Attached Dupatta\r\n\r\nWork: Multi Thread Embroidery + Sequence + Fancy Lace Border\r\n\r\nInner: Micro Cotton for Comfortable Fit\r\n\r\nStitching: Ready-to-Wear – Fits up to Size 42\r\n\r\nBlouse:\r\n\r\nFabric: Faux Georgette\r\n\r\nWork: Matching Multi-Thread Embroidery with Sequence\r\n\r\nType: Unstitched – Can Be Tailored Up to Size 44\r\n\r\nIdeal For: Wedding Functions | Bridesmaids | Festive Wear | Boutique Displays', 'uploads/subcategories/1753206324_Blush_Pink_Frill_Saree-2.jpg', 'uploads/subcategories/1753206324_Blush_Pink_Frill_Saree-3.jpg', 'uploads/subcategories/1753206324_Blush_Pink_Frill_Saree-6.jpg', 'uploads/subcategories/1753206324_Blush_Pink_Frill_Saree-6-1.jpg', 'Sarees'),
(72, 118, 'Banarasi Style Lichi Saree with Rich Contrast Blouse', 'IshaHiya', 1150.00, 'Product Details\r\nIshaHiya5443\r\n\r\nMake a royal statement with this Soft Lichi Silk Saree, designed with a beautifully woven rich pallu and full-body jacquard weaving. The saree exudes tradition and luxury, ideal for weddings, festive wear, and ethnic celebrations. Comes with a contrast blouse piece featuring an exclusive jacquard border for a premium finish.\r\n\r\nProduct Specifications:\r\n\r\nFabric: Soft Lichi Silk\r\n\r\nDesign: All-over Jacquard Weaving with Rich Pallu\r\n\r\nBlouse: Contrast Jacquard Border (Unstitched)\r\n\r\nStyle: Traditional | Elegant | Festive', 'uploads/subcategories/1753206652_Banarasi_Style_Lichi_Saree_-1.jpg', 'uploads/subcategories/1753206652_Banarasi_Style_Lichi_Saree_-3.jpg', 'uploads/subcategories/1753206652_Banarasi_Style_Lichi_Saree_-2.jpg', 'uploads/subcategories/1753206652_Banarasi_Style_Lichi_Saree_-4.jpg', 'Sarees'),
(73, 118, 'Banarsi Artsilk Traditional Saree', 'IshaHiya', 1000.00, 'Product Details\r\nDescription\r\nThis saree is perfect for multiple occasions — be it a casual party, festive event, or even daily wear to elevate your everyday look.\r\n\r\nThe solid design gives a classy and elegant appearance, guaranteed to turn heads.\r\n\r\nLook exceptionally gorgeous and stunning with this elegant saree.\r\n\r\nProduct Specifications\r\nSaree Length: 5.5 meters\r\n\r\nBlouse Piece Length: 0.8 meters (attached)\r\n\r\nPackage Contains\r\n1 saree with an attached blouse piece\r\n\r\nBlouse Disclaimer\r\nThe last image shows a detailed view of the blouse piece included with the saree.\r\n(Note: The blouse worn by the model is for styling purposes only.)\r\n\r\nWash Care\r\nDry clean only', 'uploads/subcategories/1753207483_Banarsi_Artsilk_Traditional_Saree-1(599).jpg', NULL, 'uploads/subcategories/1753207483_Banarsi_Artsilk_Traditional_Saree-2.jpg', 'uploads/subcategories/1753207483_Banarsi_Artsilk_Traditional_Saree-3.jpg', 'Sarees'),
(74, 118, 'Baby Pink Burberry Silk Saree', 'IshaHiya', 1500.00, 'Product Details\r\nIshaHiya3399\r\n\r\nExperience elegance with this Soft Burberry Silk Saree, beautifully crafted with real mirror handwork, embroidery, and intricate cutwork border. The saree comes with a matching mirror embroidered blouse piece, perfect for festive and party wear. Available in premium quality at wholesale and manufacturer rates.\r\n\r\nComplete Fabric & Design Details\r\n\r\nFabric: Soft Burberry Silk\r\n\r\nWork: Embroidery with Real Mirror Hand Work and Cutwork at Border\r\n\r\nSaree Length: 5.50 meters\r\n\r\nBlouse Piece: 0.80 meters (Unstitched with Mirror Embroidery Work)\r\n\r\nType: Party Wear / Festive Collection', 'uploads/subcategories/1753208076_Baby_Pink_Burberry_Silk_Saree-1(1149).jpg', 'uploads/subcategories/1753208076_Baby_Pink_Burberry_Silk_Saree-2.jpg', 'uploads/subcategories/1753208076_Baby_Pink_Burberry_Silk_Saree-1(1149).jpg', 'uploads/subcategories/1753208076_Baby_Pink_Burberry_Silk_Saree-2.jpg', 'Sarees'),
(75, 118, 'Premium Sequin Mirror Saree with Cutwork Border', 'IshaHiya', 1500.00, 'Product Details\r\nIshaHiya3905\r\n\r\nIndulge in elegance with this premium georgette sequin saree, adorned with all-over heavy mirror and sequin embroidery. The saree features a unique abstract cutwork border, adding a designer flair to the look. Comes with a fully stitched blouse featuring heavy embroidery and mirror cutwork on premium silk.\r\n\r\nComplete Fabric & Design Details\r\n\r\nSaree Fabric: Pure Georgette with All-Over Heavy Sequin & Mirror Work\r\n\r\nBorder: Designer Abstract Cutwork with Embroidery\r\n\r\nBlouse Fabric: Premium Silk with Both Side Heavy Embroidery & Mirror Cutwork – Fully Stitched\r\n\r\nStyle: Party Wear / Luxury Ethnic Wear\r\n\r\nStitching: Blouse comes Fully Stitched\r\n\r\nLook: Elegant, Glamorous & Contemporary', 'uploads/subcategories/1753208345_Premium_Sequin_Mirror_Saree_-1(1249).jpg', 'uploads/subcategories/1753208345_Premium_Sequin_Mirror_Saree_-2.jpg', 'uploads/subcategories/1753208345_Premium_Sequin_Mirror_Saree_-3.jpg', 'uploads/subcategories/1753208345_Premium_Sequin_Mirror_Saree_-1(1249).jpg', 'Sarees'),
(76, 118, 'Luxe Kanjivaram Silk Saree with Contrast Border', 'IshaHiya', 1250.00, 'Product Details\r\nIshaHiya4206\r\n\r\nIntroducing the timeless elegance of Kanchi Silks – crafted from soft and luxurious Kanjivaram silk. This saree embodies traditional charm with a rich texture and graceful drape. Perfect for weddings, festivals, or special occasions.\r\n\r\nProduct Details\r\n\r\nProduct: Kanchivaram Silk Saree\r\n\r\nFabric: Premium Soft Kanjivaram Silk\r\n\r\nTexture: Soft, Smooth & Lustrous Finish\r\n\r\nDrape: Elegant & Comfortable\r\n\r\nOccasion: Wedding, Festive, Traditional Functions\r\n\r\nBlouse: Contrast or Matching (As per design – optional to add)', 'uploads/subcategories/1753208641_Luxe_Kanjivaram_Silk_Saree-1(899).jpg', 'uploads/subcategories/1753208641_Luxe_Kanjivaram_Silk_Saree-2.jpg', 'uploads/subcategories/1753208641_Luxe_Kanjivaram_Silk_Saree-3.jpg', 'uploads/subcategories/1753208641_Luxe_Kanjivaram_Silk_Saree-4.jpg', 'Sarees'),
(83, 128, 'tes', 'IshaHiya', 100.00, 'itst ', 'uploads/subcategories/1753533231_A-line Kurta-2.jpg', 'uploads/subcategories/1753533231_A-line Kurta.avif', 'uploads/subcategories/1753533231_A-line Kurta-3.jpg', 'uploads/subcategories/1753533231_A-line Kurta-3.jpg', NULL),
(78, 101, 'Embroidery And Mirror Work', 'IshaHiya', 1650.00, '\r\nProduct Details\r\nIshaHiya6024\r\n\r\nStep into elegance with this premium Heavy Muslin Cotton Lehenga Set, featuring vibrant digital prints and real mirror work throughout. The fully-stitched lehenga includes a 4-meter flair and canvas patta border for volume, paired with an unstitched designer choli and a beautifully crafted dupatta with fancy latkan lace – perfect for festive and partywear buyers.\r\n\r\nLehenga:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print & Real Mirror Work\r\n\r\nInner: Micro\r\n\r\nLength: 42-44 Inches\r\n\r\nFlair: 4 Meters\r\n\r\nSize: Fully Stitched up to XXL (44) with Canvas Patta\r\n\r\nCholi:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print & Real Mirror Work\r\n\r\nType: Unstitched (1 Meter Fabric)\r\n\r\nDupatta:\r\n\r\nFabric: Heavy Muslin Cotton\r\n\r\nWork: Digital Print, Real Mirror Work & Fancy Latkan Lace\r\n\r\nLength: 2.20 Meters', 'uploads/subcategories/1753260456_Traditional Navratri Lehenga Set With Embroidery And Mirror Work Festival Collection-1.jpg', 'uploads/subcategories/1753260456_Traditional Navratri Lehenga Set With Embroidery And Mirror Work Festival Collection-2.jpg', 'uploads/subcategories/1753260456_Traditional Navratri Lehenga Set With Embroidery And Mirror Work Festival Collection-3.jpg', 'uploads/subcategories/1753260456_Traditional Navratri Lehenga Set With Embroidery And Mirror Work Festival Collection-4.jpg', NULL),
(79, 118, 'Jimmy Choo Saree with Multi Thread Embroidered Blouse', 'IshaHiya', 1500.00, 'Product Details\r\nIshaHiya5257\r\n\r\nExperience elegance with our premium Jimmy Choo saree featuring 3mm embroidery lace border, 5mm sequins butti, and intricate jati dori work.\r\nPaired with a rich mono diamond silk blouse adorned with multicolor thread and zari dori embroidery.\r\nIdeal for weddings and partywear, this saree set includes 5.5 meters saree and 1 meter blouse fabric.', 'uploads/subcategories/1753298955_Jimmy Choo Saree with Multi Thread Embroidered Blouse-2.jpg', 'uploads/subcategories/1753298955_Jimmy Choo Saree with Multi Thread Embroidered Blouse-1(1500).jpg', 'uploads/subcategories/1753298955_Jimmy Choo Saree with Multi Thread Embroidered Blouse-3.jpg', 'uploads/subcategories/1753298955_Jimmy Choo Saree with Multi Thread Embroidered Blouse-4.jpg', NULL),
(80, 127, 'Pure roman silk chaniya choli with heavy vintage duptta', 'IshaHiya', 1850.00, 'IshaHiya6130\r\n\r\nPure roman silk chaniya choli with heavy vintage duptta 🔥\r\n\r\nDno :- Chandrakala \r\n\r\n6 MTR flare L size\r\n\r\n41 waist \r\n41 length \r\n\r\nL size blouse with margin\r\nBlouse with heavy pads', 'uploads/subcategories/1753467110_WhatsApp Image 2025-07-25 at 12.09.58 PM (1).jpeg', 'uploads/subcategories/1753467110_WhatsApp Image 2025-07-25 at 12.09.58 PM.jpeg', 'uploads/subcategories/1753467110_WhatsApp Image 2025-07-25 at 12.09.55 PM.jpeg', 'uploads/subcategories/1753467110_WhatsApp Image 2025-07-25 at 12.09.54 PM.jpeg', NULL),
(82, 101, 'Pure roman silk chaniya choli with heavy vintage duptta', 'IshaHiya', 1850.00, 'IshaHiya6130 Pure roman silk chaniya choli with heavy vintage duptta Dno :- Chandrakala 6 MTR flare L size 41 waist 41 length L size blouse with margin Blouse with heavy pads', 'uploads/subcategories/1753472156_WhatsApp Image 2025-07-25 at 12.09.58 PM (1).jpeg', 'uploads/subcategories/1753472156_WhatsApp Image 2025-07-25 at 12.09.58 PM.jpeg', 'uploads/subcategories/1753472156_WhatsApp Image 2025-07-25 at 12.09.51 PM (1).jpeg', 'uploads/subcategories/1753472156_WhatsApp Image 2025-07-25 at 12.09.51 PM.jpeg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_arrivals`
--

DROP TABLE IF EXISTS `sub_arrivals`;
CREATE TABLE IF NOT EXISTS `sub_arrivals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_arrival_images`
--

DROP TABLE IF EXISTS `sub_arrival_images`;
CREATE TABLE IF NOT EXISTS `sub_arrival_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub_arrival_id` int DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_arrival_id` (`sub_arrival_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `created_at`, `username`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$NAXrJklx2j0KzLzUdaMS6.g89O4Ml/aad7ih/yjSjWm7uNqQY7qQ.', 'admin', '2024-11-13 09:35:44', NULL),
(2, 'UKI', 'Ukihunter77@gmail.com', '$2y$10$.MZrIcDjjYlrNs9rRBDmo.LiU/6EcMWUDc2mZgAOSbECt6SZDU2U6', 'user', '2024-11-13 09:35:55', NULL),
(9, 'test', 'bhavin@itcentre.org', '$2y$10$bubYa.9P9PwQM4YIOUDGg.bLBIpSz44l8AGJxi9TdGGlHgbp2ygAS', 'user', '2025-07-03 19:39:14', NULL),
(10, 'Ishani', 'ishanipatel323@gmail.com', '$2y$10$v8k1H0ShG42wdF5KQtgIDeuAzmVbNPvQoMCAOvTEINGXVy2vmQdaG', 'user', '2025-07-04 08:42:48', NULL),
(11, '', '', '$2y$10$Wz0Ht9JZ4iYPHhRzORbLh.0eZtGp0OH9e0DuFTaxJmUV7oZPvUXfu', 'admin', '2025-07-10 05:43:12', 'adminuser'),
(12, 'test', 'test@gmail.com', '$2y$10$TWdrIeFif.Oz.jWjrHR5.OqTwS3L8Ol4oWoYi2sZefwd.YZeZ0uVm', 'user', '2025-07-10 10:07:22', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
