-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 02, 2020 at 05:10 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `custom_ecommerce_frontend`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `job_title`, `role_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Laravel Admin', 'admin@admin.com', 'Admin', 1, '$2y$10$tONaHrA5u9lwXOC3XB7Hm.OqleLpLMvadXLyymvP4tYMsWcv0sxli', 'Uu74SFvBQocjYPhYWOtQYhfQXbTvyetEh88punD4VJlInLyhO3catmwrWsMK', '2018-11-06 03:52:05', '2018-11-06 03:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `nid` varchar(17) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `is_active` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `frontend_payment_methods`
--

CREATE TABLE `frontend_payment_methods` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `logo` text DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `frontend_payment_methods`
--

INSERT INTO `frontend_payment_methods` (`id`, `name`, `logo`, `instruction`, `is_active`) VALUES
(1, 'Bkash', 'payment_bkash.png', 'Send money through Bkash Personal Number: 01819-XXX-XXX, Enter Reference Id: ', 1),
(2, 'Rocket', 'payment_rocket.jpg', 'Send money through Rocket Personal Number: 01819-XXX-XXX', 1),
(3, 'Cash on Delivery', 'payment_cod.png', '', 1),
(4, 'American', 'payment_american.png', 'XXX XXX XXX', 0),
(5, 'Paypal', 'payment_paypal.png', 'XXX XXX XXX', 0),
(6, 'Mastercard', 'payment_mastercard.png', 'XXX XXX XXX', 0);

-- --------------------------------------------------------

--
-- Table structure for table `journal_amounts`
--

CREATE TABLE `journal_amounts` (
  `id` int(11) NOT NULL,
  `voucher_ref` varchar(50) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(100) NOT NULL,
  `description` tinytext NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_04_26_061309_create_admins_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `money_receipts`
--

CREATE TABLE `money_receipts` (
  `mr_id` int(11) NOT NULL,
  `sales_master_id` int(11) DEFAULT NULL,
  `voucher_ref` varchar(25) NOT NULL,
  `narration` text NOT NULL,
  `payment_method` varchar(250) DEFAULT NULL,
  `payment_by` varchar(250) DEFAULT NULL,
  `amount` double NOT NULL,
  `on_account_of_supply` varchar(250) DEFAULT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT 'invoiced'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `author_id`, `title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'About', 'Excerpt', '<p><h3><br></h3><img src=\"http://localhost/ecom/frontend/public/frontend/images/page/1554803601banner-1-750x400.jpg\" style=\"width: 50%;\"></p><p><b>WELCOME</b></p>', 'public/frontend/images/page/default.jpg', 'about', NULL, NULL, 'PUBLISHED', '2019-01-16 11:46:25', '2019-04-09 03:53:38'),
