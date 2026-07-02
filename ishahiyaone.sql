-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 25, 2026 at 08:01 PM
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
-- Database: `ishahiyaone`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `username`, `password`, `role`) VALUES
(7, NULL, NULL, 'admin', 'GopaL@1437', 'admin'),
(9, 'Admin12', 'Admin12@gmail.com', 'Admin12@gmail.com', '123456789', 'admin'),
(10, 'Ashutosh', 'ashutossh@gmail.com', 'ashutossh@gmail.com', 'Ashutosh@123', 'admin'),
(11, 'ashutosh', 'admin2@gmail.com', 'admin2@gmail.com', 'Ashutosh@123', 'admin'),
(12, 'admin3', 'admin3@gmail.com', 'admin3@gmail.com', 'Ashutosh@123', 'admin'),
(13, 'Admin User', 'admin@test.com', 'admin@test.com', 'password123', 'admin');

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
  `is_our_collection` tinyint DEFAULT '0',
  `is_new_arrival` tinyint DEFAULT '0',
  `is_best` tinyint DEFAULT '0',
  `main_category_id` int DEFAULT '0',
  `sub_category_id` int DEFAULT '0',
  `sku_no` varchar(100) DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `size` varchar(255) DEFAULT NULL,
  `is_bumper_offer` tinyint(1) DEFAULT '0',
  `bumper_title` varchar(255) DEFAULT NULL,
  `bumper_start_date` datetime DEFAULT NULL,
  `bumper_end_date` datetime DEFAULT NULL,
  `bumper_discount` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `all_category`
--

INSERT INTO `all_category` (`id`, `name`, `description`, `price`, `category`, `brand`, `Image1`, `Image2`, `Image3`, `Image4`, `created_at`, `updated_at`, `is_our_collection`, `is_new_arrival`, `is_best`, `main_category_id`, `sub_category_id`, `sku_no`, `quantity`, `size`, `is_bumper_offer`, `bumper_title`, `bumper_start_date`, `bumper_end_date`, `bumper_discount`) VALUES
(26, 'Allen Solly Polo T-Shirt', 'Premium Quality Fabric\r\n✔ Trendy High-Neck Design\r\n✔ Sleeveless Comfort Fit\r\n✔ Modern Poncho-Style Pattern\r\n✔ Lightweight & Breathable Material\r\n✔ Soft, Smooth & Skin-Friendly Fabric\r\n✔ Stylish Sporty Look\r\n✔ Perfect for Casual & Daily Wear\r\n✔ Easy to Wash & Quick Dry\r\n✔ Comfortable All-Day Wear', 450.00, 'AllGen Fashion Wear', 'IshaHiya', 'uploads/subshop/1781632901_6a318f8570326.png', NULL, NULL, NULL, '2026-06-16 18:01:41', '2026-06-24 20:17:43', 1, 0, 0, 7, 123, 'IHASPT-450', 5, '[\"S\",\"M\",\"L\",\"XL\",\"XXL\"]', 0, 'Megha Offer', '2026-06-23 17:00:00', '2026-06-30 17:00:00', 2),
(30, 'Puma T-Shirt', 'Classic polo collar with button placket\r\nSignature embroidered Puma logo on chest\r\nClean and minimalistic design\r\nPremium stitching for long-lasting durability\r\nModern regular-fit silhouette', 450.00, 'AllGen Fashion Wear', 'IshaHiya', 'uploads/subshop/1781640632_6a31adb852b8f.png', NULL, NULL, NULL, '2026-06-16 20:10:32', '2026-06-24 20:17:34', 1, 1, 0, 7, 123, 'IHPPTS', 5, '[\"XL\",\"XXL\"]', 0, 'mega offer', '2026-06-23 18:04:00', '2026-06-25 18:04:00', 20);

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
-- Table structure for table `billing_details`
--

