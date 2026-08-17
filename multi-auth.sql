-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2026 at 02:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `multi-auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `category_id`, `content`, `status`, `image`, `likes`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', 1, 'test dfgdg', 'active', 'Blogs/test/1765449883_32buK8O7FemCpDuC38USuIW1FhaQHn839Undkmm5.jpg', 0, '2025-12-11 05:14:43', '2026-04-24 07:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_likes`
--

CREATE TABLE `blog_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `user_id`, `name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'i Phone', 'i-phone', 'iphone test', NULL, 'active', '2026-03-10 11:22:47', '2026-03-10 11:22:47'),
(2, NULL, 'Rajasthani Art House', 'rajasthani-art-house', 'Traditional Rajasthani paintings, miniature art and handcrafted products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(3, NULL, 'Heritage Handicrafts', 'heritage-handicrafts', 'Traditional Indian handicrafts and heritage-inspired handmade products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(4, NULL, 'Royal Miniature Arts', 'royal-miniature-arts', 'Hand-painted Indian miniature paintings inspired by royal and traditional art.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(5, NULL, 'Crafted India', 'crafted-india', 'Authentic handmade crafts and artistic products from Indian artisans.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(6, NULL, 'Desi Artisan', 'desi-artisan', 'Contemporary and traditional handmade products created by Indian artisans.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(7, NULL, 'Kala Heritage', 'kala-heritage', 'Indian traditional art, paintings, sculptures and handcrafted decor.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(8, NULL, 'The Artisan Studio', 'the-artisan-studio', 'Unique handmade artwork, decor and craft products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(9, NULL, 'Royal Rajasthan Crafts', 'royal-rajasthan-crafts', 'Rajasthani handicrafts, traditional decor and cultural art products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(10, NULL, 'Indian Craft Collective', 'indian-craft-collective', 'Curated handmade products from talented Indian artisans.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(11, NULL, 'KalaKriti', 'kalakriti', 'Creative handmade art, crafts and traditional Indian products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(12, NULL, 'Artisan Heritage', 'artisan-heritage', 'Premium handcrafted art and heritage-inspired products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(13, NULL, 'Mitti & Craft', 'mitti-and-craft', 'Handmade pottery, terracotta, ceramics and natural craft products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(14, NULL, 'Crafted Rajasthan', 'crafted-rajasthan', 'Authentic handcrafted products inspired by Rajasthan culture and traditions.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(15, NULL, 'Kala Kendra', 'kala-kendra', 'Traditional Indian artwork, handicrafts and artisan creations.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40'),
(16, NULL, 'Artisan Originals', 'artisan-originals', 'Original handmade artwork and unique artisan-made products.', NULL, 'active', '2026-08-08 07:24:40', '2026-08-08 07:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `slug`, `description`, `image`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'test', 'test', '<p>test</p>', '1781442631_img.jpg', NULL, 'active', '2025-12-11 05:14:20', '2026-06-14 13:10:31'),
(2, NULL, 'Rings', 'rings', NULL, '1781442930_img.jpg', NULL, 'active', '2026-06-14 10:42:03', '2026-06-14 13:15:30'),
(3, NULL, 'Earrings', 'earrings', NULL, '1781442942_img.jpg', NULL, 'active', '2026-06-14 10:42:24', '2026-06-14 13:15:42'),
(4, NULL, 'Necklace', 'necklace', NULL, '1781442463_img.webp', NULL, 'active', '2026-06-14 10:42:39', '2026-06-14 13:07:43'),
(5, NULL, 'Bracelets', 'bracelets', NULL, '1781442480_img.webp', NULL, 'active', '2026-06-14 10:42:56', '2026-06-14 13:08:00'),
(6, NULL, 'Paintings & Wall Art', 'paintings-wall-art', 'Traditional, contemporary and handmade paintings and wall art.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(7, NULL, 'Miniature Paintings', 'miniature-paintings', 'Traditional Indian miniature paintings including Rajasthani, Mughal and Pahari styles.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(8, NULL, 'Handmade Home Decor', 'handmade-home-decor', 'Handcrafted decorative products for homes and interiors.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(9, NULL, 'Handicrafts', 'handicrafts', 'Traditional and contemporary handmade handicraft products.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(10, NULL, 'Wooden Handicrafts', 'wooden-handicrafts', 'Handcrafted wooden art, sculptures, decor and utility products.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(11, NULL, 'Metal Handicrafts', 'metal-handicrafts', 'Handcrafted products made from brass, copper, iron and other metals.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(12, NULL, 'Pottery & Ceramics', 'pottery-ceramics', 'Handmade pottery, ceramics, terracotta and clay products.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(13, NULL, 'Sculptures & Statues', 'sculptures-statues', 'Handcrafted sculptures, figurines and decorative statues.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(14, NULL, 'Textile & Embroidery', 'textile-embroidery', 'Handmade textiles, embroidery, traditional fabrics and textile art.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(15, NULL, 'Bags & Accessories', 'bags-accessories', 'Handmade bags, wallets, pouches and traditional accessories.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(16, NULL, 'Jewellery & Accessories', 'jewellery-accessories', 'Handcrafted jewellery and traditional fashion accessories.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(17, NULL, 'Traditional Crafts', 'traditional-crafts', 'Traditional Indian crafts representing regional art and cultural heritage.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(18, NULL, 'Paper & Handmade Stationery', 'paper-handmade-stationery', 'Handmade paper products, journals, notebooks, cards and stationery.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(19, NULL, 'Decorative Art', 'decorative-art', 'Handmade decorative art pieces for homes, offices and special occasions.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15'),
(20, NULL, 'Gift & Collectibles', 'gifts-collectibles', 'Unique handmade gifts, collectibles and artistic products.', NULL, NULL, 'active', '2026-08-08 07:24:15', '2026-08-08 07:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `user_id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Red', '#FF0000', 'active', '2026-04-11 18:43:25', '2026-04-11 18:43:25'),
(2, NULL, 'Blue', '#0000FF', 'active', '2026-04-11 18:43:25', '2026-04-11 18:43:25'),
(3, NULL, 'Green', '#008000', 'active', '2026-04-11 18:43:25', '2026-04-11 18:43:25'),
(4, NULL, 'Black', '#000000', 'active', '2026-04-11 18:43:25', '2026-04-11 18:43:25'),
(5, NULL, 'White', '#FFFFFF', 'active', '2026-04-11 18:43:25', '2026-04-11 18:43:25'),
(6, NULL, 'Pink', NULL, 'active', '2026-04-13 07:36:40', '2026-04-13 07:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `discount_type` enum('percentage','flat') NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `user_id`, `code`, `discount_type`, `discount_amount`, `usage_limit`, `used_count`, `expiry_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, '05166363', 'percentage', 100.00, 1, 0, '2026-07-15', 1, '2026-04-12 01:35:04', '2026-06-18 15:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `discount_type` enum('percentage','flat') NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `apply_to_all` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `user_id`, `name`, `discount_type`, `discount_amount`, `apply_to_all`, `category_id`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Diwali Sale 2025', 'percentage', 10.00, 0, NULL, '2026-04-12', '2026-04-22', 1, '2026-04-12 01:36:08', '2026-04-13 07:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `discount_product`
--

CREATE TABLE `discount_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_product`
--

INSERT INTO `discount_product` (`id`, `discount_id`, `product_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_category_id`, `title`, `slug`, `url`, `parent_id`, `order`, `status`, `created_at`, `updated_at`) VALUES
(4, NULL, 'about', 'About-us', '/page/About-us', 7, 1, 1, '2025-12-11 05:35:36', '2026-08-09 07:22:01'),
(5, NULL, 'Cart', 'cart', '/cart', NULL, 1, 1, '2026-04-20 03:40:12', '2026-08-09 07:22:01'),
(6, NULL, 'ring', 'shop', '/products', 4, 1, 1, '2026-04-21 11:59:32', '2026-08-09 07:22:01'),
(7, NULL, 'abour', 'amethyst', NULL, NULL, 2, 1, '2026-08-09 07:21:41', '2026-08-09 07:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_29_115237_create_roles_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('subscribed','unsubscribed') NOT NULL DEFAULT 'subscribed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletters`
--

INSERT INTO `newsletters` (`id`, `email`, `status`, `created_at`, `updated_at`) VALUES
(4, 'piyushkumawat90607@gmail.com', 'subscribed', '2026-04-25 11:19:49', '2026-04-25 11:19:49'),
(5, 'aauyushkumawat284@gmail.com', 'subscribed', '2026-06-14 13:44:48', '2026-06-14 13:44:48'),
(6, 'admin@gmail.com', 'subscribed', '2026-06-14 13:45:25', '2026-06-14 13:45:25'),
(7, 'admin@example.com', 'subscribed', '2026-06-14 13:48:23', '2026-06-14 13:48:23'),
(8, 'deepakrout54321@gmail.com', 'subscribed', '2026-06-18 15:26:26', '2026-06-18 15:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0459c8e1-8745-4336-a8aa-99e8a5076dde', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-20 for \\u20b9180.\",\"url\":\"https:\\/\\/192.168.1.4\\/karigar\\/admin\\/orders\\/20\"}', NULL, '2026-08-11 13:32:43', '2026-08-11 13:32:43'),
('06fe767e-7a02-4e70-b41a-7a759f7e619d', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Rings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:15:30', '2026-06-14 14:02:17'),
('089dae20-c2e5-464e-b5ee-fd8b365592d2', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Necklace\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:07:44', '2026-06-14 13:07:44'),
('0a6240bc-6ec6-4c93-a85d-571de5d9b9e3', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-17 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/17\"}', NULL, '2026-06-20 08:24:28', '2026-06-20 08:24:28'),
('0b71d6e5-ed64-4622-8e68-3f0011430e6b', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-14 for \\u20b920.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/14\"}', NULL, '2026-06-20 07:26:11', '2026-06-20 07:26:11'),
('17b0e66b-f1c7-40ae-8ad0-ca0390610cfd', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'test\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:10:31', '2026-06-14 14:02:17'),
('1ad8a6c7-2740-4ce7-a7f7-df1b29e265f9', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Rings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 13:04:20', '2026-06-14 14:02:18'),
('1eb94aac-ee8e-416a-ab88-75a227d9cc2c', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-18 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/18\"}', NULL, '2026-06-20 08:24:56', '2026-06-20 08:24:56'),
('1f04f082-9c06-4896-a9dc-e0c7ba884b93', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-7 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/7\"}', NULL, '2026-06-20 05:49:52', '2026-06-20 05:49:52'),
('2227ef8e-302c-4d61-9a46-8e90d863e76a', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"review\",\"message\":\"New Subscriber: admin@example.com joined the newsletter.\",\"url\":\"#\"}', '2026-06-14 14:02:17', '2026-06-14 13:48:23', '2026-06-14 14:02:17'),
('2414aefd-aeaa-496c-81da-9c7ef7713df0', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"stock\",\"message\":\"Low Stock Alert: Only 0 left for test!\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/stock\\/3\\/edit\"}', NULL, '2026-04-24 06:33:35', '2026-04-24 06:33:35'),
('2615b611-94ce-4ef0-8c1a-8e52fdb698b0', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-7 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/7\"}', NULL, '2026-06-20 05:49:52', '2026-06-20 05:49:52'),
('2746db9a-2645-473f-8c5b-c9dfcbf29f27', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Rings\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 10:42:04', '2026-06-14 10:42:04'),
('292051fb-0fe9-468b-8a65-e61a8b4ae18f', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-10 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/10\"}', NULL, '2026-06-20 06:17:27', '2026-06-20 06:17:27'),
('296fa055-1fbd-48a3-8153-f82aeecfa5f9', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-9 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/9\"}', NULL, '2026-06-20 05:51:12', '2026-06-20 05:51:12'),
('2d8910c8-37b6-4bdc-a1d1-dd9a3660c56e', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-13 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/13\"}', NULL, '2026-06-20 07:15:15', '2026-06-20 07:15:15'),
('3962290e-99c7-4af3-879a-df721d22b6a1', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-16 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/16\"}', NULL, '2026-06-20 07:59:38', '2026-06-20 07:59:38'),
('39fe035d-4817-4c1a-842f-dc9ac630621c', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'test\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:10:31', '2026-06-14 13:10:31'),
('3c9b898a-b1de-472d-aa56-5a6a461fb68d', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-10 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/10\"}', NULL, '2026-06-20 06:17:27', '2026-06-20 06:17:27'),
('3d2a7752-a626-4d0f-8e10-023fc30c4dc3', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-11 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/11\"}', NULL, '2026-06-20 06:36:55', '2026-06-20 06:36:55'),
('3fba75ea-3460-4e1b-bc85-e3ccf8bed726', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-12 for \\u20b920.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/12\"}', NULL, '2026-06-20 07:11:32', '2026-06-20 07:11:32'),
('43fe3b59-73a2-48c6-97b8-a6fb4718fa20', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"stock\",\"message\":\"Low Stock Alert: Only 1 left for test!\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/stock\\/3\\/edit\"}', NULL, '2026-06-20 07:14:43', '2026-06-20 07:14:43'),
('46d87be1-a7e8-43c6-9d28-db6bfcfc0fc0', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Earrings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:15:42', '2026-06-14 13:15:42'),
('4a03d41a-1f72-4751-a4d4-30f5b46a6196', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-18 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/18\"}', NULL, '2026-06-20 08:24:56', '2026-06-20 08:24:56'),
('4c1d3213-2400-4fb5-bf40-c75723880904', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Necklace\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 10:42:39', '2026-06-14 10:42:39'),
('550d2db0-3550-45c4-bc34-67ac121cd246', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-16 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/16\"}', NULL, '2026-06-20 07:59:38', '2026-06-20 07:59:38'),
('5573ea89-2d8f-4444-9d55-c132d109d35f', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-5 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/5\"}', NULL, '2026-06-20 05:25:13', '2026-06-20 05:25:13'),
('56d3d8c0-a03c-44e0-a665-e29ddcad0468', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-5 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/5\"}', NULL, '2026-06-20 05:25:13', '2026-06-20 05:25:13'),
('5b25ab31-17a0-435f-976b-62e8be00cb4b', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-14 for \\u20b920.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/14\"}', NULL, '2026-06-20 07:26:11', '2026-06-20 07:26:11'),
('650224ea-73d4-4b21-98a4-2f9a4ee6d679', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-6 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/6\"}', NULL, '2026-06-20 05:49:44', '2026-06-20 05:49:44'),
('65045542-fb07-4bc5-945c-16853ee4ee36', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"system\",\"message\":\"SECURITY ALERT: System SMTP (Email) settings were modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/smtp-settings\"}', '2026-04-24 06:45:26', '2026-04-24 06:45:10', '2026-04-24 06:45:26'),
('6529de0e-52d4-4083-87c5-89fbfb589178', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-13 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/13\"}', NULL, '2026-06-20 07:15:15', '2026-06-20 07:15:15'),
('65f2ecf8-fde9-42b3-89fa-8fa9f713745f', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Earrings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 13:07:28', '2026-06-14 14:02:18'),
('6ab83695-67b2-40d7-b34a-75590f7e1dad', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-19 for \\u20b950.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/19\"}', NULL, '2026-06-20 08:27:23', '2026-06-20 08:27:23'),
('71976917-6951-4033-a90f-e6d8d63a5251', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"system\",\"message\":\"SECURITY ALERT: System SMTP (Email) settings were modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/smtp-settings\"}', '2026-04-24 06:42:56', '2026-04-24 06:42:38', '2026-04-24 06:42:56'),
('747e7508-ec54-4865-8d55-bd853e9b2e7f', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"system\",\"message\":\"SECURITY ALERT: System SMTP (Email) settings were modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/smtp-settings\"}', '2026-04-24 06:45:26', '2026-04-24 06:45:01', '2026-04-24 06:45:26'),
('74c2e1d3-b9fb-4898-b620-ecec4be59b84', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'test\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:08:20', '2026-06-14 13:08:20'),
('74c873cd-ed35-4f17-9617-0ab395e6ac25', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"review\",\"message\":\"New Subscriber: deepakrout54321@gmail.com joined the newsletter.\",\"url\":\"#\"}', NULL, '2026-06-18 15:26:29', '2026-06-18 15:26:29'),
('793f0cf5-08b8-406f-ac58-d8d931832f2c', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Bracelets\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:08:00', '2026-06-14 14:02:17'),
('7cdf8974-0082-4d81-8e12-6a2508d46089', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Menu Updated: Navigation link \'ring\' was modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/menus\"}', '2026-06-14 14:02:18', '2026-06-14 05:48:37', '2026-06-14 14:02:18'),
('7dcee1f1-5f39-4277-8a21-6a3bd3ea4140', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-17 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/17\"}', NULL, '2026-06-20 08:24:28', '2026-06-20 08:24:28'),
('7ec53b82-6843-4c6f-aa18-7c491c9d877e', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Necklace\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:07:43', '2026-06-14 14:02:17'),
('8743b4d9-6d84-4729-b7c8-148c8f4285df', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-15 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/15\"}', NULL, '2026-06-20 07:41:36', '2026-06-20 07:41:36'),
('905a407b-2721-478a-977c-5aa610e9302d', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"review\",\"message\":\"Blog Post \'test\' status changed to 1.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/blogs\\/1\\/edit\"}', '2026-04-24 07:06:35', '2026-04-24 07:02:15', '2026-04-24 07:06:35'),
('919345b8-d960-48cc-8356-734e4166796d', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-6 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/6\"}', NULL, '2026-06-20 05:49:44', '2026-06-20 05:49:44'),
('919778d2-4ce3-4ed5-93da-29aee5efcd15', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-19 for \\u20b950.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/19\"}', NULL, '2026-06-20 08:27:23', '2026-06-20 08:27:23'),
('939ea7b2-708e-47ae-8ac7-2a22c0bd724c', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Earrings\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 10:42:24', '2026-06-14 10:42:24'),
('9465d530-8724-4bf3-980c-5d16f76d3000', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Rings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:15:30', '2026-06-14 13:15:30'),
('a47a5e7a-7bce-4d39-9c15-bef04b043e8e', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-20 for \\u20b9180.\",\"url\":\"https:\\/\\/192.168.1.4\\/karigar\\/admin\\/orders\\/20\"}', NULL, '2026-08-11 13:32:43', '2026-08-11 13:32:43'),
('a799b9d3-a70b-4a1a-87e2-44620be66f7c', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Bracelets\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:08:00', '2026-06-14 13:08:00'),
('abdb0fe9-b7a4-419a-99b0-1ac3702247cf', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Necklace\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 10:42:39', '2026-06-14 14:02:18'),
('ae17e438-043a-4074-8744-2ba12e50aad7', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Rings\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 10:42:04', '2026-06-14 14:02:18'),
('aebc9c7a-7f47-4974-bb98-ca5525eda8f6', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Menu Updated: Navigation link \'ring\' was modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/menus\"}', '2026-06-14 14:02:18', '2026-06-14 05:47:58', '2026-06-14 14:02:18'),
('b5f1cf6c-88ef-46a4-a365-b9efeb209789', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-8 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/8\"}', NULL, '2026-06-20 05:50:11', '2026-06-20 05:50:11'),
('b63a17dd-67dc-47d4-a2c3-bcd55154f49b', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Bracelets\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 10:42:56', '2026-06-14 10:42:56'),
('b9cefb73-26b5-4c09-80bc-4ac8a1d684bd', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Rings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:04:20', '2026-06-14 13:04:20'),
('bb84f922-911c-4843-9a60-43812dfa9689', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"stock\",\"message\":\"Low Stock Alert: Only 0 left for test!\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/stock\\/3\\/edit\"}', '2026-04-24 06:39:28', '2026-04-24 06:33:35', '2026-04-24 06:39:28'),
('bb9603b2-f1d2-4799-a597-daa3d843baf3', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Earrings\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 10:42:24', '2026-06-14 14:02:18'),
('be17fa0a-84b7-4a97-81a1-5bc9ae0f68c2', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-12 for \\u20b920.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/12\"}', NULL, '2026-06-20 07:11:32', '2026-06-20 07:11:32'),
('bf04a4bc-9f6d-49a7-90b2-59a146cfd537', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Menu Updated: Navigation link \'ring\' was modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/menus\"}', '2026-06-14 14:02:18', '2026-06-14 05:48:56', '2026-06-14 14:02:18'),
('c0d8b88d-882e-424f-a2f6-a1784ea2d9aa', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"New Product Category added: \'Bracelets\'.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:18', '2026-06-14 10:42:56', '2026-06-14 14:02:18'),
('c39076ce-afaa-4bed-93d0-19b356881573', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-9 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/9\"}', NULL, '2026-06-20 05:51:12', '2026-06-20 05:51:12'),
('c6d9bc58-f4c3-4c1e-af1d-f2072ef55538', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'test\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:08:20', '2026-06-14 14:02:17'),
('c858a0be-0d04-4eca-9bed-37e3b132b07b', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"setting\",\"message\":\"Category \'Earrings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', NULL, '2026-06-14 13:07:28', '2026-06-14 13:07:28'),
('c8d9275a-3d8a-44d4-addb-7139a81c7361', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-15 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/15\"}', NULL, '2026-06-20 07:41:36', '2026-06-20 07:41:36'),
('cc0818d7-0c67-46cd-8ded-14a71143f05e', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Menu Updated: Navigation link \'ring\' was modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/menus\"}', '2026-06-14 14:02:18', '2026-06-14 10:06:44', '2026-06-14 14:02:18'),
('d9439544-8c9f-4305-b213-f6161858facc', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"review\",\"message\":\"Blog Post \'test\' status changed to 1.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/blogs\\/1\\/edit\"}', NULL, '2026-04-24 07:02:15', '2026-04-24 07:02:15'),
('deb001a1-8049-4558-9a91-5d727524e2fe', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-8 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/8\"}', NULL, '2026-06-20 05:50:11', '2026-06-20 05:50:11'),
('e95d6e38-97af-40ba-80b0-abe629c42d38', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"review\",\"message\":\"New Subscriber: admin@example.com joined the newsletter.\",\"url\":\"#\"}', NULL, '2026-06-14 13:48:23', '2026-06-14 13:48:23'),
('ea58a973-dfdc-4f29-8a45-51342d88ae22', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"system\",\"message\":\"SECURITY ALERT: System SMTP (Email) settings were modified.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/smtp-settings\"}', '2026-04-24 06:42:56', '2026-04-24 06:42:52', '2026-04-24 06:42:56'),
('eb319e78-0e47-49ac-973a-650898500150', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"stock\",\"message\":\"Low Stock Alert: Only 1 left for test!\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/stock\\/3\\/edit\"}', NULL, '2026-06-20 07:14:43', '2026-06-20 07:14:43'),
('f255dff9-25c1-40c2-a902-a480080c1e06', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"review\",\"message\":\"New Subscriber: deepakrout54321@gmail.com joined the newsletter.\",\"url\":\"#\"}', NULL, '2026-06-18 15:26:28', '2026-06-18 15:26:28'),
('f3331321-707e-449b-b928-94fa48703e3f', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 10, '{\"type\":\"order\",\"message\":\"New Order Received! ID: #ORD-11 for \\u20b910.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/orders\\/11\"}', NULL, '2026-06-20 06:36:55', '2026-06-20 06:36:55'),
('f5095359-654f-434e-934c-518c5bbdd036', 'App\\Notifications\\AdminAlertNotification', 'App\\Models\\User', 3, '{\"type\":\"setting\",\"message\":\"Category \'Earrings\' was Activated.\",\"url\":\"http:\\/\\/localhost\\/multi-auth\\/admin\\/admin\\/categories\"}', '2026-06-14 14:02:17', '2026-06-14 13:15:42', '2026-06-14 14:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'COD',
  `payment_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_response` text DEFAULT NULL,
  `order_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `total_amount`, `payment_method`, `payment_status`, `transaction_id`, `payment_response`, `order_status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 100.00, 'COD', 'Pending', NULL, NULL, 'Pending', '2026-04-15 13:54:38', '2026-04-15 13:54:38'),
(2, 3, 'admin', 'admin@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 100.00, 'COD', 'Pending', NULL, NULL, 'Pending', '2026-04-16 12:45:39', '2026-04-16 12:45:39'),
(3, 3, 'admin', 'piyushkumawat90607@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 100.00, 'COD', 'Paid', NULL, NULL, 'Pending', '2026-04-16 13:06:55', '2026-04-16 13:29:17'),
(4, 3, 'admin', 'admin@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 1287.00, 'COD', 'Pending', NULL, NULL, 'Pending', '2026-04-18 17:05:58', '2026-04-18 17:05:58'),
(5, 4, 'Normal Usaaaer', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'RAZORPAY', 'Pending', NULL, NULL, 'Pending', '2026-06-20 05:25:13', '2026-06-20 05:25:13'),
(6, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'PAYU', 'Pending', 'PAYU61781934584', NULL, 'Pending', '2026-06-20 05:49:43', '2026-06-20 05:49:44'),
(7, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'CASHFREE', 'Pending', NULL, NULL, 'Pending', '2026-06-20 05:49:51', '2026-06-20 05:49:51'),
(8, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'CASHFREE', 'Pending', NULL, NULL, 'Pending', '2026-06-20 05:50:11', '2026-06-20 05:50:11'),
(9, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'GPAY', 'Pending', NULL, NULL, 'Pending', '2026-06-20 05:51:12', '2026-06-20 05:51:12'),
(10, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'PAYPAL', 'Pending', NULL, NULL, 'Pending', '2026-06-20 06:17:27', '2026-06-20 06:17:27'),
(11, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'RAZORPAY', 'Paid', 'pay_T3n2idtjgEus0Z', '{\"razorpay_order_id\":\"order_T3myjyTGaEWHkI\",\"razorpay_payment_id\":\"pay_T3n2idtjgEus0Z\",\"razorpay_signature\":\"0e04b6fc49d4c647b023935144e7626975ac8a0f665ebfa869d7f4a53f610780\"}', 'Pending', '2026-06-20 06:36:55', '2026-06-20 06:41:14'),
(12, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 20.00, 'PAYU', 'Pending', 'PAYU121781939492', NULL, 'Pending', '2026-06-20 07:11:32', '2026-06-20 07:11:32'),
(13, 4, 'Normal User', 'user@gmail.com', '1111111111', '13 bhreav nagar vistar benar raad', 'Barrow', 'Alaska', '30101', 10.00, 'PAYU', 'Pending', 'PAYU131781939716', NULL, 'Pending', '2026-06-20 07:15:15', '2026-06-20 07:15:16'),
(14, 4, 'Normal User', 'user@gmail.com', '1111111111', '13 bhreav nagar vistar benar raad', 'Barrow', 'Alaska', '30101', 20.00, 'PAYU', 'Paid', '403993715537724295', '{\"mihpayid\":\"403993715537724295\",\"mode\":\"CC\",\"status\":\"success\",\"unmappedstatus\":\"captured\",\"key\":\"hvU6V0\",\"txnid\":\"PAYU141781940371\",\"amount\":\"20.00\",\"cardCategory\":\"domestic\",\"discount\":\"0.00\",\"net_amount_debit\":\"20\",\"addedon\":\"2026-06-20 12:56:13\",\"productinfo\":\"Order  14\",\"firstname\":\"Normal User\",\"lastname\":null,\"address1\":null,\"address2\":null,\"city\":null,\"state\":null,\"country\":null,\"zipcode\":null,\"email\":\"user@gmail.com\",\"phone\":\"1111111111\",\"udf1\":null,\"udf2\":null,\"udf3\":null,\"udf4\":null,\"udf5\":null,\"udf6\":null,\"udf7\":null,\"udf8\":null,\"udf9\":null,\"udf10\":null,\"field1\":\"211125018522\",\"field2\":\"284921\",\"field3\":\"20.00\",\"field4\":null,\"field5\":\"00\",\"field6\":\"02\",\"field7\":\"AUTHPOSITIVE\",\"field8\":\"AUTHORIZED\",\"field9\":\"Transaction is Successful\",\"payment_source\":\"payu\",\"PG_TYPE\":\"CC-PG\",\"bank_ref_num\":\"718294119270339600\",\"bankcode\":\"CC\",\"error\":\"E000\",\"error_Message\":\"No Error\",\"cardnum\":\"XXXXXXXXXXXX2346\"}', 'Returns', '2026-06-20 07:26:11', '2026-06-22 06:28:20'),
(15, 4, 'Normal User', 'user@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'COD', 'Pending', NULL, NULL, 'Pending', '2026-06-20 07:41:36', '2026-06-20 07:41:36'),
(16, 4, 'Normal User', 'user@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'CASHFREE', 'Paid', 'CF161781942379', '{\"cart_details\":null,\"cf_order_id\":\"2210929605\",\"created_at\":\"2026-06-20T13:29:41+05:30\",\"customer_details\":{\"customer_id\":\"CUST4\",\"customer_name\":\"Normal User\",\"customer_email\":\"user@gmail.com\",\"customer_phone\":\"9782870390\",\"customer_uid\":null},\"entity\":\"order\",\"order_amount\":10.00,\"order_currency\":\"INR\",\"order_expiry_time\":\"2026-07-20T13:29:41+05:30\",\"order_id\":\"CF161781942379\",\"order_meta\":{\"return_url\":\"http://localhost/multi-auth/payment/16/callback\",\"notify_url\":null,\"payment_methods\":null},\"order_note\":null,\"order_splits\":[],\"order_status\":\"PAID\",\"order_tags\":null,\"payment_session_id\":\"session_k1e1QiNcLHFHLPo6_-EV_3DhZbnBMliVpD7ulaMQTLxKctGswGNBvhmLn3JO8FuIIZL62xAGD0hkVGbJnMwV2g2bjVawT76iryQHrnrZRJog7myVLPW6rLqnfQHSWQpaymentpayment\",\"terminal_data\":null}\n', 'Pending', '2026-06-20 07:59:38', '2026-06-20 08:03:24'),
(17, 4, 'Normal User', 'user@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'STRIPE', 'Pending', NULL, NULL, 'Pending', '2026-06-20 08:24:28', '2026-06-20 08:24:28'),
(18, 4, 'Normal User', 'user@gmail.com', '09680827272', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 10.00, 'STRIPE', 'Paid', NULL, NULL, 'Returns', '2026-06-20 08:24:56', '2026-06-21 18:05:18'),
(19, 4, 'Normal User', 'user@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'JAIPUR', 'Rajasthan', '302012', 50.00, 'STRIPE', 'Paid', 'pi_3TkKFuI1LAI1tggD1xfZnlYJ', '{\"id\":\"cs_test_a1U3bh8rURXtZPZHYBVO6ndvpvCtiz286j3pVlZxLvmyFDucDoP46dzeF5\",\"object\":\"checkout.session\",\"adaptive_pricing\":{\"enabled\":true},\"after_expiration\":null,\"allow_promotion_codes\":null,\"amount_subtotal\":5000,\"amount_total\":5000,\"automatic_tax\":{\"enabled\":false,\"liability\":null,\"provider\":null,\"status\":null},\"billing_address_collection\":null,\"branding_settings\":{\"background_color\":\"#ffffff\",\"border_style\":\"rounded\",\"button_color\":\"#0074d4\",\"display_name\":\"\",\"font_family\":\"default\",\"icon\":null,\"logo\":null},\"cancel_url\":\"http:\\/\\/localhost\\/multi-auth\\/checkout\",\"client_reference_id\":null,\"client_secret\":null,\"collected_information\":{\"business_name\":null,\"individual_name\":null,\"shipping_details\":null},\"consent\":null,\"consent_collection\":null,\"created\":1781944045,\"currency\":\"inr\",\"currency_conversion\":null,\"custom_fields\":[],\"custom_text\":{\"after_submit\":null,\"shipping_address\":null,\"submit\":null,\"terms_of_service_acceptance\":null},\"customer\":null,\"customer_account\":null,\"customer_creation\":\"if_required\",\"customer_details\":{\"address\":{\"city\":null,\"country\":\"IN\",\"line1\":null,\"line2\":null,\"postal_code\":null,\"state\":null},\"business_name\":null,\"email\":\"user@gmail.com\",\"individual_name\":null,\"name\":\"piyush\",\"phone\":null,\"tax_exempt\":\"none\",\"tax_ids\":[]},\"customer_email\":\"user@gmail.com\",\"discounts\":[],\"expires_at\":1782030445,\"integration_identifier\":null,\"invoice\":null,\"invoice_creation\":{\"enabled\":false,\"invoice_data\":{\"account_tax_ids\":null,\"custom_fields\":null,\"description\":null,\"footer\":null,\"issuer\":null,\"metadata\":[],\"rendering_options\":null}},\"livemode\":false,\"locale\":null,\"managed_payments\":{\"enabled\":false},\"metadata\":[],\"mode\":\"payment\",\"origin_context\":null,\"payment_intent\":\"pi_3TkKFuI1LAI1tggD1xfZnlYJ\",\"payment_link\":null,\"payment_method_collection\":\"if_required\",\"payment_method_configuration_details\":{\"id\":\"pmc_1SWI6qI1LAI1tggDZLjXNAKL\",\"parent\":null},\"payment_method_options\":{\"card\":{\"request_three_d_secure\":\"automatic\"}},\"payment_method_types\":[\"card\",\"link\"],\"payment_status\":\"paid\",\"permissions\":null,\"phone_number_collection\":{\"enabled\":false},\"recovered_from\":null,\"saved_payment_method_options\":null,\"setup_intent\":null,\"shipping_address_collection\":null,\"shipping_cost\":null,\"shipping_options\":[],\"status\":\"complete\",\"submit_type\":null,\"subscription\":null,\"success_url\":\"http:\\/\\/localhost\\/multi-auth\\/payment\\/19\\/callback?session_id={CHECKOUT_SESSION_ID}\",\"total_details\":{\"amount_discount\":0,\"amount_shipping\":0,\"amount_tax\":0},\"ui_mode\":\"hosted_page\",\"url\":null,\"wallet_options\":null}', 'Returns', '2026-06-20 08:27:23', '2026-06-22 05:29:07'),
(20, 4, 'Aaayush', 'user@gmail.com', '09680827272', '13, Bherav Nagar Vistar,Benad May Doulatpura', 'Jaipur', 'Rajasthan', '302012', 180.00, 'COD', 'Pending', NULL, NULL, 'Pending', '2026-08-11 13:32:43', '2026-08-11 13:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variation_info` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 'about', 'About-us', 'about as', 'active', '2025-12-11 05:35:14', '2025-12-11 05:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('aayushkumawat284@gmail.com', '$2y$12$ZUoVtaLkWgoS205z0LeTSubQU/Sb3tdS5AGKbaPmT1PVkB5ryDzru', '2025-08-29 03:07:58'),
('user@gmail.com', '$2y$12$2V588Uk4GhPY3.J/sPW8k.ns1pI4Ffi0yrOteyU.ILUxjZVT.K2dK', '2025-08-29 03:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `mode` enum('test','live') NOT NULL DEFAULT 'test',
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`credentials`)),
  `instructions` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `action`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'products', 'view', 'View Products', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(7, 'products', 'add', 'Add Products', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(8, 'products', 'edit', 'Edit Products & Stock', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(9, 'products', 'delete', 'Delete Products', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(10, 'categories', 'view', 'View Categories', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(11, 'categories', 'add', 'Add Categories', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(12, 'categories', 'edit', 'Edit Categories', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(13, 'categories', 'delete', 'Delete Categories', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(14, 'brands', 'view', 'View Brands', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(15, 'brands', 'add', 'Add Brands', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(16, 'brands', 'edit', 'Edit Brands', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(17, 'brands', 'delete', 'Delete Brands', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(18, 'colors', 'view', 'View Colors', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(19, 'colors', 'add', 'Add Colors', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(20, 'colors', 'edit', 'Edit Colors', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(21, 'colors', 'delete', 'Delete Colors', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(22, 'sizes', 'view', 'View Sizes', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(23, 'sizes', 'add', 'Add Sizes', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(24, 'sizes', 'edit', 'Edit Sizes', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(25, 'sizes', 'delete', 'Delete Sizes', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(26, 'orders', 'view', 'View Orders', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(27, 'orders', 'edit', 'Update Order Status', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(28, 'reviews', 'view', 'View Reviews', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(29, 'reviews', 'add', 'Add Reviews', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(30, 'reviews', 'edit', 'Edit/Moderate Reviews', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(31, 'reviews', 'delete', 'Delete Reviews', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(32, 'customers', 'view', 'View Customers', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(33, 'customers', 'edit', 'Edit Customers', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(34, 'customers', 'delete', 'Delete Customers', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(35, 'coupons', 'view', 'View Coupons', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(36, 'coupons', 'add', 'Add Coupons', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(37, 'coupons', 'edit', 'Edit Coupons', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(38, 'coupons', 'delete', 'Delete Coupons', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(39, 'discounts', 'view', 'View Discounts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(40, 'discounts', 'add', 'Add Discounts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(41, 'discounts', 'edit', 'Edit Discounts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(42, 'discounts', 'delete', 'Delete Discounts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(43, 'newsletter', 'view', 'Manage Newsletter', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(44, 'blogs', 'view', 'View Blogs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(45, 'blogs', 'add', 'Add Blogs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(46, 'blogs', 'edit', 'Edit Blogs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(47, 'blogs', 'delete', 'Delete Blogs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(48, 'blogs', 'status', 'Update Blog Status', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(49, 'posts', 'view', 'View Posts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(50, 'posts', 'add', 'Add Posts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(51, 'posts', 'edit', 'Edit Posts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(52, 'posts', 'delete', 'Delete Posts', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(53, 'pages', 'view', 'View Pages', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(54, 'pages', 'add', 'Add Pages', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(55, 'pages', 'edit', 'Edit Pages', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(56, 'pages', 'delete', 'Delete Pages', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(57, 'menus', 'view', 'View Menus', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(58, 'menus', 'add', 'Add Menus', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(59, 'menus', 'edit', 'Edit Menus', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(60, 'menus', 'delete', 'Delete Menus', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(61, 'staffs', 'view', 'View Staffs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(62, 'staffs', 'add', 'Add Staffs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(63, 'staffs', 'edit', 'Edit Staffs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(64, 'roles', 'view', 'View Roles', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(65, 'roles', 'add', 'Add Roles', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(66, 'permissions', 'view', 'View Permissions', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(67, 'permissions', 'add', 'Add Permissions', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(68, 'permissions', 'edit', 'Edit Permissions', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(69, 'permissions', 'delete', 'Delete Permissions', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(70, 'email', 'view', 'Manage Email Settings', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(71, 'settings', 'view', 'View Settings', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(72, 'settings', 'edit', 'Edit Settings', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(73, 'logs', 'view', 'View Activity Logs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(74, 'logs', 'delete', 'Clear Logs', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(75, 'backups', 'view', 'View Backups', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(76, 'backups', 'add', 'Generate Backups', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(77, 'backups', 'delete', 'Delete Backups', 1, '2026-04-23 16:22:35', '2026-04-23 16:22:35'),
(78, 'reports', 'view', 'View Reports', 1, '2026-06-20 16:38:46', '2026-06-20 16:38:46'),
(79, 'returns', 'view', 'View Returns & Refunds', 1, '2026-06-20 16:57:11', '2026-06-20 16:57:11'),
(80, 'returns', 'edit', 'Manage Returns & Refunds', 1, '2026-06-20 16:57:11', '2026-06-20 16:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `category_id`, `content`, `status`, `image`, `likes`, `created_at`, `updated_at`) VALUES
(1, 'testt', 'test', 1, 'test', 1, 'posts/test/1765450793_32buK8O7FemCpDuC38USuIW1FhaQHn839Undkmm5.jpg', 0, '2025-12-11 05:29:53', '2025-12-11 05:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `sku` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_order_qty` int(11) NOT NULL DEFAULT 1,
  `max_order_qty` int(11) DEFAULT NULL,
  `hover_image` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `video_url` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','pending','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `parent_id`, `name`, `slug`, `sku`, `description`, `category_id`, `thumbnail_image`, `brand_id`, `price`, `sale_price`, `stock`, `min_order_qty`, `max_order_qty`, `hover_image`, `gallery_images`, `video_url`, `color`, `size`, `weight`, `warranty`, `meta_title`, `meta_description`, `meta_keywords`, `tax_rate`, `shipping_cost`, `is_trending`, `is_new_arrival`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Garnet Heart Gold Vermeil Enamel ring', 'garnet-heart-gold-vermeil-enamel-ring', 'SKU-GAR-1765453146', 'Garnet Heart Gold Vermeil Enamel ringGarnet Heart Gold Vermeil Enamel ring', 2, NULL, 1, 100.00, NULL, 10, 1, 15, 'garnet-heart-gold-vermeil-enamel-ring-hover-1781419930.png', NULL, 'https://www.youtube.com/watch?v=Vb4qfhRaBTs&list=RDEMiTlq4oI_JaET17wlYZikIA&start_radio=1&rv=8CifN2yqdg4', 'red', NULL, 3.00, '4', 'test', 'test', 'test,test', NULL, 0.00, 1, 1, 'active', '2025-12-11 06:09:06', '2026-08-12 11:52:13'),
(2, NULL, NULL, 'Audrey Heart Larimar stud Earrings', 'audrey-heart-larimar-stud-earrings', 'SKU-AUD-1776360808', 'The Audrey Heart Larimar stud Earrings at Bautlr belong to that category of jewelry where they effortlessly fit into your style. The design of these earrings is done in such a way that they don\'t look extra when you style them. When you look at the earring from a distance, it will seem that the color is calm and as you move closer to it, you will see natural variations in the Larimar which marks its beauty.\r\n\r\nDesign\r\nThe heart-shaped larimar earrings bring a sense of warmth. However, it has been intentionally kept clean so that the center of attention is on the overall setting rather than the heart cut alone.\r\n\r\nDesign details include:\r\n\r\nNatural larimar gemstones shaped into a smooth, balanced heart cut\r\n\r\nA 10mm stone size that feels present without becoming heavy on the ear\r\n\r\nMinimal metal framing that supports the stone without distracting from it\r\n\r\nMaterial\r\nWhen it comes to metal settings, the earrings are available in 18K gold-plated metal. The reason behind selecting this metal is how naturally it complements the larimar\'s soft blue tones.\r\n\r\nMaterial highlights include:\r\n\r\nNickel-free and hypoallergenic construction, suitable for sensitive ears\r\n\r\nLightweight design that stays comfortable through long hours\r\n\r\nSmooth, even finishing that maintains a clean appearance over time\r\n\r\nAbout Larimar\r\nThe beauty of Larimar is that it comes with unique ocean-inspired hues and a calming presence. Here, every stone forms naturally, which means no two pieces are ever identical.\r\n\r\nTraditionally, larimar is associated with:\r\n\r\n1. Symbolic Meaning: These heart larimar gemstone earrings are associated with the feelings of emotional balance, calm communication, and mental clarity. As a result of this, it is strongly believed that it enhances thoughtful expression and brings the mind to a steady emotional state.\r\n\r\n2. Zodiac Associations: Larimar is commonly connected to Aries, Leo, and Pisces. The earrings are believed to help the zodiacs to remain grounded and balance their emotional stability. This hence forms an excellent gifting option for all the zodiacs mentioned here.\r\n\r\n3. Natural Character & Birthstone: While not a traditional birthstone, larimar is frequently chosen to mark personal milestones due to its rarity and individuality.\r\n\r\n4. Durability: Provided that the earrings are properly set, it tends to withstand daily use without losing its shine. To maintain its glow, it demands minimal care, thus appropriate for the long run.\r\n\r\nWhat Makes It Special\r\nWhat sets these earrings apart isn’t a single feature,.it’s the balance between them. The heart shape adds softness without feeling ornamental. The 10mm size keeps them noticeable but comfortable. The finish stays restrained, allowing the stone to remain the focus. These heart-shaped larimar earrings aren’t seasonal or statement-driven. They settle naturally into a wardrobe, becoming familiar rather than attention-seeking\r\n\r\nAudrey Heart Larimar Stud Earrings at Bautlr\r\nAt Bautlr, the focus is on how a piece lives beyond the first wear. Likewise, the Audrey Heart Larimar stud Earrings are shaped to sit naturally on the ear, crafted with materials chosen for consistency. So, explore the diverse ranges of earrings at Bautlr that align with your regular style.', 2, NULL, 1, 200.00, 300.00, 19, 0, 21, 'hover_1776501974.png', NULL, 'https://www.youtube.com/watch?v=Vb4qfhRaBTs&list=RDEMiTlq4oI_JaET17wlYZikIA&start_radio=1&rv=8CifN2yqdg4', NULL, NULL, 12.00, NULL, NULL, NULL, NULL, NULL, 4.99, 1, 1, 'active', '2026-04-16 17:33:28', '2026-08-12 11:52:06'),
(3, NULL, NULL, 'test', 'test', 'SKU-TES-1776940824', NULL, 2, NULL, NULL, 9.98, NULL, 7, 1, NULL, 'test-hover-1786287036.png', NULL, NULL, 'red', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1, 1, 'active', '2026-04-23 10:40:24', '2026-08-12 11:51:58'),
(4, NULL, NULL, 'penting', 'penting', 'SKU-PEN-1786456342', '<p>test penting</p>', 6, 'E:\\xampp 8.2\\tmp\\php12E2.tmp', NULL, 10.00, 8.00, 20, 1, 5, NULL, NULL, NULL, 'red', '5-5', 10.00, NULL, 'test', NULL, 'test,test', NULL, 5.00, 1, 1, 'active', '2026-08-11 13:52:22', '2026-08-12 11:51:52'),
(5, NULL, NULL, 'Tiger\'s', 'tigers', 'SKU-TIG-1786460419', '<p>tiger\'s</p>', 7, 'E:\\xampp 8.2\\tmp\\php47F1.tmp', NULL, 200.00, 150.00, 20, 1, 5, NULL, NULL, NULL, 'Orange', '5*4', 20.00, NULL, NULL, NULL, NULL, NULL, 50.00, 1, 1, 'active', '2026-08-11 15:00:19', '2026-08-12 11:51:44'),
(6, NULL, NULL, 'Elephant', 'elephant', 'SKU-ELE-1786461254', '<p>elephant</p>', 7, 'E:\\xampp 8.2\\tmp\\php9BF.tmp', NULL, 200.00, 190.00, 17, 1, NULL, 'elephant-hover-1786461286.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1, 1, 'active', '2026-08-11 15:14:14', '2026-08-12 11:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `side_image` varchar(255) DEFAULT NULL,
  `product_color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `side_image`, `product_color`, `created_at`, `updated_at`, `sort_order`, `is_primary`) VALUES
(52, 2, '1776509195_69e3610bf15bb_main.jpeg', '1776509195_69e3610bf15bb_side.jpeg', 'Red', '2026-04-18 10:46:36', '2026-08-12 11:52:06', 0, 0),
(53, 2, '1776509196_69e3610c3be70_main.png', '1776509196_69e3610c3be70_side.png', 'Red', '2026-04-18 10:46:36', '2026-08-12 11:52:06', 0, 0),
(54, 2, '1776509196_69e3610c93b66_main.jpg', '1776509196_69e3610c93b66_side.jpg', 'Red', '2026-04-18 10:46:36', '2026-08-12 11:52:06', 0, 0),
(55, 2, '1776509196_69e3610cbf4cc_main.png', '1776509196_69e3610cbf4cc_side.png', 'Red', '2026-04-18 10:46:37', '2026-08-12 11:52:06', 0, 0),
(56, 2, '1776509197_69e3610d4ff3f_main.png', '1776509197_69e3610d4ff3f_side.png', 'Blue', '2026-04-18 10:46:37', '2026-08-12 11:52:06', 0, 0),
(57, 2, '1776771936_69e76360c13e4_main.jpeg', '1776771936_69e76360c13e4_side.jpeg', 'Red', '2026-04-21 11:45:40', '2026-08-12 11:52:06', 0, 0),
(58, 1, 'garnet-heart-gold-vermeil-enamel-ring-red-gallery-1781430264-6a2e77f8210ac_main.png', 'garnet-heart-gold-vermeil-enamel-ring-red-gallery-1781430264-6a2e77f8210ac_side.png', 'Red', '2026-06-14 09:44:25', '2026-08-12 11:52:13', 0, 0),
(59, 5, 'tigers-gallery-1786460668-6a7b39fcf012e.jpg', 'tigers-gallery-1786460668-6a7b39fcf012e_side.jpg', NULL, '2026-08-11 15:04:31', '2026-08-12 11:51:44', 0, 0),
(60, 5, 'tigers-gallery-1786460671-6a7b39ff1cd0a.jpg', 'tigers-gallery-1786460671-6a7b39ff1cd0a_side.jpg', NULL, '2026-08-11 15:04:32', '2026-08-12 11:51:44', 0, 0),
(61, 5, 'tigers-gallery-1786460672-6a7b3a00d9f14.jpg', 'tigers-gallery-1786460672-6a7b3a00d9f14_side.jpg', NULL, '2026-08-11 15:04:34', '2026-08-12 11:51:44', 0, 0),
(62, 5, 'tigers-gallery-1786460674-6a7b3a02d9fb5.jpg', 'tigers-gallery-1786460674-6a7b3a02d9fb5_side.jpg', NULL, '2026-08-11 15:04:36', '2026-08-12 11:51:44', 0, 0),
(63, 6, 'elephant-red-gallery-1786461287-6a7b3c6700990_main.jpg', 'elephant-red-gallery-1786461287-6a7b3c6700990_side.jpg', 'Red', '2026-08-11 15:14:47', '2026-08-12 11:51:34', 0, 0),
(64, 6, 'elephant-red-gallery-1786461287-6a7b3c67c8a64_main.jpg', 'elephant-red-gallery-1786461287-6a7b3c67c8a64_side.jpg', 'Red', '2026-08-11 15:14:48', '2026-08-12 11:51:34', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `color`, `size`, `sku`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(227, 6, 'Red', 'S', 'SKU-ELE-1786461254-RED-S', 200.00, 10, 'elephant-red-s-1786461288-6a7b3c68a243b.jpg', '2026-08-12 11:51:35', '2026-08-12 11:51:35'),
(228, 5, 'Red', NULL, 'SKU-TIG-1786460419-RED-', 200.00, 20, 'tigers-red-1786460677-6a7b3a050b98b.jpg', '2026-08-12 11:51:44', '2026-08-12 11:51:44'),
(230, 3, 'Green', 'XS', NULL, 50.00, 9, 'test-1786287037-6a7893bda779a.png', '2026-08-12 11:51:58', '2026-08-12 11:51:58'),
(231, 2, 'Red', 'XS', 'SKU-AUD-1776360808-RED-XS', 190.00, 100, '1776502659_var_69e347838d51c.png', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(232, 2, 'Blue', 'S', 'SKU-AUD-1776360808-BLUE-S', 180.00, 99, '1776502659_var_69e34783c6717.png', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(233, 2, 'Green', 'M', 'SKU-AUD-1776360808-GREEN-M', 185.00, 100, '1776502660_var_69e347840d74b.png', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(234, 2, 'Black', 'L', 'SKU-AUD-1776360808-BLACK-L', 190.00, 100, 'audrey-heart-larimar-stud-earrings-1776941888-69e9fb4001cd9.jpeg', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(235, 2, 'Pink', 'XXL', 'SKU-AUD-1776360808-PINK-XXL', 170.00, 100, '1776502660_var_69e347848782f.jpg', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(236, 2, 'Red', 'M', NULL, 20.00, 2, 'audrey-heart-larimar-stud-earrings-1786439785-6a7ae86940b65.png', '2026-08-12 11:52:06', '2026-08-12 11:52:06'),
(237, 1, 'Red', 'XS', 'SKU-GAR-1765453146-RED-XS', 50.00, 500, 'garnet-heart-gold-vermeil-enamel-ring-1781447162-6a2eb9facc15a.png', '2026-08-12 11:52:13', '2026-08-12 11:52:13'),
(238, 1, 'Blue', 'S', 'SKU-GAR-1765453146-BLUE-S', 35.00, 400, NULL, '2026-08-12 11:52:13', '2026-08-12 11:52:13'),
(239, 1, 'Red', 'S', 'SKU-GAR-1765453146-RED-S', 55.00, 300, NULL, '2026-08-12 11:52:13', '2026-08-12 11:52:13'),
(240, 4, 'Red', 'M', 'SKU-PEN-1786456342-RED-M', 10.00, 0, 'penting-1786456367-6a7b292fac5c5.jpg', '2026-08-16 12:59:08', '2026-08-16 12:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `related_products`
--

CREATE TABLE `related_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `related_product_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_requests`
--

CREATE TABLE `return_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `reason` varchar(255) NOT NULL,
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `refund_status` varchar(255) NOT NULL DEFAULT 'Not Initiated',
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_method` varchar(255) DEFAULT NULL,
  `refund_reference` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_requests`
--

INSERT INTO `return_requests` (`id`, `order_id`, `user_id`, `order_item_id`, `product_id`, `quantity`, `reason`, `comment`, `image`, `status`, `refund_status`, `refund_amount`, `refund_method`, `refund_reference`, `admin_note`, `approved_at`, `refunded_at`, `created_at`, `updated_at`) VALUES
(1, 19, 4, 23, 3, 1, 'Damaged Product', 'hgjkjghfgfchkjgkgghugughgughjvhghhvhhhhgjkjghfgfchkjgkgghugughgughjvhghhvhhhhgjkjghfgfchkjgkgghugughgughjvhghhvhhhhgjkjghfgfchkjgkgghugughgughjvhghhvhhhhgjkjghfgfchkjgkgghugughgughjvhghhvhhh', 'r3SZmZisUPFBj3PXMzvJ.jpg', 'Rejected', 'Not Initiated', 10.00, NULL, NULL, 'test', NULL, NULL, '2026-06-21 17:24:41', '2026-06-21 17:54:20'),
(2, 18, 4, 22, 3, 1, 'Damaged Product', '1', 'yeoCyLtMp36n3vtd6oDk.jpg', 'Approved', 'Refunded', 10.00, 'Gateway (manual)', NULL, NULL, '2026-06-21 18:05:18', '2026-06-21 18:05:54', '2026-06-21 17:56:13', '2026-06-21 18:05:54'),
(3, 19, 4, 23, 3, 5, 'Damaged Product', '1', 'e0lFJHgk4tJN2RZfGOLU.jpg', 'Approved', 'Refunded', 50.00, 'Auto (STRIPE)', 're_3TkKFuI1LAI1tggD1C4U5p84', NULL, '2026-06-22 05:29:07', '2026-06-22 05:33:50', '2026-06-22 05:28:53', '2026-06-22 05:33:50'),
(4, 14, 4, 18, 3, 1, 'Damaged Product', 'ew', 't5W3QLbkc1waBPeLYlu9.jpg', 'Approved', 'Refunded', 10.00, 'Auto (PAYU)', '139985720', NULL, '2026-06-22 06:28:20', '2026-06-22 06:29:25', '2026-06-22 06:26:45', '2026-06-22 06:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) DEFAULT NULL,
  `reviewable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `comment` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0 COMMENT '1 = Verified Buyer',
  `is_spam` tinyint(1) DEFAULT 0 COMMENT '1 = Spam/Fake',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `reviewable_type`, `reviewable_id`, `rating`, `comment`, `status`, `is_verified`, `is_spam`, `created_at`, `updated_at`) VALUES
(1, 4, 'App\\Models\\Product', 2, 5, 'nice product', 1, 1, 0, '2026-04-23 16:00:38', '2026-08-16 14:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2025-08-29 06:38:32', '2025-08-29 06:38:32'),
(3, 'subadmin', '2025-08-29 07:20:11', '2025-08-29 07:20:11'),
(4, 'sallesperson', '2025-08-29 07:58:36', '2025-08-29 07:58:36'),
(5, 'teamleader', '2025-08-29 08:00:53', '2025-08-29 08:00:53'),
(6, 'seller', '2026-04-11 12:30:47', '2026-04-11 12:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `permission_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
(21, 4, 6),
(22, 4, 7),
(23, 4, 8),
(24, 4, 9),
(25, 4, 10),
(26, 4, 11),
(27, 4, 12),
(28, 4, 13),
(29, 4, 14),
(30, 4, 15),
(31, 4, 16),
(32, 4, 17),
(33, 4, 18),
(34, 4, 19),
(35, 4, 20),
(36, 4, 21),
(37, 4, 22),
(38, 4, 23),
(39, 4, 24),
(40, 4, 25),
(41, 4, 26),
(42, 4, 27),
(43, 4, 28),
(44, 4, 29),
(45, 4, 30),
(46, 4, 31),
(47, 4, 32),
(48, 4, 33),
(49, 4, 34),
(50, 4, 35),
(51, 4, 36),
(52, 4, 37),
(53, 4, 38),
(54, 4, 39),
(55, 4, 40),
(56, 4, 41),
(57, 4, 42),
(58, 4, 43),
(59, 4, 44),
(60, 4, 45),
(61, 4, 46),
(62, 4, 47),
(63, 4, 48),
(64, 6, 6),
(65, 6, 7),
(66, 6, 8),
(67, 6, 9),
(68, 6, 10),
(69, 6, 11),
(70, 6, 12),
(71, 6, 13),
(72, 6, 14),
(73, 6, 15),
(74, 6, 16),
(75, 6, 17),
(76, 6, 18),
(77, 6, 19),
(78, 6, 20),
(79, 6, 21),
(80, 6, 22),
(81, 6, 23),
(82, 6, 24),
(83, 6, 25),
(84, 6, 26),
(85, 6, 27),
(86, 6, 28),
(87, 6, 29),
(88, 6, 30),
(89, 6, 31),
(90, 6, 32),
(91, 6, 33),
(92, 6, 34),
(93, 6, 35),
(94, 6, 36),
(95, 6, 37),
(96, 6, 38),
(97, 6, 39),
(98, 6, 40),
(99, 6, 41),
(100, 6, 42),
(101, 6, 43),
(102, 6, 44),
(103, 6, 45),
(104, 6, 46),
(105, 6, 47),
(106, 6, 48),
(107, 6, 49),
(108, 6, 50),
(109, 6, 51),
(110, 6, 52),
(111, 6, 53),
(112, 6, 54),
(113, 6, 55),
(114, 6, 56),
(115, 6, 57),
(116, 6, 58),
(117, 6, 59),
(118, 6, 60),
(119, 6, 61),
(120, 6, 62),
(121, 6, 63),
(122, 6, 64),
(123, 6, 65),
(124, 6, 66),
(125, 6, 67),
(126, 6, 68),
(127, 6, 69),
(128, 6, 70),
(129, 6, 71),
(130, 6, 72),
(131, 6, 73),
(132, 6, 74),
(133, 6, 75),
(134, 6, 76),
(135, 6, 77);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('evIsX0JUktFe10MnXPiSfT9immUKYEGhGvldhADw', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDhEbDJTYVVtUzRpQUI3cGdUTlRCRXJ5Qll5d3BpQ2hHaXBrRnM2ciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovL2xvY2FsaG9zdC9tdWx0aS1hdXRoL2FkbWluL2FkbWluL2Jsb2dzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvbXVsdGktYXV0aC9hZG1pbi9hZG1pbi9ibG9ncyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781408929),
('KoGswzmsGnNxKDFlD7ZIn9w6jj9pWW9eay1JQlZx', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUpMVEppMEkwMWhLZ0dSTjlWTDJZQlJBWXAwQ2ZsM0p5WnJjQzBqYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3QvbXVsdGktYXV0aCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1781411536);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `website_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `website_name`, `contact_email`, `contact_phone`, `address`, `logo`, `favicon`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 'ArtHubly,ArtHubPro', 'piyushkumawat90607@gmail.com', '9782870390', '13 bhreav nagar vistar benar raad', 'logo_1776274025.png', NULL, NULL, NULL, NULL, '2026-04-12 08:56:50', '2026-04-18 18:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `user_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'XS', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32'),
(2, NULL, 'S', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32'),
(3, NULL, 'M', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32'),
(4, NULL, 'L', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32'),
(5, NULL, 'XL', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32'),
(6, NULL, 'XXL', 'active', '2026-04-11 18:43:32', '2026-04-11 18:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `smtp_settings`
--

CREATE TABLE `smtp_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mailer` varchar(100) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `encryption` varchar(50) DEFAULT NULL,
  `from_address` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smtp_settings`
--

INSERT INTO `smtp_settings` (`id`, `mailer`, `host`, `port`, `username`, `password`, `encryption`, `from_address`, `from_name`, `created_at`, `updated_at`) VALUES
(1, 'smtp', 'smtp.gmail.com', 587, 'piyushKumawat90607@gmail.com', 'plnc vejp hwir qrzd', 'tls', 'piyushKumawat90607@gmail.com', 'no-reply', '2025-01-28 16:17:52', '2026-04-24 06:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `seller_id`, `name`, `email`, `role_id`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, NULL, 'admin', 'admin@gmail.com', NULL, NULL, '$2y$12$lgZpy4.C2JBGrVDuqW7JBevGMV7lwh8Q372JGq1sU5z.y9DK1qVeO', 'admin', NULL, '2025-08-29 01:25:25', '2025-08-29 01:25:25'),
(4, NULL, 'Aaayush', 'user@gmail.com', NULL, NULL, '$2y$12$StYhQcbE1YEItY46bfzZ2OHS4wP0YORkljH9Jf7QRMqKvzKUmJeCu', 'user', 'PCtoMip4EiNa2pa6uRmfXMdvQGNEAmbhGfZirGVUpI5TjFRtJDOBx9noRmJF', '2025-08-29 01:25:26', '2026-08-09 05:27:32'),
(5, NULL, 'Test User 1', 'aayushkumawat284@gmail.com', NULL, '2025-08-29 01:25:26', '$2y$12$StYhQcbE1YEItY46bfzZ2OHS4wP0YORkljH9Jf7QRMqKvzKUmJeCu', 'user', '4yz3wg6sYF', '2025-08-29 01:25:26', '2026-04-11 23:01:15'),
(10, NULL, 'subadmin', 'subadmin@gmail.com', NULL, NULL, '$2y$12$9YcvDnt4eSuzyuzDh/HqMOgYw.CMD7s22suOjrb9j9KMuz25a2MkO', 'subadmin', NULL, '2025-08-29 07:44:27', '2025-08-29 07:44:27'),
(11, NULL, 'sallesperson', 'sallesperson@gmail.com', NULL, NULL, '$2y$12$MC9lIuK8CGUjwMeVYoR8iuAya75M9yQUYFbNIENI/.BeyV8A.KEPy', 'sallesperson', NULL, '2025-08-29 07:58:59', '2025-08-29 07:58:59'),
(12, NULL, 'teamleader', 'team@gmail.com', NULL, NULL, '$2y$12$pDcbh4cw2k52I4DRP4dhre8HSLMtWbwljCagwE.KY7wtTM/u4VGIm', 'teamleader', NULL, '2025-08-29 08:01:17', '2025-08-29 08:01:17'),
(13, NULL, 'seller', 'seller@gmail.com', NULL, NULL, '$2y$12$2TQFwzHJ76TSPyT8NFrqRu1sLdJYCn.ssegwt/MWqRy3Ct5NYYW5O', 'seller', NULL, '2026-04-14 08:13:21', '2026-04-14 08:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(8, 3, 2, '2026-04-20 09:19:50', '2026-04-20 09:19:50'),
(9, 3, 1, '2026-04-20 09:25:12', '2026-04-20 09:25:12'),
(13, 4, 5, '2026-08-14 17:17:25', '2026-08-14 17:17:25'),
(15, 4, 6, '2026-08-14 17:29:59', '2026-08-14 17:29:59'),
(16, 4, 2, '2026-08-14 17:35:14', '2026-08-14 17:35:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_blog_like` (`blog_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colors_user_id_foreign` (`user_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `coupons_user_id_foreign` (`user_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `discount_product`
--
ALTER TABLE `discount_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_category_id` (`menu_category_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `menus_slug_index` (`slug`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `pages_slug_index` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_slug_unique` (`slug`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_post_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `products_parent_id_foreign` (`parent_id`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_images_product` (`product_id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variations_product_id_foreign` (`product_id`);

--
-- Indexes for table `related_products`
--
ALTER TABLE `related_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `related_products_product_id_related_product_id_unique` (`product_id`,`related_product_id`),
  ADD KEY `related_products_related_product_id_foreign` (`related_product_id`);

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_requests_order_id_index` (`order_id`),
  ADD KEY `return_requests_user_id_index` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_user_generic` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sizes_user_id_foreign` (`user_id`);

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_seller_id_foreign` (`seller_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_likes`
--
ALTER TABLE `blog_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discount_product`
--
ALTER TABLE `discount_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `related_products`
--
ALTER TABLE `related_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD CONSTRAINT `blog_likes_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `colors`
--
ALTER TABLE `colors`
  ADD CONSTRAINT `colors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_product`
--
ALTER TABLE `discount_product`
  ADD CONSTRAINT `discount_product_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `related_products`
--
ALTER TABLE `related_products`
  ADD CONSTRAINT `related_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `related_products_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_user_generic` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sizes`
--
ALTER TABLE `sizes`
  ADD CONSTRAINT `sizes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