(2, 1, 'Terms of Use', 'Excerpt here..', '<h3>Body</h3>', 'public/frontend/images/page/default.jpg', 'terms-of-use', NULL, NULL, 'PUBLISHED', '2019-01-16 11:46:25', NULL),
(3, 1, 'Privacy Policy', NULL, '<h3>Body</h3>', 'public/frontend/images/page/default.jpg', 'privacy-policy', NULL, NULL, 'PUBLISHED', '2019-01-16 11:46:25', NULL),
(4, 1, 'Terms and Condition', NULL, '<h3>Body</h3>', 'public/frontend/images/page/default.jpg', 'terms-and-condition', NULL, NULL, 'DRAFTED', NULL, NULL),
(5, 2, 'Delivery Information', 'Delivery Information', 'Delivery Information Delivery Information', 'public/frontend/images/page/default.jpg', 'delivery-information', NULL, NULL, 'PUBLISHED', '2019-04-04 06:37:03', '2019-04-04 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `display_as` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission`, `table_name`, `display_as`) VALUES
(1, 'browse', 'roles', 'Role'),
(2, 'create', 'roles', 'Role'),
(3, 'read', 'roles', 'Role'),
(4, 'update', 'roles', 'Role'),
(5, 'delete', 'roles', 'Role'),
(6, 'browse', 'users', 'Users'),
(7, 'create', 'users', 'Users'),
(8, 'read', 'users', 'Users'),
(9, 'update', 'users', 'Users'),
(10, 'delete', 'users', 'Users'),
(11, 'browse', 'settings', 'Settings'),
(12, 'browse', 'admins', 'Admins'),
(13, 'create', 'admins', 'Admins'),
(14, 'read', 'admins', 'Admins'),
(15, 'update', 'admins', 'Admins'),
(16, 'delete', 'admins', 'Admins');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `name`, `display_name`) VALUES
(1, 'admin', 'Administrator'),
(2, 'user', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `sales_master_add_less`
--

CREATE TABLE `sales_master_add_less` (
  `add_less_id` int(11) NOT NULL,
  `sales_master_id` int(11) NOT NULL,
  `particular` text DEFAULT NULL,
  `amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `system_logo` varchar(250) DEFAULT NULL,
  `favicon` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `vat_registration_no` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `payment_instruction` text DEFAULT NULL,
  `full_sidebar` int(11) NOT NULL DEFAULT 0,
  `theme` varchar(15) DEFAULT NULL,
  `profit_margin` float NOT NULL DEFAULT 15,
  `vat_percent` float NOT NULL DEFAULT 0,
  `invoice_prefix` varchar(20) DEFAULT NULL,
  `discount_mode` varchar(20) DEFAULT NULL,
  `sms_sender` varchar(200) DEFAULT NULL,
  `sms_api_key` text DEFAULT NULL,
  `payment_notification` varchar(50) NOT NULL DEFAULT 'off',
  `next_due_date` date NOT NULL,
  `next_service_hault_date` date DEFAULT NULL,
  `service_charge` float NOT NULL,
  `bkash_no` varchar(100) DEFAULT NULL,
  `banner_1` text DEFAULT NULL,
  `banner_2` text DEFAULT NULL,
  `banner_3` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `company_name`, `website`, `logo`, `system_logo`, `favicon`, `address`, `mobile`, `phone`, `fax`, `email`, `vat_registration_no`, `currency`, `payment_instruction`, `full_sidebar`, `theme`, `profit_margin`, `vat_percent`, `invoice_prefix`, `discount_mode`, `sms_sender`, `sms_api_key`, `payment_notification`, `next_due_date`, `next_service_hault_date`, `service_charge`, `bkash_no`, `banner_1`, `banner_2`, `banner_3`) VALUES
(1, 'Shopper', 'www.example.com', 'public/admin/images/1554714555logoecom.png', 'public/admin/images/1554714668jmg.jpg', 'public/admin/images/1554714406StoreIcon.png', 'Central Square, 22 Hoi Wing Road, Chittagong, Bangladesh', '01816-302720', '031-235265', '', 'info@gmail.com', '', 'BDT', '', 0, 'blue', 0, 0, 'BTG', 'TK', '', '', 'off', '2019-03-01', '2099-03-07', 1000, 'XXXXX-YYY-YYY', 'public/frontend/images/banner/1601648648ban-1.jpg', 'public/frontend/images/banner/1601648648ban-2.jpg', 'public/frontend/images/banner/16016486481blockbanner-1140x75.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'main',
  `title` varchar(250) NOT NULL,
  `sub_title` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `redirect_link` varchar(250) NOT NULL DEFAULT '#',
  `active` int(11) NOT NULL DEFAULT 1,
  `slider_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `type`, `title`, `sub_title`, `image`, `redirect_link`, `active`, `slider_order`, `created_at`, `updated_at`) VALUES
(1, 'main', '1', '1', 'public/frontend/images/main-slider/1598712331bundle-offer-1wb.jpg', '#', 1, 1, '2019-04-04 06:19:14', '2020-08-29 14:45:31'),
(2, 'main', '2', '2', 'public/frontend/images/main-slider/1598712343BenQ-EX2780Q.jpg', '#', 1, 1, '2019-04-04 06:20:07', '2020-10-02 14:49:13'),
(3, 'main', '3', '3', 'public/frontend/images/main-slider/1598712353ekwb aio banner.jpg', '#', 1, 1, '2019-04-04 06:20:16', '2020-10-02 14:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `from_stock_id` int(11) NOT NULL,
  `to_stock_id` int(11) NOT NULL,
  `from_stock_location_id` int(11) NOT NULL,
  `to_stock_location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_group`
--

CREATE TABLE `tbl_account_group` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_account_group`
--

INSERT INTO `tbl_account_group` (`id`, `name`) VALUES
(1, 'Cash & Bank Ac.'),
(2, 'Liabilities'),
(3, 'Expense');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_type`
--

CREATE TABLE `tbl_account_type` (
  `account_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_account_type`
--

INSERT INTO `tbl_account_type` (`account_type_id`, `name`) VALUES
(1, 'Asset'),
(2, 'Liability'),
(3, 'Equity'),
(4, 'Revenue'),
(5, 'Expense');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank_account`
--

CREATE TABLE `tbl_bank_account` (
  `bank_account_id` int(11) NOT NULL,
  `bank_name` varchar(250) NOT NULL,
  `bank_account` varchar(150) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `account_type_id` int(11) DEFAULT NULL,
  `sub_account_type_id` int(11) DEFAULT NULL,
  `account_group_id` int(11) DEFAULT NULL,
  `is_payment_method` int(11) DEFAULT 0,
  `op_bal_dr` double NOT NULL DEFAULT 0,
  `op_bal_cr` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_bank_account`
--

INSERT INTO `tbl_bank_account` (`bank_account_id`, `bank_name`, `bank_account`, `description`, `account_type_id`, `sub_account_type_id`, `account_group_id`, `is_payment_method`, `op_bal_dr`, `op_bal_cr`) VALUES
(4, 'Cash', NULL, NULL, NULL, NULL, 1, 1, 5000, 0),
(5, 'Card', NULL, NULL, NULL, NULL, 1, 1, 0, 0),
(6, 'Investment', NULL, NULL, NULL, NULL, 2, 0, 128450, 0),
(7, 'Daily Expense', NULL, 'Null', NULL, NULL, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank_transaction`
--

CREATE TABLE `tbl_bank_transaction` (
  `bank_transaction_id` int(11) NOT NULL,
  `voucher_ref` varchar(100) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `bank_account_id` int(11) NOT NULL,
  `transaction_description` text CHARACTER SET utf8 NOT NULL,
  `deposit` double NOT NULL,
  `expense` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bank_transaction`
--

INSERT INTO `tbl_bank_transaction` (`bank_transaction_id`, `voucher_ref`, `transaction_date`, `bank_account_id`, `transaction_description`, `deposit`, `expense`, `created_at`) VALUES
(1, '1', '2019-05-08', 4, 'Gadget-1', 400, 0, '2019-05-08 08:39:46'),
(2, '2', '2019-05-08', 4, 'Gadget-2', 1500, 0, '2019-05-08 08:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_batch`
--

CREATE TABLE `tbl_batch` (
  `id` int(11) NOT NULL,
  `code` text DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `purchase_rate` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(150) NOT NULL,
  `brand_image` text DEFAULT NULL,
  `brand_details` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_brand`
--

INSERT INTO `tbl_brand` (`brand_id`, `brand_name`, `brand_image`, `brand_details`, `is_active`) VALUES
(1, 'Asus', 'public/admin/images/brand/15987120775593-1-500x500.jpg', NULL, 1),
(2, 'Dell', 'public/admin/images/brand/1598711144Dell_logo_2016.svg.png', NULL, 1),
(3, 'Huawei', 'public/admin/images/brand/1598711162download.png', NULL, 1),
(4, 'Samsung', 'public/admin/images/brand/1598711172samsung-logo1-300x300-e1465307161764.jpeg', NULL, 1),
(5, 'Gigabyte', 'public/admin/images/brand/15987112130_gigabyte_brand.jpg', NULL, 1),
(6, 'Acer', 'public/admin/images/brand/1598711260download (1).png', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cata_id` int(11) NOT NULL,
  `cata_name` varchar(150) NOT NULL,
  `cata_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cata_id`, `cata_name`, `cata_details`) VALUES
(1, 'Desktop PC', NULL),
(2, 'Laptop & Notebook', NULL),
(3, 'Monitor', NULL),
(4, 'UPS', NULL),
(5, 'Tablet PC', NULL),
(6, 'Office Equipment', NULL),
(7, 'Projector', NULL),
(8, 'Camera', NULL),
(9, 'Accessories', NULL),
(10, 'Software', NULL),
(11, 'Television', NULL),
(12, 'Gadget', NULL),
(13, 'Gaming', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` int(11) NOT NULL,
  `customer_code` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `gender` text DEFAULT NULL,
  `mobile_no` varchar(200) DEFAULT NULL,
  `customer_image` varchar(100) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `op_bal_debit` double NOT NULL DEFAULT 0,
  `op_bal_credit` double NOT NULL DEFAULT 0,
  `billing_address_id` int(11) DEFAULT NULL,
  `delivery_address_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customer_id`, `customer_code`, `customer_name`, `gender`, `mobile_no`, `customer_image`, `category`, `is_active`, `op_bal_debit`, `op_bal_credit`, `billing_address_id`, `delivery_address_id`, `created_at`) VALUES
(1, 1, 'Walk-in', 'Male', NULL, NULL, '1', 1, 0, 0, NULL, NULL, '2019-04-20 09:40:56'),
(2, 2, 'Monayem Rumad', 'Male', '8801826958412', NULL, '1', 1, 0, 0, NULL, NULL, '2019-04-20 09:40:55'),
(3, 3, 'Himel', 'Male', '8801685324125', NULL, '1', 1, 0, 0, NULL, NULL, '2019-04-20 09:40:52'),
(7, 4, 'Rafsan Zaman', 'Male', '8801675711884', NULL, '1', 1, 0, 0, 4, 4, '2019-04-20 10:04:04'),
(8, 5, 'Shakil Khan', 'Male', '8801675711884', NULL, '1', 1, 0, 0, 5, 6, '2019-04-20 10:08:42'),
(12, 6, 'Abhi', 'Male', '8801625321456', NULL, '1', 1, 0, 0, 10, 10, '2019-04-20 10:30:08'),
(13, 7, 'Jamil Hossen', 'Male', '8801675711884', NULL, '1', 1, 0, 0, 11, 11, '2019-04-20 10:38:13'),
(14, 8, 'Shahidul Alam', NULL, '8801675711884', '', '1', 1, 0, 0, NULL, NULL, '2019-04-20 12:17:17'),
(15, 9, 'Shahidul Alam', NULL, '8801675711884', '', '1', 1, 0, 0, NULL, NULL, '2019-04-20 12:17:38'),
(16, 10, 'Shahidul Alam', NULL, '8801675711884', '', '1', 1, 0, 0, 12, 12, '2019-04-20 12:18:05'),
(17, 11, 'Shamsuddin Taiser', 'Male', '8801675711884', NULL, '1', 1, 0, 0, 13, 13, '2019-05-02 10:02:36'),
(18, 12, 'Monayem', 'Male', '8801675842569', NULL, '1', 1, 0, 0, 14, 14, '2019-06-13 07:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_address_books`
--

CREATE TABLE `tbl_customer_address_books` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `fullname` text DEFAULT NULL,
  `mobile` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `postal_code` text DEFAULT NULL,
  `country` varchar(50) DEFAULT 'Bangladesh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer_address_books`
--

INSERT INTO `tbl_customer_address_books` (`id`, `customer_id`, `fullname`, `mobile`, `address`, `city`, `postal_code`, `country`) VALUES
(1, 1, 'Amanat Juwel', '01675711884', 'Prosanti R/A, Road No#1, Hosue No#56, 5th floor, South side flat, Kornelhat', 'Chattogram ', 'Chattogram ', 'Bangladesh'),
(2, 1, 'Amanat Juwel', '01675711884', 'V-link Network, VIP Tower-3rd floor, Kazir deory', 'Chattogram ', 'Chattogram ', 'Bangladesh'),
(4, 7, 'Rafsan Zaman', '01675711884', 'Mogultuly', 'Chittagong', '1200', 'Bangladesh'),
(5, 8, 'Shakil Khan', '01675711884', 'GEC', 'Chittagong', '1000', 'Bangladesh'),
(6, 8, 'Shakil Khan', '01825365478', 'Chawkbazar', 'Chittagong', '1010', 'Bangladesh'),
(10, 12, 'Abhi', '01625321456', 'Pahartali', 'Chittagong', '1200', 'Bangladesh'),
(11, 13, 'Jamil Hossen', '01675711884', 'GEC', 'Chittagong', '1200', 'Bangladesh'),
(12, 16, 'Shahidul Alam', '01675711884', 'Mogultuly', 'Chittagong', '1000', 'Bangladesh'),
(13, 17, 'Shamsuddin Taiser', '01675711884', 'Pateyabad', 'Chittagong', '1200', 'Bangladesh'),
(14, 18, 'Monayem', '01675842569', 'GEC', 'Chittagong', '12000', 'Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_category`
--

CREATE TABLE `tbl_customer_category` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `credit_limit` float NOT NULL DEFAULT 100000
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `tbl_customer_category`
--

INSERT INTO `tbl_customer_category` (`id`, `cat_name`, `credit_limit`) VALUES
(1, 'Local', 100000),
(2, 'Corporates', 100000),
(3, 'Industrial', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_ledger`
--

CREATE TABLE `tbl_customer_ledger` (
  `customer_ledger_id` int(11) NOT NULL,
  `voucher_ref` varchar(200) DEFAULT NULL,
  `sales_master_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `tran_ref_id` int(11) NOT NULL,
  `tran_ref_name` varchar(150) NOT NULL,
  `debit` double NOT NULL,
  `credit` double NOT NULL,
  `transaction_date` date NOT NULL,
  `particulars` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer_ledger`
--

INSERT INTO `tbl_customer_ledger` (`customer_ledger_id`, `voucher_ref`, `sales_master_id`, `customer_id`, `tran_ref_id`, `tran_ref_name`, `debit`, `credit`, `transaction_date`, `particulars`) VALUES
(1, '2', 1, 1, 1, 'BuyProduct', 3500, 0, '2019-04-07', 'Buy - inv 1'),
(2, '2', 1, 1, 5, 'AdvancedPaid', 0, 3500, '2019-04-07', 'Payment - inv 1'),
(3, '1', 1, 1, 1, 'BuyProduct', 400, 0, '2019-05-08', 'Buy - inv 1'),
(4, '1', 1, 1, 5, 'AdvancedPaid', 0, 400, '2019-05-08', 'Payment - inv 1'),
(5, '2', 2, 1, 1, 'BuyProduct', 1500, 0, '2019-05-08', 'Buy - inv 2'),
(6, '2', 2, 1, 5, 'AdvancedPaid', 0, 1500, '2019-05-08', 'Payment - inv 2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_system_user`
--

CREATE TABLE `tbl_customer_system_user` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer_system_user`
--

INSERT INTO `tbl_customer_system_user` (`id`, `customer_id`, `user_id`) VALUES
(2, 7, 5),
(3, 8, 6),
(7, 12, 10),
(8, 13, 11),
(9, 16, 2),
(10, 17, 13),
(11, 18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `item_code` varchar(250) DEFAULT NULL,
  `cata_id` int(11) NOT NULL,
  `sub_cata_id` int(11) DEFAULT NULL,
  `sub_sub_cata_id` int(11) DEFAULT NULL,
  `brand_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `item_image` text DEFAULT NULL,
  `opening_stock_qty` float NOT NULL DEFAULT 0,
  `mrp` float DEFAULT NULL,
  `discounted_price` float DEFAULT NULL,
  `is_vat_applicable` int(11) NOT NULL DEFAULT 0,
  `unit_id` int(11) DEFAULT NULL,
  `reorder_level_min` float NOT NULL DEFAULT 5,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`item_id`, `item_name`, `item_code`, `cata_id`, `sub_cata_id`, `sub_sub_cata_id`, `brand_id`, `description`, `specification`, `item_image`, `opening_stock_qty`, `mrp`, `discounted_price`, `is_vat_applicable`, `unit_id`, `reorder_level_min`, `created_at`, `is_active`) VALUES
(1, 'Asus VivoBook X402YA AMD Dual Core 14\" HD Laptop with Genuine Windows 10', '1000', 2, 5, NULL, 1, '<h2 class=\"section-head\" style=\"margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 18px; color: rgb(34, 34, 34); font-weight: 600; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;\">Description :</h2><div itemprop=\"description\" style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><h2 style=\"margin-top: 10px; margin-right: 0px; margin-left: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 20px;\">Asus VivoBook X402YA AMD Dual Core 14\"HD Laptop with Genuine Windows 10</h2><div style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; text-align: justify;\">Asus VivoBook X402YA has 14\" HD (1366x768) display with AMD Dual Core E2-7015 processor having 1.5 GHz speed and 4 GB DDR3 RAM. This professional grade design with advanced cooling device also contains 1TB HDD to ensure greater compatibility. In addition, it has AMD Radeon R2 Graphics, Genuine Windows 10, chiclet keyboard and built-in stereo 2 W speakers and microphone Sonic Master technology for sound optimization. Its 2 cells battery and weight of only 1.68 kg will give high level of power backup and movability. This spectacular gray device comes with&nbsp;<span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: bold;\">2&nbsp;</span><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: 700;\">years International Limited Warranty (Terms &amp; condition Apply As Per Asus)</span></div></div>', '<table class=\"data-table\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: rgb(241, 243, 245); margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; width: 847px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><thead style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"heading-row\" colspan=\"3\" style=\"padding: 10px 30px; margin: 0px; -webkit-font-smoothing: antialiased; background: rgb(151, 161, 161); font-size: 15px; color: rgb(255, 255, 255); font-weight: 600;\">Basic Information</td></tr></thead><tbody style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Processor</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">AMD Dual Core E2-7015 Processor (1.5GHz,1MB Cache)</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Display</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">14\" HD (1366x768)</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Memory</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">4GB 2133MHz DDR3 Ram</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Storage</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">1TB HDD</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Graphics</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">AMD Radeon R2 Graphics</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Operating System</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Genuine Windows 10</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Battery</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">32WHrs, 2S1P, 2-cell Li-ion</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Adapter</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">ø4.0<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">45W AC Adapter<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Output: 19V DC, 2.37A, 45W , Input: 100~240V AC<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">50/60Hz universal</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Audio</td><td class=\"value\" style=\"padding-right: 30px; padding-left: 30px; margin: 0px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Sonic Master</td></tr></tbody></table>', 'public/admin/images/item_images/1598710672x402ya-500x500.png', 0, 27900, 27300, 0, NULL, 5, '2020-08-29 00:00:00', 1),
(2, 'Gigabyte AERO 15-SA Core i7 9th Gen GTX 1660 Ti 15.6 inch OLED UHD AMOLED Gaming Laptop', '1000', 2, 5, NULL, 5, '<h2 style=\"margin-top: 10px; margin-right: 0px; margin-left: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 20px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;\"><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: 700;\">Gigabyte AERO 15-SA Core i7 9th Gen GTX 1660 Ti 15.6 inch OLED UHD AMOLED Gaming Laptop</span><br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></h2><div style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: bold;\">Gigabyte AERO&nbsp;</span><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: 700;\">15-SA</span>&nbsp;is the World\'s First Microsoft Azure AI Notebook. This is the The World’s Only \"All Intel Inside\" laptop. It has a 15.6 inch OLED thin bezel Samsung UHD 3840x2160 AMOLED display. Its OLED UHD AMOLED display increases the enjoyment of watching. This laptop is powered by Intel Core i7 9750H (9th Gen) processor which cache memory 12M and clock speed up to 4.50GHz. It comes with 8GB RAM with 2 ram slot, 8GB contains each slot. If we consider its storage we find, it not only has 256GB SSD.The storage combination is huge and fast. It runs on Free DOS operating system. As far as the graphics issue this laptop has NVIDIA GTX 1660 Ti 6GB Graphics to manage the graphical functions. With this now you can play any high graphics game without lacking.If we come to its connectivity, it has all type of connectivity like LAN, Wi-Fi, Bluetooth &amp; Card reader. To keep it alive, it has Li Polymer 94.24Wh battery and weighs 4.4 lbs.</div><div style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: bold;\">This laptop comes with&nbsp;</span><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: 700;\">2 Years International Warranty</span></div>', '<table class=\"data-table\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; background-color: rgb(241, 243, 245); width: 847px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><thead style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"heading-row\" colspan=\"3\" style=\"margin: 0px; padding: 10px 30px; -webkit-font-smoothing: antialiased; background: rgb(151, 161, 161); font-size: 15px; color: rgb(255, 255, 255); font-weight: 600;\">Basic Information</td></tr></thead><tbody style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Processor</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Intel Core i7-9750H (12M Cache , 2.60 GHz up to 4.50 GHz) Processor</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Display</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">15.6\" 0.11 Inch Thin Bezel Samsung UHD 3840x2160 AMOLED display<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">X-Rite Pantone Certified, individually factory calibrated</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Memory</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">8GB DDR4 2666MHz</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Storage</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">256GB M.2 PCIe SSD</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Graphics</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">NVIDIA GTX 1660 Ti 6GB Graphics</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Chipset</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Mobile Intel HM370 Express Chipset</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Operating System</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Free DOS</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Battery</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Li Polymer 94.24Wh<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Adapter</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">230W</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Audio</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">2x 2 Watt Speaker<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Microphone<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Nahimic 3</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Special Feature</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Nahimic 3 3D Audio for Gamers<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">World\'s First Microsoft Azure AI Notebook<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Killer DoubleShot Pro</td></tr></tbody></table>', 'public/admin/images/item_images/159871192015-sa-1-500x500.jpg', 0, 173000, 163000, 0, NULL, 5, '2020-08-29 00:00:00', 1),
(3, 'Dell Inspiron 15 5593 Core i3 10th Gen 15.6\" FHD Laptop with Windows 10', '1000', 2, 5, NULL, 2, '<h2 style=\"margin-top: 10px; margin-right: 0px; margin-left: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 20px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;\"><span style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-weight: bold;\">Dell Inspiron 15 5593 Core i3 10th Gen 15.6\" FHD Laptop with Windows 10</span></h2><p style=\"margin-top: 0px; margin-bottom: 10px; padding: 0px; -webkit-font-smoothing: antialiased; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\">Visuals appear every bit as lifelike as the world around you. Experience richer, more vibrant color and details enabled by Dell Color Profiles. Waves MaxxAudio Pro boosts volume and clarifies every tone, so you experience studio quality sound. SmartByte channels maximum bandwidth to your videos or music for a seamless, stutter-free experience. Narrow border: Narrow borders emphasize the screen, rather than the bezel, so you can watch your favorite shows or work on your latest projects in style. With an FHD IPS anti-glare panel, images look good at a wide range of viewing angles, so you’re not limited to looking at the display only</p>', '<table class=\"data-table\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; background-color: rgb(241, 243, 245); width: 847px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><colgroup style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><col class=\"name\" style=\"margin: 0px; padding: 0px 30px; -webkit-font-smoothing: antialiased; width: 0px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\"><col class=\"value\" style=\"margin: 0px; padding: 0px 30px; -webkit-font-smoothing: antialiased; width: 0px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\"></colgroup><thead style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"heading-row\" colspan=\"3\" style=\"margin: 0px; padding: 10px 30px; -webkit-font-smoothing: antialiased; background: rgb(151, 161, 161); font-size: 15px; color: rgb(255, 255, 255); font-weight: 600;\">Basic Information</td></tr></thead><tbody style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Processor</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Intel Core i3-1005G1 Processor (4M Cache, 1.20GHz up to 3.40 GHz)</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Display</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">15.6-inch FHD(1920x1080) Anti-Glare LED-Backlit Non-touch Display Narrow Border Display</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Memory</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">4GB DDR4 2400MHz RAM<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Storage</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">128GB M.2 PCIe NVMe SSD</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Graphics</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Intel UHD Graphics</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Operating System</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Windows 10</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Battery</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">3-Cell, 42 WHr, Integrated battery</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Audio</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">2 tuned speakers with Waves MaxxAudio Pro<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">1 combo headphone / microphone jack</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Special Feature</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Palm-rest with Finger Print Reader<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Multi-touch gesture-enabled precision touch-pad with integrated scrolling</td></tr></tbody></table>', 'public/admin/images/item_images/15987121445593-1-500x500.jpg', 0, 52500, 49000, 0, NULL, 5, '2020-08-29 00:00:00', 1),
(4, 'Asus Tuf FX505DT Ryzen 5 3550H GTX 1650 4GB Graphics 15.6\" FHD Gaming Laptop', '1000', 2, 5, 0, 1, '<h2 style=\"margin: 10px 0px 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 15px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;\">Features</h2><ul style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 10px 0px; -webkit-font-smoothing: antialiased; list-style: none; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">Model: Asus Tuf FX505DT</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">Ryzen 5 3550H ( 4MB Cach, Base Clock 2.1MHz up to 3.7MHz)</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">8GB DDR4 Ram + 1TB HDD</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">GeForce GTX 1650 4GB Graphics</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">15.6\" FHD (1920x1080) 60Hz Display</li></ul>', '<table class=\"data-table\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; background-color: rgb(241, 243, 245); width: 847px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><thead style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"heading-row\" colspan=\"3\" style=\"margin: 0px; padding: 10px 30px; -webkit-font-smoothing: antialiased; background: rgb(151, 161, 161); font-size: 15px; color: rgb(255, 255, 255); font-weight: 600;\">Basic Information</td></tr></thead><tbody style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Processor</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">AMD Ryzen 5 3550H Processor (4MB caches, Base Speed 2.1GHz up to 3.7GHz with 4 cores and 8 threads)<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Display</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">15.6\" (16:9) LED-backlit FHD (1920x1080) 60Hz Anti-Glare IPS-level Panel with 45% NTSC</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Memory</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">8GB DDR4 RAM</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Storage</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">1TB HDD</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Graphics</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">NVIDIA GeForce GTX 1650 , with 4GB GDDR6 VRAM<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Operating System</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Windows 10</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Battery</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">3 -Cell 48 mAh Polymer Battery</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Adapter</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Plug type : 6.0 (mm)<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Output :<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">19 V DC, 6.32 A, 120 W<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Output :<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">20 V DC, 7.5 A, 150 W<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Output :<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">19.5 V DC, 9.23 A, 180 W<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Input :<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">100 -240 V AC, 50/60 Hz universal</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Audio</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Built-in 2 W Stereo Speakers with Microphone<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">DTS Headphone: X</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Special Feature</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Kensington lock<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">fTPM (Firmware-based Trusted Platform Module)</td></tr></tbody></table>', 'public/admin/images/item_images/1598712812fx505du-01-500x500.jpg', 0, 79000, 76000, 0, NULL, 5, '2020-08-29 00:00:00', 1),
(5, 'Asus VivoBook Pro 15 N580GD Core i5 8th Gen 15.6\" Full HD Laptop With Genuine Win 10', '1000', 2, 5, NULL, 1, '<h2 style=\"margin: 10px 0px 0px; padding: 0px; -webkit-font-smoothing: antialiased; font-size: 15px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;\">Features</h2><ul style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 10px 0px; -webkit-font-smoothing: antialiased; list-style: none; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">Model: Asus VivoBook Pro 15 N580GD</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">Intel Core i5-8300H Processor (2.30 GHz up to 4.00 GHz)</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">8GB DDR4 Ram</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">1TB HDD+256GB SSD</li><li style=\"margin: 0px 0px 5px; padding: 0px; -webkit-font-smoothing: antialiased; list-style: inside; font-size: 13px; line-height: 18px; color: rgb(86, 86, 86); position: relative;\">GTX 1050 4GB GDDR5 Graphics</li></ul>', '<table class=\"data-table\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 0px 0px 15px; padding: 0px; -webkit-font-smoothing: antialiased; background-color: rgb(241, 243, 245); width: 847px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px;\"><thead style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"heading-row\" colspan=\"3\" style=\"margin: 0px; padding: 10px 30px; -webkit-font-smoothing: antialiased; background: rgb(151, 161, 161); font-size: 15px; color: rgb(255, 255, 255); font-weight: 600;\">Basic Information</td></tr></thead><tbody style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Processor</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Intel® Core™ i5-8300H Processor (8M Cache, 2.30 GHz up to 4.00 GHz, 4 Core, 8 threads)<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\"></td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Display</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">15.6\" (16:9) LED-backlit FHD (1920x1080)</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Memory</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">8GB DDR4</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Storage</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">1TB HDD+256GB SSD</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Graphics</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">NVIDIA® GeForce® GTX 1050 , with 4GB GDDR5 VRAM</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Chipset</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Intel® HM370 Express Chipset</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Operating System</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">Genuine Win 10</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Battery</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">3 -Cell 47 Wh Polymer Battery</td></tr><tr style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased; border-bottom: 1px solid rgb(225, 225, 225);\"><td class=\"name\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 212px; background: rgb(238, 238, 238); text-align: right; color: rgb(70, 68, 68); font-size: 13px; line-height: 35px; font-weight: 600;\">Audio</td><td class=\"value\" style=\"margin: 0px; padding-right: 30px; padding-left: 30px; -webkit-font-smoothing: antialiased; width: 635px; background: rgb(255, 255, 255); color: rgb(34, 34, 34); line-height: 22px;\">ASUS SonicMaster Technology<br style=\"margin: 0px; padding: 0px; -webkit-font-smoothing: antialiased;\">Harman Kardon</td></tr></tbody></table>', 'public/admin/images/item_images/1598712925n580gd-500x500.jpg', 0, 88970, 86000, 0, NULL, 5, '2020-08-29 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_method` varchar(25) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders_history`
--

CREATE TABLE `tbl_orders_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `id` int(11) NOT NULL,
  `order_master_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_master`
--

CREATE TABLE `tbl_purchase_master` (
  `purchase_master_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `memo_total` double NOT NULL,
  `advanced_amount` double NOT NULL,
  `discount` double NOT NULL,
  `purchase_date` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attachment` mediumtext NOT NULL,
  `voucher_ref` varchar(100) NOT NULL,
  `purchased_by` int(11) DEFAULT NULL,
  `bill_no` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_return_details`
--

CREATE TABLE `tbl_purchase_return_details` (
  `purchase_return_details_id` int(11) NOT NULL,
  `purchase_return_master_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_return_master`
--

CREATE TABLE `tbl_purchase_return_master` (
  `purchase_return_master_id` int(11) NOT NULL,
  `voucher_ref` varchar(20) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `memo_total` float NOT NULL,
  `advanced_amount` float NOT NULL,
  `discount` float NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_details`
--

CREATE TABLE `tbl_sales_details` (
  `sales_details_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `sales_master_id` int(11) NOT NULL,
  `memo_no` varchar(150) DEFAULT NULL,
  `quantity` float NOT NULL,
  `sales_price` double NOT NULL,
  `item_vat` double NOT NULL DEFAULT 0,
  `is_delivered` varchar(20) DEFAULT 'yes',
  `chalan_no` int(11) DEFAULT NULL,
  `item_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_master`
--

CREATE TABLE `tbl_sales_master` (
  `sales_master_id` int(11) NOT NULL,
  `voucher_ref` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `memo_no` varchar(20) DEFAULT NULL,
  `memo_total` double NOT NULL,
  `advanced_amount` double NOT NULL,
  `discount` double NOT NULL,
  `sales_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `sold_by` int(11) DEFAULT NULL,
  `reference_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sales_note` text DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_return_exchange_master`
--

CREATE TABLE `tbl_sales_return_exchange_master` (
  `id` int(11) NOT NULL,
  `voucher_ref` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `sales_master_id` int(11) DEFAULT NULL,
  `purchase_master_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `stock_id` int(11) NOT NULL,
  `stock_master_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `stock_in` int(11) NOT NULL,
  `stock_out` int(11) NOT NULL,
  `particulars` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_location`
--

CREATE TABLE `tbl_stock_location` (
  `stock_location_id` int(11) NOT NULL,
  `stock_location_name` varchar(250) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_stock_location`
--

INSERT INTO `tbl_stock_location` (`stock_location_id`, `stock_location_name`, `code`, `address`) VALUES
(1, 'Showroom', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_master`
--

CREATE TABLE `tbl_stock_master` (
  `id` int(11) NOT NULL,
  `voucher_ref` text DEFAULT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `type` text NOT NULL,
  `ref_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_account_type`
--

CREATE TABLE `tbl_sub_account_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `account_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_category`
--

CREATE TABLE `tbl_sub_category` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `cata_id` int(11) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_sub_category`
--

INSERT INTO `tbl_sub_category` (`id`, `name`, `cata_id`, `description`) VALUES
(1, 'Apple Mac', 1, NULL),
(2, 'Apple IMac', 1, NULL),
(3, 'All In One Pc', 1, NULL),
(4, 'All Laptop', 2, NULL),
(5, 'Gaming Laptop', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_sub_category`
--

CREATE TABLE `tbl_sub_sub_category` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `sub_cata_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_sub_sub_category`
--

INSERT INTO `tbl_sub_sub_category` (`id`, `name`, `sub_cata_id`) VALUES
(1, 'Lenovo', 3),
(2, 'Hp', 3),
(3, 'Dell', 3),
(4, 'Asus', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` int(11) NOT NULL,
  `sup_name` varchar(150) NOT NULL,
  `sup_address` varchar(255) DEFAULT NULL,
  `sup_phone_no` varchar(100) DEFAULT NULL,
  `sup_email` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `op_bal_debit` double NOT NULL DEFAULT 0,
  `op_bal_credit` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`supplier_id`, `sup_name`, `sup_address`, `sup_phone_no`, `sup_email`, `note`, `is_active`, `op_bal_debit`, `op_bal_credit`) VALUES
(1, 'Default', NULL, NULL, NULL, NULL, 1, 0, 0),
(2, 'Local', NULL, NULL, NULL, NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier_ledger`
--

CREATE TABLE `tbl_supplier_ledger` (
  `supplier_ledger_id` int(11) NOT NULL,
  `voucher_ref` varchar(200) DEFAULT NULL,
  `purchase_master_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `tran_ref_id` int(11) NOT NULL,
  `tran_ref_name` varchar(150) NOT NULL,
  `debit` double NOT NULL,
  `credit` double NOT NULL,
  `transaction_date` date NOT NULL,
  `particulars` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unit`
--

CREATE TABLE `tbl_unit` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `tbl_unit`
--

INSERT INTO `tbl_unit` (`id`, `name`) VALUES
(1, 'Pcs'),
(2, 'Kg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Laravel User', 'user@user.com', '$2y$10$lCKKy3iyUehuTQ8AGblqP.HKvhmG/9ta9CZdRktmM7XvKrDHIncMi', 'z1oPSEpEFJ27Brbtn1sgCmY6E6GBPDmjVVzUhQjgqOjBswhs09YEsz2xXs8W', 'admin', '2018-11-06 03:48:47', '2018-11-06 03:48:47'),
(2, 'Peter Smith', 'peter@admin.com', '$2y$10$YOwJos2fzdA9gbJ6nZ90Kup5ZZKx46FHiMDIp9IT6YxZQDZh00CPK', 'z0571nljxPVRH436XWN7qAO44Q7Y9aJNeuohqhHM3MZe5R3bFCDq3UYqbND6', 'admin', '2019-04-04 04:28:47', '2019-04-04 04:28:47'),
(5, 'Rafsan Zaman', 'rafsan@gmail.com', '$2y$10$DkIVgg1cJhlkEMQtd7i78us9KMCoQdRadiMOQRVMBymBCPeaEK2Zy', NULL, 'admin', '2019-04-20 04:04:03', '2019-04-20 04:04:03'),
(6, 'Shakil Khan', 'shakil@gmail.com', '$2y$10$/FMZ.7qWO8Rlfs2WV5VM8OIgZnBkYIL95UdQfL9tRc.h1FX9yotGK', NULL, 'admin', '2019-04-20 04:08:42', '2019-04-20 04:08:42'),
(10, 'Abhi', 'abhi@gmail.com', '$2y$10$Sfm/jyssoXNsVvWqaZwcz.MtslwsJxRtOetqRA031NCSVB9A8JoQO', NULL, 'admin', '2019-04-20 10:30:08', '2019-04-20 10:30:08'),
(11, 'Jamil Hossen', 'jamil@gmail.com', '$2y$10$AdVKLgTLMsqYkAkG24/qcuwLYPkM0uHWNvurLpJMo4KeXB6T8WP.m', NULL, 'admin', '2019-04-20 10:38:13', '2019-04-20 10:38:13'),
(12, 'Shahidul Alam', 'shahid@gmail.com', '$2y$10$yGW376l8Wqx4T9BwFBLe5OmlQtzJMFEVPVT6niBbXKknXyGVQzr5y', NULL, 'admin', '2019-04-20 12:18:05', '2019-04-20 12:18:05'),
(13, 'Shamsuddin Taiser', 'taiser@gmail.com', '$2y$10$p0vKdGFXeZ78yZAL/TIfHOuyjHKZYuMeWbekgkuf8X6vM7CXKPRG.', NULL, 'admin', '2019-05-02 10:02:36', '2019-05-02 10:02:36'),
(14, 'Monayem', 'monayem@gmail.com', '$2y$10$XCAwx1hcRjS99ZxrABazkuIcpgJ1YN9/d3JdOKCjTa5L5Hiarz3Q.', NULL, 'admin', '2019-06-13 07:53:51', '2019-06-13 07:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `voucher_ref` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `voucher_description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `website_vistor_counters`
--

CREATE TABLE `website_vistor_counters` (
  `id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `website_vistor_counters`
--

INSERT INTO `website_vistor_counters` (`id`, `count`, `created_at`, `updated_at`) VALUES
(1, 877, '2019-03-31 06:19:11', '2019-03-31 06:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontend_payment_methods`
--
ALTER TABLE `frontend_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_amounts`
--
ALTER TABLE `journal_amounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD PRIMARY KEY (`mr_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `sales_master_add_less`
--
ALTER TABLE `sales_master_add_less`
  ADD PRIMARY KEY (`add_less_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_group`
--
ALTER TABLE `tbl_account_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  ADD PRIMARY KEY (`account_type_id`);

--
-- Indexes for table `tbl_bank_account`
--
ALTER TABLE `tbl_bank_account`
  ADD PRIMARY KEY (`bank_account_id`);

--
-- Indexes for table `tbl_bank_transaction`
--
ALTER TABLE `tbl_bank_transaction`
  ADD PRIMARY KEY (`bank_transaction_id`);

--
-- Indexes for table `tbl_batch`
--
ALTER TABLE `tbl_batch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cata_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_customer_address_books`
--
ALTER TABLE `tbl_customer_address_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_category`
--
ALTER TABLE `tbl_customer_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_ledger`
--
ALTER TABLE `tbl_customer_ledger`
  ADD PRIMARY KEY (`customer_ledger_id`);

--
-- Indexes for table `tbl_customer_system_user`
--
ALTER TABLE `tbl_customer_system_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders_history`
--
ALTER TABLE `tbl_orders_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase_master`
--
ALTER TABLE `tbl_purchase_master`
  ADD PRIMARY KEY (`purchase_master_id`);

--
-- Indexes for table `tbl_purchase_return_details`
--
ALTER TABLE `tbl_purchase_return_details`
  ADD PRIMARY KEY (`purchase_return_details_id`);

--
-- Indexes for table `tbl_purchase_return_master`
--
ALTER TABLE `tbl_purchase_return_master`
  ADD PRIMARY KEY (`purchase_return_master_id`);

--
-- Indexes for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  ADD PRIMARY KEY (`sales_details_id`);

--
-- Indexes for table `tbl_sales_master`
--
ALTER TABLE `tbl_sales_master`
  ADD PRIMARY KEY (`sales_master_id`);

--
-- Indexes for table `tbl_sales_return_exchange_master`
--
ALTER TABLE `tbl_sales_return_exchange_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `tbl_stock_location`
--
ALTER TABLE `tbl_stock_location`
  ADD PRIMARY KEY (`stock_location_id`);

--
-- Indexes for table `tbl_stock_master`
--
ALTER TABLE `tbl_stock_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sub_account_type`
--
ALTER TABLE `tbl_sub_account_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sub_category`
--
ALTER TABLE `tbl_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sub_sub_category`
--
ALTER TABLE `tbl_sub_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_supplier_ledger`
--
ALTER TABLE `tbl_supplier_ledger`
  ADD PRIMARY KEY (`supplier_ledger_id`);

--
-- Indexes for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`voucher_ref`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `website_vistor_counters`
--
ALTER TABLE `website_vistor_counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontend_payment_methods`
--
ALTER TABLE `frontend_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `journal_amounts`
--
ALTER TABLE `journal_amounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `mr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales_master_add_less`
--
ALTER TABLE `sales_master_add_less`
  MODIFY `add_less_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_account_group`
--
ALTER TABLE `tbl_account_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  MODIFY `account_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_bank_account`
--
ALTER TABLE `tbl_bank_account`
  MODIFY `bank_account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_bank_transaction`
--
ALTER TABLE `tbl_bank_transaction`
  MODIFY `bank_transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_batch`
--
ALTER TABLE `tbl_batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cata_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_customer_address_books`
--
ALTER TABLE `tbl_customer_address_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_customer_category`
--
ALTER TABLE `tbl_customer_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_customer_ledger`
--
ALTER TABLE `tbl_customer_ledger`
  MODIFY `customer_ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_customer_system_user`
--
ALTER TABLE `tbl_customer_system_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_orders_history`
--
ALTER TABLE `tbl_orders_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_purchase_master`
--
ALTER TABLE `tbl_purchase_master`
  MODIFY `purchase_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_purchase_return_details`
--
ALTER TABLE `tbl_purchase_return_details`
  MODIFY `purchase_return_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_purchase_return_master`
--
ALTER TABLE `tbl_purchase_return_master`
  MODIFY `purchase_return_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  MODIFY `sales_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_master`
--
ALTER TABLE `tbl_sales_master`
  MODIFY `sales_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sales_return_exchange_master`
--
ALTER TABLE `tbl_sales_return_exchange_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_stock_location`
--
ALTER TABLE `tbl_stock_location`
  MODIFY `stock_location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_stock_master`
--
ALTER TABLE `tbl_stock_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sub_account_type`
--
ALTER TABLE `tbl_sub_account_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_sub_category`
--
ALTER TABLE `tbl_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_sub_sub_category`
--
ALTER TABLE `tbl_sub_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_supplier_ledger`
--
ALTER TABLE `tbl_supplier_ledger`
  MODIFY `supplier_ledger_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unit`
--
ALTER TABLE `tbl_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_vistor_counters`
--
ALTER TABLE `website_vistor_counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