DROP TABLE IF EXISTS `billing_details`;
CREATE TABLE IF NOT EXISTS `billing_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `address` text,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `Mode` varchar(50) DEFAULT NULL,
  `RefNo` varchar(100) DEFAULT NULL,
  `product_name` text,
  `TXNID` varchar(100) DEFAULT NULL,
  `sku_no` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `billing_details`
--

INSERT INTO `billing_details` (`id`, `user_id`, `fullname`, `email`, `mobile`, `alt_mobile`, `address`, `landmark`, `city`, `state`, `pincode`, `Mode`, `RefNo`, `product_name`, `TXNID`, `sku_no`, `total_amount`, `payment_status`, `created_at`) VALUES
(10, 12, 'ddwdwfewfwef', 'ashnandani@gmail.com', '7490026191', '7490026192', 'efwwefwefw', 'efwefwefwf', 'Anand', 'Gujarat', '388301', 'Razorpay', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-1W4CHZXED780', 'IHHE840g5i5', 135.70, 'SUCCESS', '2026-06-16 13:41:46'),
(11, 12, 'ddwdgegtgtgtrg', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewefwfwf', 'fwfewew', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-ZNIBQTXGJDCV', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:43:52'),
(12, 12, 'ddwdgegtgtgtrg', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewefwfwf', 'fwfewew', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-1K2LWRFVATNX', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:47:46'),
(13, 12, 'ddwdgegtgtgtrg', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewefwfwf', 'fwfewew', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-UHFGBRNY8WL2', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:47:59'),
(14, 12, 'ddwdgegtgtgtrg', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewefwfwf', 'fwfewew', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-154XYFQJDL2G', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:48:05'),
(15, 12, 'ddwdgegtgtgtrg', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewefwfwf', 'fwfewew', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-9V7618CTMESH', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:49:59'),
(16, 12, 'ddwddedewd', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewedde', 'wqeqwee', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-F3LOZXSUMCRV', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:50:24'),
(17, 12, 'ddwddedewd', 'ashnandani@gmail.com', '7490026191', '7201808176', 'edewedde', 'wqeqwee', 'Anand', 'Gujarat', '388301', 'COD', '', 'Dell Latitude 5420 i7 11TH  gen', 'ISHAHIYA-RP1QLCAY3VFM', 'IHHE840g5i5', 135.70, NULL, '2026-06-16 13:54:56'),
(18, 12, 'fewfewfwefewf', 'ashnandani@gmail.com', '7490026191', '7201808176', 'efwefwe', 'efewfewf', 'Anand', 'Gujarat', '388301', 'Razorpay', '', '🔴 Allen Solly Classic Red Polo – Premium Edition', 'ISHAHIYA-CKA5MWRNI4SE', 'IHASRPT-450', 5197.50, 'PENDING', '2026-06-16 19:58:34'),
(19, 12, 'rgergergerg', 'ashnandani@gmail.com', '7490026191', '7490026191', 'gergerg', 'wrerew', 'Anand', 'Gujarat', '388301', 'COD', '', '🔴 Allen Solly Classic Red Polo – Premium Edition', 'ISHAHIYA-DLWP9ZGO07YE', 'IHASRPT-450', 5197.50, NULL, '2026-06-16 19:59:33'),
(20, 7, 'Bhavin', 'bhavinpatel323@gmail.com', '9974328904', '', 'Anoopam mission', 'Mogri', 'Anand', 'Gujarat', '388001', 'Razorpay', '', '⚫ Allen Solly Classic Black Polo – Premium Edition', 'ISHAHIYA-PTKVJBURS71D', 'IHASPT-450', 5.25, 'PENDING', '2026-06-17 05:17:16'),
(21, 12, 'gsgdgdgdfg', 'ashnandani33@gmail.com', '7490026191', '7490026191', 'ewrwerwer', 'erwrwerw', 'Anand', 'Gujarat', '388301', 'Razorpay', '', 'fewfwefw, Allen Solly Polo T-Shirt', 'ISHAHIYA-EC5YS132GMI7', 'BUMPER--4, IHASPT-450', 1890.00, 'PENDING', '2026-06-24 18:58:43'),
(22, 12, 'afafaf', 'ashnandan444i@gmail.com', '7490026191', '9974328904', 'fsdfsf', 'sdfsdffds', 'Anand', 'Gujarat', '388301', 'COD', '', 'fewfwefw, Allen Solly Polo T-Shirt', 'ISHAHIYA-XYO960RHW7US', 'BUMPER--4, IHASPT-450', 1890.00, NULL, '2026-06-24 19:00:46'),
(23, 12, 'wefwferfger', 'ashnandani34@gmail.com', '7490026191', '7490026191', 'geggrrg', 'gegrer', 'Anand', 'Gujarat', '388301', 'COD', '', 'Puma T-Shirt', 'ISHAHIYA-SY89HGEO3JDL', 'IHPPTS', 2362.50, NULL, '2026-06-24 19:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `bumper_offers`
--

DROP TABLE IF EXISTS `bumper_offers`;
CREATE TABLE IF NOT EXISTS `bumper_offers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `sub_category_id` int DEFAULT NULL,
  `discount_percent` int DEFAULT '0',
  `promo_code` varchar(50) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bumper_offers`
--

INSERT INTO `bumper_offers` (`id`, `title`, `description`, `sub_category_id`, `discount_percent`, `promo_code`, `banner_image`, `start_date`, `end_date`, `status`) VALUES
(4, 'fewfwefw', '3', 123, 0, '', '1781900299_Printed 2-Piece Co-Ord Set-1(599).jpg', '2026-06-20 00:00:00', '2026-06-30 00:00:00', 1),
(5, '', '', NULL, 0, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `images1` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sku_no` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `size`, `quantity`, `images1`, `name`, `sku_no`, `price`, `created_at`) VALUES
(25, 12, 26, 'L', 1, 'uploads/subshop/1781632901_6a318f8570326.png', 'Allen Solly Polo T-Shirt', 'IHASPT-450', 450.00, '2026-06-24 19:18:59'),
(21, 7, 24, 'XL', 1, '1781638326_6a31a4b6046b6.png', '⚫ Allen Solly Classic Black Polo – Premium Edition', 'IHASPT-450', 5.00, '2026-06-17 05:15:49');

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
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Mobile_Number` varchar(20) DEFAULT NULL,
  `is_verified` tinyint DEFAULT '0',
  `Date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `Mobile_Number`, `is_verified`, `Date`) VALUES
