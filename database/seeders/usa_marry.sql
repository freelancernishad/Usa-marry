-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 07:57 AM
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
-- Database: `usa_marry`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'admin@gmail.com', NULL, '$2y$12$sO8ScDYtbfZjFeDPHBfiUuO3lISx0.aQVSMJJk.1bk354JrI9Egy.', NULL, '2025-04-13 09:50:54', '2025-04-13 09:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `allowed_origins`
--

CREATE TABLE `allowed_origins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `origin_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allowed_origins`
--

INSERT INTO `allowed_origins` (`id`, `origin_url`, `created_at`, `updated_at`) VALUES
(1, 'postman', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article_category`
--

CREATE TABLE `article_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `value` decimal(8,2) NOT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `usage_limit` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_associations`
--

CREATE TABLE `coupon_associations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` enum('user','package','service') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_package_requests`
--

CREATE TABLE `custom_package_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `business` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `service_description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `interactions`
--

CREATE TABLE `interactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('View','Interest','Message') NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interactions`
--

INSERT INTO `interactions` (`id`, `match_id`, `type`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'View', NULL, '2025-04-14 04:42:03', '2025-04-14 04:42:03'),
(2, 1, 'Interest', NULL, '2025-04-14 04:42:03', '2025-04-14 04:42:03'),
(3, 2, 'Message', 'Hello Priya, I liked your profile', '2025-04-14 04:42:03', '2025-04-14 04:42:03'),
(4, 3, 'View', NULL, '2025-04-14 04:42:03', '2025-04-14 04:42:03'),
(5, 3, 'Interest', NULL, '2025-04-14 04:42:03', '2025-04-14 04:42:03');

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
(4, '2024_10_27_073742_create_admins_table', 1),
(5, '2024_10_27_160421_create_token_blacklists_table', 1),
(6, '2024_10_27_161336_add_user_id_and_user_type_to_token_blacklist_table', 1),
(7, '2024_11_06_095326_create_system_settings_table', 1),
(8, '2024_11_06_104505_create_allowed_origins_table', 1),
(9, '2024_11_07_061122_create_payments_table', 1),
(10, '2024_11_14_085438_create_coupons_table', 1),
(11, '2024_11_14_085439_create_coupon_usages_table', 1),
(12, '2024_11_14_091237_create_coupon_associations_table', 1),
(13, '2024_11_15_161032_add_payable_and_coupon_columns_to_payments_table', 1),
(14, '2024_11_16_052309_create_social_media_links_table', 1),
(15, '2024_11_16_082809_add_hover_icon_index_no_status_to_social_media_links_table', 1),
(16, '2024_11_16_100743_create_packages_table', 1),
(17, '2024_11_16_100801_create_user_packages_table', 1),
(18, '2024_11_19_053402_create_package_discounts_table', 1),
(19, '2024_11_19_100435_create_package_addons_table', 1),
(20, '2024_11_19_103115_create_user_package_addons_table', 1),
(21, '2024_11_20_035039_create_support_tickets_table', 1),
(22, '2024_11_20_035135_create_replies_table', 1),
(23, '2024_11_24_124451_add_attachment_to_support_tickets_table', 1),
(24, '2024_11_24_124532_add_attachment_to_replies_table', 1),
(25, '2024_11_30_055829_add_user_package_id_to_payments_table', 1),
(26, '2024_12_05_071349_add_session_id_to_payments_table', 1),
(27, '2024_12_12_041816_add_business_name_to_user_packages_table', 1),
(28, '2024_12_18_034141_create_categories_table', 1),
(29, '2024_12_18_043211_create_articles_table', 1),
(30, '2024_12_18_043536_create_article_category_table', 1),
(31, '2025_01_14_153622_create_custom_package_requests_table', 1),
(32, '2025_01_14_155100_add_user_id_to_custom_package_requests_table', 1),
(33, '2025_01_18_163903_create_notifications_table', 1),
(34, '2025_01_19_091157_add_type_to_packages_table', 1),
(35, '2025_01_19_092404_add_package_id_to_custom_package_requests_table', 1),
(36, '2025_01_20_064701_add_subscription_columns_to_user_packages_table', 1),
(37, '2025_01_22_051530_add_payment_method_details_to_user_packages_table', 1),
(38, '2025_04_13_103530_create_profiles_table', 1),
(39, '2025_04_13_103531_create_partner_preferences_table', 1),
(40, '2025_04_13_103532_create_photos_table', 1),
(41, '2025_04_13_103533_create_user_matches_table', 1),
(42, '2025_04_13_103534_create_interactions_table', 1),
(43, '2025_04_13_103542_create_subscriptions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'info',
  `message` text NOT NULL,
  `related_model` varchar(255) DEFAULT NULL,
  `related_model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` enum('public','private') NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_addons`
--

