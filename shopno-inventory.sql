-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 03:18 PM
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
-- Database: `shopno-inventory`
--

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `created_at`, `updated_at`, `name`, `slug`, `description`, `image`, `status`) VALUES
(1, '2025-06-16 09:02:08', '2025-06-18 09:26:14', 'Electro', 'random', NULL, 'uploads/2_1750260374.jpg', 1),
(6, '2025-06-18 09:25:01', '2025-06-18 09:25:32', 'vua', 'vua-1750260301', 'fasasf', 'uploads/2_1750260332.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `customer_type` enum('regular','wholesale','retail','premium') NOT NULL DEFAULT 'regular',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `created_at`, `updated_at`, `name`, `email`, `phone`, `city`, `customer_type`, `status`, `user_id`) VALUES
(1, '2025-06-16 09:02:14', '2025-06-16 09:02:14', 'mark1', 'm@2', '2312341241', 'Dhaka', 'regular', 1, 1),
(2, '2025-06-16 09:02:31', '2025-06-16 09:02:31', 'mark2', 'm@1', '2312341241', 'Dhaka', 'regular', 1, 1),
(3, '2025-06-16 09:02:37', '2025-06-16 09:02:37', 'mark3', 'm@3', '2312341241', 'Dhaka', 'regular', 1, 1),
(5, '2025-06-18 09:43:34', '2025-06-18 09:43:34', 'sad', 'sad@gg', '24314541', 'dhaka', 'regular', 1, 2),
(7, '2025-06-19 06:49:24', '2025-06-19 06:49:24', 'sadsss', 'ss@g', '213145153', 'dhaka', 'regular', 1, 2);

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `sale_id`, `customer_id`, `tax`, `discount`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 16, 5, 0.00, 0.00, 6156.10, 'draft', '2025-06-18 13:12:02', '2025-06-18 13:12:02'),
(2, 17, 5, 0.03, 0.03, 246.00, 'draft', '2025-06-19 07:03:50', '2025-06-19 07:03:50'),
(3, 18, 5, 11.00, 100.00, 1760.76, 'draft', '2025-06-19 07:10:26', '2025-06-19 07:10:26');

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
(4, '2025_06_13_104510_create_categories_table', 1),
(5, '2025_06_13_104957_create_customers_table', 1),
(6, '2025_06_13_105609_create_products_table', 1),
(7, '2025_06_13_111425_create_sales_table', 1),
(8, '2025_06_13_112041_create_sale_items_table', 1),
(9, '2025_06_13_123148_create_stock_movements', 1),
(10, '2025_06_13_123746_create_invoices_table', 1),
(11, '2025_06_14_145158_add_user_id_to_product_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 20,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `created_at`, `updated_at`, `name`, `description`, `category_id`, `price`, `selling_price`, `stock_quantity`, `image`, `status`) VALUES
(1, 1, '2025-06-16 09:03:03', '2025-06-16 11:42:04', 'Goodproduct', 'Bad Product3', 1, 1502.00, 2002.00, 100, 'uploads/products/product_1_1750086183.jpg', 1),
(2, 1, '2025-06-16 09:03:07', '2025-06-16 11:42:12', 'Goodproduct2', 'Bad Product3', 1, 1502.00, 2002.00, 5, 'uploads/products/product_1_1750086187.jpg', 1),
(3, 1, '2025-06-16 09:03:10', '2025-06-16 11:42:19', 'Goodproduct3', 'Bad Product3', 1, 1502.00, 2002.00, 4, 'uploads/products/product_1_1750086190.jpg', 1),
(4, 1, '2025-06-16 09:03:27', '2025-06-16 11:41:55', 'Bad Product1', 'Bad Product3', 1, 1502.00, 2002.00, 100, 'uploads/products/product_1_1750086207.jpg', 1),
(5, 1, '2025-06-16 09:03:31', '2025-06-16 11:42:31', 'Bad Product2', 'Bad Product3', 1, 1502.00, 2002.00, 5, 'uploads/products/product_1_1750086211.jpg', 1),
(6, 1, '2025-06-16 09:03:34', '2025-06-16 12:02:14', 'Bad Product3', 'Bad Product3', 1, 1502.00, 2002.00, 8, 'uploads/products/product_1_1750086214.jpg', 1),
(8, 2, '2025-06-18 10:08:40', '2025-06-18 10:58:14', 'dad', 'asdasd', 1, 123.00, 1233213.00, 424119, NULL, 1),
(9, 2, '2025-06-18 10:16:13', '2025-06-19 07:10:26', 'vua2', 'sdasdsd2', 6, 231.22, 1231.22, 222198, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment` enum('pending','partial','paid') NOT NULL DEFAULT 'pending',
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `created_at`, `updated_at`, `customer_id`, `user_id`, `tax`, `discount`, `total`, `paid`, `due`, `payment`, `status`, `sale_date`) VALUES
(2, '2025-06-16 09:10:58', '2025-06-16 09:10:58', 1, 1, 0.00, 0.00, 785.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 15:10:58'),
(3, '2025-06-16 09:14:39', '2025-06-16 09:14:39', 1, 1, 0.00, 0.00, 785.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 15:14:39'),
(4, '2025-06-16 09:19:35', '2025-06-16 09:19:35', 1, 1, 0.00, 0.00, 785.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 15:19:35'),
(5, '2025-06-16 09:20:53', '2025-06-16 09:20:53', 1, 1, 0.00, 0.00, 785.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 15:20:53'),
(6, '2025-06-16 10:10:27', '2025-06-16 10:10:27', 2, 1, 0.00, 0.00, 775.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 16:10:27'),
(7, '2025-06-16 11:16:13', '2025-06-16 11:16:13', 3, 1, 0.00, 0.00, 775.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 17:16:13'),
(8, '2025-06-16 11:16:24', '2025-06-16 11:16:24', 3, 1, 0.00, 0.00, 775.00, 0.00, 0.00, 'pending', 'completed', '2025-06-16 17:16:24'),
(10, '2025-06-16 11:36:05', '2025-06-16 11:36:05', 3, 1, 0.00, 0.00, 800.00, 0.00, 0.00, 'pending', 'cancelled', '2025-06-16 17:36:05'),
(11, '2025-06-16 11:37:10', '2025-06-16 11:37:10', 3, 1, 0.00, 0.00, 775.00, 0.00, 0.00, 'pending', 'pending', '2025-06-16 17:37:10'),
(12, '2025-06-16 11:39:46', '2025-06-16 11:39:46', 3, 1, 5.00, 0.00, 775.00, 0.00, 0.00, 'pending', 'pending', '2025-06-16 17:39:46'),
(13, '2025-06-18 10:18:09', '2025-06-18 10:18:09', 5, 2, 0.07, 0.05, 20000.02, 0.00, 0.00, 'pending', 'completed', '2025-06-18 16:18:09'),
(14, '2025-06-18 10:58:14', '2025-06-18 10:58:14', 5, 2, 0.00, 0.00, 6166065.00, 0.00, 0.00, 'pending', 'cancelled', '2025-06-18 16:58:14'),
(15, '2025-06-18 13:09:48', '2025-06-18 13:09:48', 5, 2, 0.00, 0.00, 4924.88, 0.00, 0.00, 'pending', 'pending', '2025-06-18 19:09:48'),
(16, '2025-06-18 13:12:02', '2025-06-18 13:12:02', 5, 2, 0.00, 0.00, 6156.10, 0.00, 0.00, 'pending', 'completed', '2025-06-18 19:12:02'),
(17, '2025-06-19 07:03:50', '2025-06-19 07:03:50', 5, 2, 0.03, 0.03, 246.00, 0.00, 0.00, 'pending', 'pending', '2025-06-19 13:03:50'),
(18, '2025-06-19 07:10:26', '2025-06-19 07:13:39', 5, 2, 1.00, 100.00, 1760.76, 0.00, 0.00, 'pending', 'pending', '2025-06-19 13:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `created_at`, `updated_at`, `sale_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(1, '2025-06-16 09:10:58', '2025-06-16 09:10:58', 2, 1, 2, 400.00, 800.00),
(2, '2025-06-16 09:14:39', '2025-06-16 09:14:39', 3, 1, 2, 400.00, 800.00),
(3, '2025-06-16 09:19:35', '2025-06-16 09:19:35', 4, 2, 2, 400.00, 800.00),
(4, '2025-06-16 09:20:53', '2025-06-16 09:20:53', 5, 2, 2, 400.00, 800.00),
(5, '2025-06-16 10:10:27', '2025-06-16 10:10:27', 6, 3, 2, 400.00, 800.00),
(6, '2025-06-16 11:16:13', '2025-06-16 11:16:13', 7, 4, 2, 400.00, 800.00),
(7, '2025-06-16 11:16:24', '2025-06-16 11:16:24', 8, 5, 2, 400.00, 800.00),
(9, '2025-06-16 11:36:05', '2025-06-16 11:36:05', 10, 5, 2, 400.00, 800.00),
(10, '2025-06-16 11:37:10', '2025-06-16 11:37:10', 11, 4, 2, 400.00, 800.00),
(11, '2025-06-16 11:39:46', '2025-06-16 11:39:46', 12, 6, 2, 400.00, 800.00),
(12, '2025-06-18 10:18:09', '2025-06-18 10:18:09', 13, 9, 5, 4000.00, 20000.00),
(13, '2025-06-18 10:58:14', '2025-06-18 10:58:14', 14, 8, 5, 1233213.00, 6166065.00),
(14, '2025-06-18 13:09:48', '2025-06-18 13:09:48', 15, 9, 4, 1231.22, 4924.88),
(15, '2025-06-18 13:12:02', '2025-06-18 13:12:02', 16, 9, 5, 1231.22, 6156.10),
(16, '2025-06-19 07:03:50', '2025-06-19 07:03:50', 17, 9, 2, 123.00, 246.00),
(17, '2025-06-19 07:10:26', '2025-06-19 07:10:26', 18, 9, 8, 231.22, 1849.76);

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
('3eKXuiv2jVDhErhu68aiLjfYA7A8V9XJye5D0hOC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTW9CM0k4OWJxUTZ5cDFnNFdDNWVhWDdCYW1nVkw1blhGNUVVUVc1VyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336709),
('4tdqlszDh9QRfdHOIPHNl7axejTfNYW4N4HxSWba', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnJNdnhFS2VteE5zZ3NDS251a09nSW9uTHhER2VrOFJoTjdGVTlIRiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750335941),
('74R0bKMpVZWVJqGZNaR7hSz46h7QApuvn9TFsCav', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWg3Qm9xaEFEUFpjT0x2djdmc2ppVHRtWWNORzdmWWdscEtNZ0VySCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336851),
('8x5rnUYTy2s4NdPOSgy61v7gQbO83XFYkakdW1Qr', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVEhkRVVUQ2RZVFRSSGdRck44RWVodVFscUhQV0lWaTV6VEVPd243MSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3Blcl9wYWdlPTEwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338743),
('bGuMhKD5wYSulHo6w6JBKeKFf70Ip06orWCdrkF4', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1dvQnZmckVScUltdXRSWVRhVXFIQTRJcHR5NEpKSFAxdGQxQUl5MyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3NhbGVzTGlzdD9wYWdlPTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1750335937),
('ECMUF86JtrfkCH2O2lSGfGFpGK1JblVP1m0Hv3mm', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidmNUWXVnS2F1bVlJYkVHUDJKMWVKN1pSMzNnbEpYSHQybVRFMjNkdyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3NhbGVzTGlzdD9wYWdlPTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1750338852),
('Ep3KoMo520mgh050tloLudKrLE2BIt5iVAlCLv6C', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjRBRzd6d3BoOW1ndzIzcFRRQ3dXbWc1Z29tNTFqR1JiUUJVNDVpcSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338461),
('HJKO4TlCTLcOW9jiF1vZbXn6RIx9ttiHd3y3TQF9', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDFMUzlEM2V3eUVBamRydFNMOW1hWXhlbkxTbUVKYTlBOVZ3cHg1eSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338758),
('HS8jf8H2DqKbxOG3hu12VrHx6puzBs3SqffFIEbs', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRVZsaGhZREVKZTF5dW5LMWVEOVdHQ1JqYlR1OXlVY3EycmtrQ1FwWSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rvd25sb2FkSW52b2ljZT9zYWxlX2lkPTE4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750338839),
('inG3TIFNrjnrUOSjfy2duQDxcYPlJgBFavvU3RLo', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYUZNampUTjl2ZEg4RDFLZXdtV2k5UHJwM3BINUsyd1lLb241dVFybSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338463),
('iSMRLnIJ6OaGLn37ONDP4lsHkKAZvqZWeSceKPRi', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTzRyVW9aRlp5V1cyVndwYzVQbnhaR29taVMyVmxtVFE4M3YzMHdGMSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336205),
('jeZ3LJBL9K568LR6hCTokpYeQ8E3nWsekzGLJUzz', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU29LRjgzdHBsUXBHcFZHVXBGWnZuVXFpUkVGMVJKRnBMdEFvdjNSbyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338379),
('jgxxT9YzHgDJTcqg19RyohAHaYyYWhSuzpSO2bf5', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT2NicDJ5YUdTSnVVZ2d4N25oUzh2eXM3Wktjc29wT2huWlFLcUFlZyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3NhbGVzTGlzdD9wYWdlPTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1750335940),
('jZj3xvOQp64WWIaPTQ7AF411SyE809wTW21w9S0A', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGFDNzZwNHF5N1lEZEZJZWtwY1BlS2J5U3dZZFZuNW1iZ0NWM2dTQyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338037),
('mMCz25crklqNUIMfle4zhhfW2S0XO300oAjszeP2', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoielAzZm05VmRaT3VUN0RhNnZGaGI5b3ZnR0g0aFdNV1RmSUlJSUJzNCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336525),
('n5Dv5J5EOm1vGPyAU7FVU9ziNlWbZzgogjquHkvo', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXRVcXVCTXUwY29QbHlPWUdSb0VnbjNKWUhHYWJmcFZ4OWVUeEZXbSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338574),
('oBpBrHcwjSpDTedebWSIk73lZGcHa47kxH4kcOPs', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMVNXWnd1alNDZzJZVUkyU1BnWkNheEtyMXVjamlzUWZJMnJwamJ5eSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338182),
('P8QbCqLa6cdqh59VOdSUn7hgNc5Kel0ujgv2m39b', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmhERFJjakgwRURwTm5sN2J1TWM5c1BNWDAzd0RSOGNTWmFUTERqQSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338237),
('Q6PXQloAUoEg4yaGEwADUzrMFpakG2g1HqMml8j0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNFZvVWJLOFdxTG12dktmR2pWeWhrU1p6Qmk1WWFJanp2OWVQaEcwMiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3Blcl9wYWdlPTEwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338758),
('QfoqQdYHdDxkDW7PgIujU1ZxE3PPCp1DyuZ6tEMe', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWdlTm0wejNVNHYwN2E0OG9HZktUYm01QXFacFcwMDJMTFViSE5MdCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338337),
('rpVIwpxVvgMqi1vKep2HflKJVZYWkUlRSlXg3BEG', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkFHSEFpcmpOUHpYaXhad0w4TTBmcENNRGZ5VjM2TngwVVpSVE8xUyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3Blcl9wYWdlPTEwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338806),
('RzRrJ1WNtnlL6QJByIyC3FyeZd2NMBTeEyNyWjaL', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiempuQWRacFB0U1dDeHhXT3JyQk1FQk5zQkREMFhiNHlHTjRkWjZYMyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3Blcl9wYWdlPTEwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750337889),
('UysAVjzl1NMEWliaWXKpH0B5vcmxiS0iutInxnN3', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaXVCaHRnOFBObmdmeHhkQWoyemt3OG9jVGdNSE9OY0k2TzRiVFVFTiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338742),
('Wu8nvPHXQ9aHz1YfzwP9O1FeGgw4ljfyMRYl7Mu2', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWGxQOXlCZVRhbW9EdTlINktmbDZXcmRpcnhxZGwzekg4cERsMjg1ZCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336057),
('WVJzZ3RTPOTJoSszAgINoZyrRIwJ9GW2pYLpkGKJ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibXduVlNtalFFdVp5eTRXNzRMVWJyT1lsU2JDWG1tV21FblJoYlJHayI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750338806),
('XWr65vvNxSluuTnRle6YhSDtkO1S8lnNzBDA3hPb', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWpDS2VQcllOMkpGZmdlN1BIUkxqTlVNNXVkNEM0UEU4S2hoRE03TSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1c3RvbWVyTGlzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750335937),
('zyQq83hlYEKgicnTjzkqPBRtqCrpnraB3kmUF8zm', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUlBTM2xuZVgwUkNKbzNlSzNaNU9GTUpsY1FkVTFCM3I5UHdlS0xlNCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RMaXN0P3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750336252);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `quantity`, `previous_stock`, `current_stock`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'in', 4, 0, 4, 1, '2025-06-16 09:03:03', '2025-06-16 09:03:03'),
(2, 2, 'in', 4, 0, 4, 1, '2025-06-16 09:03:07', '2025-06-16 09:03:07'),
(3, 3, 'in', 4, 0, 4, 1, '2025-06-16 09:03:10', '2025-06-16 09:03:10'),
(4, 4, 'in', 4, 0, 4, 1, '2025-06-16 09:03:27', '2025-06-16 09:03:27'),
(5, 5, 'in', 4, 0, 4, 1, '2025-06-16 09:03:31', '2025-06-16 09:03:31'),
(6, 6, 'in', 4, 0, 4, 1, '2025-06-16 09:03:34', '2025-06-16 09:03:34'),
(7, 1, 'out', 2, 4, 2, 1, '2025-06-16 09:10:58', '2025-06-16 09:10:58'),
(8, 1, 'out', 2, 2, 0, 1, '2025-06-16 09:14:39', '2025-06-16 09:14:39'),
(9, 2, 'out', 2, 4, 2, 1, '2025-06-16 09:19:35', '2025-06-16 09:19:35'),
(10, 2, 'out', 2, 2, 0, 1, '2025-06-16 09:20:53', '2025-06-16 09:20:53'),
(11, 3, 'out', 2, 4, 2, 1, '2025-06-16 10:10:27', '2025-06-16 10:10:27'),
(12, 4, 'out', 2, 4, 2, 1, '2025-06-16 11:16:13', '2025-06-16 11:16:13'),
(13, 5, 'out', 2, 4, 2, 1, '2025-06-16 11:16:24', '2025-06-16 11:16:24'),
(14, 6, 'out', 2, 4, 2, 1, '2025-06-16 11:35:02', '2025-06-16 11:35:02'),
(15, 5, 'out', 2, 2, 0, 1, '2025-06-16 11:36:05', '2025-06-16 11:36:05'),
(16, 4, 'out', 2, 2, 0, 1, '2025-06-16 11:37:10', '2025-06-16 11:37:10'),
(17, 6, 'out', 2, 2, 0, 1, '2025-06-16 11:39:46', '2025-06-16 11:39:46'),
(18, 4, 'in', 100, 0, 100, 1, '2025-06-16 11:41:55', '2025-06-16 11:41:55'),
(19, 1, 'in', 100, 0, 100, 1, '2025-06-16 11:42:04', '2025-06-16 11:42:04'),
(20, 2, 'in', 5, 0, 5, 1, '2025-06-16 11:42:12', '2025-06-16 11:42:12'),
(21, 3, 'in', 4, 0, 4, 1, '2025-06-16 11:42:19', '2025-06-16 11:42:19'),
(22, 5, 'in', 5, 0, 5, 1, '2025-06-16 11:42:31', '2025-06-16 11:42:31'),
(23, 6, 'in', 6, 0, 6, 1, '2025-06-16 11:42:37', '2025-06-16 11:42:37'),
(24, 6, 'in', 2, 6, 8, 1, '2025-06-16 12:02:14', '2025-06-16 12:02:14'),
(32, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:08:40', '2025-06-18 10:08:40'),
(33, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:08:50', '2025-06-18 10:08:50'),
(34, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:09:02', '2025-06-18 10:09:02'),
(35, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:09:49', '2025-06-18 10:09:49'),
(36, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:09:58', '2025-06-18 10:09:58'),
(37, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:12:37', '2025-06-18 10:12:37'),
(38, 8, 'in', 4444, 0, 4444, 2, '2025-06-18 10:12:43', '2025-06-18 10:12:43'),
(39, 8, 'in', 22222, 0, 22222, 2, '2025-06-18 10:12:50', '2025-06-18 10:12:50'),
(40, 8, 'in', 22222, 0, 22222, 2, '2025-06-18 10:12:59', '2025-06-18 10:12:59'),
(41, 8, 'in', 22222, 0, 22222, 2, '2025-06-18 10:13:33', '2025-06-18 10:13:33'),
(42, 8, 'in', 424124, 0, 424124, 2, '2025-06-18 10:14:07', '2025-06-18 10:14:07'),
(43, 9, 'in', 222222, 0, 222222, 2, '2025-06-18 10:16:13', '2025-06-18 10:16:13'),
(44, 9, 'in', 222222, 0, 222222, 2, '2025-06-18 10:16:43', '2025-06-18 10:16:43'),
(45, 9, 'out', 5, 222222, 222217, 2, '2025-06-18 10:18:09', '2025-06-18 10:18:09'),
(46, 8, 'out', 5, 424124, 424119, 2, '2025-06-18 10:58:14', '2025-06-18 10:58:14'),
(47, 9, 'out', 4, 222217, 222213, 2, '2025-06-18 13:09:48', '2025-06-18 13:09:48'),
(48, 9, 'out', 5, 222213, 222208, 2, '2025-06-18 13:12:02', '2025-06-18 13:12:02'),
(49, 9, 'out', 2, 222208, 222206, 2, '2025-06-19 07:03:50', '2025-06-19 07:03:50'),
(50, 9, 'out', 8, 222206, 222198, 2, '2025-06-19 07:10:26', '2025-06-19 07:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `role` enum('admin','shopKeeper') NOT NULL DEFAULT 'shopKeeper',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `mobile`, `role`, `email_verified_at`, `password`, `otp`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ami', 'ss', 'a@1', NULL, 'shopKeeper', NULL, '123', 0, NULL, '2025-06-16 09:01:59', '2025-06-16 09:01:59'),
(2, 'Ehsan', 'Abdullah', 'saadkhan420000@gmail.com', '01531540639', 'shopKeeper', NULL, '$2y$12$jdadOMCHZFLlVUhy8xtJBe3sLi0fprqSBJJ8xG0F13Fa47jy25Lou', 0, NULL, '2025-06-18 08:21:56', '2025-06-18 08:21:56');

--
-- Indexes for dumped tables
--

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
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_sale_id_foreign` (`sale_id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sales_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sales_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