(3, '7490026191', 1, '2026-06-16 19:44:38'),
(4, '9974328904', 1, '2026-06-17 05:15:49'),
(5, '7984748522', 1, '2026-06-24 19:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `gif_banner`
--

DROP TABLE IF EXISTS `gif_banner`;
CREATE TABLE IF NOT EXISTS `gif_banner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slider`
--

DROP TABLE IF EXISTS `hero_slider`;
CREATE TABLE IF NOT EXISTS `hero_slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `mobile_image` varchar(255) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL,
  `image_4` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text,
  `link` varchar(255) DEFAULT NULL,
  `btn_text` varchar(50) DEFAULT 'Shop Now',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hero_slider`
--

INSERT INTO `hero_slider` (`id`, `image`, `mobile_image`, `image_3`, `image_4`, `title`, `subtitle`, `link`, `btn_text`, `created_at`) VALUES
(33, 'slide_1781694603_8400.png', 'slide_mobile_1781694603_8911.png', NULL, NULL, 'Premium Round Neck Dry Fit Sports T-Shirt', '', 'shop.php', '', '2026-06-17 11:10:03'),
(34, 'slide_1781695607_6152.png', 'slide_mobile_1781695607_8077.png', NULL, NULL, '', '', 'shop.php', '', '2026-06-17 11:10:36'),
(35, 'slide_1781695975_8903.png', 'slide_mobile_1781696791_7766.png', NULL, NULL, '', '', 'shop.php', '', '2026-06-17 11:32:55'),
(36, 'slide_1781701214_4356.png', NULL, NULL, NULL, '', '', 'shop.php?main=Offer of the day', '', '2026-06-17 12:48:03');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text,
  `token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_category`
--

DROP TABLE IF EXISTS `main_category`;
CREATE TABLE IF NOT EXISTS `main_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `main_category_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon_class` varchar(255) DEFAULT 'fas fa-folder',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`id`, `main_category_name`, `slug`, `icon_class`) VALUES
(6, 'Electronics', 'electronics', 'fa-solid fa-tv'),
(7, 'AllGen Fashion Wear', 'all generation fashion wear', 'fa-solid fa-shirt'),
(11, 'Smart Home & Connected Living', 'smart-home-connected-living', 'fas fa-folder'),
(10, 'Networking & Structured Cabling', 'networking-structured-cabling', 'fas fa-folder'),
(15, 'Bulk SMS Services', 'bulk - sms - services', 'fas fa-folder'),
(17, 'Cattle Feed', 'cattle - feed', 'fa-solid fa-house'),
(19, 'Bumper Offer', 'Offer of the day', 'fa-solid fa-shirt');

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

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT '0',
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Contact` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` int NOT NULL DEFAULT '0',
  `pay_status` int NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'COD',
  `gst_amount` decimal(10,2) DEFAULT '0.00',
  `shipping_amount` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `address`, `Contact`, `order_date`, `order_status`, `pay_status`, `total_price`, `payment_mode`, `gst_amount`, `shipping_amount`) VALUES
(22, 0, 'ddwdgegtgtgtrg', 'edewefwfwf', '7490026191', '2026-06-16 13:47:46', 0, 0, 135.70, 'COD', 0.00, 0.00),
(23, 0, 'ddwdgegtgtgtrg', 'edewefwfwf', '7490026191', '2026-06-16 13:47:59', 0, 0, 135.70, 'COD', 0.00, 0.00),
(24, 0, 'ddwdgegtgtgtrg', 'edewefwfwf', '7490026191', '2026-06-16 13:48:05', 0, 0, 135.70, 'COD', 0.00, 0.00),
(25, 0, 'ddwdgegtgtgtrg', 'edewefwfwf', '7490026191', '2026-06-16 13:49:59', 0, 0, 135.70, 'COD', 0.00, 0.00),
(26, 0, 'ddwddedewd', 'edewedde', '7490026191', '2026-06-16 13:50:24', 0, 0, 135.70, 'COD', 0.00, 0.00),
(27, 0, 'ddwddedewd', 'edewedde', '7490026191', '2026-06-16 13:54:56', 0, 0, 135.70, 'COD', 0.00, 0.00),
(28, 12, 'fewfewfwefewf', 'efwefwe', '7490026191', '2026-06-16 19:58:34', 0, 0, 5197.50, '0', 247.50, 0.00),
(29, 12, 'rgergergerg', 'gergerg', '7490026191', '2026-06-16 19:59:33', 0, 0, 5197.50, '0', 247.50, 0.00),
(30, 7, 'Bhavin', 'Anoopam mission', '9974328904', '2026-06-17 05:17:16', 0, 0, 5.25, '0', 0.25, 60.00),
(31, 12, 'gsgdgdgdfg', 'ewrwerwer', '7490026191', '2026-06-24 18:58:43', 0, 0, 1890.00, '0', 90.00, 0.00),
(32, 12, 'afafaf', 'fsdfsf', '7490026191', '2026-06-24 19:00:46', 0, 0, 1890.00, '0', 90.00, 0.00),
(33, 12, 'wefwferfger', 'geggrrg', '7490026191', '2026-06-24 19:17:26', 0, 0, 2362.50, '0', 112.50, 0.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `size`, `quantity`, `price`, `image`) VALUES
(33, 22, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(34, 23, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(35, 24, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(36, 25, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(37, 26, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(38, 27, 'Dell Latitude 5420 i7 11TH  gen', 'One Size', 23, 5.00, NULL),
(39, 29, '🔴 Allen Solly Classic Red Polo – Premium Edition', 'XXL', 11, 450.00, NULL),
(40, 32, 'fewfwefw', '', 1, 0.00, NULL),
(41, 32, 'Allen Solly Polo T-Shirt', 'XXL', 4, 450.00, NULL),
(42, 33, 'Puma T-Shirt', 'XL', 5, 450.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pricing`
--

DROP TABLE IF EXISTS `pricing`;
CREATE TABLE IF NOT EXISTS `pricing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sms_package` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sms_count` int DEFAULT NULL,
  `paise_per_sms` float DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pricing`
--

INSERT INTO `pricing` (`id`, `sms_package`, `category`, `sms_count`, `paise_per_sms`, `sort_order`) VALUES
(1, '1 Lakh SMS Pack', 'Promotional', 100000, 12, 1),
(2, '5 Lakh SMS Pack', 'Promotional', 500000, 10, 2),
(3, '10 Lakh SMS Pack', 'Promotional', 1000000, 8, 3),
(4, 'Transactional Starter', 'Transactional', 50000, 15, 1);

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
  `sub_category_id` int DEFAULT '0',
  `size_ids` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '',
  `stock` int DEFAULT '0',
  `sku` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '',
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_best_product` tinyint(1) DEFAULT '0',
  `is_best` tinyint(1) DEFAULT '0',
  `is_new_arrival` int DEFAULT '1',
  `is_bumper_offer` tinyint(1) DEFAULT '0',
  `bumper_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bumper_start_date` datetime DEFAULT NULL,
  `bumper_end_date` datetime DEFAULT NULL,
  `bumper_discount` int DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int DEFAULT '0',
  `user_name` varchar(100) NOT NULL,
  `rating` tinyint NOT NULL,
  `review_title` varchar(255) DEFAULT '',
  `review_text` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `user_name`, `rating`, `review_title`, `review_text`, `created_at`) VALUES
(1, 23, 0, 'Bhav Patel', 5, '', 'Amazing polo shirt! The fabric quality is top notch and fits perfectly.', '2026-06-25 15:02:19'),
(2, 23, 0, 'Bhav Patel', 5, '', 'Amazing polo shirt! The fabric quality is top notch and fits perfectly.', '2026-06-25 15:02:19');

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

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

DROP TABLE IF EXISTS `promo_codes`;
CREATE TABLE IF NOT EXISTS `promo_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `offer_name` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `starts_at` date DEFAULT NULL,
  `ends_at` date DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_location` varchar(50) DEFAULT 'Homepage',
  `cta_text` varchar(100) DEFAULT 'Shop Now',
  `cta_link` varchar(255) DEFAULT 'shop.php',
  `link_best_sellers` tinyint(1) DEFAULT '0',
  `display_order` int DEFAULT '0',
  `linked_subcategory_id` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `offer_name`, `subtitle`, `status`, `starts_at`, `ends_at`, `code`, `discount`, `type`, `category`, `image`, `display_location`, `cta_text`, `cta_link`, `link_best_sellers`, `display_order`, `linked_subcategory_id`) VALUES
(8, 'Best Offer', '', 1, '2026-06-19', '2026-07-10', 'SAVE10', 10.00, 'Percentage', 'Flash Sale', '1781814151_2-Piece_Shirt___Pants_Set-2.jpg', 'Homepage', '', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `size_id` int NOT NULL AUTO_INCREMENT,
  `size_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`) VALUES
(16, 'S'),
(17, 'M'),
(18, 'L'),
(19, 'XL'),
(20, 'XXL'),
(21, 'Free Size'),
(22, 'One Size');

-- --------------------------------------------------------

--
-- Table structure for table `special_offer`
--

DROP TABLE IF EXISTS `special_offer`;
CREATE TABLE IF NOT EXISTS `special_offer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `timer_text` varchar(255) DEFAULT NULL,
  `active` tinyint DEFAULT '1',
  `sub_category_id` int DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `special_offer`
--

INSERT INTO `special_offer` (`id`, `title`, `image`, `timer_text`, `active`, `sub_category_id`, `start_date`, `end_date`) VALUES
(5, 'BUy 2 Get 1 Free', '1779285855_Regular Fit Gown Dress-4.jpg', 'fwf', 1, 102, '2026-05-20 00:00:00', '2026-06-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
  `id` int NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`) VALUES
(1, 'Andhra Pradesh'),
(2, 'Arunachal Pradesh'),
(3, 'Assam'),
(4, 'Bihar'),
(5, 'Chhattisgarh'),
(6, 'Goa'),
(7, 'Gujarat'),
(8, 'Haryana'),
(9, 'Himachal Pradesh'),
(10, 'Jharkhand'),
(11, 'Karnataka'),
(12, 'Kerala'),
(13, 'Madhya Pradesh'),
(14, 'Maharashtra'),
(15, 'Manipur'),
(16, 'Meghalaya'),
(17, 'Mizoram'),
(18, 'Nagaland'),
(19, 'Odisha'),
(20, 'Punjab'),
(21, 'Rajasthan'),
(22, 'Sikkim'),
(23, 'Tamil Nadu'),
(24, 'Telangana'),
(25, 'Tripura'),
(26, 'Uttar Pradesh'),
(27, 'Uttarakhand'),
(28, 'West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE IF NOT EXISTS `subcategories` (
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
  `is_our_collection` tinyint DEFAULT '0',
  `is_new_arrival` tinyint DEFAULT '0',
  `is_best` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Stock` int DEFAULT '0',
  `main_category_id` int DEFAULT '0',
  `sub_category_id` int DEFAULT '0',
  `category_id` int DEFAULT '0',
  `Size` varchar(255) DEFAULT 'One Size',
  `sku_no` varchar(100) DEFAULT '-',
  `quantity` int DEFAULT '0',
  `is_bumper_offer` tinyint(1) DEFAULT '0',
  `bumper_title` varchar(255) DEFAULT NULL,
  `bumper_start_date` datetime DEFAULT NULL,
  `bumper_end_date` datetime DEFAULT NULL,
  `bumper_discount` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `description`, `price`, `category`, `brand`, `Image1`, `Image2`, `Image3`, `Image4`, `is_our_collection`, `is_new_arrival`, `is_best`, `created_at`, `updated_at`, `Stock`, `main_category_id`, `sub_category_id`, `category_id`, `Size`, `sku_no`, `quantity`, `is_bumper_offer`, `bumper_title`, `bumper_start_date`, `bumper_end_date`, `bumper_discount`) VALUES
(23, '🔵 Allen Solly Classic Navy Blue Polo – Premium Edition', '🔵 Allen Solly Classic Navy Blue Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Allen Solly Navy Blue Polo Shirt, designed to deliver the perfect balance of modern style, comfort, and timeless elegance. The rich navy blue tone adds a smart, versatile, and premium look, making it ideal for casual outings, office wear, and semi-formal occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality cotton fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Allen Solly logo on chest pocket\r\nFunctional chest pocket for added style and utility\r\nClean stitching with a structured silhouette\r\nModern slim-fit look\r\n\r\nEvery detail reflects elegance with a contemporary touch.\r\n\r\n✔ Premium Cotton\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior stitching\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Classic Navy Blue Polo is a timeless wardrobe essential that offers a perfect blend of sophistication and everyday comfort. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, or formal pants, making it a versatile choice for office wear, casual outings, and modern daily styling.', 450.00, NULL, 'IshaHiya', '1781635789_6a319acd9ad1d.png', '1781635789_6a319acd9b037.png', '1781635789_6a319acd9b1aa.png', '1781635789_6a319acd9b313.png', 0, 0, 0, '2026-06-16 18:49:49', '2026-06-19 18:32:41', 5, 0, 0, 123, '[\"M\",\"L\",\"XL\",\"XXL\"]', 'IHASSBPT-450', 5, 0, NULL, NULL, NULL, 0),
(20, '⚫ Allen Solly Classic Light Grey Polo – Premium Edition', '⚫ Allen Solly Classic Light Grey Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Allen Solly Light Grey Polo Shirt, designed to deliver the perfect blend of modern style, comfort, and timeless elegance. The soft light grey tone adds a clean, versatile, and premium look, making it ideal for casual outings, office wear, and semi-formal occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality cotton fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nLightweight comfort for all-day wear\r\nSmooth finish with a premium feel\r\nDurable stitching for long-lasting performance\r\nStylish Design\r\n\r\nThe polo features a clean and classy silhouette enhanced with:\r\n\r\nElegant light grey colour with textured finish\r\nSignature Allen Solly embroidered deer logo\r\nSmart collared neck with button placket\r\nComfortable half sleeves\r\nModern tailored fit for a sharp appearance\r\nPerfect For\r\n\r\n✔ Casual daily wear\r\n✔ Office & smart casual styling\r\n✔ Travel & outings\r\n✔ Pairing with jeans, chinos, or trousers\r\n\r\nProduct Highlights\r\nBrand: Allen Solly\r\nColour: Light Grey\r\nFabric: Premium Cotton Blend\r\nFit: Regular Fit\r\nSleeve Type: Half Sleeve\r\nNeck Type: Polo Collar\r\nStyle: Casual / Smart Casual\r\n\r\n✨ A must-have premium polo t-shirt for men who prefer classy minimal fashion with maximum comfort.', 450.00, NULL, 'IshaHiya', '1781633638_6a31926608f4c.png', '1781633638_6a31926609212.png', '1781633953_9704.png', '1781633638_6a319266092f3.png', 0, 0, 0, '2026-06-16 18:13:58', '2026-06-19 18:32:41', 5, 0, 0, 123, '[\"M\",\"L\",\"XL\",\"XXL\"]', 'IHASLGPT-450', 5, 0, NULL, NULL, NULL, 0),
(21, '🔴 Allen Solly Classic Red Polo – Premium Edition', '🔴 Allen Solly Classic Red Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Allen Solly Red Polo Shirt, designed to deliver the perfect balance of bold style, comfort, and refined elegance. The rich red tone adds a vibrant, confident, and modern touch, making it an ideal choice for casual outings, weekend wear, and smart casual occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality cotton fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Allen Solly logo on chest pocket\r\nFunctional chest pocket for added style and utility\r\nClean stitching with a structured silhouette\r\nModern slim-fit look\r\n\r\nEvery detail reflects elegance with a contemporary edge.\r\n\r\n✔ Premium Cotton\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior stitching\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Classic Red Polo is a timeless wardrobe essential that combines premium comfort with a bold and stylish appearance. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, or casual sneakers, making it a versatile addition to your wardrobe for office casuals, evening outings, and everyday fashion.', 450.00, NULL, 'IshaHiya', '1781635364_6a31992499770.jpg', '1781635364_6a31992499ba2.jpg', '1781635400_8950.jpg', '1781635364_6a31992499cff.jpg', 0, 0, 0, '2026-06-16 18:42:44', '2026-06-24 20:20:44', 2, 0, 0, 123, '[\"M\",\"L\",\"XL\",\"XXL\"]', 'IHASRPT-450', 5, 0, NULL, NULL, NULL, 0),
(25, '⚫ Puma Classic Black Polo – Premium Edition', '⚫ Puma Classic Black Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Puma Black Polo Shirt, designed to deliver the perfect blend of sporty style, premium comfort, and modern elegance. The deep black tone creates a sleek and versatile look, making it ideal for casual wear, office-casual styling, travel, and weekend outings.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort and flexibility\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Puma logo on chest\r\nClean and minimalistic design\r\nPremium stitching for long-lasting durability\r\nModern regular-fit silhouette\r\n\r\nEvery detail reflects Puma\'s commitment to style, performance, and comfort.\r\n\r\n✔ Premium Fabric\r\nSoft, breathable & comfortable for daily wear\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior craftsmanship\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Puma Classic Black Polo is a timeless wardrobe essential that combines athletic inspiration with everyday sophistication. It pairs effortlessly with jeans, chinos, joggers, or shorts, making it a versatile choice for office casuals, weekend outings, travel, and daily wear. Its sleek black finish and iconic Puma branding create a confident, stylish look suitable for every occasion.', 450.00, NULL, 'IshaHiya', '1781640745_6a31ae2937af7.png', '1781640745_6a31ae2937e46.png', '1781640745_6a31ae2937f52.png', '1781640745_6a31ae293806f.png', 0, 0, 0, '2026-06-16 20:12:25', '2026-06-19 18:32:41', 5, 0, 0, 123, '[\"XL\",\"XXL\"]', 'IHBPPTS', 5, 0, NULL, NULL, NULL, 0),
(22, '⚫ Allen Solly Classic Dark Grey Polo – Premium Edition', '⚫ Allen Solly Classic Dark Grey Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Allen Solly Dark Grey Polo Shirt, designed to deliver the perfect balance of modern style, comfort, and timeless elegance. The rich dark grey tone adds a smart, versatile, and premium look, making it ideal for casual outings, office wear, and semi-formal occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality cotton fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Allen Solly logo on chest pocket\r\nFunctional chest pocket for added style and utility\r\nClean stitching with a structured silhouette\r\nModern slim-fit look\r\n\r\nEvery detail reflects elegance with a contemporary touch.\r\n\r\n✔ Premium Cotton\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior stitching\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Classic Dark Grey Polo is a timeless wardrobe essential that offers a perfect blend of sophistication and everyday comfort. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, or formal pants, making it a versatile choice for office wear, casual outings, and modern daily styling.', 450.00, NULL, 'IshaHiya', '1781635606_6a319a1608e2d.png', '1781635606_6a319a160921c.png', '1781635606_6a319a160935f.png', '1781635606_6a319a16094fa.png', 0, 0, 0, '2026-06-16 18:46:46', '2026-06-19 18:32:41', 5, 0, 0, 123, '[\"M\",\"L\",\"XL\",\"XXL\"]', 'IHASDGPT-450', 5, 0, NULL, NULL, NULL, 0),
(24, '⚫ Allen Solly Classic Black Polo – Premium Edition', '⚫ Allen Solly Classic Black Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Allen Solly Black Polo Shirt, designed to deliver the perfect balance of style, comfort, and timeless elegance. The deep black tone adds a smart, versatile, and premium look, making it ideal for casual outings, office wear, and semi-formal occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality cotton fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Allen Solly logo on chest pocket\r\nFunctional chest pocket for added style and utility\r\nClean stitching with a structured silhouette\r\nModern slim-fit look\r\n\r\nEvery detail reflects elegance with a contemporary touch.\r\n\r\n✔ Premium Cotton\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior stitching\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Classic Black Polo is a timeless wardrobe essential that offers a perfect blend of sophistication and everyday comfort. It pairs effortlessly with blue denim jeans, grey chinos, beige trousers, or formal pants, making it a versatile choice for office wear, casual outings, evening gatherings, and modern daily styling. Its sleek black finish delivers a confident, refined look that never goes out of fashion.', 5.00, NULL, 'IshaHiya', '1781638326_6a31a4b6046b6.png', '1781638326_6a31a4b604b23.png', '1781638326_6a31a4b604c9a.png', '1781638326_6a31a4b604ebf.png', 0, 0, 0, '2026-06-16 19:32:06', '2026-06-24 19:00:46', 1, 0, 0, 123, '[\"M\",\"L\",\"XL\",\"XXL\"]', 'IHASPT-450', 5, 0, NULL, NULL, NULL, 0),
(26, '🔵 Puma Classic Blue Polo – Premium Edition', '🔵 Puma Classic Blue Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Puma Blue Polo Shirt, designed to deliver the perfect blend of sporty style, premium comfort, and modern elegance. The rich blue tone adds a fresh, confident, and versatile touch, making it ideal for casual outings, office-casual wear, travel, and weekend adventures.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort and flexibility\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a refined and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Puma logo on chest\r\nClean and minimalistic design\r\nPremium stitching for long-lasting durability\r\nModern regular-fit silhouette\r\n\r\nEvery detail reflects Puma\'s commitment to performance, comfort, and contemporary style.\r\n\r\n✔ Premium Fabric\r\nSoft, breathable & comfortable for daily wear\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior craftsmanship\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Puma Classic Blue Polo is a timeless wardrobe essential that perfectly combines athletic inspiration with everyday sophistication. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, joggers, or shorts, making it a versatile choice for office casuals, weekend outings, travel, and daily wear. Its vibrant blue finish and iconic Puma branding create a confident, stylish look suitable for every occasion.', 450.00, NULL, 'IshaHiya', '1781641456_6a31b0f0642df.png', '1781641456_6a31b0f064563.png', '1781641456_6a31b0f064628.png', '1781641456_6a31b0f0646d9.png', 0, 0, 0, '2026-06-16 20:24:16', '2026-06-24 20:17:48', 0, 0, 0, 123, '[\"XL\",\"XXL\"]', 'IHPPTS', 5, 0, NULL, NULL, NULL, 0),
(27, '⚪ Puma Classic White Polo – Premium Edition', '⚪ Puma Classic White Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this sophisticated Puma White Polo Shirt, designed to deliver the perfect blend of sporty style, premium comfort, and timeless elegance. The crisp white tone adds a clean, fresh, and versatile look, making it ideal for casual wear, office-casual styling, travel, and weekend outings.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort and flexibility\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Puma logo on chest\r\nClean and minimalistic design\r\nPremium stitching for long-lasting durability\r\nModern regular-fit silhouette\r\n\r\nEvery detail reflects Puma\'s commitment to style, performance, and comfort.\r\n\r\n✔ Premium Fabric\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior craftsmanship\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Puma Classic White Polo is a timeless wardrobe essential that combines athletic inspiration with everyday sophistication. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, shorts, or joggers, making it a versatile choice for office casuals, weekend outings, travel, and daily wear. Its clean white finish and iconic Puma branding create a fresh, confident look suitable for every occasion.', 450.00, NULL, 'IshaHiya', '1781659787_6a31f88b16060.png', '1781659787_6a31f88b16539.png', '1781659787_6a31f88b166dc.png', '1781659787_6a31f88b16878.png', 0, 0, 0, '2026-06-17 01:29:47', '2026-06-25 17:44:44', 0, 0, 0, 123, '[\"XL\",\"XXL\"]', 'IHPPTS', 5, 1, 'mega offer', '2026-06-25 23:14:00', '2026-06-26 23:14:00', 20),
(28, '🔴 Puma Classic Red Polo – Premium Edition', '🔴 Puma Classic Red Polo – Premium Edition\r\n\r\nElevate your everyday wardrobe with this bold and sophisticated Puma Red Polo Shirt, designed to deliver the perfect blend of sporty style, premium comfort, and modern elegance. The vibrant red tone adds a confident, energetic, and eye-catching look, making it ideal for casual wear, outings, travel, and smart-casual occasions.\r\n\r\nFabric & Comfort\r\n\r\nCrafted from premium-quality fabric, this polo ensures:\r\n\r\nSoft and breathable texture\r\nAll-day comfort and flexibility\r\nLightweight feel for every season\r\n\r\nThe smooth fabric finish enhances durability while maintaining a rich and premium appearance.\r\n\r\nDesign & Detailing\r\nClassic polo collar with button placket\r\nSignature embroidered Puma logo on chest\r\nClean and minimalistic design\r\nPremium stitching for long-lasting durability\r\nModern regular-fit silhouette\r\n\r\nEvery detail reflects Puma\'s commitment to performance, style, and comfort.\r\n\r\n✔ Premium Fabric\r\nSoft, breathable & gentle on skin\r\n\r\n✔ Durable Quality\r\nBuilt to last with superior craftsmanship\r\n\r\n✔ Classic Fit\r\nComfortable fit for every body type\r\n\r\nOverall Appeal\r\n\r\nThe Puma Classic Red Polo is a timeless wardrobe essential that combines athletic inspiration with everyday sophistication. It pairs effortlessly with blue denim jeans, black chinos, beige trousers, shorts, or joggers, making it a versatile choice for weekend outings, casual office wear, travel, and daily styling. Its bold red finish and iconic Puma branding create a confident, energetic look that stands out on every occasion.', 450.00, NULL, 'IshaHiya', '1781660046_6a31f98e4ce6a.png', '1781660046_6a31f98e4d1e8.png', '1781660046_6a31f98e4d2e4.png', '1781660046_6a31f98e4d3c8.png', 0, 0, 0, '2026-06-17 01:34:06', '2026-06-24 20:19:40', 2, 0, 0, 123, '[\"XL\",\"XXL\"]', 'IHPPTS', 5, 0, NULL, NULL, NULL, 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subshop`
--

DROP TABLE IF EXISTS `subshop`;
CREATE TABLE IF NOT EXISTS `subshop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `sub_category`
--

DROP TABLE IF EXISTS `sub_category`;
CREATE TABLE IF NOT EXISTS `sub_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub_category_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `main_category_id` int NOT NULL,
  `icon` varchar(255) DEFAULT 'fas fa-circle',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `sub_category_name`, `slug`, `main_category_id`, `icon`) VALUES
(123, 'MEN Wear', 'MEN-Wear', 7, 'fa fa-male'),
(124, 'Mobile Devices', 'certified-pre-owned-mobile-devices', 6, 'fa fa-mobile'),
(122, 'Women Wear', 'Women-Wear', 7, 'fa fa-female'),
(120, 'Laptop', 'certified-pre-owned-laptop', 6, 'fa fa-laptop'),
(118, 'Drives and Storage', 'drives-and-storage', 6, 'fa fa-hdd'),
(119, 'Desktop Computers', 'certified-pre-owned-desktop-computers', 6, 'fa fa-desktop'),
(116, 'Wearable Tech', 'wearable-technology', 11, 'fa fa-clock'),
(117, 'Printers, Scanners & Copiers', 'Printers-Scanners-Copiers', 6, 'fa fa-print'),
(114, 'Home Security', 'home-security', 11, 'fa fa-lock'),
(115, 'Smart Energy Solutions', 'smart-energy-solutions', 11, 'fa fa-bolt'),
(113, 'Smart Lighting', 'smart-lighting', 11, 'fa fa-lightbulb'),
(112, 'GPS Devices', 'gps-devices', 11, 'fa fa-map-marker-alt'),
(110, 'Access Points & Routers', 'wifi-routers-access-points', 10, 'fa fa-network-wired'),
(111, 'Network Adapters & Hardware', 'network-adapters-hardware', 10, 'fa fa-network-wired'),
(109, 'Switches & Servers', 'Switches-Servers', 10, 'fa fa-network-wired'),
(121, 'Kids Wear', 'Kids-Wear', 7, 'fa fa-child'),
(108, 'Computer Monitors', 'certified-pre-owned-computer-monitors', 6, 'fa fa-desktop'),
(106, 'Couple Combo collection', 'couple-combo-collection', 7, 'fa fa-male'),
(107, 'Server System', 'certified-pre-owned-server-system', 6, 'fa fa-server'),
(105, 'Father Son Collection', 'father-son-collection', 7, 'fa fa-male'),
(104, 'Family Combo Collection', 'family-combo-collection', 7, 'fa fa-female'),
(103, 'Tablet & E-Reader Accessories', 'tablet-ereader-accessories', 6, 'fa fa-desktop'),
(102, 'Jewellery', 'fashion-jewellery', 7, 'fa fa-female'),
(101, 'CCTV systems', 'ctv-security-systems', 6, 'fa fa-lock'),
(100, 'Spy Gadgets & Cameras', 'spy-gadgets-cameras', 6, 'fa fa-lock'),
(99, 'Video Cameras & Accessories', 'video-cameras-accessories', 6, 'fa fa-lock'),
(98, 'Computers Components', 'computer-hardware-components', 6, 'fa fa-desktop'),
(97, 'Festival  Offers', 'Festival -offers', 19, 'fa fa-female'),
(96, 'Promotional Bulk SMS Services', 'promotional-bulk -SMS -services', 15, 'fa fa-network-wired'),
(95, 'Transactional Bulk SMS Services', 'transactional- bulk -SMS - services', 15, 'fa fa-network-wired'),
(94, 'Long Code Services', 'long - code - services', 15, 'fa fa-network-wired'),
(93, 'Short Code Services', 'short -code -services', 15, 'fa fa-network-wired'),
(92, 'Missed Call Alert Services', 'missed - call - alert - services', 15, 'fa fa-network-wired'),
(91, 'Voice Call Services', 'voice - call - services', 15, 'fa fa-network-wired'),
(90, 'Anoopam Calf Starter', 'anoopam - calf - starter', 17, 'fa fa-child'),
(89, 'Anoopam Buffalo Feed', 'anoopam - buffalo - feed', 17, 'fa fa-child'),
(88, 'Anoopam PowerMix Feed', 'anoopam - powerMix - feed', 17, 'fa fa-child'),
(87, 'Anoopam NutriMash Mixture', 'anoopam - nutriMash - mixture', 17, 'fa fa-child'),
(86, 'Anoopam Khor', 'anoopam - khor', 17, 'fa fa-child'),
(85, 'Anoopam Dan', 'anoopam - dan', 17, 'fa fa-male'),
(84, 'Offer of the day', 'Bumper Offer', 19, 'fa fa-male');

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
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','banned') COLLATE utf8mb4_general_ci DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `created_at`, `username`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$NAXrJklx2j0KzLzUdaMS6.g89O4Ml/aad7ih/yjSjWm7uNqQY7qQ.', 'admin', '2024-11-13 09:35:44', NULL, 'active'),
(11, '', '', '$2y$10$Wz0Ht9JZ4iYPHhRzORbLh.0eZtGp0OH9e0DuFTaxJmUV7oZPvUXfu', 'admin', '2025-07-10 05:43:12', 'adminuser', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(50) DEFAULT 'Active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `Type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `status`, `start_date`, `end_date`, `image`, `Type`) VALUES
(4, 'Active', '2026-05-20', '2026-05-29', '1779282887_Regular_Fit_Gown_Dress-1_500_.jpg', 'Festival'),
(5, 'Active', '2026-05-20', '2026-05-27', '1779284163_Regular_Fit_Gown_Dress-3.jpg', 'Festival');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`) VALUES
(6, 7, 6);

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