CREATE TABLE `package_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `addon_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_discounts`
--

CREATE TABLE `package_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `duration_months` int(11) NOT NULL,
  `discount_rate` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partner_preferences`
--

CREATE TABLE `partner_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `age_min` int(11) DEFAULT NULL,
  `age_max` int(11) DEFAULT NULL,
  `height_min` decimal(5,2) DEFAULT NULL,
  `height_max` decimal(5,2) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `caste` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_preferences`
--

INSERT INTO `partner_preferences` (`id`, `user_id`, `age_min`, `age_max`, `height_min`, `height_max`, `marital_status`, `religion`, `caste`, `education`, `occupation`, `country`, `created_at`, `updated_at`) VALUES
(1, 1, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(2, 2, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(3, 3, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(4, 4, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(5, 5, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(6, 6, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(7, 7, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(8, 8, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(9, 9, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(10, 10, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Brahmin', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(11, 11, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(12, 12, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(13, 13, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(14, 14, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(15, 15, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(16, 16, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(17, 17, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(18, 18, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(19, 19, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(20, 20, 22, 28, 150.00, 170.00, 'Never Married', 'Hindu', 'Patel', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(21, 21, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(22, 22, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(23, 23, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(24, 24, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(25, 25, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(26, 26, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(27, 27, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(28, 28, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(29, 29, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(30, 30, 22, 28, 150.00, 170.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(31, 31, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Pathan', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(32, 32, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Sheikh', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(33, 33, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(34, 34, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Qureshi', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(35, 35, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Ansari', 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(36, 36, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(37, 37, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Khan', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(38, 38, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Ansari', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(39, 39, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(40, 40, 22, 28, 150.00, 170.00, 'Never Married', 'Muslim', 'Sheikh', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(41, 41, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(42, 42, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(43, 43, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(44, 44, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Orthodox', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(45, 45, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(46, 46, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Protestant', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(47, 47, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(48, 48, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(49, 49, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Protestant', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(50, 50, 22, 28, 150.00, 170.00, 'Never Married', 'Christian', 'Orthodox', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(51, 51, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(52, 52, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(53, 53, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(54, 54, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(55, 55, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(56, 56, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(57, 57, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(58, 58, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(59, 59, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(60, 60, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(61, 61, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Brahmin', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(62, 62, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(63, 63, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(64, 64, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(65, 65, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(66, 66, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(67, 67, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(68, 68, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(69, 69, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(70, 70, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(71, 71, 25, 32, 160.00, 180.00, 'Never Married', 'Hindu', 'Patel', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(72, 72, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(73, 73, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(74, 74, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(75, 75, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(76, 76, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(77, 77, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(78, 78, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(79, 79, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(80, 80, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', 'Jat', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(81, 81, 25, 32, 160.00, 180.00, 'Never Married', 'Sikh', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(82, 82, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Pathan', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(83, 83, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Sheikh', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(84, 84, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(85, 85, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Qureshi', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(86, 86, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Ansari', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(87, 87, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(88, 88, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Khan', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(89, 89, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Ansari', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(90, 90, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', NULL, 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(91, 91, 25, 32, 160.00, 180.00, 'Never Married', 'Muslim', 'Sheikh', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(92, 92, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(93, 93, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(94, 94, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(95, 95, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Orthodox', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(96, 96, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(97, 97, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Protestant', 'Graduate', 'Business Professional', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(98, 98, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Catholic', 'Graduate', 'Doctor', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(99, 99, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', NULL, 'Post Graduate', 'Teacher', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53'),
(100, 100, 25, 32, 160.00, 180.00, 'Never Married', 'Christian', 'Protestant', 'Post Graduate', 'Engineer', 'India', '2025-04-14 05:52:53', '2025-04-14 05:52:53');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gateway` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `amount` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payable_type` varchar(255) DEFAULT NULL,
  `payable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `payment_method` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `user_id`, `path`, `is_primary`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'profiles/1/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(2, 2, 'profiles/2/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(3, 3, 'profiles/3/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(4, 4, 'profiles/4/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(5, 5, 'profiles/5/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(6, 6, 'profiles/6/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(7, 7, 'profiles/7/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(8, 8, 'profiles/8/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(9, 9, 'profiles/9/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(10, 10, 'profiles/10/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(11, 11, 'profiles/11/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(12, 12, 'profiles/12/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(13, 13, 'profiles/13/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(14, 14, 'profiles/14/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(15, 15, 'profiles/15/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(16, 16, 'profiles/16/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(17, 17, 'profiles/17/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(18, 18, 'profiles/18/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(19, 19, 'profiles/19/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(20, 20, 'profiles/20/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(21, 21, 'profiles/21/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(22, 22, 'profiles/22/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(23, 23, 'profiles/23/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(24, 24, 'profiles/24/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(25, 25, 'profiles/25/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(26, 26, 'profiles/26/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(27, 27, 'profiles/27/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(28, 28, 'profiles/28/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(29, 29, 'profiles/29/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(30, 30, 'profiles/30/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(31, 31, 'profiles/31/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(32, 32, 'profiles/32/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(33, 33, 'profiles/33/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(34, 34, 'profiles/34/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(35, 35, 'profiles/35/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(36, 36, 'profiles/36/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(37, 37, 'profiles/37/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(38, 38, 'profiles/38/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(39, 39, 'profiles/39/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(40, 40, 'profiles/40/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(41, 41, 'profiles/41/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(42, 42, 'profiles/42/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(43, 43, 'profiles/43/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(44, 44, 'profiles/44/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(45, 45, 'profiles/45/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(46, 46, 'profiles/46/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(47, 47, 'profiles/47/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(48, 48, 'profiles/48/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(49, 49, 'profiles/49/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(50, 50, 'profiles/50/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(51, 51, 'profiles/51/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(52, 52, 'profiles/52/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(53, 53, 'profiles/53/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(54, 54, 'profiles/54/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(55, 55, 'profiles/55/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(56, 56, 'profiles/56/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(57, 57, 'profiles/57/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(58, 58, 'profiles/58/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(59, 59, 'profiles/59/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(60, 60, 'profiles/60/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(61, 61, 'profiles/61/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(62, 62, 'profiles/62/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(63, 63, 'profiles/63/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(64, 64, 'profiles/64/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(65, 65, 'profiles/65/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(66, 66, 'profiles/66/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(67, 67, 'profiles/67/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(68, 68, 'profiles/68/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(69, 69, 'profiles/69/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(70, 70, 'profiles/70/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(71, 71, 'profiles/71/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(72, 72, 'profiles/72/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(73, 73, 'profiles/73/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(74, 74, 'profiles/74/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(75, 75, 'profiles/75/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(76, 76, 'profiles/76/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(77, 77, 'profiles/77/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(78, 78, 'profiles/78/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(79, 79, 'profiles/79/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(80, 80, 'profiles/80/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(81, 81, 'profiles/81/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(82, 82, 'profiles/82/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(83, 83, 'profiles/83/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(84, 84, 'profiles/84/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(85, 85, 'profiles/85/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(86, 86, 'profiles/86/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(87, 87, 'profiles/87/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(88, 88, 'profiles/88/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(89, 89, 'profiles/89/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(90, 90, 'profiles/90/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(91, 91, 'profiles/91/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(92, 92, 'profiles/92/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(93, 93, 'profiles/93/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(94, 94, 'profiles/94/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(95, 95, 'profiles/95/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(96, 96, 'profiles/96/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(97, 97, 'profiles/97/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(98, 98, 'profiles/98/photo3.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(99, 99, 'profiles/99/photo1.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43'),
(100, 100, 'profiles/100/photo2.jpg', 1, 1, '2025-04-14 05:53:43', '2025-04-14 05:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `about` text DEFAULT NULL,
  `highest_degree` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `annual_income` varchar(255) DEFAULT NULL,
  `employed_in` varchar(255) DEFAULT NULL,
  `father_status` varchar(255) DEFAULT NULL,
  `mother_status` varchar(255) DEFAULT NULL,
  `siblings` int(11) DEFAULT NULL,
  `family_type` varchar(255) DEFAULT NULL,
  `family_values` varchar(255) DEFAULT NULL,
  `financial_status` varchar(255) DEFAULT NULL,
  `diet` varchar(255) DEFAULT NULL,
  `drink` varchar(255) DEFAULT NULL,
  `smoke` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `resident_status` varchar(255) DEFAULT NULL,
  `has_horoscope` tinyint(1) NOT NULL DEFAULT 0,
  `rashi` varchar(255) DEFAULT NULL,
  `nakshatra` varchar(255) DEFAULT NULL,
  `manglik` varchar(255) DEFAULT NULL,
  `show_contact` tinyint(1) NOT NULL DEFAULT 0,
  `visible_to` varchar(255) NOT NULL DEFAULT 'All',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `about`, `highest_degree`, `institution`, `occupation`, `annual_income`, `employed_in`, `father_status`, `mother_status`, `siblings`, `family_type`, `family_values`, `financial_status`, `diet`, `drink`, `smoke`, `country`, `state`, `city`, `resident_status`, `has_horoscope`, `rashi`, `nakshatra`, `manglik`, `show_contact`, `visible_to`, `created_at`, `updated_at`) VALUES
(1, 1, 'About Aarav Sharma - He is a professional looking for a life partner.', 'BSc', 'IIT 2', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Taurus', 'Bharani', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(2, 2, 'About Vihaan Patel - He is a professional looking for a life partner.', 'MBA', 'IIT 3', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Gemini', 'Krittika', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(3, 3, 'About Aditya Joshi - He is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Cancer', 'Rohini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(4, 4, 'About Arjun Iyer - He is a professional looking for a life partner.', 'MBA', 'IIT 2', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Leo', 'Mrigashira', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(5, 5, 'About Reyansh Nair - He is a professional looking for a life partner.', 'PhD', 'IIT 3', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Virgo', 'Ardra', 'Partial', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(6, 6, 'About Atharva Deshpande - He is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Libra', 'Punarvasu', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(7, 7, 'About Dhruv Chaturvedi - He is a professional looking for a life partner.', 'BSc', 'IIT 2', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Scorpio', 'Pushya', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(8, 8, 'About Ishaan Mishra - He is a professional looking for a life partner.', 'MBA', 'IIT 3', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Sagittarius', 'Ashlesha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(9, 9, 'About Kabir Trivedi - He is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Capricorn', 'Magha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(10, 10, 'About Advait Tiwari - He is a professional looking for a life partner.', 'PhD', 'IIT 2', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Purva Phalguni', 'Yes', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(11, 11, 'About Rahul Patel - He is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Pisces', 'Uttara Phalguni', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(12, 12, 'About Vivaan Patel - He is a professional looking for a life partner.', 'MTech', 'Nirma University', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Aries', 'Hasta', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(13, 13, 'About Kunal Patel - He is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Taurus', 'Chitra', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(14, 14, 'About Rohan Patel - He is a professional looking for a life partner.', 'MBA', 'Nirma University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Gemini', 'Swati', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(15, 15, 'About Nirav Patel - He is a professional looking for a life partner.', 'PhD', 'Nirma University', 'Engineer', '15-20 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Cancer', 'Vishakha', 'Partial', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(16, 16, 'About Yash Patel - He is a professional looking for a life partner.', 'MBA', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Leo', 'Anuradha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(17, 17, 'About Harsh Patel - He is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Virgo', 'Jyeshtha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(18, 18, 'About Manav Patel - He is a professional looking for a life partner.', 'MTech', 'Nirma University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Libra', 'Mula', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(19, 19, 'About Parth Patel - He is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Scorpio', 'Purva Ashadha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(20, 20, 'About Jay Patel - He is a professional looking for a life partner.', 'PhD', 'Nirma University', 'Engineer', '15-20 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Non-Vegetarian', 'Yes', 'Yes', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Sagittarius', 'Uttara Ashadha', 'Yes', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(21, 21, 'About Gurpreet Singh - He is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 1, 'Joint', 'Moderate', 'Affluent', 'Vegetarian', 'Occasionally', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Capricorn', 'Shravana', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(22, 22, 'About Harpreet Singh - He is a professional looking for a life partner.', 'MBA', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Aquarius', 'Dhanishta', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(23, 23, 'About Jagmeet Singh - He is a professional looking for a life partner.', 'BSc', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Pisces', 'Shatabhisha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(24, 24, 'About Manjeet Singh - He is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Aries', 'Purva Bhadrapada', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(25, 25, 'About Rajdeep Singh - He is a professional looking for a life partner.', 'PhD', 'Punjab University', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Taurus', 'Uttara Bhadrapada', 'Partial', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(26, 26, 'About Simranjit Singh - He is a professional looking for a life partner.', 'MBA', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Gemini', 'Revati', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(27, 27, 'About Sukhdeep Singh - He is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Cancer', 'Ashwini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(28, 28, 'About Inderjeet Singh - He is a professional looking for a life partner.', 'MBA', 'Punjab University', 'Doctor', '25-30 Lakh', 'Private', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Affluent', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Leo', 'Bharani', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(29, 29, 'About Baljeet Singh - He is a professional looking for a life partner.', 'BSc', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Virgo', 'Krittika', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(30, 30, 'About Tejinder Singh - He is a professional looking for a life partner.', 'PhD', 'Punjab University', 'Engineer', '15-20 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Libra', 'Rohini', 'Yes', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(31, 31, 'About Ayaan Khan - He is a professional looking for a life partner.', 'BSc', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Scorpio', 'Mrigashira', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(32, 32, 'About Imran Ali - He is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Sagittarius', 'Ardra', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(33, 33, 'About Faisal Ahmad - He is a professional looking for a life partner.', 'MTech', 'Aligarh Muslim University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Capricorn', 'Punarvasu', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(34, 34, 'About Zaid Qureshi - He is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Pushya', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(35, 35, 'About Tariq Rahman - He is a professional looking for a life partner.', 'PhD', 'Aligarh Muslim University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Affluent', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Pisces', 'Ashlesha', 'Partial', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(36, 36, 'About Sameer Sheikh - He is a professional looking for a life partner.', 'MTech', 'Aligarh Muslim University', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Aries', 'Magha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(37, 37, 'About Salman Khan - He is a professional looking for a life partner.', 'BSc', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Taurus', 'Purva Phalguni', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(38, 38, 'About Nasir Ansari - He is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Gemini', 'Uttara Phalguni', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(39, 39, 'About Yusuf Farooqi - He is a professional looking for a life partner.', 'MTech', 'Aligarh Muslim University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Cancer', 'Hasta', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(40, 40, 'About Rizwan Sheikh - He is a professional looking for a life partner.', 'PhD', 'Aligarh Muslim University', 'Engineer', '15-20 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Upper Middle Class', 'Non-Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Leo', 'Chitra', 'Yes', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(41, 41, 'About Ethan D Souza - He is a professional looking for a life partner.', 'BSc', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Virgo', 'Swati', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(42, 42, 'About Noah Fernandes - He is a professional looking for a life partner.', 'MTech', 'St. Xavier\'s College', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 2, 'Joint', 'Moderate', 'Affluent', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Libra', 'Vishakha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(43, 43, 'About Nathan D Costa - He is a professional looking for a life partner.', 'BSc', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Scorpio', 'Anuradha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(44, 44, 'About Liam Sequeira - He is a professional looking for a life partner.', 'MBA', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Sagittarius', 'Jyeshtha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(45, 45, 'About Aiden D Silva - He is a professional looking for a life partner.', 'PhD', 'St. Xavier\'s College', 'Engineer', '15-20 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Capricorn', 'Mula', 'Partial', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(46, 46, 'About Caleb Pinto - He is a professional looking for a life partner.', 'MBA', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Purva Ashadha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(47, 47, 'About Isaac Dias - He is a professional looking for a life partner.', 'BSc', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Pisces', 'Uttara Ashadha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(48, 48, 'About Elijah Gomes - He is a professional looking for a life partner.', 'MTech', 'St. Xavier\'s College', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Aries', 'Shravana', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(49, 49, 'About Samuel Pereira - He is a professional looking for a life partner.', 'BSc', 'St. Xavier\'s College', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Taurus', 'Dhanishta', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(50, 50, 'About Daniel Coutinho - He is a professional looking for a life partner.', 'PhD', 'St. Xavier\'s College', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Gemini', 'Shatabhisha', 'Yes', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(51, 51, 'About Ananya Sharma - She is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Cancer', 'Purva Bhadrapada', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(52, 52, 'About Aditi Sharma - She is a professional looking for a life partner.', 'MBA', 'IIT 2', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Leo', 'Uttara Bhadrapada', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(53, 53, 'About Ishita Joshi - She is a professional looking for a life partner.', 'BSc', 'IIT 3', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Virgo', 'Revati', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(54, 54, 'About Kavya Iyer - She is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Libra', 'Ashwini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(55, 55, 'About Meera Nair - She is a professional looking for a life partner.', 'PhD', 'IIT 2', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Scorpio', 'Bharani', 'Partial', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(56, 56, 'About Riya Deshpande - She is a professional looking for a life partner.', 'MBA', 'IIT 3', 'Doctor', '25-30 Lakh', 'Private', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Affluent', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Sagittarius', 'Krittika', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(57, 57, 'About Sanya Chaturvedi - She is a professional looking for a life partner.', 'MTech', 'IIT 1', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Capricorn', 'Rohini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(58, 58, 'About Tanya Mishra - She is a professional looking for a life partner.', 'MBA', 'IIT 2', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Mrigashira', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(59, 59, 'About Vaishnavi Trivedi - She is a professional looking for a life partner.', 'BSc', 'IIT 3', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Karnataka', 'Mumbai', 'Citizen', 0, 'Pisces', 'Ardra', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(60, 60, 'About Yamini Tiwari - She is a professional looking for a life partner.', 'PhD', 'IIT 1', 'Engineer', '15-20 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Traditional', 'Upper Middle Class', 'Non-Vegetarian', 'Yes', 'Yes', 'India', 'Uttar Pradesh', 'Lucknow', 'Citizen', 1, 'Aries', 'Punarvasu', 'Yes', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(61, 61, 'About Zoya Sharma - She is a professional looking for a life partner.', 'BSc', 'IIT 2', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Maharashtra', 'Mumbai', 'Citizen', 0, 'Taurus', 'Pushya', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(62, 62, 'About Riya Patel - She is a professional looking for a life partner.', 'MBA', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Gemini', 'Ashlesha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(63, 63, 'About Nidhi Patel - She is a professional looking for a life partner.', 'MTech', 'Nirma University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 3, 'Joint', 'Moderate', 'Affluent', 'Vegetarian', 'Occasionally', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Cancer', 'Magha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(64, 64, 'About Krisha Patel - She is a professional looking for a life partner.', 'MBA', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Leo', 'Purva Phalguni', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(65, 65, 'About Mahi Patel - She is a professional looking for a life partner.', 'PhD', 'Nirma University', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Virgo', 'Uttara Phalguni', 'Partial', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(66, 66, 'About Jiya Patel - She is a professional looking for a life partner.', 'MTech', 'Nirma University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Libra', 'Hasta', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(67, 67, 'About Anika Patel - She is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Scorpio', 'Chitra', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(68, 68, 'About Diya Patel - She is a professional looking for a life partner.', 'MBA', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Sagittarius', 'Swati', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(69, 69, 'About Isha Patel - She is a professional looking for a life partner.', 'MTech', 'Nirma University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 1, 'Capricorn', 'Vishakha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(70, 70, 'About Kavya Patel - She is a professional looking for a life partner.', 'PhD', 'Nirma University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Affluent', 'Vegetarian', 'Yes', 'Yes', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Aquarius', 'Anuradha', 'Yes', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(71, 71, 'About Tanya Patel - She is a professional looking for a life partner.', 'BSc', 'Nirma University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Gujarat', 'Ahmedabad', 'Citizen', 0, 'Pisces', 'Jyeshtha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(72, 72, 'About Simran Kaur - She is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Aries', 'Mula', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(73, 73, 'About Harleen Kaur - She is a professional looking for a life partner.', 'BSc', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Taurus', 'Purva Ashadha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(74, 74, 'About Jasleen Kaur - She is a professional looking for a life partner.', 'MBA', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Gemini', 'Uttara Ashadha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(75, 75, 'About Navneet Kaur - She is a professional looking for a life partner.', 'PhD', 'Punjab University', 'Engineer', '15-20 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Cancer', 'Shravana', 'Partial', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(76, 76, 'About Amrit Kaur - She is a professional looking for a life partner.', 'MBA', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Leo', 'Dhanishta', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(77, 77, 'About Gurleen Kaur - She is a professional looking for a life partner.', 'BSc', 'Punjab University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Virgo', 'Shatabhisha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(78, 78, 'About Manpreet Kaur - She is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Libra', 'Purva Bhadrapada', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(79, 79, 'About Rajveer Kaur - She is a professional looking for a life partner.', 'BSc', 'Punjab University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Scorpio', 'Uttara Bhadrapada', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(80, 80, 'About Taran Kaur - She is a professional looking for a life partner.', 'PhD', 'Punjab University', 'Engineer', '15-20 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Non-Vegetarian', 'Yes', 'Yes', 'India', 'Punjab', 'Chandigarh', 'Citizen', 0, 'Sagittarius', 'Revati', 'Yes', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(81, 81, 'About Kiran Kaur - She is a professional looking for a life partner.', 'MTech', 'Punjab University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Punjab', 'Chandigarh', 'Citizen', 1, 'Capricorn', 'Ashwini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(82, 82, 'About Ayesha Khan - She is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Bharani', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(83, 83, 'About Zara Ali - She is a professional looking for a life partner.', 'BSc', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Pisces', 'Krittika', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(84, 84, 'About Fatima Ahmad - She is a professional looking for a life partner.', 'MTech', 'Aligarh Muslim University', 'Doctor', '25-30 Lakh', 'Private', 'Retired', 'Homemaker', 0, 'Joint', 'Traditional', 'Affluent', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Aries', 'Rohini', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(85, 85, 'About Sana Qureshi - She is a professional looking for a life partner.', 'PhD', 'Aligarh Muslim University', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Taurus', 'Mrigashira', 'Partial', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(86, 86, 'About Hina Rahman - She is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Gemini', 'Ardra', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(87, 87, 'About Sameera Sheikh - She is a professional looking for a life partner.', 'MTech', 'Aligarh Muslim University', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Traditional', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Cancer', 'Punarvasu', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(88, 88, 'About Nazia Khan - She is a professional looking for a life partner.', 'MBA', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Traditional', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Leo', 'Pushya', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(89, 89, 'About Yasmin Ansari - She is a professional looking for a life partner.', 'BSc', 'Aligarh Muslim University', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Traditional', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Virgo', 'Ashlesha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(90, 90, 'About Amina Farooqi - She is a professional looking for a life partner.', 'PhD', 'Aligarh Muslim University', 'Engineer', '15-20 Lakh', 'Government', 'Business', 'Homemaker', 2, 'Joint', 'Traditional', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Libra', 'Magha', 'Yes', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(91, 91, 'About Rukhsar Sheikh - She is a professional looking for a life partner.', 'BSc', 'Aligarh Muslim University', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 3, 'Nuclear', 'Traditional', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Scorpio', 'Purva Phalguni', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(92, 92, 'About Sophia D Souza - She is a professional looking for a life partner.', 'MBA', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'No', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Sagittarius', 'Uttara Phalguni', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(93, 93, 'About Olivia Fernandes - She is a professional looking for a life partner.', 'MTech', 'St. Xavier\'s College', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 1, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Capricorn', 'Hasta', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(94, 94, 'About Emma D Costa - She is a professional looking for a life partner.', 'MBA', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Aquarius', 'Chitra', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(95, 95, 'About Ava Sequeira - She is a professional looking for a life partner.', 'PhD', 'St. Xavier\'s College', 'Engineer', '15-20 Lakh', 'Business', 'Business', 'Homemaker', 3, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Pisces', 'Swati', 'Partial', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(96, 96, 'About Mia D Silva - She is a professional looking for a life partner.', 'MTech', 'St. Xavier\'s College', 'Teacher', '6-8 Lakh', 'Government', 'Retired', 'Homemaker', 0, 'Joint', 'Moderate', 'Middle Class', 'Non-Vegetarian', 'Occasionally', 'Occasionally', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Aries', 'Vishakha', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(97, 97, 'About Isabella Pinto - She is a professional looking for a life partner.', 'BSc', 'St. Xavier\'s College', 'Business Professional', '10-15 Lakh', 'Business', 'Business', 'Homemaker', 1, 'Nuclear', 'Moderate', 'Middle Class', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Taurus', 'Anuradha', 'No', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(98, 98, 'About Amelia Dias - She is a professional looking for a life partner.', 'MBA', 'St. Xavier\'s College', 'Doctor', '25-30 Lakh', 'Private', 'Business', 'Homemaker', 2, 'Nuclear', 'Moderate', 'Affluent', 'Vegetarian', 'No', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Gemini', 'Jyeshtha', 'No', 1, 'My Matches', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(99, 99, 'About Charlotte Gomes - She is a professional looking for a life partner.', 'MTech', 'St. Xavier\'s College', 'Teacher', '6-8 Lakh', 'Government', 'Business', 'Homemaker', 3, 'Joint', 'Moderate', 'Middle Class', 'Vegetarian', 'Occasionally', 'No', 'India', 'Delhi', 'Mumbai', 'Citizen', 1, 'Cancer', 'Mula', 'No', 1, 'All', '2025-04-14 05:51:21', '2025-04-14 05:51:21'),
(100, 100, 'About Evelyn Pereira - She is a professional looking for a life partner.', 'PhD', 'St. Xavier\'s College', 'Engineer', '15-20 Lakh', 'Business', 'Retired', 'Homemaker', 0, 'Nuclear', 'Moderate', 'Upper Middle Class', 'Non-Vegetarian', 'Yes', 'Yes', 'India', 'Delhi', 'Mumbai', 'Citizen', 0, 'Leo', 'Purva Ashadha', 'Yes', 1, 'My Community', '2025-04-14 05:51:21', '2025-04-14 05:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reply` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `reply_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('hYLwplCA360aBfzZlunbJgXY0tSKVGcdk8ywnV6c', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicHRSdUpZSGg0dHluUWYzQUhLZ0tWYkw4enJEOXJNdXNDejdTRkh2MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jbGVhci1jYWNoZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1744559549),
('Q043lkkbOeAYuZOGcuczYZ71eYGfOVGaXx0ufe0m', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVENIdmJCMUp6N0VUZ0pzQkptdEpTMU9XQTFhTWxpR29sVXpFZ1lDdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jbGVhci1jYWNoZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1744561588);

-- --------------------------------------------------------

--
-- Table structure for table `social_media_links`
--

CREATE TABLE `social_media_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `hover_icon` varchar(255) DEFAULT NULL,
  `index_no` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `status` enum('Success','Pending','Failed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_name`, `start_date`, `end_date`, `amount`, `payment_method`, `transaction_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Premium', '2025-04-14', '2025-07-14', 2999.00, 'Credit Card', 'txn_123456', 'Success', '2025-04-14 04:42:15', '2025-04-14 04:42:15'),
(2, 4, 'VIP', '2025-04-14', '2026-04-14', 9999.00, 'PayTM', 'txn_789012', 'Success', '2025-04-14 04:42:15', '2025-04-14 04:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('open','closed','pending','replay') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--



-- --------------------------------------------------------

--
-- Table structure for table `token_blacklists`
--

CREATE TABLE `token_blacklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` longtext NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `date` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `email_verification_hash` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `dob` date NOT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `caste` varchar(255) DEFAULT NULL,
  `sub_caste` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `disability` tinyint(1) NOT NULL DEFAULT 0,
  `mother_tongue` varchar(255) DEFAULT NULL,
  `profile_created_by` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `profile_completion` int(11) NOT NULL DEFAULT 0,
  `account_status` enum('Active','Suspended','Deleted') NOT NULL DEFAULT 'Active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `email_verification_hash`, `otp`, `otp_expires_at`, `password`, `phone`, `gender`, `dob`, `religion`, `caste`, `sub_caste`, `marital_status`, `height`, `disability`, `mother_tongue`, `profile_created_by`, `verified`, `profile_completion`, `account_status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Aarav Sharma', 'aarav1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'Male', '1990-05-15', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 175.00, 0, 'Hindi', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(2, 'Vihaan Patel', 'vihaan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'Male', '1988-07-20', 'Hindu', 'Brahmin', 'Gaur', 'Never Married', 168.00, 0, 'Hindi', 'Parent', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(3, 'Aditya Joshi', 'aditya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 'Male', '1985-11-10', 'Hindu', 'Brahmin', 'Sanadhya', 'Divorced', 182.00, 0, 'Hindi', 'Self', 1, 60, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(4, 'Arjun Iyer', 'arjun1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', 'Male', '1992-03-25', 'Hindu', 'Brahmin', 'Iyer', 'Never Married', 170.00, 0, 'Tamil', 'Self', 1, 90, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(5, 'Reyansh Nair', 'reyansh1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543214', 'Male', '1995-09-12', 'Hindu', 'Brahmin', 'Namboodiri', 'Never Married', 165.00, 0, 'Malayalam', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(6, 'Atharva Deshpande', 'atharva1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543215', 'Male', '1993-12-05', 'Hindu', 'Brahmin', 'Deshastha', 'Never Married', 172.00, 0, 'Marathi', 'Self', 1, 70, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(7, 'Dhruv Chaturvedi', 'dhruv1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543216', 'Male', '1987-08-18', 'Hindu', 'Brahmin', 'Kanyakubja', 'Awaiting Divorce', 178.00, 0, 'Hindi', 'Self', 1, 65, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(8, 'Ishaan Mishra', 'ishaan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543217', 'Male', '1991-06-30', 'Hindu', 'Brahmin', 'Maithil', 'Never Married', 180.00, 0, 'Hindi', 'Parent', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(9, 'Kabir Trivedi', 'kabir1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543218', 'Male', '1989-04-22', 'Hindu', 'Brahmin', 'Saraswat', 'Never Married', 169.00, 0, 'Konkani', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(10, 'Advait Tiwari', 'advait1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543219', 'Male', '1994-02-14', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 174.00, 0, 'Hindi', 'Self', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(11, 'Rahul Patel', 'rahul1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543220', 'Male', '1988-11-15', 'Hindu', 'Patel', 'Leuva', 'Never Married', 171.00, 0, 'Gujarati', 'Parent', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(12, 'Vivaan Patel', 'vivaan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543221', 'Male', '1990-07-22', 'Hindu', 'Patel', 'Kadva', 'Never Married', 176.00, 0, 'Gujarati', 'Self', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(13, 'Kunal Patel', 'kunal1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543222', 'Male', '1989-03-18', 'Hindu', 'Patel', 'Leuva', 'Never Married', 174.00, 0, 'Gujarati', 'Self', 1, 72, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(14, 'Rohan Patel', 'rohan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543223', 'Male', '1991-12-11', 'Hindu', 'Patel', 'Kadva', 'Never Married', 169.00, 0, 'Gujarati', 'Parent', 1, 70, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(15, 'Nirav Patel', 'nirav1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543224', 'Male', '1990-08-14', 'Hindu', 'Patel', 'Leuva', 'Divorced', 180.00, 0, 'Gujarati', 'Self', 1, 65, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(16, 'Yash Patel', 'yash1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543225', 'Male', '1992-04-05', 'Hindu', 'Patel', 'Kadva', 'Never Married', 173.00, 0, 'Gujarati', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(17, 'Harsh Patel', 'harsh1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543226', 'Male', '1993-06-27', 'Hindu', 'Patel', 'Leuva', 'Never Married', 167.00, 0, 'Gujarati', 'Parent', 1, 69, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(18, 'Manav Patel', 'manav1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543227', 'Male', '1987-10-30', 'Hindu', 'Patel', 'Kadva', 'Widowed', 175.00, 0, 'Gujarati', 'Self', 1, 74, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(19, 'Parth Patel', 'parth1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543228', 'Male', '1991-11-21', 'Hindu', 'Patel', 'Leuva', 'Never Married', 172.00, 0, 'Gujarati', 'Self', 1, 77, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(20, 'Jay Patel', 'jay1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543229', 'Male', '1994-09-13', 'Hindu', 'Patel', 'Kadva', 'Never Married', 168.00, 0, 'Gujarati', 'Parent', 1, 79, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(21, 'Gurpreet Singh', 'gurpreet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543230', 'Male', '1987-09-10', 'Sikh', 'Jat', NULL, 'Never Married', 179.00, 0, 'Punjabi', 'Self', 1, 90, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(22, 'Harpreet Singh', 'harpreet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543231', 'Male', '1990-10-05', 'Sikh', 'Jat', NULL, 'Never Married', 181.00, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(23, 'Jagmeet Singh', 'jagmeet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543232', 'Male', '1988-02-19', 'Sikh', 'Jat', NULL, 'Divorced', 177.00, 0, 'Punjabi', 'Self', 1, 67, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(24, 'Manjeet Singh', 'manjeet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543233', 'Male', '1989-07-23', 'Sikh', 'Jat', NULL, 'Never Married', 174.00, 0, 'Punjabi', 'Self', 1, 72, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(25, 'Rajdeep Singh', 'rajdeep1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543234', 'Male', '1992-06-16', 'Sikh', 'Jat', NULL, 'Never Married', 176.00, 0, 'Punjabi', 'Parent', 1, 83, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(26, 'Simranjit Singh', 'simranjit1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543235', 'Male', '1986-12-30', 'Sikh', 'Jat', NULL, 'Widowed', 178.00, 0, 'Punjabi', 'Self', 1, 69, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(27, 'Sukhdeep Singh', 'sukhdeep1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543236', 'Male', '1990-01-25', 'Sikh', 'Jat', NULL, 'Never Married', 175.00, 0, 'Punjabi', 'Self', 1, 76, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(28, 'Inderjeet Singh', 'inderjeet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543237', 'Male', '1988-05-11', 'Sikh', 'Jat', NULL, 'Awaiting Divorce', 173.00, 0, 'Punjabi', 'Parent', 1, 68, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(29, 'Baljeet Singh', 'baljeet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543238', 'Male', '1993-04-07', 'Sikh', 'Jat', NULL, 'Never Married', 179.00, 0, 'Punjabi', 'Self', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(30, 'Tejinder Singh', 'tejinder1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543239', 'Male', '1991-09-29', 'Sikh', 'Jat', NULL, 'Never Married', 170.00, 0, 'Punjabi', 'Parent', 1, 74, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(31, 'Ayaan Khan', 'ayaan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543240', 'Male', '1989-12-05', 'Muslim', 'Pathan', NULL, 'Never Married', 173.00, 0, 'Urdu', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(32, 'Imran Ali', 'imran1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543241', 'Male', '1990-04-18', 'Muslim', 'Sheikh', NULL, 'Never Married', 171.00, 0, 'Urdu', 'Self', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(33, 'Faisal Ahmad', 'faisal1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543242', 'Male', '1987-06-10', 'Muslim', 'Syed', NULL, 'Never Married', 177.00, 0, 'Urdu', 'Parent', 1, 83, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(34, 'Zaid Qureshi', 'zaid1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543243', 'Male', '1993-01-15', 'Muslim', 'Qureshi', NULL, 'Never Married', 172.00, 0, 'Urdu', 'Self', 1, 72, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(35, 'Tariq Rahman', 'tariq1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543244', 'Male', '1988-09-09', 'Muslim', 'Ansari', NULL, 'Divorced', 170.00, 0, 'Urdu', 'Self', 1, 65, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(36, 'Sameer Sheikh', 'sameer1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543245', 'Male', '1991-02-20', 'Muslim', 'Sheikh', NULL, 'Never Married', 176.00, 0, 'Urdu', 'Parent', 1, 77, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(37, 'Salman Khan', 'salman1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543246', 'Male', '1986-05-01', 'Muslim', 'Khan', NULL, 'Never Married', 179.00, 0, 'Urdu', 'Self', 1, 70, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(38, 'Nasir Ansari', 'nasir1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543247', 'Male', '1989-11-17', 'Muslim', 'Ansari', NULL, 'Never Married', 173.00, 0, 'Urdu', 'Self', 1, 79, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(39, 'Yusuf Farooqi', 'yusuf1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543248', 'Male', '1992-08-14', 'Muslim', 'Farooqi', NULL, 'Widowed', 174.00, 0, 'Urdu', 'Parent', 1, 68, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(40, 'Rizwan Sheikh', 'rizwan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543249', 'Male', '1990-12-01', 'Muslim', 'Sheikh', NULL, 'Never Married', 169.00, 0, 'Urdu', 'Self', 1, 74, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(41, 'Ethan D Souza', 'ethan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543250', 'Male', '1991-03-18', 'Christian', 'Catholic', NULL, 'Never Married', 177.00, 0, 'English', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(42, 'Noah Fernandes', 'noah1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543251', 'Male', '1990-04-12', 'Christian', 'Protestant', NULL, 'Never Married', 174.00, 0, 'English', 'Self', 1, 83, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(43, 'Nathan D Costa', 'nathan1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543252', 'Male', '1988-11-07', 'Christian', 'Catholic', NULL, 'Divorced', 179.00, 0, 'English', 'Parent', 1, 70, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(44, 'Liam Sequeira', 'liam1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543253', 'Male', '1986-08-15', 'Christian', 'Orthodox', NULL, 'Never Married', 182.00, 0, 'English', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(45, 'Aiden D Silva', 'aiden1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543254', 'Male', '1989-10-20', 'Christian', 'Catholic', NULL, 'Never Married', 170.00, 0, 'English', 'Self', 1, 76, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(46, 'Caleb Pinto', 'caleb1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543255', 'Male', '1993-06-18', 'Christian', 'Protestant', NULL, 'Never Married', 176.00, 0, 'English', 'Parent', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(47, 'Isaac Dias', 'isaac1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543256', 'Male', '1992-02-28', 'Christian', 'Catholic', NULL, 'Never Married', 173.00, 0, 'English', 'Self', 1, 81, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(48, 'Elijah Gomes', 'elijah1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543257', 'Male', '1987-12-01', 'Christian', 'Catholic', NULL, 'Awaiting Divorce', 180.00, 0, 'English', 'Self', 1, 74, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(49, 'Samuel Pereira', 'samuel1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543258', 'Male', '1995-03-10', 'Christian', 'Protestant', NULL, 'Never Married', 168.00, 0, 'English', 'Parent', 1, 86, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(50, 'Daniel Coutinho', 'daniel1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543259', 'Male', '1991-01-19', 'Christian', 'Orthodox', NULL, 'Never Married', 175.00, 0, 'English', 'Self', 1, 79, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(51, 'Ananya Sharma', 'ananya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543260', 'Female', '1992-05-15', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 160.00, 0, 'Hindi', 'Self', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(52, 'Aditi Sharma', 'aditi1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543261', 'Female', '1993-03-12', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 155.00, 0, 'Hindi', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(53, 'Ishita Joshi', 'ishita1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543262', 'Female', '1990-07-25', 'Hindu', 'Brahmin', 'Sanadhya', 'Never Married', 160.00, 0, 'Hindi', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(54, 'Kavya Iyer', 'kavya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543263', 'Female', '1995-11-18', 'Hindu', 'Brahmin', 'Iyer', 'Never Married', 158.00, 0, 'Tamil', 'Self', 1, 90, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(55, 'Meera Nair', 'meera1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543264', 'Female', '1992-06-10', 'Hindu', 'Brahmin', 'Namboodiri', 'Never Married', 162.00, 0, 'Malayalam', 'Parent', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(56, 'Riya Deshpande', 'riya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543265', 'Female', '1994-09-05', 'Hindu', 'Brahmin', 'Deshastha', 'Never Married', 157.00, 0, 'Marathi', 'Self', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(57, 'Sanya Chaturvedi', 'sanya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543266', 'Female', '1991-12-22', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 159.00, 0, 'Hindi', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(58, 'Tanya Mishra', 'tanya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543267', 'Female', '1990-04-15', 'Hindu', 'Brahmin', 'Maithil', 'Never Married', 161.00, 0, 'Hindi', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(59, 'Vaishnavi Trivedi', 'vaishnavi1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543268', 'Female', '1993-08-30', 'Hindu', 'Brahmin', 'Saraswat', 'Never Married', 156.00, 0, 'Konkani', 'Parent', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(60, 'Yamini Tiwari', 'yamini1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543269', 'Female', '1991-02-14', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 154.00, 0, 'Hindi', 'Self', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(61, 'Zoya Sharma', 'zoya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543270', 'Female', '1994-05-20', 'Hindu', 'Brahmin', 'Gaur', 'Never Married', 160.00, 0, 'Hindi', 'Parent', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(62, 'Riya Patel', 'riya2@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543271', 'Female', '1992-03-15', 'Hindu', 'Patel', 'Leuva', 'Never Married', 158.00, 0, 'Gujarati', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(63, 'Nidhi Patel', 'nidhi1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543272', 'Female', '1993-07-20', 'Hindu', 'Patel', 'Kadva', 'Never Married', 160.00, 0, 'Gujarati', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(64, 'Krisha Patel', 'krisha1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543273', 'Female', '1990-05-10', 'Hindu', 'Patel', 'Leuva', 'Never Married', 157.00, 0, 'Gujarati', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(65, 'Mahi Patel', 'mahi1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543274', 'Female', '1991-11-25', 'Hindu', 'Patel', 'Kadva', 'Never Married', 159.00, 0, 'Gujarati', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(66, 'Jiya Patel', 'jiya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543275', 'Female', '1994-08-14', 'Hindu', 'Patel', 'Leuva', 'Never Married', 156.00, 0, 'Gujarati', 'Self', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(67, 'Anika Patel', 'anika1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543276', 'Female', '1993-02-18', 'Hindu', 'Patel', 'Kadva', 'Never Married', 158.00, 0, 'Gujarati', 'Self', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(68, 'Diya Patel', 'diya1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543277', 'Female', '1990-09-30', 'Hindu', 'Patel', 'Leuva', 'Never Married', 160.00, 0, 'Gujarati', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(69, 'Isha Patel', 'isha1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543278', 'Female', '1992-06-12', 'Hindu', 'Patel', 'Kadva', 'Never Married', 157.00, 0, 'Gujarati', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(70, 'Kavya Patel', 'kavya2@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543279', 'Female', '1991-04-22', 'Hindu', 'Patel', 'Leuva', 'Never Married', 159.00, 0, 'Gujarati', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(71, 'Tanya Patel', 'tanya2@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543280', 'Female', '1994-12-05', 'Hindu', 'Patel', 'Kadva', 'Never Married', 156.00, 0, 'Gujarati', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(72, 'Simran Kaur', 'simran1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543281', 'Female', '1992-01-15', 'Sikh', 'Jat', NULL, 'Never Married', 162.00, 0, 'Punjabi', 'Self', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(73, 'Harleen Kaur', 'harleen1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543282', 'Female', '1993-05-20', 'Sikh', 'Jat', NULL, 'Never Married', 160.00, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(74, 'Jasleen Kaur', 'jasleen1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543283', 'Female', '1990-03-10', 'Sikh', 'Jat', NULL, 'Never Married', 158.00, 0, 'Punjabi', 'Self', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(75, 'Navneet Kaur', 'navneet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543284', 'Female', '1991-09-25', 'Sikh', 'Jat', NULL, 'Never Married', 161.00, 0, 'Punjabi', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(76, 'Amrit Kaur', 'amrit1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543285', 'Female', '1994-07-14', 'Sikh', 'Jat', NULL, 'Never Married', 159.00, 0, 'Punjabi', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(77, 'Gurleen Kaur', 'gurleen1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543286', 'Female', '1993-02-18', 'Sikh', 'Jat', NULL, 'Never Married', 160.00, 0, 'Punjabi', 'Self', 1, 90, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(78, 'Manpreet Kaur', 'manpreet1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543287', 'Female', '1990-09-30', 'Sikh', 'Jat', NULL, 'Never Married', 162.00, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(79, 'Rajveer Kaur', 'rajveer1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543288', 'Female', '1992-06-12', 'Sikh', 'Jat', NULL, 'Never Married', 158.00, 0, 'Punjabi', 'Self', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(80, 'Taran Kaur', 'taran1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543289', 'Female', '1991-04-22', 'Sikh', 'Jat', NULL, 'Never Married', 161.00, 0, 'Punjabi', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(81, 'Kiran Kaur', 'kiran1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543290', 'Female', '1994-12-05', 'Sikh', 'Jat', NULL, 'Never Married', 159.00, 0, 'Punjabi', 'Self', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(82, 'Ayesha Khan', 'ayesha1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543291', 'Female', '1992-01-15', 'Muslim', 'Pathan', NULL, 'Never Married', 160.00, 0, 'Urdu', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(83, 'Zara Ali', 'zara2@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543292', 'Female', '1993-05-20', 'Muslim', 'Sheikh', NULL, 'Never Married', 158.00, 0, 'Urdu', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(84, 'Fatima Ahmad', 'fatima1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543293', 'Female', '1990-03-10', 'Muslim', 'Syed', NULL, 'Never Married', 157.00, 0, 'Urdu', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(85, 'Sana Qureshi', 'sana1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543294', 'Female', '1991-09-25', 'Muslim', 'Qureshi', NULL, 'Never Married', 159.00, 0, 'Urdu', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(86, 'Hina Rahman', 'hina1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543295', 'Female', '1994-07-14', 'Muslim', 'Ansari', NULL, 'Never Married', 156.00, 0, 'Urdu', 'Self', 1, 75, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(87, 'Sameera Sheikh', 'sameera1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543296', 'Female', '1993-02-18', 'Muslim', 'Sheikh', NULL, 'Never Married', 158.00, 0, 'Urdu', 'Self', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(88, 'Nazia Khan', 'nazia1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543297', 'Female', '1990-09-30', 'Muslim', 'Khan', NULL, 'Never Married', 160.00, 0, 'Urdu', 'Parent', 1, 80, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(89, 'Yasmin Ansari', 'yasmin1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543298', 'Female', '1992-06-12', 'Muslim', 'Ansari', NULL, 'Never Married', 157.00, 0, 'Urdu', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(90, 'Amina Farooqi', 'amina1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543299', 'Female', '1991-04-22', 'Muslim', 'Farooqi', NULL, 'Never Married', 159.00, 0, 'Urdu', 'Parent', 1, 82, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(91, 'Rukhsar Sheikh', 'rukhsar1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543300', 'Female', '1994-12-05', 'Muslim', 'Sheikh', NULL, 'Never Married', 156.00, 0, 'Urdu', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(92, 'Sophia D Souza', 'sophia1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543301', 'Female', '1992-03-18', 'Christian', 'Catholic', NULL, 'Never Married', 165.00, 0, 'English', 'Self', 1, 85, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(93, 'Olivia Fernandes', 'olivia1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543302', 'Female', '1991-04-12', 'Christian', 'Protestant', NULL, 'Never Married', 162.00, 0, 'English', 'Self', 1, 83, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(94, 'Emma D Costa', 'emma1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543303', 'Female', '1990-11-07', 'Christian', 'Catholic', NULL, 'Divorced', 168.00, 0, 'English', 'Parent', 1, 70, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(95, 'Ava Sequeira', 'ava1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543304', 'Female', '1989-08-15', 'Christian', 'Orthodox', NULL, 'Never Married', 170.00, 0, 'English', 'Self', 1, 78, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(96, 'Mia D Silva', 'mia1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543305', 'Female', '1993-10-20', 'Christian', 'Catholic', NULL, 'Never Married', 160.00, 0, 'English', 'Self', 1, 76, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(97, 'Isabella Pinto', 'isabella1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543306', 'Female', '1994-06-18', 'Christian', 'Protestant', NULL, 'Never Married', 164.00, 0, 'English', 'Parent', 1, 88, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(98, 'Amelia Dias', 'amelia1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543307', 'Female', '1992-02-28', 'Christian', 'Catholic', NULL, 'Never Married', 161.00, 0, 'English', 'Self', 1, 81, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(99, 'Charlotte Gomes', 'charlotte1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543308', 'Female', '1990-12-01', 'Christian', 'Catholic', NULL, 'Awaiting Divorce', 167.00, 0, 'English', 'Self', 1, 74, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57'),
(100, 'Evelyn Pereira', 'evelyn1@example.com', '2025-04-14 05:46:57', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543309', 'Female', '1995-03-10', 'Christian', 'Protestant', NULL, 'Never Married', 159.00, 0, 'English', 'Parent', 1, 86, 'Active', NULL, '2025-04-14 05:46:57', '2025-04-14 05:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_matches`
--

CREATE TABLE `user_matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `matched_user_id` bigint(20) UNSIGNED NOT NULL,
  `match_score` decimal(5,2) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected','Shortlisted') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_packages`
--

CREATE TABLE `user_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ends_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL COMMENT 'Stripe subscription ID for recurring payments',
  `stripe_customer_id` varchar(255) DEFAULT NULL COMMENT 'Stripe customer ID for recurring payments',
  `payment_method_type` varchar(50) DEFAULT NULL,
  `card_brand` varchar(50) DEFAULT NULL,
  `card_last_four` varchar(4) DEFAULT NULL,
  `card_exp_month` int(11) DEFAULT NULL,
  `card_exp_year` int(11) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `iban_last_four` varchar(4) DEFAULT NULL,
  `account_holder_type` varchar(50) DEFAULT NULL,
  `account_last_four` varchar(4) DEFAULT NULL,
  `routing_number` varchar(50) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active' COMMENT 'Subscription status: active, canceled, expired',
  `canceled_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp when the subscription was canceled',
  `next_billing_at` timestamp NULL DEFAULT NULL COMMENT 'Next billing date for recurring subscriptions'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_package_addons`
--

CREATE TABLE `user_package_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `allowed_origins`
--
ALTER TABLE `allowed_origins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `allowed_origins_origin_url_unique` (`origin_url`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`);

--
-- Indexes for table `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_category_article_id_category_id_unique` (`article_id`,`category_id`),
  ADD KEY `article_category_category_id_foreign` (`category_id`);

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
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `coupon_associations`
--
ALTER TABLE `coupon_associations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_associations_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_usages_user_id_foreign` (`user_id`);

--
-- Indexes for table `custom_package_requests`
--
ALTER TABLE `custom_package_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_package_requests_user_id_foreign` (`user_id`),
  ADD KEY `custom_package_requests_package_id_foreign` (`package_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `interactions`
--
ALTER TABLE `interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interactions_match_id_foreign` (`match_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_addons`
--
ALTER TABLE `package_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_discounts`
--
ALTER TABLE `package_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_discounts_package_id_foreign` (`package_id`);

--
-- Indexes for table `partner_preferences`
--
ALTER TABLE `partner_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partner_preferences_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_gateway_index` (`gateway`),
  ADD KEY `payments_coupon_id_foreign` (`coupon_id`),
  ADD KEY `payments_user_package_id_foreign` (`user_package_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_user_id_foreign` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `replies_support_ticket_id_foreign` (`support_ticket_id`),
  ADD KEY `replies_admin_id_foreign` (`admin_id`),
  ADD KEY `replies_user_id_foreign` (`user_id`),
  ADD KEY `replies_reply_id_foreign` (`reply_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_media_links_name_unique` (`name`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `token_blacklists`
--
ALTER TABLE `token_blacklists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_matches`
--
ALTER TABLE `user_matches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_matches_user_id_matched_user_id_unique` (`user_id`,`matched_user_id`),
  ADD KEY `user_matches_matched_user_id_foreign` (`matched_user_id`);

--
-- Indexes for table `user_packages`
--
ALTER TABLE `user_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_packages_user_id_foreign` (`user_id`),
  ADD KEY `user_packages_package_id_foreign` (`package_id`);

--
-- Indexes for table `user_package_addons`
--
ALTER TABLE `user_package_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_package_addons_user_id_foreign` (`user_id`),
  ADD KEY `user_package_addons_package_id_foreign` (`package_id`),
  ADD KEY `user_package_addons_addon_id_foreign` (`addon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allowed_origins`
--
ALTER TABLE `allowed_origins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article_category`
--
ALTER TABLE `article_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_associations`
--
ALTER TABLE `coupon_associations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_package_requests`
--
ALTER TABLE `custom_package_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interactions`
--
ALTER TABLE `interactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_addons`
--
ALTER TABLE `package_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_discounts`
--
ALTER TABLE `package_discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partner_preferences`
--
ALTER TABLE `partner_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `token_blacklists`
--
ALTER TABLE `token_blacklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `user_matches`
--
ALTER TABLE `user_matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_packages`
--
ALTER TABLE `user_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_package_addons`
--
ALTER TABLE `user_package_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article_category`
--
ALTER TABLE `article_category`
  ADD CONSTRAINT `article_category_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_associations`
--
ALTER TABLE `coupon_associations`
  ADD CONSTRAINT `coupon_associations_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `custom_package_requests`
--
ALTER TABLE `custom_package_requests`
  ADD CONSTRAINT `custom_package_requests_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `custom_package_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `interactions`
--
ALTER TABLE `interactions`
  ADD CONSTRAINT `interactions_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `user_matches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_discounts`
--
ALTER TABLE `package_discounts`
  ADD CONSTRAINT `package_discounts_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partner_preferences`
--
ALTER TABLE `partner_preferences`
  ADD CONSTRAINT `partner_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_package_id_foreign` FOREIGN KEY (`user_package_id`) REFERENCES `user_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `replies_reply_id_foreign` FOREIGN KEY (`reply_id`) REFERENCES `replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_matches`
--
ALTER TABLE `user_matches`
  ADD CONSTRAINT `user_matches_matched_user_id_foreign` FOREIGN KEY (`matched_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_matches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_packages`
--
ALTER TABLE `user_packages`
  ADD CONSTRAINT `user_packages_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_packages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_package_addons`
--
ALTER TABLE `user_package_addons`
  ADD CONSTRAINT `user_package_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `package_addons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_package_addons_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_package_addons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
