-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 07, 2026 at 12:24 PM
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
-- Database: `vocmanindia_rts`
--

-- --------------------------------------------------------

--
-- Table structure for table `aai_applications`
--

CREATE TABLE `aai_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `flats_count` tinyint(3) UNSIGNED NOT NULL,
  `flat_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`flat_types`)),
  `has_dustbins` tinyint(1) NOT NULL DEFAULT 1,
  `attached_toilet` tinyint(1) DEFAULT 0,
  `road_access` tinyint(1) NOT NULL DEFAULT 1,
  `food_on_request` tinyint(1) NOT NULL DEFAULT 0,
  `payment_cash` tinyint(1) NOT NULL DEFAULT 0,
  `payment_upi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `additional_features`
--

CREATE TABLE `additional_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `additional_features`
--

INSERT INTO `additional_features` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Other', 1, '2025-11-25 07:20:59', '2025-11-25 07:20:59'),
(2, 'Rainwater Harvesting', 1, '2025-11-25 07:21:13', '2025-11-25 07:21:13'),
(3, 'Sewage Treatment Plant', 1, '2025-11-25 07:21:27', '2025-11-25 07:21:27'),
(4, 'Conference Facilities', 1, '2025-11-25 07:21:38', '2025-11-25 07:21:38'),
(5, 'Business Center', 1, '2025-11-25 07:21:49', '2025-11-25 07:21:49'),
(6, 'Paid transportation on call', 1, '2025-11-25 07:22:00', '2025-11-25 07:22:00'),
(7, 'Iron and Iron Board facility', 1, '2025-11-25 07:22:12', '2025-11-25 07:22:12'),
(8, 'F and B Outlet', 1, '2025-11-25 07:22:23', '2025-11-25 07:22:23'),
(9, 'Suite (2 rooms or 2 room- bays having a bedroom and separate sitting area, having one bathroom and one powder room)', 1, '2025-11-25 07:22:32', '2025-11-25 07:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `adventure_applications`
--

CREATE TABLE `adventure_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `applicant_type` varchar(255) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `applicant_address` text DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `adventure_category` varchar(255) DEFAULT NULL,
  `activity_name` varchar(255) DEFAULT NULL,
  `activity_location` text DEFAULT NULL,
  `pan_file` varchar(255) DEFAULT NULL,
  `aadhar_file` varchar(255) DEFAULT NULL,
  `status` enum('submitted','approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agriculture_registrations`
--

CREATE TABLE `agriculture_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `status` enum('submitted','approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `center_name` varchar(255) NOT NULL,
  `applicant_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `applicant_address` text NOT NULL,
  `center_address` text DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `land_description` text DEFAULT NULL,
  `facility_day_trip` tinyint(1) NOT NULL DEFAULT 0,
  `facility_accommodation` tinyint(1) NOT NULL DEFAULT 0,
  `facility_recreational_service` tinyint(1) NOT NULL DEFAULT 0,
  `facility_play_area_children` tinyint(1) NOT NULL DEFAULT 0,
  `facility_adventure_games` tinyint(1) NOT NULL DEFAULT 0,
  `facility_rural_games` tinyint(1) NOT NULL DEFAULT 0,
  `facility_agricultural_camping` tinyint(1) NOT NULL DEFAULT 0,
  `facility_horticulture_product_sale` tinyint(1) NOT NULL DEFAULT 0,
  `applicant_live_in_place` enum('yes','no') NOT NULL DEFAULT 'yes',
  `activity_green_house` tinyint(1) NOT NULL DEFAULT 0,
  `activity_milk_business` tinyint(1) NOT NULL DEFAULT 0,
  `activity_fisheries` tinyint(1) NOT NULL DEFAULT 0,
  `activity_rop_vatika` tinyint(1) NOT NULL DEFAULT 0,
  `activity_animal_bird_rearing` tinyint(1) NOT NULL DEFAULT 0,
  `activity_nature_adventure_tourism` tinyint(1) NOT NULL DEFAULT 0,
  `activity_other` tinyint(1) NOT NULL DEFAULT 0,
  `activity_other_text` varchar(255) DEFAULT NULL,
  `center_started_on` varchar(255) DEFAULT NULL,
  `file_signature_stamp` varchar(255) DEFAULT NULL,
  `file_land_documents` varchar(255) DEFAULT NULL,
  `file_registration_certificate` varchar(255) DEFAULT NULL,
  `file_authorization_letter` varchar(255) DEFAULT NULL,
  `file_pan_card` varchar(255) DEFAULT NULL,
  `file_aadhar_card` varchar(255) DEFAULT NULL,
  `file_registration_fee_challan` varchar(255) DEFAULT NULL,
  `file_electricity_bill` varchar(255) DEFAULT NULL,
  `file_food_security_licence` varchar(255) DEFAULT NULL,
  `file_building_permission` varchar(255) DEFAULT NULL,
  `file_declaration_form` varchar(255) DEFAULT NULL,
  `file_zone_certificate` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `current_step` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `status` enum('draft','submitted','approved','rejected','pending') NOT NULL DEFAULT 'draft',
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `region_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(50) DEFAULT NULL,
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_desk_number` int(11) NOT NULL DEFAULT 1,
  `form_current_status` varchar(255) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_details`
--

CREATE TABLE `application_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `pan` varchar(255) DEFAULT NULL,
  `business_pan` varchar(255) DEFAULT NULL,
  `aadhaar` varchar(255) DEFAULT NULL,
  `udyam` varchar(255) DEFAULT NULL,
  `ownership_proof` varchar(255) DEFAULT NULL,
  `ownership_proof_type` varchar(255) DEFAULT NULL,
  `is_property_rented` tinyint(1) NOT NULL DEFAULT 0,
  `operator_name` varchar(255) DEFAULT NULL,
  `rental_agreement` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_type` varchar(255) DEFAULT NULL,
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document_key` varchar(255) NOT NULL,
  `document_label` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `role_approvals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`role_approvals`)),
  `overall_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_forms`
--

CREATE TABLE `application_forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `short_description` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_forms`
--

INSERT INTO `application_forms` (`id`, `name`, `slug`, `short_description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Issuance of Temporary Registration Certificate', 'issuance-of-temporary-registration-certificate', 'For Tourist Entities under the Tourism Policy 2024.', 'application_forms/issuance-of-temporary-registration-certificate-1762230533.jpeg', 1, '2025-11-03 22:58:54', '2025-11-03 22:58:54'),
(2, 'Issuance of Eligibility Certificate', 'issuance-of-eligibility-certificate', 'For Tourist Entities under the Tourism Policy 2024.', 'application_forms/issuance-of-eligibility-certificate-1762231179.avif', 1, '2025-11-03 23:09:39', '2025-11-03 23:09:39'),
(3, 'Issuance of No Objection Certificate', 'issuance-of-no-objection-certificate', 'For Stamp Duty Concession under Tourism Policy 2024.', 'application_forms/issuance-of-no-objection-certificate-1762231231.webp', 1, '2025-11-03 23:10:31', '2025-11-03 23:10:31'),
(4, 'Registration of Tourism Villas', 'registration-of-tourism-villas', 'Under the Tourism Policy 2024.', 'application_forms/registration-of-tourism-villas-1762231270.webp', 1, '2025-11-03 23:11:11', '2025-11-03 23:11:11'),
(5, 'Registration of Tourism Apartments', 'registration-of-tourism-apartments', 'Under the Tourism Policy 2024.', 'application_forms/registration-of-tourism-apartments-1762231342.png', 1, '2025-11-03 23:12:22', '2025-11-03 23:12:22'),
(6, 'Registration of Homestays', 'registration-of-homestays', 'Under the Tourism Policy 2024.', 'application_forms/registration-of-homestays-1762231382.avif', 1, '2025-11-03 23:13:02', '2025-11-03 23:13:02'),
(7, 'Registration of Vacation Homes', 'registration-of-vacation-homes', 'Under the Tourism Policy 2024.', 'application_forms/registration-of-vacation-homes-1762231516.avif', 1, '2025-11-03 23:15:16', '2025-11-03 23:15:16'),
(8, 'Women-Centered Tourism Policy Registration', 'women-centered-tourism-policy-registration', 'Mahila Kendrit Tourism Policy 2024.', 'application_forms/women-centered-tourism-policy-registration-1762231662.jpg', 1, '2025-11-03 23:17:42', '2025-11-03 23:17:42'),
(9, 'Granting Industrial Status to Hospitality Sector', 'granting-industrial-status-to-hospitality-sector', 'Under Tourism Policy 2024.', 'application_forms/granting-industrial-status-to-hospitality-sector-1762231706.jpg', 1, '2025-11-03 23:18:26', '2025-11-03 23:18:26'),
(10, 'Agricultural Tourism Policy Registration', 'agricultural-tourism-policy-registration', 'Under Tourism Policy 2024.', 'application_forms/agricultural-tourism-policy-registration-1762231771.jpg', 1, '2025-11-03 23:19:31', '2025-11-03 23:19:31'),
(11, 'Adventure Tourism Policy Registration', 'adventure-tourism-policy-registration', 'Under Tourism Policy 2024.', 'application_forms/adventure-tourism-policy-registration-1762231929.webp', 1, '2025-11-03 23:22:09', '2025-11-03 23:22:09'),
(12, 'Caravan Tourism Policy Registration', 'caravan-tourism-policy-registration', 'Under Tourism Policy 2024.', 'application_forms/caravan-tourism-policy-registration-1762231970.jpg', 1, '2025-11-03 23:22:50', '2025-11-03 23:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `application_movements`
--

CREATE TABLE `application_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` varchar(255) DEFAULT NULL,
  `desk_number` int(11) NOT NULL,
  `officer_name` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `action_datetime` datetime NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_movements`
--

INSERT INTO `application_movements` (`id`, `application_id`, `desk_number`, `officer_name`, `action`, `action_datetime`, `remarks`, `created_at`, `updated_at`) VALUES
(4, 'PVR-64569805', 1, 'Clerk', 'Approved', '2026-01-20 12:23:06', 'Documents verified', '2026-01-23 06:53:06', '2026-01-23 06:53:06'),
(5, 'PVR-64569805', 2, 'Assistant Director', 'Approved', '2026-01-21 12:23:06', 'Approved by AD', '2026-01-23 06:53:06', '2026-01-23 06:53:06'),
(6, 'PVR-64569805', 3, 'Dy Director', 'Pending', '2026-01-22 12:23:06', 'Under review', '2026-01-23 06:53:06', '2026-01-23 06:53:06');

-- --------------------------------------------------------

--
-- Table structure for table `application_workflow_logs`
--

CREATE TABLE `application_workflow_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_type` varchar(255) DEFAULT NULL,
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stage` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mumbai', 1, '2026-01-02 01:39:28', '2026-01-02 01:39:28'),
(2, 'Thane', 1, '2026-01-02 01:39:41', '2026-01-02 01:39:41'),
(3, 'Navi', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(4, 'Mumbai Municipal Area', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(5, 'Nashik', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(6, 'Pune', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(7, 'Aurangabad', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(8, 'Nagpur Municipal Corporation', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(9, 'Rest of Maharashtra', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(10, 'Specially Declared Tourism Zones', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02'),
(11, 'Entire State', 1, '2026-01-02 07:16:02', '2026-01-02 07:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('mumbai-tourism-cache-314b7d3ef79fbaac247a519a39903a05567e3282', 'i:2;', 1769772034),
('mumbai-tourism-cache-314b7d3ef79fbaac247a519a39903a05567e3282:timer', 'i:1769772034;', 1769772034),
('mumbai-tourism-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1770373234),
('mumbai-tourism-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1770373234;', 1770373234),
('mumbai-tourism-cache-995bf31241ef243f2d1a4fa386b0658032a23d9a', 'i:1;', 1769775049),
('mumbai-tourism-cache-995bf31241ef243f2d1a4fa386b0658032a23d9a:timer', 'i:1769775049;', 1769775049),
('mumbai-tourism-cache-captcha_1cc47ce148a01b4c334996e00cb77616', 'a:6:{i:0;s:1:\"c\";i:1;s:1:\"f\";i:2;s:1:\"b\";i:3;s:1:\"q\";i:4;s:1:\"t\";i:5;s:1:\"m\";}', 1770097532),
('mumbai-tourism-cache-captcha_42af352ffce7bd64022a9761268c6297', 'a:6:{i:0;s:1:\"p\";i:1;s:1:\"n\";i:2;s:1:\"t\";i:3;s:1:\"e\";i:4;s:1:\"j\";i:5;s:1:\"9\";}', 1770097264),
('mumbai-tourism-cache-captcha_5233c895c12ae61052e45428e299d92b', 'a:6:{i:0;s:1:\"5\";i:1;s:1:\"l\";i:2;s:1:\"g\";i:3;s:1:\"h\";i:4;s:1:\"c\";i:5;s:1:\"u\";}', 1770100415),
('mumbai-tourism-cache-captcha_5c255bea9e5e59c0cc0c0b178630b9b8', 'a:6:{i:0;s:1:\"k\";i:1;s:1:\"m\";i:2;s:1:\"i\";i:3;s:1:\"l\";i:4;s:1:\"m\";i:5;s:1:\"u\";}', 1770097300),
('mumbai-tourism-cache-captcha_754161fdf759d83dcb93152eba72683d', 'a:6:{i:0;s:1:\"i\";i:1;s:1:\"y\";i:2;s:1:\"f\";i:3;s:1:\"i\";i:4;s:1:\"x\";i:5;s:1:\"a\";}', 1770097302),
('mumbai-tourism-cache-captcha_855f5d7e199355c056fa7b9d51931d24', 'a:6:{i:0;s:1:\"p\";i:1;s:1:\"i\";i:2;s:1:\"y\";i:3;s:1:\"u\";i:4;s:1:\"z\";i:5;s:1:\"s\";}', 1770097270),
('mumbai-tourism-cache-captcha_8c0503f2afabb50d0b82298e085ceac4', 'a:6:{i:0;s:1:\"f\";i:1;s:1:\"v\";i:2;s:1:\"g\";i:3;s:1:\"q\";i:4;s:1:\"s\";i:5;s:1:\"c\";}', 1770097251),
('mumbai-tourism-cache-captcha_9fdcd407cab3c9d24b5eb02e54720db1', 'a:6:{i:0;s:1:\"9\";i:1;s:1:\"w\";i:2;s:1:\"d\";i:3;s:1:\"o\";i:4;s:1:\"x\";i:5;s:1:\"t\";}', 1770185960),
('mumbai-tourism-cache-captcha_bd40d572e0996a10976e83b337690586', 'a:6:{i:0;s:1:\"r\";i:1;s:1:\"0\";i:2;s:1:\"q\";i:3;s:1:\"n\";i:4;s:1:\"p\";i:5;s:1:\"j\";}', 1770185921),
('mumbai-tourism-cache-captcha_cbf1dc9f7c803a003732f539b712443d', 'a:6:{i:0;s:1:\"w\";i:1;s:1:\"4\";i:2;s:1:\"o\";i:3;s:1:\"e\";i:4;s:1:\"4\";i:5;s:1:\"y\";}', 1770188915),
('mumbai-tourism-cache-captcha_da321c018d21e6454b054cd0d0e71895', 'a:6:{i:0;s:1:\"i\";i:1;s:1:\"p\";i:2;s:1:\"x\";i:3;s:1:\"t\";i:4;s:1:\"t\";i:5;s:1:\"q\";}', 1770102892),
('mumbai-tourism-cache-captcha_f0807b50a67cf8ed3e2096731be9a624', 'a:6:{i:0;s:1:\"p\";i:1;s:1:\"g\";i:2;s:1:\"h\";i:3;s:1:\"y\";i:4;s:1:\"v\";i:5;s:1:\"d\";}', 1770097243),
('mumbai-tourism-cache-captcha_f842a824837b7224892454c810b8f08c', 'a:6:{i:0;s:1:\"x\";i:1;s:1:\"p\";i:2;s:1:\"j\";i:3;s:1:\"c\";i:4;s:1:\"m\";i:5;s:1:\"5\";}', 1770440400),
('mumbai-tourism-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:6:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"d\";s:10:\"group_name\";s:1:\"r\";s:5:\"roles\";s:1:\"j\";s:6:\"status\";}s:11:\"permissions\";a:21:{i:0;a:5:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"view user\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"User\";s:1:\"r\";a:1:{i:0;i:3;}}i:1;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"edit user\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"User\";s:1:\"r\";a:1:{i:0;i:3;}}i:2;a:5:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"delete user\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"User\";s:1:\"r\";a:1:{i:0;i:3;}}i:3;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"create user\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"User\";s:1:\"r\";a:1:{i:0;i:3;}}i:4;a:5:{s:1:\"a\";i:6;s:1:\"b\";s:15:\"view permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:10:\"Permission\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:5;}}i:5;a:5:{s:1:\"a\";i:7;s:1:\"b\";s:17:\"create permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:10:\"Permission\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:5;}}i:6;a:5:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"edit permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:10:\"Permission\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:5;}}i:7;a:5:{s:1:\"a\";i:9;s:1:\"b\";s:17:\"delete permission\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:10:\"Permission\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:5;}}i:8;a:5:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"Role\";s:1:\"r\";a:1:{i:0;i:3;}}i:9;a:5:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"Role\";s:1:\"r\";a:1:{i:0;i:3;}}i:10;a:5:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"Role\";s:1:\"r\";a:1:{i:0;i:3;}}i:11;a:5:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:4:\"Role\";s:1:\"r\";a:1:{i:0;i:3;}}i:12;a:5:{s:1:\"a\";i:14;s:1:\"b\";s:8:\"Director\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:8:\"Workflow\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:10;}}i:13;a:5:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"Joint Director\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:8:\"Workflow\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:12;}}i:14;a:5:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"Dy Director\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:8:\"Workflow\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:11;}}i:15;a:5:{s:1:\"a\";i:17;s:1:\"b\";s:13:\"Asst Director\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:8:\"Workflow\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:9;}}i:16;a:5:{s:1:\"a\";i:18;s:1:\"b\";s:5:\"Clerk\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:8:\"Workflow\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:8;}}i:17;a:5:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"delete forms\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Application Forms\";s:1:\"r\";a:1:{i:0;i:3;}}i:18;a:5:{s:1:\"a\";i:20;s:1:\"b\";s:10:\"edit forms\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Application Forms\";s:1:\"r\";a:1:{i:0;i:3;}}i:19;a:5:{s:1:\"a\";i:21;s:1:\"b\";s:12:\"create forms\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Application Forms\";s:1:\"r\";a:1:{i:0;i:3;}}i:20;a:5:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"view forms\";s:1:\"c\";s:3:\"web\";s:1:\"d\";s:17:\"Application Forms\";s:1:\"r\";a:6:{i:0;i:3;i:1;i:8;i:2;i:9;i:3;i:10;i:4;i:11;i:5;i:12;}}}s:5:\"roles\";a:7:{i:0;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:1;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:2;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Director\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:3;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:14:\"Joint Director\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:4;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"Dy Director\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:5;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"Asst Director\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}i:6;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:5:\"Clerk\";s:1:\"c\";s:3:\"web\";s:1:\"j\";i:1;}}}', 1770183921);

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
-- Table structure for table `caravan_amenities`
--

CREATE TABLE `caravan_amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `caravan_amenities`
--

INSERT INTO `caravan_amenities` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Air Conditioning', 1, '2025-11-16 07:45:50', '2025-11-16 07:45:50'),
(2, 'GPS System', 1, '2025-11-16 07:54:20', '2025-11-16 12:32:01'),
(3, 'Gas / Electric Water Heater', 1, '2025-11-16 12:32:24', '2025-11-16 12:32:24'),
(4, 'Separate arrangement for communication between drivers and passengers in large caravans', 1, '2025-11-16 12:32:37', '2025-11-16 12:32:37'),
(5, 'External Barbeque Points', 1, '2025-11-16 12:32:48', '2025-11-16 12:32:48'),
(6, 'Mobile Charging SLot', 1, '2025-11-16 12:32:59', '2025-11-16 12:32:59'),
(7, 'Convenience of supplying electricity through battery or external hook up', 1, '2025-11-16 12:33:14', '2025-11-16 12:33:14'),
(8, 'Power wheel mover System for caravan parking (Integrated or clip-on)', 1, '2025-11-16 12:33:26', '2025-11-16 12:33:26'),
(9, 'Caravan Stabilizer', 1, '2025-11-16 12:34:30', '2025-11-16 12:34:30'),
(11, 'RTG', 1, '2025-11-24 22:16:42', '2025-11-24 22:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `caravan_optional_features`
--

CREATE TABLE `caravan_optional_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `caravan_optional_features`
--

INSERT INTO `caravan_optional_features` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Washing Machine and clothes drawer', 1, '2025-11-16 08:45:17', '2025-11-16 12:29:08'),
(2, 'Tent', 1, '2025-11-16 12:29:22', '2025-11-16 12:29:22'),
(3, 'Fridge and Microwave', 1, '2025-11-16 12:29:32', '2025-11-16 12:29:32'),
(4, 'Dining Table', 1, '2025-11-16 12:29:41', '2025-11-16 12:29:41'),
(5, 'WheelChair Facility', 1, '2025-11-16 12:29:54', '2025-11-16 12:29:54'),
(6, 'Convenience to keep the cycle behind the caravan', 1, '2025-11-16 12:30:10', '2025-11-16 12:30:10'),
(7, 'Audio Guide', 1, '2025-11-16 12:30:19', '2025-11-16 12:30:19'),
(8, 'Washroom with sufficient water supply along with shower and hand shower facility', 1, '2025-11-16 12:30:29', '2025-11-16 12:30:29'),
(9, 'Separate Tanks for grey water (wash water) and black water (sewage)', 1, '2025-11-16 12:30:38', '2025-11-16 12:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `caravan_registrations`
--

CREATE TABLE `caravan_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `applicant_type` bigint(20) UNSIGNED NOT NULL,
  `emergency_contact` varchar(255) NOT NULL,
  `caravan_type_id` bigint(20) UNSIGNED NOT NULL,
  `prior_experience` text DEFAULT NULL,
  `vehicle_reg_no` varchar(255) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `beds` int(11) DEFAULT NULL,
  `engine_no` varchar(255) DEFAULT NULL,
  `chassis_no` varchar(255) DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `optional_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`optional_features`)),
  `routes` text NOT NULL,
  `registration_fee_challan` varchar(255) NOT NULL,
  `vehicle_reg_card` varchar(255) NOT NULL,
  `vehicle_insurance` varchar(255) NOT NULL,
  `declaration_form` varchar(255) NOT NULL,
  `aadhar_card` varchar(255) NOT NULL,
  `pan_card` varchar(255) NOT NULL,
  `vehicle_purchase_copy` varchar(255) NOT NULL,
  `company_proof` varchar(255) NOT NULL,
  `status` enum('submitted','approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caravan_types`
--

CREATE TABLE `caravan_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `caravan_types`
--

INSERT INTO `caravan_types` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Camper Trailer', 1, '2025-11-19 01:27:04', '2025-11-19 01:27:04'),
(3, 'Folding Caravan', 1, '2025-11-19 01:27:28', '2025-11-19 01:27:28'),
(4, 'Tent Trailer', 1, '2025-11-19 01:27:51', '2025-11-19 01:27:51'),
(5, 'Twin Axle Caravan', 1, '2025-11-19 01:28:13', '2025-11-19 01:28:13'),
(6, 'Single Axle Conventional Caravan', 1, '2025-11-19 01:28:26', '2025-11-19 01:28:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'GEN', 1, '2025-11-06 01:58:59', '2025-11-06 02:12:51'),
(2, 'OBC', 1, '2025-11-06 02:13:48', '2025-11-06 02:13:48'),
(3, 'SC', 1, '2025-11-06 02:14:07', '2025-11-06 02:14:07'),
(4, 'ST', 1, '2025-11-06 02:14:20', '2025-11-06 02:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `category_registrations`
--

CREATE TABLE `category_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso_code` varchar(3) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso_code`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'India', 'IND', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(2, 'United States', 'USA', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(3, 'United Kingdom', 'GBR', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(4, 'Canada', 'CAN', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(5, 'Australia', 'AUS', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(6, 'Germany', 'DEU', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(7, 'France', 'FRA', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(8, 'Japan', 'JPN', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(9, 'China', 'CHN', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL),
(10, 'Brazil', 'BRA', 1, '2025-11-01 17:11:14', '2025-11-01 17:11:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `name`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 14, 'Ahmednagar', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(2, 14, 'Akola', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(3, 14, 'Amravati', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(4, 14, 'Aurangabad', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(5, 14, 'Beed', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(6, 14, 'Bhandara', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(7, 14, 'Buldhana', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(8, 14, 'Chandrapur', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(9, 14, 'Dhule', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(10, 14, 'Gadchiroli', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(11, 14, 'Gondia', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(12, 14, 'Hingoli', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(13, 14, 'Jalgaon', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(14, 14, 'Jalna', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(15, 14, 'Kolhapur', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(16, 14, 'Latur', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(17, 14, 'Mumbai City', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(18, 14, 'Mumbai Suburban', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(19, 14, 'Nagpur', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(20, 14, 'Nanded', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(21, 14, 'Nandurbar', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(22, 14, 'Nashik', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(23, 14, 'Osmanabad', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(24, 14, 'Palghar', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(25, 14, 'Parbhani', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(26, 14, 'Pune', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(27, 14, 'Raigad', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(28, 14, 'Ratnagiri', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(29, 14, 'Sangli', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(30, 14, 'Satara', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(31, 14, 'Sindhudurg', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(32, 14, 'Solapur', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(33, 14, 'Thane', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(34, 14, 'Wardha', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(35, 14, 'Washim', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL),
(36, 14, 'Yavatmal', 1, '2025-11-13 06:36:46', '2025-11-13 06:36:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `districts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`districts`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `code`, `districts`, `is_active`, `created_at`, `updated_at`) VALUES
(12, 'Konkan', '1', '\"[\\\"17\\\",\\\"18\\\",\\\"24\\\",\\\"27\\\",\\\"28\\\",\\\"31\\\",\\\"33\\\"]\"', 1, '2025-11-20 01:38:05', '2025-11-20 01:38:05'),
(13, 'Pune', '2', '\"[\\\"15\\\",\\\"26\\\",\\\"29\\\",\\\"30\\\",\\\"32\\\"]\"', 1, '2025-11-20 01:38:54', '2025-11-20 01:38:54'),
(14, 'Nashik', '3', '\"[\\\"1\\\",\\\"9\\\",\\\"13\\\",\\\"21\\\",\\\"22\\\"]\"', 1, '2025-11-20 01:40:05', '2025-11-20 01:40:05'),
(15, 'Aurangabad', '4', '\"[\\\"4\\\",\\\"5\\\",\\\"12\\\",\\\"14\\\",\\\"16\\\",\\\"20\\\",\\\"23\\\",\\\"25\\\"]\"', 1, '2025-11-20 01:41:08', '2025-11-20 01:45:25'),
(16, 'Amravati', '5', '\"[\\\"2\\\",\\\"3\\\",\\\"7\\\",\\\"35\\\",\\\"36\\\"]\"', 1, '2025-11-20 01:41:58', '2025-11-20 01:41:58'),
(17, 'Nagpur', '6', '\"[\\\"6\\\",\\\"8\\\",\\\"10\\\",\\\"11\\\",\\\"19\\\",\\\"34\\\"]\"', 1, '2025-11-20 01:42:45', '2025-11-20 01:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eligibility_registrations`
--

CREATE TABLE `eligibility_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `slug_id` varchar(255) NOT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `provisional_number` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entrepreneurs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`entrepreneurs`)),
  `project_description` text DEFAULT NULL,
  `commencement_date` date DEFAULT NULL,
  `operation_details` varchar(255) DEFAULT NULL,
  `cost_component` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cost_component`)),
  `asset_age` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`asset_age`)),
  `ownership` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ownership`)),
  `enclosures` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`enclosures`)),
  `other_docs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_docs`)),
  `signature_path` varchar(255) DEFAULT NULL,
  `declaration_place` varchar(255) DEFAULT NULL,
  `declaration_date` date DEFAULT NULL,
  `status` enum('submitted','approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enclosures`
--

CREATE TABLE `enclosures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enclosures`
--

INSERT INTO `enclosures` (`id`, `application_id`, `user_id`, `meta`, `created_at`, `updated_at`) VALUES
(1, 1, 6, '[\"aadhar\",\"pan\",\"business_pan\",\"udyam\",\"business_reg\",\"ownership\",\"property_photos\",\"character\",\"society_noc\",\"gras_copy\",\"building_perm\",\"undertaking\",\"rental_agreement\"]', '2025-11-24 03:14:25', '2025-11-24 03:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `enterprises`
--

CREATE TABLE `enterprises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enterprises`
--

INSERT INTO `enterprises` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Proprietorship', 1, NULL, NULL),
(2, 'Partnership', 1, NULL, NULL),
(3, 'Private Limited (Pvt Ltd)', 1, NULL, NULL),
(4, 'Limited Liability Partnership (LLP)', 1, NULL, NULL),
(5, 'Public Limited Company', 1, NULL, NULL),
(6, 'Co-operative Society', 1, NULL, NULL),
(7, 'Society', 1, NULL, NULL),
(8, 'Trust', 1, NULL, NULL),
(9, 'Self Help Group (SHG)', 1, NULL, NULL),
(10, 'Joint Forest Management Committee (JFMC)', 1, NULL, NULL),
(11, 'Other', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `kitchen` tinyint(1) NOT NULL DEFAULT 0,
  `dining_hall` tinyint(1) NOT NULL DEFAULT 0,
  `garden` tinyint(1) NOT NULL DEFAULT 0,
  `parking` tinyint(1) NOT NULL DEFAULT 0,
  `ev_charging` tinyint(1) NOT NULL DEFAULT 0,
  `children_play_area` tinyint(1) NOT NULL DEFAULT 0,
  `swimming_pool` tinyint(1) NOT NULL DEFAULT 0,
  `wifi` tinyint(1) NOT NULL DEFAULT 0,
  `first_aid` tinyint(1) NOT NULL DEFAULT 0,
  `fire_safety` tinyint(1) NOT NULL DEFAULT 0,
  `water_purifier` tinyint(1) NOT NULL DEFAULT 0,
  `rainwater_harvesting` tinyint(1) NOT NULL DEFAULT 0,
  `solar_power` tinyint(1) NOT NULL DEFAULT 0,
  `other_renewable` tinyint(1) NOT NULL DEFAULT 0,
  `gras_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `application_id`, `user_id`, `facilities`, `kitchen`, `dining_hall`, `garden`, `parking`, `ev_charging`, `children_play_area`, `swimming_pool`, `wifi`, `first_aid`, `fire_safety`, `water_purifier`, `rainwater_harvesting`, `solar_power`, `other_renewable`, `gras_paid`, `created_at`, `updated_at`) VALUES
(10, 14, 6, '[2,3]', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, '2025-11-28 07:11:15', '2025-11-28 07:11:15');

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
-- Table structure for table `general_requirements`
--

CREATE TABLE `general_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_requirements`
--

INSERT INTO `general_requirements` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Security Guards available 24 hours a day', 1, '2025-11-25 05:44:46', '2025-11-25 05:44:46'),
(5, 'Differently Abled Guest Friendly Access at the entrance', 1, '2025-11-25 05:45:48', '2025-11-25 05:45:48'),
(6, 'Hotel Entrances and all common areas are controlled by CCTV Cameras 24 hours a day', 1, '2025-11-25 05:46:15', '2025-11-25 05:46:15'),
(7, 'Emergency lights available in the public areas', 1, '2025-11-25 05:46:48', '2025-11-25 05:46:48'),
(8, '24x7 availability of electricity', 1, '2025-11-25 05:47:03', '2025-11-25 05:47:03'),
(9, '24 hrs. elevators for buildings higher than ground plus four floors or as per the prevailing local building norms applicable', 1, '2025-11-25 05:47:17', '2025-11-25 05:47:17'),
(10, 'Full time operation 7 days a week', 1, '2025-11-25 05:47:28', '2025-11-25 05:47:28'),
(11, 'Bathroom Sanitary Fixtures Toilets must be well ventilated. Each western WC toilet should have a seat with lid and toilet paper. Post toilet hygiene facilities - toilet paper, soap, sanitary bin, 24-hour running water.', 1, '2025-11-25 05:47:38', '2025-11-25 05:47:38'),
(12, 'Minimum Bathroom size should be 30 sq. ft.', 1, '2025-11-25 05:47:54', '2025-11-25 05:47:54'),
(13, 'Minimum Rooms size should be as follows (Room size excludes bathroom) All rooms must have attached bathroom mandatorily - Single - 80 sq. ft. and Double - 120 sq. fL', 1, '2025-11-25 05:48:04', '2025-11-25 05:48:04'),
(14, 'Minimum 6 lettable rooms, all rooms with outside windows / ventilation', 1, '2025-11-25 05:48:16', '2025-11-25 05:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_name` varchar(255) NOT NULL,
  `site_description` varchar(500) NOT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest_services`
--

CREATE TABLE `guest_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_additional_features`
--

CREATE TABLE `industrial_additional_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `industrial_additional_features`
--

INSERT INTO `industrial_additional_features` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Other', 1, '2025-11-25 07:20:59', '2025-11-25 07:20:59'),
(2, 'Rainwater Harvesting', 1, '2025-11-25 07:21:13', '2025-11-25 07:21:13'),
(3, 'Sewage Treatment Plant', 1, '2025-11-25 07:21:27', '2025-11-25 07:21:27'),
(4, 'Conference Facilities', 1, '2025-11-25 07:21:38', '2025-11-25 07:21:38'),
(5, 'Business Center', 1, '2025-11-25 07:21:49', '2025-11-25 07:21:49'),
(6, 'Paid transportation on call', 1, '2025-11-25 07:22:00', '2025-11-25 07:22:00'),
(7, 'Iron and Iron Board facility', 1, '2025-11-25 07:22:12', '2025-11-25 07:22:12'),
(8, 'F and B Outlet', 1, '2025-11-25 07:22:23', '2025-11-25 07:22:23'),
(9, 'Suite (2 rooms or 2 room- bays having a bedroom and separate sitting area, having one bathroom and one powder room)', 1, '2025-11-25 07:22:32', '2025-11-25 07:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `industrial_applications`
--

CREATE TABLE `industrial_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved','rejected','pending') NOT NULL DEFAULT 'draft',
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `current_step` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_general_requirements`
--

CREATE TABLE `industrial_general_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `industrial_general_requirements`
--

INSERT INTO `industrial_general_requirements` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Security Guards available 24 hours a day', 1, '2025-11-25 05:44:46', '2025-11-25 05:44:46'),
(5, 'Differently Abled Guest Friendly Access at the entrance', 1, '2025-11-25 05:45:48', '2025-11-25 05:45:48'),
(6, 'Hotel Entrances and all common areas are controlled by CCTV Cameras 24 hours a day', 1, '2025-11-25 05:46:15', '2025-11-25 05:46:15'),
(7, 'Emergency lights available in the public areas', 1, '2025-11-25 05:46:48', '2025-11-25 05:46:48'),
(8, '24x7 availability of electricity', 1, '2025-11-25 05:47:03', '2025-11-25 05:47:03'),
(9, '24 hrs. elevators for buildings higher than ground plus four floors or as per the prevailing local building norms applicable', 1, '2025-11-25 05:47:17', '2025-11-25 05:47:17'),
(10, 'Full time operation 7 days a week', 1, '2025-11-25 05:47:28', '2025-11-25 05:47:28'),
(11, 'Bathroom Sanitary Fixtures Toilets must be well ventilated. Each western WC toilet should have a seat with lid and toilet paper. Post toilet hygiene facilities - toilet paper, soap, sanitary bin, 24-hour running water.', 1, '2025-11-25 05:47:38', '2025-11-25 05:47:38'),
(12, 'Minimum Bathroom size should be 30 sq. ft.', 1, '2025-11-25 05:47:54', '2025-11-25 05:47:54'),
(13, 'Minimum Rooms size should be as follows (Room size excludes bathroom) All rooms must have attached bathroom mandatorily - Single - 80 sq. ft. and Double - 120 sq. fL', 1, '2025-11-25 05:48:04', '2025-11-25 05:48:04'),
(14, 'Minimum 6 lettable rooms, all rooms with outside windows / ventilation', 1, '2025-11-25 05:48:16', '2025-11-25 05:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `industrial_guest_services`
--

CREATE TABLE `industrial_guest_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `industrial_guest_services`
--

INSERT INTO `industrial_guest_services` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Availability of Doctor-on-call service and name address and telephone number of doctors with front desk', 1, '2025-11-25 07:29:16', '2025-11-25 07:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `industrial_registrations`
--

CREATE TABLE `industrial_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `registration_id` varchar(255) NOT NULL,
  `slug_id` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved','rejected','pending') NOT NULL DEFAULT 'submitted',
  `is_apply` tinyint(1) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `hotel_address` text NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `authorized_person` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `applicant_type` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `total_area` decimal(10,2) DEFAULT NULL,
  `total_employees` int(11) DEFAULT NULL,
  `general_requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`general_requirements`)),
  `additional_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_features`)),
  `water_saving_taps_showers` tinyint(1) NOT NULL DEFAULT 0,
  `total_rooms` int(11) DEFAULT NULL,
  `commencement_date` date DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `mseb_consumer_number` varchar(255) DEFAULT NULL,
  `star_category` varchar(255) DEFAULT NULL,
  `electricity_company` text DEFAULT NULL,
  `property_tax_dept` text DEFAULT NULL,
  `water_bill_dept` text DEFAULT NULL,
  `pan_card_path` varchar(255) DEFAULT NULL,
  `aadhaar_card_path` varchar(255) DEFAULT NULL,
  `gst_cert_path` varchar(255) DEFAULT NULL,
  `fssai_cert_path` varchar(255) DEFAULT NULL,
  `business_reg_path` varchar(255) DEFAULT NULL,
  `declaration_path` varchar(255) DEFAULT NULL,
  `mpcb_cert_path` varchar(255) DEFAULT NULL,
  `light_bill_path` varchar(255) DEFAULT NULL,
  `fire_noc_path` varchar(255) DEFAULT NULL,
  `property_tax_path` varchar(255) DEFAULT NULL,
  `star_cert_path` varchar(255) DEFAULT NULL,
  `water_bill_path` varchar(255) DEFAULT NULL,
  `electricity_bill_path` varchar(255) DEFAULT NULL,
  `building_cert_path` varchar(255) DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `region_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(50) DEFAULT NULL,
  `safety_security` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`safety_security`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_safety_and_securities`
--

CREATE TABLE `industrial_safety_and_securities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `industrial_safety_and_securities`
--

INSERT INTO `industrial_safety_and_securities` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'First aid kit at the front desk', 1, '2025-11-25 06:39:18', '2025-11-25 06:39:18'),
(3, 'Conducting regular fire fighting drills and adherence to norms of the Fire department', 1, '2025-11-25 06:39:32', '2025-11-25 06:39:32'),
(4, 'Police Verification for employees', 1, '2025-11-25 06:39:44', '2025-11-25 06:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `industrial_step1s`
--

CREATE TABLE `industrial_step1s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `hotel_address` text NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `authorized_person` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `applicant_type` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `total_area` decimal(10,2) DEFAULT NULL,
  `total_employees` int(11) DEFAULT NULL,
  `total_rooms` int(11) DEFAULT NULL,
  `commencement_date` date DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `mseb_consumer_number` varchar(255) DEFAULT NULL,
  `star_category` varchar(255) DEFAULT NULL,
  `electricity_company` text DEFAULT NULL,
  `property_tax_dept` text DEFAULT NULL,
  `water_bill_dept` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_step2s`
--

CREATE TABLE `industrial_step2s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `min_rooms` tinyint(1) NOT NULL DEFAULT 0,
  `general_requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`general_requirements`)),
  `water_saving_taps_showers` tinyint(1) NOT NULL DEFAULT 0,
  `room_size_ok` tinyint(1) NOT NULL DEFAULT 0,
  `bathroom_size_ok` tinyint(1) NOT NULL DEFAULT 0,
  `bathroom_fixtures` tinyint(1) NOT NULL DEFAULT 0,
  `full_time_operation` tinyint(1) NOT NULL DEFAULT 0,
  `elevators` tinyint(1) NOT NULL DEFAULT 0,
  `electricity_availability` tinyint(1) NOT NULL DEFAULT 0,
  `emergency_lights` tinyint(1) NOT NULL DEFAULT 0,
  `cctv` tinyint(1) NOT NULL DEFAULT 0,
  `disabled_access` tinyint(1) NOT NULL DEFAULT 0,
  `security_guards` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_step3s`
--

CREATE TABLE `industrial_step3s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `bath_attached` tinyint(1) NOT NULL DEFAULT 0,
  `bath_hot_cold` tinyint(1) NOT NULL DEFAULT 0,
  `water_saving_taps` tinyint(1) NOT NULL DEFAULT 0,
  `public_lobby` tinyint(1) NOT NULL DEFAULT 0,
  `reception` tinyint(1) NOT NULL DEFAULT 0,
  `public_restrooms` tinyint(1) NOT NULL DEFAULT 0,
  `disabled_room` tinyint(1) NOT NULL DEFAULT 0,
  `fssai_kitchen` tinyint(1) NOT NULL DEFAULT 0,
  `additional_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_features`)),
  `uniforms` tinyint(1) NOT NULL DEFAULT 0,
  `pledge_display` tinyint(1) NOT NULL DEFAULT 0,
  `complaint_book` tinyint(1) NOT NULL DEFAULT 0,
  `nodal_officer` tinyint(1) NOT NULL DEFAULT 0,
  `doctor_on_call` tinyint(1) NOT NULL DEFAULT 0,
  `police_verification` tinyint(1) NOT NULL DEFAULT 0,
  `fire_drills` tinyint(1) NOT NULL DEFAULT 0,
  `first_aid` tinyint(1) NOT NULL DEFAULT 0,
  `suite` tinyint(1) NOT NULL DEFAULT 0,
  `fb_outlet` tinyint(1) NOT NULL DEFAULT 0,
  `iron_facility` tinyint(1) NOT NULL DEFAULT 0,
  `paid_transport` tinyint(1) NOT NULL DEFAULT 0,
  `business_center` tinyint(1) NOT NULL DEFAULT 0,
  `conference_facilities` tinyint(1) NOT NULL DEFAULT 0,
  `sewage_treatment` tinyint(1) NOT NULL DEFAULT 0,
  `rainwater_harvesting` tinyint(1) NOT NULL DEFAULT 0,
  `safety_security` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`safety_security`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industrial_step4s`
--

CREATE TABLE `industrial_step4s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `mseb_consumer_no` varchar(255) DEFAULT NULL,
  `star_category` varchar(255) DEFAULT NULL,
  `pan_card_path` varchar(255) DEFAULT NULL,
  `aadhaar_card_path` varchar(255) DEFAULT NULL,
  `gst_cert_path` varchar(255) DEFAULT NULL,
  `fssai_cert_path` varchar(255) DEFAULT NULL,
  `business_reg_path` varchar(255) DEFAULT NULL,
  `declaration_path` varchar(255) DEFAULT NULL,
  `mpcb_cert_path` varchar(255) DEFAULT NULL,
  `light_bill_path` varchar(255) DEFAULT NULL,
  `fire_noc_path` varchar(255) DEFAULT NULL,
  `property_tax_path` varchar(255) DEFAULT NULL,
  `star_cert_path` varchar(255) DEFAULT NULL,
  `water_bill_path` varchar(255) DEFAULT NULL,
  `electricity_bill_path` varchar(255) DEFAULT NULL,
  `building_cert_path` varchar(255) DEFAULT NULL,
  `extra_doc_path` varchar(255) DEFAULT NULL,
  `trade_license_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(7, '0001_01_01_000000_create_users_table', 1),
(8, '0001_01_01_000001_create_cache_table', 1),
(9, '0001_01_01_000002_create_jobs_table', 1),
(10, '2025_11_01_165444_create_countries_table', 2),
(11, '2025_11_01_165444_create_states_table', 2),
(12, '2025_11_01_165502_create_districts_table', 2),
(13, '2025_11_02_195600_create_permission_tables', 3),
(15, '2025_11_03_201026_create_general_settings_table', 4),
(16, '2025_11_03_201704_create_application_forms_table', 4),
(17, '2025_11_05_033646_create_tourist_villa_registrations_table', 5),
(18, '2025_11_06_040339_create_applications_table', 6),
(19, '2025_11_06_040412_create_application_details_table', 6),
(20, '2025_11_06_041104_create_property_details_table', 6),
(21, '2025_11_06_041120_create_accommodations_table', 6),
(22, '2025_11_06_041135_create_facilities_table', 6),
(23, '2025_11_06_041406_create_photos_signatures_table', 6),
(24, '2025_11_06_041421_create_enclosures_table', 6),
(25, '2025_11_06_041508_create_documents_table', 6),
(26, '2026_01_09_083100_create_category_registrations_table', 7),
(27, '2026_01_23_043333_create_application_movements_table', 8),
(28, '2026_01_23_052047_add_current_desk_number_to_applications_table', 9),
(29, '2026_01_23_063209_add_form_current_status_to_applications_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 17),
(8, 'App\\Models\\User', 12),
(9, 'App\\Models\\User', 16),
(10, 'App\\Models\\User', 13),
(11, 'App\\Models\\User', 15),
(12, 'App\\Models\\User', 14);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'view user', 'web', 'User', '2025-11-02 16:46:28', '2025-11-02 16:46:28'),
(3, 'edit user', 'web', 'User', '2025-11-02 16:47:37', '2025-11-02 16:47:37'),
(4, 'delete user', 'web', 'User', '2025-11-02 16:47:56', '2025-11-02 16:47:56'),
(5, 'create user', 'web', 'User', '2025-11-02 17:13:06', '2025-11-02 17:13:06'),
(6, 'view permission', 'web', 'Permission', '2025-11-02 23:41:53', '2025-11-02 23:41:53'),
(7, 'create permission', 'web', 'Permission', '2025-11-02 23:43:48', '2025-11-02 23:43:48'),
(8, 'edit permission', 'web', 'Permission', '2025-11-02 23:44:10', '2025-11-02 23:44:10'),
(9, 'delete permission', 'web', 'Permission', '2025-11-02 23:45:35', '2025-11-02 23:45:35'),
(10, 'view roles', 'web', 'Role', '2025-11-02 23:47:42', '2025-11-02 23:47:42'),
(11, 'create roles', 'web', 'Role', '2025-11-02 23:47:59', '2025-11-02 23:47:59'),
(12, 'edit roles', 'web', 'Role', '2025-11-02 23:48:23', '2025-11-02 23:48:23'),
(13, 'delete roles', 'web', 'Role', '2025-11-02 23:48:41', '2025-11-02 23:48:41'),
(14, 'Director', 'web', 'Workflow', '2025-12-15 05:50:05', '2025-12-15 05:50:05'),
(15, 'Joint Director', 'web', 'Workflow', '2025-12-15 05:50:22', '2025-12-15 05:50:22'),
(16, 'Dy Director', 'web', 'Workflow', '2025-12-15 05:50:41', '2025-12-15 05:50:41'),
(17, 'Asst Director', 'web', 'Workflow', '2025-12-15 05:51:05', '2025-12-15 05:51:15'),
(18, 'Clerk', 'web', 'Workflow', '2025-12-15 05:51:39', '2025-12-15 05:51:39'),
(19, 'delete forms', 'web', 'Application Forms', '2025-12-15 05:52:16', '2025-12-15 05:52:16'),
(20, 'edit forms', 'web', 'Application Forms', '2025-12-15 05:52:38', '2025-12-15 05:52:38'),
(21, 'create forms', 'web', 'Application Forms', '2025-12-15 05:52:56', '2025-12-15 05:52:56'),
(22, 'view forms', 'web', 'Application Forms', '2025-12-15 05:53:10', '2025-12-15 05:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `photos_signatures`
--

CREATE TABLE `photos_signatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `applicant_image` varchar(255) DEFAULT NULL,
  `applicant_signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_categories`
--

CREATE TABLE `project_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `units` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_categories`
--

INSERT INTO `project_categories` (`id`, `name`, `units`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Accommodations (A)', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]', 1, '2026-01-02 05:32:40', '2026-01-02 05:52:33'),
(2, 'Accommodations (B)', '[\"7\",\"8\",\"9\"]', 1, '2026-01-02 05:43:49', '2026-01-02 06:03:02'),
(3, 'Food & Beverages', '[\"24\",\"25\",\"26\",\"27\",\"28\",\"29\"]', 1, '2026-01-02 06:04:14', '2026-01-02 06:04:14'),
(4, 'Travels & Tourism', '[\"30\",\"31\",\"32\",\"33\",\"34\",\"35\"]', 1, '2026-01-02 06:05:33', '2026-01-02 06:05:33'),
(5, 'Entertainment & Recreation', '[\"55\",\"56\",\"57\",\"58\",\"59\",\"60\",\"61\",\"62\",\"63\",\"64\",\"65\",\"66\",\"67\",\"68\",\"69\",\"70\",\"71\",\"72\",\"73\",\"74\",\"75\"]', 1, '2026-01-02 06:07:28', '2026-01-02 06:07:28'),
(6, 'Other Tourism Units', '[\"52\",\"53\",\"76\",\"77\",\"78\",\"79\",\"80\"]', 1, '2026-01-02 06:08:23', '2026-01-02 06:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `project_types`
--

CREATE TABLE `project_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_types`
--

INSERT INTO `project_types` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hotels', 1, '2026-01-02 04:17:15', '2026-01-02 04:17:15'),
(2, 'Motels', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(3, 'Youth Hostels', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(4, 'Youth Clubs Resorts', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(5, 'Log Huts', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(6, 'Cottages', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(7, 'Serviced Apartments', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(8, 'Apartment Hotel', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(9, 'Tourist Villas Time-Sharing Resorts', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(10, 'Agro Tourism Units', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(11, 'Rural Tourism Units', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(12, 'Eco Tourism Units', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(13, 'Homestays', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(14, 'Bed and Breakfast', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(15, 'Vacation Rental Homes', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(16, 'Tented Accommodation', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(17, 'Tourist Apartments', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(18, 'Bamboo Huts', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(19, 'Tree House', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(20, 'Mud Cottages', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(21, 'Cruise Boats', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(22, 'Yachts', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(23, 'House Boats for Tourists', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(24, 'Wayside Amenities', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(25, 'Restaurants', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(26, 'Food Kiosks Food Courts at Tourist Destinations', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(27, 'Beach Shacks', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(28, 'Tents', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(29, 'Glassy Pods with Scientific Waste Management Facilities', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(30, 'MICE Convention Centres', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(31, 'Exhibition Spaces', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(32, 'Wellness Centres', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(33, 'Tourism or Hospitality Training Centres', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(34, 'Hotel Management Institutes', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(35, 'Tourist Facilitation Centres', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(51, 'Development of Adventure Tourism Landing Sites', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(52, 'Development of Hospitality Parks', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(53, 'E Vehicles for Tourists', 1, '2026-01-02 10:02:44', '2026-01-02 10:02:44'),
(55, 'Ropeways Cars', 1, '2026-01-02 10:15:44', '2026-01-02 04:46:52'),
(56, 'Cable Cars', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(57, 'Amusement Parks', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(58, 'Theme Parks', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(59, 'Adventure Tourism Units or Parks', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(60, 'Golf Course', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(61, 'Caravan Caravan Park', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(62, 'Heli Tourism Projects', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(63, 'Unity Malls', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(64, 'AR VR Zones', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(65, '7D and Above Experience', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(66, 'Global Tourism', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(67, 'Art and Cultural Centre', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(68, 'Cultural Centre', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(69, 'Amphitheatres', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(70, 'Theatres', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(71, 'Art Galleries', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(72, 'Viewing Gallery', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(73, 'Retail Zones Shopping Malls', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(74, 'Multiplex', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(75, 'Video Game Zones', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(76, 'Handloom Handicraft Shops', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(77, 'Conservation of Historical Heritage Structures', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(78, 'Accommodations', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(79, 'Buildings Used as Tourist Attractions', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44'),
(80, 'Restoration of Historical Heritage Structures', 1, '2026-01-02 10:15:44', '2026-01-02 10:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `property_details`
--

CREATE TABLE `property_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` int(50) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `property_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `address_proof` varchar(255) DEFAULT NULL,
  `address_proof_type` varchar(255) DEFAULT NULL,
  `geo_link` varchar(255) DEFAULT NULL,
  `is_operational` tinyint(1) NOT NULL DEFAULT 0,
  `operational_since` varchar(255) DEFAULT NULL,
  `guests_till_march` int(11) DEFAULT NULL,
  `total_area_sqft` int(11) DEFAULT NULL,
  `mahabooking_reg_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provisional_registrations`
--

CREATE TABLE `provisional_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `application_form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `registration_id` varchar(255) NOT NULL,
  `district_id` int(50) DEFAULT NULL,
  `region_id` int(50) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `is_apply` tinyint(1) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `enterprise_type` varchar(255) DEFAULT NULL,
  `aadhar_number` varchar(12) DEFAULT NULL,
  `application_category` varchar(255) DEFAULT NULL,
  `site_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`site_address`)),
  `udyog_aadhar` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `zone` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `expansion_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`expansion_details`)),
  `entrepreneurs_profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`entrepreneurs_profile`)),
  `project_category` varchar(255) DEFAULT NULL,
  `other_category` varchar(255) DEFAULT NULL,
  `project_subcategory` varchar(255) DEFAULT NULL,
  `project_description` text DEFAULT NULL,
  `land_area` decimal(10,2) DEFAULT NULL,
  `land_ownership_type` varchar(255) DEFAULT NULL,
  `building_ownership_type` varchar(255) DEFAULT NULL,
  `project_cost` decimal(15,2) DEFAULT NULL,
  `total_employees` int(11) DEFAULT NULL,
  `investment_components` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`investment_components`)),
  `means_of_finance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`means_of_finance`)),
  `enclosures` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`enclosures`)),
  `other_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_documents`)),
  `declaration_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `place` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `current_step` int(11) NOT NULL DEFAULT 1,
  `progress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`progress`)),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','submitted','under_review','approved','pending','rejected') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `current_desk_number` int(11) NOT NULL DEFAULT 1,
  `form_current_status` varchar(255) NOT NULL DEFAULT 'pending',
  `eligibility_certificate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provisional_registrations`
--

INSERT INTO `provisional_registrations` (`id`, `current_stage`, `user_id`, `application_id`, `application_form_id`, `registration_id`, `district_id`, `region_id`, `slug_id`, `is_apply`, `submitted_at`, `applicant_name`, `company_name`, `enterprise_type`, `aadhar_number`, `application_category`, `site_address`, `udyog_aadhar`, `gst_number`, `zone`, `project_type`, `expansion_details`, `entrepreneurs_profile`, `project_category`, `other_category`, `project_subcategory`, `project_description`, `land_area`, `land_ownership_type`, `building_ownership_type`, `project_cost`, `total_employees`, `investment_components`, `means_of_finance`, `enclosures`, `other_documents`, `declaration_accepted`, `place`, `date`, `signature_path`, `current_step`, `progress`, `is_completed`, `status`, `created_at`, `updated_at`, `workflow_status`, `current_desk_number`, `form_current_status`, `eligibility_certificate`) VALUES
(23, 'Clerk', 18, NULL, 1, 'PVR-03878631', 12, 15, 'PVR-77932546', 1, '2026-02-03 04:18:56', 'Rajeev Kumar Mahto', 'ASTHA MOTORS', '1', '654987654123', '2', '{\"survey_type\":\"CTS No.\",\"survey_number\":\"11\",\"village_city\":\"Ranchi\",\"taluka\":\"test\",\"district\":\"Hingoli\",\"state\":\"Maharashtra\",\"pincode\":\"834003\",\"mobile\":\"6202399942\",\"email\":\"rajeevmahto275@gmail.com\",\"website\":\"https:\\/\\/www.vocmanindia.com\\/\"}', '6549871236', '654654987789554', 'STZ/STD', 'Expansion', '[{\"existing_facilities\":\"hgchv\",\"existing_employment\":\"87\",\"expansion_facilities\":\"65\",\"expansion_employment\":\"14\"}]', '[{\"name\":\"test\",\"designation\":\"test\",\"ownership\":\"54\",\"gender\":\"Male\",\"age\":\"32\"},{\"name\":\"test\",\"designation\":\"test\",\"ownership\":\"65\",\"gender\":\"Female\",\"age\":\"65\"}]', '4', NULL, 'Accommodations', 'bfgbn fghfg eryhert     rthgrt rthrt rtyh rtyht', 42453.00, 'Owned', 'Leased', 45678754.00, 52, '{\"land\":{\"estimated\":\"100\",\"investment_made\":\"125\"},\"building\":{\"estimated\":\"100\",\"investment_made\":\"456\"},\"machinery\":{\"estimated\":\"100\",\"investment_made\":\"456\"},\"engineering\":{\"estimated\":\"100\",\"investment_made\":\"4156\"},\"preop\":{\"estimated\":\"100\",\"investment_made\":\"456\"},\"margin\":{\"estimated\":\"1000\",\"investment_made\":\"4452\"}}', '{\"share_capital\":{\"promoters\":\"100000\",\"financial_institutions\":\"400000\",\"public\":\"0\",\"total\":500000},\"loans\":{\"financial_institutions\":\"123230.00\",\"banks\":\"100000\",\"others\":\"100000\",\"total\":323230}}', '{\"commencement_certificate\":{\"doc_no\":\"test123\",\"issue_date\":\"2026-02-04\",\"file_path\":null}}', '[]', 0, NULL, NULL, NULL, 5, '{\"done\":\"5\",\"total\":6}', 0, 'draft', '2026-02-03 04:18:56', '2026-02-06 06:10:30', 'Pending', 1, 'pending', '56hbjgfx');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Super Admin', 'web', 1, '2025-11-03 01:38:37', '2025-11-03 01:38:37'),
(5, 'Admin', 'web', 1, '2025-11-03 01:39:56', '2025-11-03 01:39:56'),
(8, 'Clerk', 'web', 1, '2025-12-15 05:46:05', '2025-12-15 05:46:05'),
(9, 'Asst Director', 'web', 1, '2025-12-15 05:47:20', '2025-12-15 05:47:20'),
(10, 'Director', 'web', 1, '2025-12-15 05:47:45', '2025-12-15 05:47:45'),
(11, 'Dy Director', 'web', 1, '2025-12-15 05:48:01', '2025-12-15 05:48:01'),
(12, 'Joint Director', 'web', 1, '2025-12-15 05:48:22', '2025-12-15 05:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(6, 5),
(7, 3),
(7, 5),
(8, 3),
(8, 5),
(9, 3),
(9, 5),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(14, 10),
(15, 3),
(15, 12),
(16, 3),
(16, 11),
(17, 3),
(17, 9),
(18, 3),
(18, 8),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(22, 8),
(22, 9),
(22, 10),
(22, 11),
(22, 12);

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
('c4WROEmSegYuGsYcIsCIHl6lIoKu8wGnC8gWkBVG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVVPdWpNU2N1U3RUS0FGTU1ibnpyeGJzSnREcDRZdFExcnUzWDBNViI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2VudGVycHJpc2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXB0Y2hhL2ZsYXQ/OWc2MWxwQlY9IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjc6ImNhcHRjaGEiO2E6Mzp7czo5OiJzZW5zaXRpdmUiO2I6MDtzOjM6ImtleSI7czozMTI6ImV5SnBkaUk2SW05RFJEQlpNRXBUUnpjMmVVOU9URWRUV1ZFdlprRTlQU0lzSW5aaGJIVmxJam9pYTBSUFQwOUdNbnA1WjBwTlZtcEViV3BKUmxWWmFWSlVVMGRuYTAxdEsxWkVjRXcwT0RaRFNXdGtZell4VjA5SE1tdzJjVk5oYWtZM1FWRTNZV0pIYnpWRWJtbHdSREYwZDNCRFZtc3lUVk42VkZNMWF6UnBjMDF2ZGpSMlpGSllTMmQ1U1RkSWNYSlFibTg5SWl3aWJXRmpJam9pWWpVd01HUm1OVGN5TTJKalpqSTVPVEE0WlRkaE9EUTBZVFkzWTJFNFlUSTVaR1prTkdNeFl6bGxOekJqTmpaaVkyVmtNek0wTldRME1UazVOelk1WkNJc0luUmhaeUk2SWlKOSI7czo3OiJlbmNyeXB0IjtiOjE7fX0=', 1770188856),
('f1BwUqCUlhh7thE65XdUiyHgx75u7srArHoMqEJM', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0tDeVNkS0trbXZ1OFBsd1hKR0hldFRIbjBpcDU0aGp5OTFQY2M0NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm92aXNpb25hbC93aXphcmQvNSI7czo1OiJyb3V0ZSI7czoyMzoicHJvdmlzaW9uYWwud2l6YXJkLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxODt9', 1770378201),
('rX8ER3Vf6pjwSityLDUDHvZiZlD5Rik6gIHQ4DPj', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiblFJOGp3eWdFT3QwbEhLd2pGWUNCWFRub1BVd1ZoT0p3WmVPMEpzMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm92aXNpb25hbC93aXphcmQvNSI7czo1OiJyb3V0ZSI7czoyMzoicHJvdmlzaW9uYWwud2l6YXJkLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxODt9', 1770189232),
('u3lxvGhtm0OSAJsnL1johwoXV8ZoxEQUNgWKX2hR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWjJuZzREWVNUVnVRbE9VV0E1NUFBdEtUUVN3aXVkR1hlS3lWOGtYayI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb3Zpc2lvbmFsL3dpemFyZC81Ijt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXB0Y2hhL2ZsYXQ/R3UxTUhhM2g9IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjc6ImNhcHRjaGEiO2E6Mzp7czo5OiJzZW5zaXRpdmUiO2I6MDtzOjM6ImtleSI7czozMTI6ImV5SnBkaUk2SW0xTVlUVktVbGMwTUROS1l6UmlUbUpvYzFvMmVuYzlQU0lzSW5aaGJIVmxJam9pYzNwQ1JFMWllSFpuVTNJMVNXOW9UbEJGY1dnM1IzSTVOeloyYldsQ1ZsYzFVVXcyUXk5aloyODFWMUpxWkRGSGEySnRWekJCT1dabWQwUlFiRWNyVDBaakx6TTRZVkJHYnpsRFFrMDFZVTExTVVWdVVrRm9lbmh2VWprelVFZFNXazA1ZGpoT1YxWXhUMVU5SWl3aWJXRmpJam9pWTJFMU9HWmtZamhrTldGaVltSTFaRGs1TXpobU16QmtObVpoWlRWbU5UVmhZVFZtT1dKaFpURTRPRE15WldSbE1ERmxZalJtTURFMlpHWTJPVEU0WVNJc0luUmhaeUk2SWlKOSI7czo3OiJlbmNyeXB0IjtiOjE7fX0=', 1770440340),
('VTCE8k8GcO1rlrrE2zm0xJtgBDpkcKyOAginr5co', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOFFpaGpwbkwwejh1cTFNTDdZM29HTndTbnRsd2lvYVJueWx3NzRpeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770185218);

-- --------------------------------------------------------

--
-- Table structure for table `stamp_duty_applications`
--

CREATE TABLE `stamp_duty_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `slug_id` varchar(255) NOT NULL,
  `current_step` int(11) NOT NULL DEFAULT 1,
  `progress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`progress`)),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','submitted','under_review','approved','pending','rejected') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `declaration_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `registration_no` varchar(255) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `applicant_type` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `c_address` text DEFAULT NULL,
  `c_city` varchar(255) DEFAULT NULL,
  `c_taluka` varchar(255) DEFAULT NULL,
  `c_district` varchar(255) DEFAULT NULL,
  `c_state` varchar(255) DEFAULT NULL,
  `c_pincode` varchar(255) DEFAULT NULL,
  `c_mobile` varchar(255) DEFAULT NULL,
  `c_phone` varchar(255) DEFAULT NULL,
  `c_email` varchar(255) DEFAULT NULL,
  `c_fax` varchar(255) DEFAULT NULL,
  `p_address` text DEFAULT NULL,
  `p_city` varchar(255) DEFAULT NULL,
  `p_taluka` varchar(255) DEFAULT NULL,
  `p_district` varchar(255) DEFAULT NULL,
  `p_state` varchar(255) DEFAULT NULL,
  `p_pincode` varchar(255) DEFAULT NULL,
  `p_mobile` varchar(255) DEFAULT NULL,
  `p_phone` varchar(255) DEFAULT NULL,
  `p_email` varchar(255) DEFAULT NULL,
  `p_website` varchar(255) DEFAULT NULL,
  `estimated_project_cost` decimal(15,2) DEFAULT NULL,
  `proposed_employment` int(11) DEFAULT NULL,
  `tourism_activities` text DEFAULT NULL,
  `incentives_availed` text DEFAULT NULL,
  `existed_before` tinyint(1) NOT NULL DEFAULT 0,
  `eligibility_cert_no` varchar(255) DEFAULT NULL,
  `eligibility_date` date DEFAULT NULL,
  `present_status` text DEFAULT NULL,
  `land_gat` varchar(255) DEFAULT NULL,
  `land_village` varchar(255) DEFAULT NULL,
  `land_taluka` varchar(255) DEFAULT NULL,
  `land_district` varchar(255) DEFAULT NULL,
  `area_a` decimal(15,2) DEFAULT NULL,
  `area_b` decimal(15,2) DEFAULT NULL,
  `area_c` decimal(15,2) DEFAULT NULL,
  `area_d` decimal(15,2) DEFAULT NULL,
  `area_e` decimal(15,2) DEFAULT NULL,
  `na_gat` varchar(255) DEFAULT NULL,
  `na_village` varchar(255) DEFAULT NULL,
  `na_taluka` varchar(255) DEFAULT NULL,
  `na_district` varchar(255) DEFAULT NULL,
  `na_area` decimal(15,2) DEFAULT NULL,
  `cost_land` decimal(15,2) DEFAULT NULL,
  `cost_building` decimal(15,2) DEFAULT NULL,
  `cost_machinery` decimal(15,2) DEFAULT NULL,
  `cost_electrical` decimal(15,2) DEFAULT NULL,
  `cost_misc` decimal(15,2) DEFAULT NULL,
  `cost_other` decimal(15,2) DEFAULT NULL,
  `project_employment` int(11) DEFAULT NULL,
  `noc_purpose` text DEFAULT NULL,
  `noc_authority` text DEFAULT NULL,
  `name_designation` varchar(255) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `stamp_path` varchar(255) DEFAULT NULL,
  `doc_challan` varchar(255) DEFAULT NULL,
  `doc_affidavit` varchar(255) DEFAULT NULL,
  `doc_registration` varchar(255) DEFAULT NULL,
  `doc_ror` varchar(255) DEFAULT NULL,
  `doc_land_map` varchar(255) DEFAULT NULL,
  `doc_dpr` varchar(255) DEFAULT NULL,
  `doc_agreement` varchar(255) DEFAULT NULL,
  `doc_construction_plan` varchar(255) DEFAULT NULL,
  `doc_dp_remarks` varchar(255) DEFAULT NULL,
  `aff_name` varchar(255) DEFAULT NULL,
  `aff_company` varchar(255) DEFAULT NULL,
  `aff_registered_office` text DEFAULT NULL,
  `aff_land_area` decimal(15,2) DEFAULT NULL,
  `aff_cts` varchar(255) DEFAULT NULL,
  `aff_village` varchar(255) DEFAULT NULL,
  `aff_taluka` varchar(255) DEFAULT NULL,
  `aff_district` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `code`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Andhra Pradesh', 'AP', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(2, 1, 'Arunachal Pradesh', 'AR', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(3, 1, 'Assam', 'AS', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(4, 1, 'Bihar', 'BR', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(5, 1, 'Chhattisgarh', 'CG', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(6, 1, 'Goa', 'GA', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(7, 1, 'Gujarat', 'GJ', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(8, 1, 'Haryana', 'HR', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(9, 1, 'Himachal Pradesh', 'HP', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(10, 1, 'Jharkhand', 'JH', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(11, 1, 'Karnataka', 'KA', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(12, 1, 'Kerala', 'KL', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(13, 1, 'Madhya Pradesh', 'MP', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(14, 1, 'Maharashtra', 'MH', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(15, 1, 'Manipur', 'MN', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(16, 1, 'Meghalaya', 'ML', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(17, 1, 'Mizoram', 'MZ', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(18, 1, 'Nagaland', 'NL', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(19, 1, 'Odisha', 'OR', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(20, 1, 'Punjab', 'PB', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(21, 1, 'Rajasthan', 'RJ', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(22, 1, 'Sikkim', 'SK', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(23, 1, 'Tamil Nadu', 'TN', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(24, 1, 'Telangana', 'TS', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(25, 1, 'Tripura', 'TR', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(26, 1, 'Uttar Pradesh', 'UP', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(27, 1, 'Uttarakhand', 'UK', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(28, 1, 'West Bengal', 'WB', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(29, 1, 'Delhi', 'DL', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(30, 1, 'Jammu and Kashmir', 'JK', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(31, 1, 'Ladakh', 'LA', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(32, 1, 'Puducherry', 'PY', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(33, 1, 'Chandigarh', 'CH', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(34, 1, 'Andaman and Nicobar Islands', 'AN', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(35, 1, 'Dadra and Nagar Haveli and Daman and Diu', 'DN', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL),
(36, 1, 'Lakshadweep', 'LD', 1, '2025-11-01 17:11:40', '2025-11-01 17:11:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_conditions`
--

CREATE TABLE `terms_and_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_and_conditions`
--

INSERT INTO `terms_and_conditions` (`id`, `form_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 4, '<p data-start=\"121\" data-end=\"313\"><strong data-start=\"121\" data-end=\"141\">1. Data Accuracy</strong><br data-start=\"141\" data-end=\"144\">\r\nThe user must provide correct, complete, and updated information. Any incorrect or misleading data may result in rejection or cancellation of the application or service.</p>\r\n<p data-start=\"315\" data-end=\"473\"><strong data-start=\"315\" data-end=\"336\">2. Acceptable Use</strong><br data-start=\"336\" data-end=\"339\">\r\nThe platform/website must be used only for lawful and authorized purposes. Any misuse may lead to suspension or restriction of access.</p>\r\n<p data-start=\"475\" data-end=\"692\"><strong data-start=\"475\" data-end=\"507\">3. Privacy & Confidentiality</strong><br data-start=\"507\" data-end=\"510\">\r\nAll personal information submitted by the user will remain confidential and will be used only for necessary processing. It will not be shared with any third party without permission.</p>\r\n<p data-start=\"694\" data-end=\"878\"><strong data-start=\"694\" data-end=\"716\">4. Right to Modify</strong><br data-start=\"716\" data-end=\"719\">\r\nThe organization reserves the right to update or modify these Terms & Conditions at any time. Updated terms will become effective immediately upon publication.</p>', 1, '2025-11-13 16:41:27', '2025-11-13 17:16:30'),
(2, 1, '<ol class=\"declaration-list\" style=\"line-height: 28px; padding-left: 20px; color: rgb(108, 117, 125); font-family: Nunito, &quot;Segoe UI&quot;, arial; letter-spacing: normal;\"><li style=\"margin-bottom: 10px;\">Certified that the information / statement contained in this application are true to the best of my / our knowledge and belief.</li><li style=\"margin-bottom: 10px;\">Declared that no Government enquiry has been instituted against the applicant unit and / or any of its Proprietor / Partner(s)/ Director(s) of this applicant unit for any economic offence.</li><li style=\"margin-bottom: 10px;\">We hereby agree to abide by the terms and conditions of the certificate to be issued.</li><li style=\"margin-bottom: 10px;\">We hereby agree that the Provisional Registration letter issued on the basis of the above statements made and information furnished either along with this application or hereafter in connection with the above matter is liable to be cancelled ab-initio or rendered invalid or withdrawn if any of the statements and / or information is / are found to incorrect / untrue. The applicant will be liable to the relevant legal prosecution by the Government in such a situation.</li></ol>', 1, '2025-12-06 13:00:20', '2025-12-06 13:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `tourismfacilities`
--

CREATE TABLE `tourismfacilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tourismfacilities`
--

INSERT INTO `tourismfacilities` (`id`, `name`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Kitchen', 'bi bi-egg-fried', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(3, 'Dining Hall', 'bi bi-cup-straw', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(4, 'Garden', 'bi bi-flower1', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(5, 'Parking', 'bi bi-car-front', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(6, 'EV Charging', 'bi bi-lightning-charge', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(7, 'Children Play Area', 'bi bi-joystick', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(8, 'Swimming Pool', 'bi bi-water', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(9, 'Wi-Fi', 'bi bi-wifi', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(10, 'First Aid Box', 'bi bi-bandaid', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(11, 'Fire Safety Equipment', 'bi bi-fire', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(12, 'Water Purifier / RO', 'bi bi-droplet', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(13, 'Rainwater Harvesting', 'bi bi-cloud-rain', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(14, 'Solar Power', 'bi bi-sun', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36'),
(15, 'Other Renewable Energy', 'bi bi-recycle', 1, '2025-11-10 17:14:36', '2025-11-10 17:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `tourism_apartments`
--

CREATE TABLE `tourism_apartments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mno` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `business` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `bpan` varchar(20) DEFAULT NULL,
  `uaadhar` varchar(50) DEFAULT NULL,
  `prop` enum('Yes','No') DEFAULT 'No',
  `opname` varchar(255) DEFAULT NULL,
  `pname` varchar(255) NOT NULL,
  `padd` text NOT NULL,
  `pradd` varchar(255) NOT NULL,
  `gc` varchar(255) NOT NULL,
  `ops` enum('Yes','No') DEFAULT 'No',
  `year` int(11) DEFAULT NULL,
  `guestno` int(11) NOT NULL,
  `area` float NOT NULL,
  `regn` varchar(50) NOT NULL,
  `fno` int(11) NOT NULL,
  `ftype` varchar(10) NOT NULL,
  `farea` float NOT NULL,
  `atinfo` enum('Yes','No') NOT NULL,
  `dbin` enum('Yes','No') NOT NULL,
  `aroad` enum('Yes','No') NOT NULL,
  `areq` enum('Yes','No') NOT NULL,
  `pay` enum('Yes','No') NOT NULL,
  `co` enum('Yes','No') NOT NULL,
  `ct` enum('Yes','No') NOT NULL,
  `cth` enum('Yes','No') NOT NULL,
  `cf` enum('Yes','No') NOT NULL,
  `cfi` enum('Yes','No') NOT NULL,
  `cs` enum('Yes','No') NOT NULL,
  `cse` enum('Yes','No') NOT NULL,
  `ce` enum('Yes','No') NOT NULL,
  `cb` enum('Yes','No') NOT NULL,
  `cn` enum('Yes','No') NOT NULL,
  `cte` enum('Yes','No') NOT NULL,
  `cel` enum('Yes','No') NOT NULL,
  `ctw` enum('Yes','No') NOT NULL,
  `cthr` enum('Yes','No') NOT NULL,
  `paystatus` enum('Yes','No') NOT NULL,
  `challan` varchar(255) DEFAULT NULL,
  `sign` varchar(255) NOT NULL,
  `aname` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `property_address_photo` varchar(255) DEFAULT NULL,
  `business_registration_certificate` varchar(255) DEFAULT NULL,
  `proprietor_ownership_document` varchar(255) DEFAULT NULL,
  `character_certificate` varchar(255) DEFAULT NULL,
  `noc_document` varchar(255) DEFAULT NULL,
  `permit_certificate` varchar(255) DEFAULT NULL,
  `challan_document` varchar(255) DEFAULT NULL,
  `utaking_document` varchar(255) DEFAULT NULL,
  `aadhar_card` varchar(255) DEFAULT NULL,
  `additional_aadhar_certificate` varchar(255) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `business_pan_card` varchar(255) DEFAULT NULL,
  `ownership_proof` varchar(255) DEFAULT NULL,
  `rental_agreement` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `property_photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`property_photos`)),
  `contract_document` varchar(255) DEFAULT NULL,
  `aadhar` varchar(20) DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `region_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourist_villa_registrations`
--

CREATE TABLE `tourist_villa_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `applicant_name` varchar(120) DEFAULT NULL,
  `applicant_phone` varchar(20) DEFAULT NULL,
  `applicant_email` varchar(120) DEFAULT NULL,
  `business_name` varchar(120) DEFAULT NULL,
  `business_type` varchar(40) DEFAULT NULL,
  `pan_number` varchar(10) DEFAULT NULL,
  `business_pan_number` varchar(10) DEFAULT NULL,
  `aadhar_number` varchar(20) DEFAULT NULL,
  `udyam_aadhar_number` varchar(30) DEFAULT NULL,
  `ownership_proof` varchar(60) DEFAULT NULL,
  `property_rented` tinyint(1) NOT NULL DEFAULT 0,
  `operator_name` varchar(120) DEFAULT NULL,
  `rental_agreement_path` varchar(255) DEFAULT NULL,
  `property_name` varchar(120) DEFAULT NULL,
  `property_address` text DEFAULT NULL,
  `address_proof` varchar(60) DEFAULT NULL,
  `property_coordinates` varchar(255) DEFAULT NULL,
  `property_operational` tinyint(1) NOT NULL DEFAULT 0,
  `operational_year` smallint(5) UNSIGNED DEFAULT NULL,
  `guests_hosted` int(10) UNSIGNED DEFAULT NULL,
  `total_area` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `mahabooking_number` varchar(40) DEFAULT NULL,
  `number_of_rooms` smallint(5) UNSIGNED DEFAULT NULL,
  `room_area` int(10) UNSIGNED DEFAULT NULL,
  `attached_toilet` tinyint(1) NOT NULL DEFAULT 0,
  `dustbins` tinyint(1) NOT NULL DEFAULT 0,
  `road_access` tinyint(1) NOT NULL DEFAULT 0,
  `food_provided` tinyint(1) NOT NULL DEFAULT 0,
  `payment_options` tinyint(1) NOT NULL DEFAULT 0,
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `application_fees` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `gras_certificate_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `region_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `undertakings`
--

CREATE TABLE `undertakings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `undertakings`
--

INSERT INTO `undertakings` (`id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'test12', 1, NULL, '2025-11-13 18:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','vendor','user') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  `aadhar` varchar(12) DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `aadhar_verified_at` timestamp NULL DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `is_aadhar_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_otp_sent_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `registration_id`, `image`, `phone`, `email`, `role`, `status`, `password`, `aadhar`, `is_email_verified`, `aadhar_verified_at`, `is_phone_verified`, `phone_verified_at`, `is_aadhar_verified`, `email_verified_at`, `last_otp_sent_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Super Admin', 'Super admin', 'ADMIN001', NULL, '9999999999', 'admin@test.com', 'admin', 'active', '$2y$12$l/Xsoe7Zv4a8mY0ThcJrJOcrY1W6hTY2zvQTjer4KQrZz80IHHMU2', '123456789018', 1, '2025-11-01 00:53:28', 1, '2025-11-01 00:53:28', 1, '2025-11-01 00:53:28', '2025-11-01 00:53:28', NULL, '2025-11-01 00:53:28', '2025-11-01 00:53:28'),
(5, 'rajeevmahto274@gmail.com', 'rajeevmahto274@gmail.com', 'MV-UZIG2LI0', NULL, '6202399942', 'rajeevmahto274@gmail.com', 'user', 'active', '$2y$12$7baeUapwXoXcjFVcWbzY9.Z6f.Dvt9kHynL0JQ0qO5GQx2HBDYENi', '674564756475', 1, '2025-11-03 05:43:34', 1, '2025-11-03 05:43:34', 1, NULL, NULL, NULL, '2025-11-03 05:43:34', '2025-11-03 05:43:34'),
(6, 'rajeevmahto275@gmail.com', 'rajeevmahto275@gmail.com', 'MV-LYOWQO9S', NULL, '6202399941', 'rajeevmahto275@gmail.com', 'user', 'active', '$2y$12$yHFrr6e0gh1G597Nh2LHDOXy6yTUJcQQYmbV/LD4m42NqWu.tr5aC', '240880243189', 1, '2025-11-03 05:51:58', 1, '2025-11-03 05:51:58', 1, NULL, NULL, 'AsftlMBkQt87NCZ8sDcLBo0o5hvlTL791175iKegluT3tos87lLPna27njLf', '2025-11-03 05:51:58', '2025-11-03 05:51:58'),
(7, 'asthamotors@hotmail.com', 'asthamotors@hotmail.com', 'MV-EMCPKM5Y', NULL, '8202399942', 'asthamotors@hotmail.com', 'user', 'active', '$2y$12$0u8xMjZKDMWe1xs1OvVY0.eGJai.hxAqqI7LqaAG.NxpJOCmrbaCa', '930356605799', 1, '2025-11-03 06:52:26', 1, '2025-11-03 06:52:26', 1, NULL, NULL, NULL, '2025-11-03 06:52:26', '2025-11-03 06:52:26'),
(8, 'Rajeev Kumar Mahto', NULL, NULL, NULL, '6202399950', 'admin@admin.com', 'admin', 'inactive', '$2y$12$aI7oh2CBnjWiL79NMakH/uvXGZ3rOgxW/HdqMoooH4nCMWK8ow2Xq', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-11-24 22:37:03', '2025-11-24 22:37:03'),
(9, 'prem', 'prem', 'MV-NBVSNRH6', NULL, '7897564523', 'prem@gmail.com', 'user', 'active', '$2y$12$PYNcJ3U6KpSptQHzKMTl5eymVDfUvJLGYxjjfZgw.Wq/oznU4jd1a', '798465435136', 1, '2025-11-24 23:59:04', 1, '2025-11-24 23:59:04', 1, NULL, NULL, NULL, '2025-11-24 23:59:04', '2025-11-24 23:59:04'),
(10, 'astha', 'astha', 'MV-YO0HY8T6', NULL, '6202311142', 'user@mail.com', 'user', 'active', '$2y$12$PYNcJ3U6KpSptQHzKMTl5eymVDfUvJLGYxjjfZgw.Wq/oznU4jd1a', '658956892356', 1, '2025-11-25 00:39:11', 1, '2025-11-25 00:39:11', 1, NULL, '2025-11-25 00:40:43', NULL, '2025-11-25 00:39:11', '2025-11-25 00:40:43'),
(11, 'Monish', 'Monish', 'MV-BGSQN4TE', NULL, '7588690658', 'monish@gmail.com', 'user', 'active', '$2y$12$qS6cFa2vhSn.yTFlLVFiI.ZfQxBcOciGkWi3c69hOBkJbMh/DPvvm', '000000000000', 1, '2025-11-25 00:40:02', 1, '2025-11-25 00:40:02', 1, NULL, NULL, NULL, '2025-11-25 00:40:02', '2025-11-25 00:40:02'),
(12, 'Clerk', NULL, NULL, NULL, '6201119942', 'clerk@gmail.com', 'admin', 'active', '$2y$12$8zgE2Df7qgXtqhaqGOLX7eOwFlfzLYkCiCl8cpIXIiuy.FfmBOkAy', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:09:28', '2025-12-15 06:09:28'),
(13, 'Director', NULL, NULL, NULL, '9748228911', 'director@gmail.com', 'admin', 'active', '$2y$12$raY1t.qWdrgZOJf1r0xRKePoVIyNEpARwz7pbgad.j0ee7ikfGyaW', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:11:09', '2025-12-15 06:11:09'),
(14, 'Joint Director', NULL, NULL, NULL, '7802399942', 'jointdirector@gmail.com', 'admin', 'active', '$2y$12$4XVA/EBDAsTpqiCcRN2HLe9D.nPSxCIqmMEvXTh7ak.jU.p8p8aQy', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:12:09', '2025-12-15 06:12:09'),
(15, 'Dy Director', NULL, NULL, NULL, '8888399942', 'dydirector@gmail.com', 'admin', 'active', '$2y$12$8qurvOzbLr/12a.PfhJ6Au8sU2A3ORwdijn6ufRl0UVn4b5bL2vaS', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:14:16', '2025-12-15 06:14:16'),
(16, 'Asst Director', NULL, NULL, NULL, '8889399942', 'asstdirector@gmail.com', 'admin', 'active', '$2y$12$uFUsW78p3T.pvIo1RdeO2.Pmdlww3KYk8lsJ5G0S3FXiL09GW8QmK', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:15:42', '2025-12-15 06:15:42'),
(17, 'superadmin', NULL, NULL, NULL, '7854399942', 'superadmin@mail.com', 'admin', 'active', '$2y$12$jtdDDp/YHc79Ao/9SUhFQuqJMOJDxcun3LAd0b2EL28H30TlY7.UC', NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-12-15 06:34:35', '2025-12-15 06:34:35'),
(18, 'raj@gmail.com', 'raj@gmail.com', 'MV-RHCFZUKT', NULL, '6202399789', 'raj@gmail.com', 'user', 'active', '$2y$12$NvKIMh6.WNZwp8/EIzIAF.w2DyeVAe1GeZPb8UFXQmCPzgOLsEWb.', '240880243111', 1, '2025-12-16 00:46:32', 1, '2025-12-16 00:46:32', 1, NULL, NULL, NULL, '2025-12-16 00:46:32', '2025-12-16 00:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `women_centered_tourism_registrations`
--

CREATE TABLE `women_centered_tourism_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_stage` varchar(255) DEFAULT 'Clerk',
  `status` enum('draft','submitted','approved','rejected','pending') NOT NULL DEFAULT 'pending',
  `is_apply` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` varchar(255) DEFAULT NULL,
  `slug_id` varchar(255) NOT NULL,
  `application_form_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `organisation_type` varchar(255) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(10) UNSIGNED DEFAULT NULL,
  `landline` varchar(20) DEFAULT NULL,
  `residential_address` text DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `tourism_business_type` varchar(255) DEFAULT NULL,
  `tourism_business_name` varchar(255) DEFAULT NULL,
  `aadhar_no` varchar(20) DEFAULT NULL,
  `pan_no` varchar(20) DEFAULT NULL,
  `company_pan_no` varchar(20) DEFAULT NULL,
  `caste` varchar(255) DEFAULT NULL,
  `has_udyog_aadhar` tinyint(1) NOT NULL DEFAULT 0,
  `udyog_aadhar_no` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `female_employees` int(10) UNSIGNED DEFAULT NULL,
  `total_employees` int(10) UNSIGNED DEFAULT NULL,
  `total_project_cost` decimal(15,2) DEFAULT NULL,
  `project_information` text DEFAULT NULL,
  `bank_account_holder` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `bank_account_type` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_ifsc` varchar(255) DEFAULT NULL,
  `applicant_image_path` varchar(255) DEFAULT NULL,
  `applicant_signature_path` varchar(255) DEFAULT NULL,
  `business_in_operation` tinyint(1) NOT NULL DEFAULT 0,
  `business_operation_since` date DEFAULT NULL,
  `business_expected_start` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_status` varchar(255) DEFAULT 'Pending',
  `region_id` int(10) UNSIGNED DEFAULT NULL,
  `district_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `areas` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `areas`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'A', '[\"1\",\"2\",\"3\",\"4\"]', 1, '2026-01-02 03:44:16', '2026-01-02 03:44:16'),
(3, 'B', '[\"5\",\"6\",\"7\",\"8\"]', 1, '2026-01-02 03:44:42', '2026-01-02 03:44:42'),
(4, 'C', '[\"9\"]', 1, '2026-01-02 03:44:57', '2026-01-02 03:44:57'),
(5, 'STZ/STD', '[\"10\"]', 1, '2026-01-02 03:45:31', '2026-01-02 03:45:31'),
(6, 'Entire State', '[\"11\"]', 1, '2026-01-02 03:45:52', '2026-01-02 03:45:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodations_application_id_foreign` (`application_id`),
  ADD KEY `accommodations_user_id_foreign` (`user_id`);

--
-- Indexes for table `adventure_applications`
--
ALTER TABLE `adventure_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adventure_applications_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `adventure_applications_email_unique` (`email`),
  ADD UNIQUE KEY `adventure_applications_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `adventure_applications_registration_id_unique` (`registration_id`),
  ADD KEY `adventure_applications_user_id_foreign` (`user_id`);

--
-- Indexes for table `agriculture_registrations`
--
ALTER TABLE `agriculture_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agriculture_registrations_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `agriculture_registrations_registration_id_unique` (`registration_id`),
  ADD KEY `agriculture_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `applications_registration_id_unique` (`registration_id`),
  ADD KEY `applications_user_id_foreign` (`user_id`);

--
-- Indexes for table `application_details`
--
ALTER TABLE `application_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_details_application_id_foreign` (`application_id`),
  ADD KEY `application_details_user_id_foreign` (`user_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_documents_application_type_application_id_index` (`application_type`,`application_id`);

--
-- Indexes for table `application_forms`
--
ALTER TABLE `application_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_forms_slug_unique` (`slug`);

--
-- Indexes for table `application_movements`
--
ALTER TABLE `application_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_workflow_logs`
--
ALTER TABLE `application_workflow_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_workflow_logs_application_type_application_id_index` (`application_type`,`application_id`),
  ADD KEY `application_workflow_logs_user_id_foreign` (`user_id`),
  ADD KEY `application_workflow_logs_stage_index` (`stage`),
  ADD KEY `application_workflow_logs_status_index` (`status`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `caravan_amenities`
--
ALTER TABLE `caravan_amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caravan_optional_features`
--
ALTER TABLE `caravan_optional_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caravan_registrations`
--
ALTER TABLE `caravan_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `caravan_registrations_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `caravan_registrations_registration_id_unique` (`registration_id`),
  ADD KEY `caravan_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `caravan_types`
--
ALTER TABLE `caravan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_registrations`
--
ALTER TABLE `category_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_name_unique` (`name`),
  ADD UNIQUE KEY `countries_iso_code_unique` (`iso_code`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `districts_state_id_name_unique` (`state_id`,`name`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `divisions_name_unique` (`name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_application_id_foreign` (`application_id`),
  ADD KEY `documents_user_id_foreign` (`user_id`);

--
-- Indexes for table `eligibility_registrations`
--
ALTER TABLE `eligibility_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eligibility_registrations_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `eligibility_registrations_registration_id_unique` (`registration_id`),
  ADD KEY `eligibility_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `enclosures`
--
ALTER TABLE `enclosures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enclosures_application_id_foreign` (`application_id`),
  ADD KEY `enclosures_user_id_foreign` (`user_id`);

--
-- Indexes for table `enterprises`
--
ALTER TABLE `enterprises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities_application_id_foreign` (`application_id`),
  ADD KEY `facilities_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_additional_features`
--
ALTER TABLE `industrial_additional_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_applications`
--
ALTER TABLE `industrial_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_id` (`slug_id`),
  ADD KEY `industrial_applications_user_id_foreign` (`user_id`),
  ADD KEY `industrial_applications_application_form_id_foreign` (`application_form_id`);

--
-- Indexes for table `industrial_general_requirements`
--
ALTER TABLE `industrial_general_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_guest_services`
--
ALTER TABLE `industrial_guest_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_registrations`
--
ALTER TABLE `industrial_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `industrial_registrations_registration_id_unique` (`registration_id`),
  ADD UNIQUE KEY `industrial_registrations_slug_id_unique` (`slug_id`);

--
-- Indexes for table `industrial_safety_and_securities`
--
ALTER TABLE `industrial_safety_and_securities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_step1s`
--
ALTER TABLE `industrial_step1s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_step2s`
--
ALTER TABLE `industrial_step2s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_step3s`
--
ALTER TABLE `industrial_step3s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industrial_step4s`
--
ALTER TABLE `industrial_step4s`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `photos_signatures`
--
ALTER TABLE `photos_signatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_signatures_application_id_foreign` (`application_id`),
  ADD KEY `photos_signatures_user_id_foreign` (`user_id`);

--
-- Indexes for table `project_categories`
--
ALTER TABLE `project_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_types`
--
ALTER TABLE `project_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_details`
--
ALTER TABLE `property_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_details_application_id_foreign` (`application_id`),
  ADD KEY `property_details_user_id_foreign` (`user_id`);

--
-- Indexes for table `provisional_registrations`
--
ALTER TABLE `provisional_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provisional_registrations_registration_id_unique` (`registration_id`),
  ADD UNIQUE KEY `provisional_registrations_slug_id_unique` (`slug_id`),
  ADD KEY `provisional_registrations_user_id_foreign` (`user_id`),
  ADD KEY `provisional_registrations_application_id_foreign` (`application_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stamp_duty_applications`
--
ALTER TABLE `stamp_duty_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stamp_duty_applications_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `stamp_duty_applications_registration_id_unique` (`registration_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `states_country_id_name_unique` (`country_id`,`name`);

--
-- Indexes for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terms_and_conditions_form_id_unique` (`form_id`);

--
-- Indexes for table `tourismfacilities`
--
ALTER TABLE `tourismfacilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tourism_apartments`
--
ALTER TABLE `tourism_apartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tourist_villa_registrations`
--
ALTER TABLE `tourist_villa_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourist_villa_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `undertakings`
--
ALTER TABLE `undertakings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_aadhar_unique` (`aadhar`);

--
-- Indexes for table `women_centered_tourism_registrations`
--
ALTER TABLE `women_centered_tourism_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `women_centered_tourism_registrations_slug_id_unique` (`slug_id`),
  ADD UNIQUE KEY `women_centered_tourism_registrations_registration_id_unique` (`registration_id`),
  ADD KEY `women_centered_tourism_registrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `adventure_applications`
--
ALTER TABLE `adventure_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `agriculture_registrations`
--
ALTER TABLE `agriculture_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `application_details`
--
ALTER TABLE `application_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_forms`
--
ALTER TABLE `application_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `application_movements`
--
ALTER TABLE `application_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `application_workflow_logs`
--
ALTER TABLE `application_workflow_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `caravan_amenities`
--
ALTER TABLE `caravan_amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `caravan_optional_features`
--
ALTER TABLE `caravan_optional_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `caravan_registrations`
--
ALTER TABLE `caravan_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `caravan_types`
--
ALTER TABLE `caravan_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category_registrations`
--
ALTER TABLE `category_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `eligibility_registrations`
--
ALTER TABLE `eligibility_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enclosures`
--
ALTER TABLE `enclosures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enterprises`
--
ALTER TABLE `enterprises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industrial_additional_features`
--
ALTER TABLE `industrial_additional_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `industrial_applications`
--
ALTER TABLE `industrial_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industrial_general_requirements`
--
ALTER TABLE `industrial_general_requirements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `industrial_guest_services`
--
ALTER TABLE `industrial_guest_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `industrial_registrations`
--
ALTER TABLE `industrial_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `industrial_safety_and_securities`
--
ALTER TABLE `industrial_safety_and_securities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `industrial_step1s`
--
ALTER TABLE `industrial_step1s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `industrial_step2s`
--
ALTER TABLE `industrial_step2s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `industrial_step3s`
--
ALTER TABLE `industrial_step3s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `industrial_step4s`
--
ALTER TABLE `industrial_step4s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `photos_signatures`
--
ALTER TABLE `photos_signatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `project_categories`
--
ALTER TABLE `project_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_types`
--
ALTER TABLE `project_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `property_details`
--
ALTER TABLE `property_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `provisional_registrations`
--
ALTER TABLE `provisional_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stamp_duty_applications`
--
ALTER TABLE `stamp_duty_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tourismfacilities`
--
ALTER TABLE `tourismfacilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tourism_apartments`
--
ALTER TABLE `tourism_apartments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tourist_villa_registrations`
--
ALTER TABLE `tourist_villa_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `undertakings`
--
ALTER TABLE `undertakings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `women_centered_tourism_registrations`
--
ALTER TABLE `women_centered_tourism_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adventure_applications`
--
ALTER TABLE `adventure_applications`
  ADD CONSTRAINT `adventure_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `agriculture_registrations`
--
ALTER TABLE `agriculture_registrations`
  ADD CONSTRAINT `agriculture_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caravan_registrations`
--
ALTER TABLE `caravan_registrations`
  ADD CONSTRAINT `caravan_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eligibility_registrations`
--
ALTER TABLE `eligibility_registrations`
  ADD CONSTRAINT `eligibility_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `industrial_applications`
--
ALTER TABLE `industrial_applications`
  ADD CONSTRAINT `industrial_applications_application_form_id_foreign` FOREIGN KEY (`application_form_id`) REFERENCES `application_forms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `industrial_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
