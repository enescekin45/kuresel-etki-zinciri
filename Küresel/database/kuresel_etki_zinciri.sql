-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 29 Eki 2025, 19:03:32
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kuresel_etki_zinciri`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `request_id` varchar(36) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `uuid`, `user_id`, `user_type`, `ip_address`, `user_agent`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `request_id`, `session_id`, `created_at`) VALUES
(1, 'f11b9b30-a5ed-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; tr-TR) WindowsPowerShell/5.1.19041.6328', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:29:41'),
(2, '0d636f55-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; tr-TR) WindowsPowerShell/5.1.19041.6328', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:30:29'),
(3, '95066fcf-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:34:16'),
(4, '9512b642-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"mehmet@gmail.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 15:34:17'),
(5, 'e35bbcda-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehemt@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:28'),
(6, 'e3768688-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehemt@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:28'),
(7, 'e8d454a4-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:36:37'),
(8, 'e9356131-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@company.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:38'),
(9, 'ea1de1ee-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:39'),
(10, 'ecb1efeb-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:44'),
(11, 'ecc35fb8-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:36:44'),
(12, 'f8b089f7-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:37:04'),
(13, 'f8c15931-a5ee-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:37:04'),
(14, '2d304e41-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; tr-TR) WindowsPowerShell/5.1.19041.6328', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:38:32'),
(15, '3affbecc-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; tr-TR) WindowsPowerShell/5.1.19041.6328', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:38:55'),
(16, '9316f5ae-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:41:23'),
(17, '9d5e3734-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehmet@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:41:40'),
(18, '9d8437fa-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehmet@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 15:41:40'),
(19, 'f4cc1afb-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:44:07'),
(20, 'f4d6afbc-a5ef-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 15:44:07'),
(21, 'f9393d24-a5f1-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 15:58:33'),
(22, 'f980d2ac-a5f1-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 15:58:34'),
(23, '3414509d-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehemt@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:12'),
(24, '342f7524-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"mehemt@gmail.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:12'),
(25, '3c32dde1-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:25'),
(26, '3c5e9053-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:26'),
(27, '3dff1c88-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:28'),
(28, '3e2c08e3-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 16:00:29'),
(29, '6d199223-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:01:47'),
(30, '6d536fa9-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:01:48'),
(31, '71e32b3c-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 16:01:55'),
(32, '71f1a920-a5f2-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:01:56'),
(33, 'fe0617f4-a5f6-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 16:34:29'),
(34, 'fe1442e1-a5f6-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:34:29'),
(35, '024ed463-a5f7-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:34:36'),
(36, '025a2fe0-a5f7-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 16:34:36'),
(37, 'a8d0f942-a5fb-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 17:07:53'),
(38, 'd1f58385-a5fb-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 17:09:02'),
(39, '74434107-a5fe-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:27:53'),
(40, '745e736c-a5fe-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:27:54'),
(41, '8fa6677b-a5fe-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:28:39'),
(42, '8fc8883e-a5fe-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:28:40'),
(43, '935abad0-a5ff-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 17:35:55'),
(44, 'f2f939e4-a5ff-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 17:38:36'),
(45, 'f34b82ca-a5ff-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:38:36'),
(46, '05cf9ea6-a600-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:39:07'),
(47, '05f1f0ec-a600-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:39:07'),
(48, 'cd465b2b-a600-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"test@example.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:44:42'),
(49, '0ab78b90-a601-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 17:46:25'),
(50, 'edb5d4ac-a601-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:52:46'),
(51, 'edc87a7e-a601-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 17:52:46'),
(52, 'f9aa2736-a601-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:53:06'),
(53, 'f9c87949-a601-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 17:53:06'),
(54, 'f29c3250-a604-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:14:22'),
(55, '262b3e23-a605-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:15:49'),
(56, '38febf80-a605-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:16:20'),
(57, '7126be82-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:17:55'),
(58, '713b0e4d-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 18:17:55'),
(59, '773bc352-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 18:18:05'),
(60, '774c2653-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 18:18:05'),
(61, '7b99eb82-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 18:18:12'),
(62, '7bb4ae0d-a605-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'registration_failed', 'auth', NULL, NULL, '{\"email\":\"likoho5133@fanlvr.com\",\"error\":\"Email already exists\"}', NULL, NULL, '2025-10-10 18:18:12'),
(63, 'ca341e55-a605-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:20:24'),
(64, '11b899ef-a606-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:22:24'),
(65, '11e2f558-a606-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:22:24'),
(66, '3fd84c71-a606-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:23:41'),
(67, '401c3a3d-a606-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:23:42'),
(68, 'fe6f7bfa-a606-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:29:01'),
(69, '0162a551-a607-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:29:06'),
(70, '01a850e5-a607-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:29:07'),
(71, '7a080188-a607-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@example.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:32:29'),
(72, '7a1cce18-a607-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:32:29'),
(73, 'c32e1ed5-a607-11f0-9b30-dcfe0744453b', 19, NULL, 'unknown', 'unknown', 'user_registered', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:34:31'),
(74, 'c364781b-a607-11f0-9b30-dcfe0744453b', 19, NULL, 'unknown', 'unknown', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:34:32'),
(75, '15e0651f-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:36:50'),
(76, '19d57a6c-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:36:57'),
(77, '19ea14d1-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@validator.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:36:57'),
(78, '1b0432ab-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@company.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:36:59'),
(79, '1b19130f-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@company.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:36:59'),
(80, '1cc3b5ee-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:02'),
(81, '1ccef95a-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:02'),
(82, '20dccf85-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:08'),
(83, '21096b21-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"admin@kuresaletzinciri.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:09'),
(84, '24294380-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@company.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:14'),
(85, '243400f7-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@company.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 18:37:14'),
(86, '2977dedf-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:37:23'),
(87, '29cdf019-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:37:23'),
(88, '3bb86188-a608-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:37:54'),
(89, '701bf477-a609-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:46:31'),
(90, '7079c1b8-a609-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:46:32'),
(91, '83718133-a609-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:47:03'),
(92, '9a67eb99-a609-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:47:42'),
(93, '9ac70dcb-a609-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:47:43'),
(94, '11f69f03-a60b-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:58:12'),
(95, '14705277-a60b-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:58:16'),
(96, '14b125bb-a60b-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 18:58:17'),
(97, '4b01a6c5-a60c-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:06:57'),
(98, '515d4f39-a60c-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:07:08'),
(99, '51ab5199-a60c-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:07:08'),
(100, '6ef76b9f-a60d-11f0-9b30-dcfe0744453b', NULL, NULL, 'unknown', 'unknown', 'login_failed', 'auth', NULL, NULL, '{\"email\":\"test@example.com\",\"error\":\"Invalid email or password\"}', NULL, NULL, '2025-10-10 19:15:07'),
(101, '4544facf-a60e-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:21:07'),
(102, 'a84d8bdc-a60e-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:23:53'),
(103, 'a8f6d1ea-a60e-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:23:54'),
(104, 'e0e496c1-a610-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:39:47'),
(105, 'e416ed1a-a610-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:39:52'),
(106, 'e55ac1e8-a610-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:39:54'),
(107, '0c68c29a-a611-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:41:00'),
(108, '0f3111a7-a611-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:41:04'),
(109, '0fc61082-a611-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 19:41:05'),
(110, 'bc5bd718-a615-11f0-9b30-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-10 20:14:33'),
(111, '42393977-a8d1-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 07:41:56'),
(112, '4341559c-a8d1-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 07:41:57'),
(113, '15f3f85e-a8de-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'password_changed', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 09:13:45'),
(114, '23809ee1-a8de-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 09:14:08'),
(115, '506b0ec1-a8de-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 09:15:23'),
(116, '50c84c21-a8de-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 09:15:24'),
(117, 'f5d3ae3e-a8f9-11f0-8c23-dcfe0744453b', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 12:33:17'),
(118, '6e1c2ab0-a904-11f0-8c23-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 13:48:14'),
(119, '6e8a5463-a904-11f0-8c23-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 13:48:14'),
(120, 'd76b83f6-a90d-11f0-8c23-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 14:55:36'),
(121, '45680741-a916-11f0-8c23-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 15:55:56'),
(122, '45efd562-a916-11f0-8c23-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-14 15:55:57'),
(123, 'b636231d-b032-11f0-b45f-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:07:10'),
(124, '8e3380e7-b037-11f0-b45f-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:41:50'),
(125, '9a513092-b037-11f0-b45f-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:42:10'),
(126, '9c3fb7a0-b037-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:42:13'),
(127, 'aa9984ee-b037-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:42:38'),
(128, 'c75b2c28-b037-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 17:43:26'),
(129, '1f6f05b3-b03a-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:00:13'),
(130, '22ad49aa-b03a-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:00:18'),
(131, '2aa161db-b03a-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:00:31'),
(132, '2d3143e6-b03a-11f0-b45f-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:00:36'),
(133, '518f7230-b03b-11f0-b45f-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:08:46'),
(134, '5379607e-b03b-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:08:49'),
(135, '59873e7c-b03b-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:09:00'),
(136, '5b420d28-b03b-11f0-b45f-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:09:02'),
(137, 'eedca206-b03e-11f0-b45f-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:34:39'),
(138, 'f114738e-b03e-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 18:34:42'),
(139, '09eac790-b047-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:32:40'),
(140, '3d6f8d8d-b047-11f0-b45f-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:34:06'),
(141, '5bcb0ccf-b047-11f0-b45f-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:34:57'),
(142, '41de4620-b048-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:41:23'),
(143, '450c7cfb-b048-11f0-b45f-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:41:29'),
(144, '4f40d5c9-b048-11f0-b45f-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:41:46'),
(145, '51fa199d-b048-11f0-b45f-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:41:50'),
(146, '5b0c7bba-b048-11f0-b45f-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:42:06'),
(147, '5cf2938b-b048-11f0-b45f-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-23 19:42:09'),
(148, '7c559d26-b1af-11f0-a816-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 14:32:51'),
(149, 'f05837ae-b1b9-11f0-a816-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 15:47:40'),
(150, 'f4425fc9-b1b9-11f0-a816-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 15:47:47'),
(151, 'bdbbac35-b1c3-11f0-a816-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 16:57:50'),
(152, 'bfafd177-b1c3-11f0-a816-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 16:57:54'),
(153, 'cdd9f615-b1c3-11f0-a816-dcfe0744453b', 22, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 16:58:17'),
(154, 'cf834e3f-b1c3-11f0-a816-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 16:58:20'),
(155, '003ad972-b1cb-11f0-a816-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 17:49:48'),
(156, '4c3bf434-b1cb-11f0-a816-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-25 17:51:56'),
(157, '4ca3f64b-b294-11f0-a92d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-26 17:50:46'),
(158, '69535f06-b294-11f0-a92d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-26 17:51:34'),
(159, '6ca7ffd6-b294-11f0-a92d-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-26 17:51:39'),
(160, 'd17385ee-b348-11f0-81a6-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-27 15:22:58'),
(161, 'efd7b62f-b368-11f0-81a6-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-27 19:12:53'),
(162, 'f1deec00-b368-11f0-81a6-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-27 19:12:56'),
(163, '0b801db2-b3f4-11f0-babb-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 11:48:39'),
(164, '71924f3b-b402-11f0-babb-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 13:31:43'),
(165, '76802ef6-b402-11f0-babb-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 13:31:51'),
(166, '4083b6ae-b410-11f0-babb-dcfe0744453b', 28, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 15:10:34'),
(167, '442d360b-b410-11f0-babb-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 15:10:40'),
(168, '628080d3-b410-11f0-babb-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-28 15:11:31'),
(169, '5ccbf93e-b4d9-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 15:10:10'),
(170, '049db093-b4e0-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 15:57:49'),
(171, '9e7f7fb2-b4e2-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 16:16:26'),
(172, 'a276a89e-b4e2-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 16:16:32'),
(173, 'a4277c40-b4e2-11f0-869d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 16:16:35'),
(174, 'b4a5fe65-b4e2-11f0-869d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 16:17:03'),
(175, 'b910a0ea-b4e2-11f0-869d-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 16:17:10'),
(176, 'e69d09d5-b4eb-11f0-869d-dcfe0744453b', 29, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:22:52'),
(177, 'e85f8bed-b4eb-11f0-869d-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:22:55'),
(178, 'f4106474-b4eb-11f0-869d-dcfe0744453b', 20, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:23:15'),
(179, 'dd307a71-b4ed-11f0-869d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:36:55'),
(180, 'e5e42e49-b4ed-11f0-869d-dcfe0744453b', 21, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:37:10'),
(181, 'eae4ecd9-b4ed-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'login_success', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:37:18'),
(182, 'ff524eb6-b4ed-11f0-869d-dcfe0744453b', 27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'logout', 'auth', NULL, NULL, '[]', NULL, NULL, '2025-10-29 17:37:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blockchain_records`
--

CREATE TABLE `blockchain_records` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `transaction_hash` varchar(66) NOT NULL,
  `block_number` bigint(20) NOT NULL,
  `block_hash` varchar(66) DEFAULT NULL,
  `transaction_index` int(11) DEFAULT NULL,
  `record_type` enum('supply_chain_step','validation','impact_score','product_registration') NOT NULL,
  `record_id` int(11) NOT NULL,
  `contract_address` varchar(42) DEFAULT NULL,
  `data_hash` varchar(66) NOT NULL,
  `ipfs_hash` varchar(64) DEFAULT NULL,
  `gas_used` bigint(20) DEFAULT NULL,
  `gas_price` bigint(20) DEFAULT NULL,
  `transaction_fee` decimal(18,8) DEFAULT NULL,
  `confirmation_status` enum('pending','confirmed','failed') DEFAULT 'pending',
  `confirmation_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `industry_sector` varchar(100) DEFAULT NULL,
  `company_type` enum('supplier','manufacturer','distributor','retailer','farmer','fisher','logistics') NOT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `certifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certifications`)),
  `compliance_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`compliance_documents`)),
  `transparency_score` decimal(5,2) DEFAULT 0.00,
  `reputation_score` decimal(5,2) DEFAULT 0.00,
  `total_products` int(11) DEFAULT 0,
  `verified_data_percentage` decimal(5,2) DEFAULT 0.00,
  `blockchain_address` varchar(42) DEFAULT NULL,
  `status` enum('active','inactive','suspended','under_review') DEFAULT 'under_review',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `companies`
--

INSERT INTO `companies` (`id`, `uuid`, `user_id`, `company_name`, `legal_name`, `tax_number`, `industry_sector`, `company_type`, `registration_number`, `website`, `description`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `contact_person`, `contact_email`, `contact_phone`, `certifications`, `compliance_documents`, `transparency_score`, `reputation_score`, `total_products`, `verified_data_percentage`, `blockchain_address`, `status`, `created_at`, `updated_at`) VALUES
(4, '7e913930-a906-11f0-8c23-dcfe0744453b', 20, 'Test Food Company', NULL, NULL, NULL, 'manufacturer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0, 0.00, NULL, 'active', '2025-10-14 14:03:00', '2025-10-14 14:03:00'),
(5, '70970c79-b037-11f0-b45f-dcfe0744453b', 21, 'Demo Company', NULL, NULL, NULL, 'manufacturer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0, 0.00, NULL, 'under_review', '2025-10-23 17:41:00', '2025-10-23 17:41:00'),
(6, '476b0406-b1cb-11f0-a816-dcfe0744453b', 29, 'Türki', NULL, NULL, 'food', 'manufacturer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0, 0.00, NULL, 'under_review', '2025-10-25 17:51:48', '2025-10-25 17:51:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `impact_scores`
--

CREATE TABLE `impact_scores` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `product_batch_id` int(11) NOT NULL,
  `overall_score` decimal(5,2) NOT NULL,
  `overall_grade` char(1) NOT NULL,
  `environmental_score` decimal(5,2) NOT NULL,
  `carbon_footprint_score` decimal(5,2) DEFAULT NULL,
  `water_footprint_score` decimal(5,2) DEFAULT NULL,
  `biodiversity_impact_score` decimal(5,2) DEFAULT NULL,
  `waste_score` decimal(5,2) DEFAULT NULL,
  `social_score` decimal(5,2) NOT NULL,
  `fair_wages_score` decimal(5,2) DEFAULT NULL,
  `working_conditions_score` decimal(5,2) DEFAULT NULL,
  `community_impact_score` decimal(5,2) DEFAULT NULL,
  `labor_rights_score` decimal(5,2) DEFAULT NULL,
  `transparency_score` decimal(5,2) NOT NULL,
  `data_completeness_score` decimal(5,2) DEFAULT NULL,
  `third_party_validation_score` decimal(5,2) DEFAULT NULL,
  `update_frequency_score` decimal(5,2) DEFAULT NULL,
  `source_credibility_score` decimal(5,2) DEFAULT NULL,
  `total_carbon_footprint` decimal(12,6) DEFAULT NULL,
  `total_water_footprint` decimal(12,3) DEFAULT NULL,
  `total_energy_consumption` decimal(12,6) DEFAULT NULL,
  `total_waste_generated` decimal(12,3) DEFAULT NULL,
  `equivalent_car_km` decimal(10,2) DEFAULT NULL,
  `equivalent_shower_minutes` decimal(10,2) DEFAULT NULL,
  `equivalent_home_energy_days` decimal(8,2) DEFAULT NULL,
  `calculation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `calculation_method` varchar(100) DEFAULT NULL,
  `data_completeness_percentage` decimal(5,2) DEFAULT NULL,
  `confidence_level` decimal(5,2) DEFAULT NULL,
  `blockchain_hash` varchar(66) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `impact_scores`
--

INSERT INTO `impact_scores` (`id`, `uuid`, `product_batch_id`, `overall_score`, `overall_grade`, `environmental_score`, `carbon_footprint_score`, `water_footprint_score`, `biodiversity_impact_score`, `waste_score`, `social_score`, `fair_wages_score`, `working_conditions_score`, `community_impact_score`, `labor_rights_score`, `transparency_score`, `data_completeness_score`, `third_party_validation_score`, `update_frequency_score`, `source_credibility_score`, `total_carbon_footprint`, `total_water_footprint`, `total_energy_consumption`, `total_waste_generated`, `equivalent_car_km`, `equivalent_shower_minutes`, `equivalent_home_energy_days`, `calculation_date`, `calculation_method`, `data_completeness_percentage`, `confidence_level`, `blockchain_hash`, `created_at`, `updated_at`) VALUES
(46, 'b0257ad7-a9c6-11f0-ad49-dcfe0744453b', 1, 8.20, 'B', 7.80, 7.50, 8.00, 8.20, 8.50, 8.50, 8.80, 8.20, 8.70, 8.30, 8.30, 9.00, 8.50, 8.00, 8.20, 52.300000, 1250.000, 171.100000, 19.500, 215.00, 75.00, 2.80, '2025-10-15 12:58:47', NULL, 92.00, 88.50, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(47, 'b04c14b5-a9c6-11f0-ad49-dcfe0744453b', 2, 8.20, 'B', 7.80, 7.50, 8.00, 8.20, 8.50, 8.50, 8.80, 8.20, 8.70, 8.30, 8.30, 9.00, 8.50, 8.00, 8.20, 52.300000, 1250.000, 171.100000, 19.500, 215.00, 75.00, 2.80, '2025-10-15 12:58:47', NULL, 92.00, 88.50, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(48, 'b05c11f9-a9c6-11f0-ad49-dcfe0744453b', 3, 8.20, 'B', 7.80, 7.50, 8.00, 8.20, 8.50, 8.50, 8.80, 8.20, 8.70, 8.30, 8.30, 9.00, 8.50, 8.00, 8.20, 52.300000, 1250.000, 171.100000, 19.500, 215.00, 75.00, 2.80, '2025-10-15 12:58:47', NULL, 92.00, 88.50, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(49, 'b06ba78d-a9c6-11f0-ad49-dcfe0744453b', 4, 7.50, 'C', 7.20, 6.80, 7.50, 7.00, 7.80, 7.80, 8.00, 7.50, 8.20, 7.50, 7.50, 8.50, 8.00, 7.00, 7.80, 87.500000, 2850.000, 353.400000, 72.900, 360.00, 170.00, 4.80, '2025-10-15 12:58:47', NULL, 87.00, 82.00, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(50, 'b07d6206-a9c6-11f0-ad49-dcfe0744453b', 5, 7.50, 'C', 7.20, 6.80, 7.50, 7.00, 7.80, 7.80, 8.00, 7.50, 8.20, 7.50, 7.50, 8.50, 8.00, 7.00, 7.80, 87.500000, 2850.000, 353.400000, 72.900, 360.00, 170.00, 4.80, '2025-10-15 12:58:47', NULL, 87.00, 82.00, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(51, 'b08ed444-a9c6-11f0-ad49-dcfe0744453b', 6, 6.80, 'D', 6.20, 5.50, 7.00, 6.50, 6.80, 7.50, 7.80, 7.20, 7.80, 7.00, 7.00, 8.00, 7.50, 6.50, 7.20, 285.300000, 1520.000, 789.600000, 125.800, 1170.00, 90.00, 10.80, '2025-10-15 12:58:47', NULL, 82.00, 78.00, NULL, '2025-10-15 12:58:47', '2025-10-15 12:58:47'),
(52, 'b09a6186-a9c6-11f0-ad49-dcfe0744453b', 7, 6.80, 'D', 6.20, 5.50, 7.00, 6.50, 6.80, 7.50, 7.80, 7.20, 7.80, 7.00, 7.00, 8.00, 7.50, 6.50, 7.20, 285.300000, 1520.000, 789.600000, 125.800, 1170.00, 90.00, 10.80, '2025-10-15 12:58:48', NULL, 82.00, 78.00, NULL, '2025-10-15 12:58:48', '2025-10-15 12:58:48'),
(53, 'b0d976d5-a9c6-11f0-ad49-dcfe0744453b', 8, 9.00, 'A', 9.20, 8.80, 9.50, 9.00, 9.50, 8.80, 9.00, 8.50, 9.20, 8.80, 9.00, 9.50, 9.20, 8.80, 9.00, 28.700000, 850.000, 108.900000, 15.300, 118.00, 50.00, 1.50, '2025-10-15 12:58:48', NULL, 95.00, 92.00, NULL, '2025-10-15 12:58:48', '2025-10-15 12:58:48'),
(54, 'b0ee36df-a9c6-11f0-ad49-dcfe0744453b', 9, 9.00, 'A', 9.20, 8.80, 9.50, 9.00, 9.50, 8.80, 9.00, 8.50, 9.20, 8.80, 9.00, 9.50, 9.20, 8.80, 9.00, 28.700000, 850.000, 108.900000, 15.300, 118.00, 50.00, 1.50, '2025-10-15 12:58:48', NULL, 95.00, 92.00, NULL, '2025-10-15 12:58:48', '2025-10-15 12:58:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `company_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(100) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `subcategory` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `volume` decimal(10,3) DEFAULT NULL,
  `dimensions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dimensions`)),
  `packaging_type` varchar(100) DEFAULT NULL,
  `shelf_life` int(11) DEFAULT NULL,
  `origin_country` varchar(100) DEFAULT NULL,
  `origin_region` varchar(100) DEFAULT NULL,
  `harvest_season` varchar(50) DEFAULT NULL,
  `product_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_images`)),
  `documentation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documentation`)),
  `qr_code_path` varchar(255) DEFAULT NULL,
  `qr_code_data` text DEFAULT NULL,
  `status` enum('active','inactive','discontinued') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `uuid`, `company_id`, `product_name`, `product_code`, `barcode`, `category`, `subcategory`, `brand`, `description`, `weight`, `volume`, `dimensions`, `packaging_type`, `shelf_life`, `origin_country`, `origin_region`, `harvest_season`, `product_images`, `documentation`, `qr_code_path`, `qr_code_data`, `status`, `created_at`, `updated_at`) VALUES
(2, '7ea60f52-a906-11f0-8c23-dcfe0744453b', 4, 'Organik Zeytinyağı', 'PRD-GIDA-001', '8691234567890', 'Gıda', 'Yağlar ve Sıvı Yağlar', 'Organik Tarım', '100% organik, soğuk sıkım zeytinyağı. Ege bölgesi zeytinlerinden elde edilmiştir. Antioksidan açısından zengin, kalp damar sağlığına faydalıdır.', 0.500, 0.500, '{\"length\":10,\"width\":7,\"height\":25}', 'Cam Şişe', 730, 'Türkiye', 'Ege', '2025 Sonbahar', '[\"\\/assets\\/images\\/products\\/organik-zeytinyagi.svg\"]', '[{\"name\":\"Organik Tar\\u0131m Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Standartlar\\u0131 Enstit\\u00fcs\\u00fc\",\"validity\":\"2025-12-31\",\"certificate_number\":\"TR-ORG-2025-001\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %100 organik i\\u00e7erikli oldu\\u011funu ve organik tar\\u0131m standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"\\u00c7evre Dostu \\u00dcr\\u00fcn Sertifikas\\u0131\",\"issuer\":\"\\u00c7evre ve \\u015eehircilik Bakanl\\u0131\\u011f\\u0131\",\"validity\":\"2026-06-30\",\"certificate_number\":\"\\u00c7S-\\u00c7EV-2025-123\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinin \\u00e7evre dostu oldu\\u011funu ve s\\u00fcrd\\u00fcr\\u00fclebilirlik ilkelerine uygun oldu\\u011funu belgeler.\"},{\"name\":\"Kalite Y\\u00f6netim Sistemi\",\"issuer\":\"ISO\",\"validity\":\"2026-03-15\",\"certificate_number\":\"ISO-9001-2025-Q1\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn kalite y\\u00f6netim sistemi standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"G\\u0131da G\\u00fcvenli\\u011fi Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Akreditasyon Kurumu\",\"validity\":\"2026-09-30\",\"certificate_number\":\"TAK-FOOD-2025-045\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn g\\u0131da g\\u00fcvenli\\u011fi standartlar\\u0131na uygun \\u00fcretildi\\u011fini ve t\\u00fcketicinin sa\\u011fl\\u0131\\u011f\\u0131 i\\u00e7in g\\u00fcvenli oldu\\u011funu belgeler.\"}]', NULL, NULL, 'active', '2025-10-14 14:03:00', '2025-10-16 10:25:06'),
(3, 'f111684a-a9be-11f0-ad49-dcfe0744453b', 4, 'Bal', 'PRD-GIDA-002', '8691234567891', 'Gıda', 'Arı Ürünleri', 'Doğal Arı Ürünleri', 'Saf çiçek balı. Karadeniz bölgesi çiçeklerinden elde edilmiştir. Enerji verici, antioksidan açısından zengin.', 0.400, 0.400, '{\"length\":8,\"width\":8,\"height\":12}', 'Cam Kavanoz', 1095, 'Türkiye', 'Karadeniz', '2025 Yaz', '[\"\\/assets\\/images\\/products\\/bal_simple.svg\"]', '[{\"name\":\"Organik Tar\\u0131m Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Standartlar\\u0131 Enstit\\u00fcs\\u00fc\",\"validity\":\"2025-12-31\",\"certificate_number\":\"TR-ORG-2025-001\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %100 organik i\\u00e7erikli oldu\\u011funu ve organik tar\\u0131m standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"\\u00c7evre Dostu \\u00dcr\\u00fcn Sertifikas\\u0131\",\"issuer\":\"\\u00c7evre ve \\u015eehircilik Bakanl\\u0131\\u011f\\u0131\",\"validity\":\"2026-06-30\",\"certificate_number\":\"\\u00c7S-\\u00c7EV-2025-123\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinin \\u00e7evre dostu oldu\\u011funu ve s\\u00fcrd\\u00fcr\\u00fclebilirlik ilkelerine uygun oldu\\u011funu belgeler.\"},{\"name\":\"Kalite Y\\u00f6netim Sistemi\",\"issuer\":\"ISO\",\"validity\":\"2026-03-15\",\"certificate_number\":\"ISO-9001-2025-Q1\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn kalite y\\u00f6netim sistemi standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"G\\u0131da G\\u00fcvenli\\u011fi Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Akreditasyon Kurumu\",\"validity\":\"2026-09-30\",\"certificate_number\":\"TAK-FOOD-2025-045\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn g\\u0131da g\\u00fcvenli\\u011fi standartlar\\u0131na uygun \\u00fcretildi\\u011fini ve t\\u00fcketicinin sa\\u011fl\\u0131\\u011f\\u0131 i\\u00e7in g\\u00fcvenli oldu\\u011funu belgeler.\"}]', NULL, NULL, 'active', '2025-10-15 12:03:20', '2025-10-16 11:12:16'),
(4, '601b7851-a9bf-11f0-ad49-dcfe0744453b', 4, 'Ton Balığı Konservesi', 'PRD-GIDA-003', '8691234567892', 'Gıda', 'Konserve Ürünler', 'Deniz Ürünleri', 'Sızma zeytinyağlı ton balığı konservesi. Omega-3 yağ asitleri açısından zengin. Protein kaynağıdır.', 0.160, 0.160, '{\"length\":7,\"width\":5,\"height\":3}', 'Teneke Kutu', 1460, 'Türkiye', 'Marmara', '2025 İlkbahar', '[\"\\/assets\\/images\\/products\\/ton-baligi.svg\"]', '[{\"name\":\"Organik Tar\\u0131m Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Standartlar\\u0131 Enstit\\u00fcs\\u00fc\",\"validity\":\"2025-12-31\",\"certificate_number\":\"TR-ORG-2025-001\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %100 organik i\\u00e7erikli oldu\\u011funu ve organik tar\\u0131m standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"\\u00c7evre Dostu \\u00dcr\\u00fcn Sertifikas\\u0131\",\"issuer\":\"\\u00c7evre ve \\u015eehircilik Bakanl\\u0131\\u011f\\u0131\",\"validity\":\"2026-06-30\",\"certificate_number\":\"\\u00c7S-\\u00c7EV-2025-123\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinin \\u00e7evre dostu oldu\\u011funu ve s\\u00fcrd\\u00fcr\\u00fclebilirlik ilkelerine uygun oldu\\u011funu belgeler.\"},{\"name\":\"Kalite Y\\u00f6netim Sistemi\",\"issuer\":\"ISO\",\"validity\":\"2026-03-15\",\"certificate_number\":\"ISO-9001-2025-Q1\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn kalite y\\u00f6netim sistemi standartlar\\u0131na uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"G\\u0131da G\\u00fcvenli\\u011fi Sertifikas\\u0131\",\"issuer\":\"T\\u00fcrk Akreditasyon Kurumu\",\"validity\":\"2026-09-30\",\"certificate_number\":\"TAK-FOOD-2025-045\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn g\\u0131da g\\u00fcvenli\\u011fi standartlar\\u0131na uygun \\u00fcretildi\\u011fini ve t\\u00fcketicinin sa\\u011fl\\u0131\\u011f\\u0131 i\\u00e7in g\\u00fcvenli oldu\\u011funu belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"4\",\"url\":\"\\/product?id=4\",\"generated_at\":\"2025-10-15T14:06:26+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:26', '2025-10-16 10:25:07'),
(5, '604c7bc7-a9bf-11f0-ad49-dcfe0744453b', 4, 'Organik Pamuk Tişört', 'PRD-TEKSTIL-001', '8691234567893', 'Tekstil', 'Üst Giyim', 'Eko Giyim', '100% organik pamuktan üretilmiş tişört. Çocuk ve yetişkin bedenlerinde mevcuttur. Çevreye duyarlı üretim.', 0.200, NULL, '{\"length\":30,\"width\":20,\"height\":2}', 'Karton Kutu', 3650, 'Türkiye', 'Ege', '2025 Yaz', '[\"\\/assets\\/images\\/products\\/tisort.svg\"]', '[{\"name\":\"Organik Pamuk Sertifikas\\u0131\",\"issuer\":\"Organic Cotton Standards\",\"validity\":\"2026-02-28\",\"certificate_number\":\"OCS-TEX-2025-078\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %100 organik pamuk kullan\\u0131larak \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"Adil Ticaret Sertifikas\\u0131\",\"issuer\":\"Fair Trade International\",\"validity\":\"2026-05-31\",\"certificate_number\":\"FTI-TEX-2025-034\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde adil ticaret ilkelerinin uyguland\\u0131\\u011f\\u0131n\\u0131 ve i\\u015f\\u00e7ilerin haklar\\u0131n\\u0131n korundu\\u011funu belgeler.\"},{\"name\":\"Su Tasarrufu Sertifikas\\u0131\",\"issuer\":\"Better Cotton Initiative\",\"validity\":\"2026-08-31\",\"certificate_number\":\"BCI-WATER-2025-012\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde su tasarrufu sa\\u011fland\\u0131\\u011f\\u0131n\\u0131 ve \\u00e7evre dostu y\\u00f6ntemlerin uyguland\\u0131\\u011f\\u0131n\\u0131 belgeler.\"},{\"name\":\"\\u00c7al\\u0131\\u015fan Haklar\\u0131 Sertifikas\\u0131\",\"issuer\":\"Social Accountability International\",\"validity\":\"2026-11-30\",\"certificate_number\":\"SA8000-TEX-2025-056\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde \\u00e7al\\u0131\\u015fan haklar\\u0131n\\u0131n korundu\\u011funu ve g\\u00fcvenli \\u00e7al\\u0131\\u015fma ko\\u015fullar\\u0131n\\u0131n sa\\u011fland\\u0131\\u011f\\u0131n\\u0131 belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"5\",\"url\":\"\\/product?id=5\",\"generated_at\":\"2025-10-15T14:06:26+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:26', '2025-10-16 10:25:07'),
(6, '605994fa-a9bf-11f0-ad49-dcfe0744453b', 4, 'Kot Pantolon', 'PRD-TEKSTIL-002', '8691234567894', 'Tekstil', 'Alt Giyim', 'Denim Style', 'Klasik kesim kot pantolon. %100 pamuk içeriği. Su tasarruflu boyama tekniği ile üretilmiştir.', 0.800, NULL, '{\"length\":40,\"width\":30,\"height\":3}', 'Plastik Poşet', 3650, 'Türkiye', 'Marmara', '2025 Sonbahar', '[\"\\/assets\\/images\\/products\\/kot-pantolon.svg\"]', '[{\"name\":\"Organik Pamuk Sertifikas\\u0131\",\"issuer\":\"Organic Cotton Standards\",\"validity\":\"2026-02-28\",\"certificate_number\":\"OCS-TEX-2025-078\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %100 organik pamuk kullan\\u0131larak \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"Adil Ticaret Sertifikas\\u0131\",\"issuer\":\"Fair Trade International\",\"validity\":\"2026-05-31\",\"certificate_number\":\"FTI-TEX-2025-034\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde adil ticaret ilkelerinin uyguland\\u0131\\u011f\\u0131n\\u0131 ve i\\u015f\\u00e7ilerin haklar\\u0131n\\u0131n korundu\\u011funu belgeler.\"},{\"name\":\"Su Tasarrufu Sertifikas\\u0131\",\"issuer\":\"Better Cotton Initiative\",\"validity\":\"2026-08-31\",\"certificate_number\":\"BCI-WATER-2025-012\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde su tasarrufu sa\\u011fland\\u0131\\u011f\\u0131n\\u0131 ve \\u00e7evre dostu y\\u00f6ntemlerin uyguland\\u0131\\u011f\\u0131n\\u0131 belgeler.\"},{\"name\":\"\\u00c7al\\u0131\\u015fan Haklar\\u0131 Sertifikas\\u0131\",\"issuer\":\"Social Accountability International\",\"validity\":\"2026-11-30\",\"certificate_number\":\"SA8000-TEX-2025-056\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde \\u00e7al\\u0131\\u015fan haklar\\u0131n\\u0131n korundu\\u011funu ve g\\u00fcvenli \\u00e7al\\u0131\\u015fma ko\\u015fullar\\u0131n\\u0131n sa\\u011fland\\u0131\\u011f\\u0131n\\u0131 belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"6\",\"url\":\"\\/product?id=6\",\"generated_at\":\"2025-10-15T14:06:26+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:26', '2025-10-16 10:25:07'),
(7, '606a9d48-a9bf-11f0-ad49-dcfe0744453b', 4, 'Akıllı Telefon', 'PRD-ELEK-001', '8691234567895', 'Elektronik', 'Mobil Cihazlar', 'TechBrand', '6.5 inch ekran, 128GB depolama, çift kamera. Enerji verimli işlemci. Geri dönüştürülmüş plastik kullanılmıştır.', 0.190, NULL, '{\"length\":16,\"width\":7.5,\"height\":0.8}', 'Karton Kutu', 1825, 'Çin', 'Shenzhen', '2025 Yaz', '[\"\\/assets\\/images\\/products\\/akilli-telefon.svg\"]', '[{\"name\":\"Enerji Verimlili\\u011fi Sertifikas\\u0131\",\"issuer\":\"Energy Star\",\"validity\":\"2026-01-31\",\"certificate_number\":\"ENERGY-2025-123\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn enerji verimlili\\u011fi standartlar\\u0131na uygun oldu\\u011funu ve d\\u00fc\\u015f\\u00fck enerji t\\u00fcketimine sahip oldu\\u011funu belgeler.\"},{\"name\":\"Geri D\\u00f6n\\u00fc\\u015f\\u00fcm Uyumlulu\\u011fu Sertifikas\\u0131\",\"issuer\":\"EPEAT\",\"validity\":\"2026-04-30\",\"certificate_number\":\"EPEAT-2025-045\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn geri d\\u00f6n\\u00fc\\u015ft\\u00fcr\\u00fclebilir malzemeler i\\u00e7erdi\\u011fini ve \\u00e7evre dostu \\u00fcretim s\\u00fcre\\u00e7lerine uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"Elektromanyetik Uyumluluk Sertifikas\\u0131\",\"issuer\":\"CE\",\"validity\":\"2026-07-31\",\"certificate_number\":\"CE-2025-078\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn elektromanyetik uyumluluk standartlar\\u0131na uygun oldu\\u011funu ve di\\u011fer elektronik cihazlar\\u0131 etkilemeyece\\u011fini belgeler.\"},{\"name\":\"G\\u00fcvenlik Sertifikas\\u0131\",\"issuer\":\"T\\u00dcV\",\"validity\":\"2026-10-31\",\"certificate_number\":\"T\\u00dcV-SAFETY-2025-091\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn g\\u00fcvenlik standartlar\\u0131na uygun \\u00fcretildi\\u011fini ve kullan\\u0131c\\u0131 i\\u00e7in g\\u00fcvenli oldu\\u011funu belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"7\",\"url\":\"\\/product?id=7\",\"generated_at\":\"2025-10-15T14:06:27+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:26', '2025-10-16 10:25:08'),
(8, '60786c7b-a9bf-11f0-ad49-dcfe0744453b', 4, 'Dizüstü Bilgisayar', 'PRD-ELEK-002', '8691234567896', 'Elektronik', 'Bilgisayarlar', 'TechBrand', '15.6 inch ekran, 512GB SSD, 16GB RAM. Enerji tasarruflu işlemci. Geri dönüştürülmüş alüminyum alaşım kasası.', 1.800, NULL, '{\"length\":36,\"width\":25,\"height\":2}', 'Karton Kutu', 1825, 'Çin', 'Shanghai', '2025 İlkbahar', '[\"\\/assets\\/images\\/products\\/dizustu-bilgisayar.svg\"]', '[{\"name\":\"Enerji Verimlili\\u011fi Sertifikas\\u0131\",\"issuer\":\"Energy Star\",\"validity\":\"2026-01-31\",\"certificate_number\":\"ENERGY-2025-123\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn enerji verimlili\\u011fi standartlar\\u0131na uygun oldu\\u011funu ve d\\u00fc\\u015f\\u00fck enerji t\\u00fcketimine sahip oldu\\u011funu belgeler.\"},{\"name\":\"Geri D\\u00f6n\\u00fc\\u015f\\u00fcm Uyumlulu\\u011fu Sertifikas\\u0131\",\"issuer\":\"EPEAT\",\"validity\":\"2026-04-30\",\"certificate_number\":\"EPEAT-2025-045\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn geri d\\u00f6n\\u00fc\\u015ft\\u00fcr\\u00fclebilir malzemeler i\\u00e7erdi\\u011fini ve \\u00e7evre dostu \\u00fcretim s\\u00fcre\\u00e7lerine uygun \\u00fcretildi\\u011fini belgeler.\"},{\"name\":\"Elektromanyetik Uyumluluk Sertifikas\\u0131\",\"issuer\":\"CE\",\"validity\":\"2026-07-31\",\"certificate_number\":\"CE-2025-078\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn elektromanyetik uyumluluk standartlar\\u0131na uygun oldu\\u011funu ve di\\u011fer elektronik cihazlar\\u0131 etkilemeyece\\u011fini belgeler.\"},{\"name\":\"G\\u00fcvenlik Sertifikas\\u0131\",\"issuer\":\"T\\u00dcV\",\"validity\":\"2026-10-31\",\"certificate_number\":\"T\\u00dcV-SAFETY-2025-091\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn g\\u00fcvenlik standartlar\\u0131na uygun \\u00fcretildi\\u011fini ve kullan\\u0131c\\u0131 i\\u00e7in g\\u00fcvenli oldu\\u011funu belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"8\",\"url\":\"\\/product?id=8\",\"generated_at\":\"2025-10-15T14:06:27+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:27', '2025-10-16 10:25:08'),
(9, '608b2c33-a9bf-11f0-ad49-dcfe0744453b', 4, 'Doğal Şampuan', 'PRD-KOZ-001', '8691234567897', 'Kozmetik', 'Saç Bakım Ürünleri', 'Doğal Bakım', 'Organik bitki özleriyle zenginleştirilmiş şampuan. Sodyum lauril sülfat içermez. Vegan ve cruelty-free.', 0.300, 0.300, '{\"length\":15,\"width\":7,\"height\":20}', 'Plastik Şişe', 1095, 'Türkiye', 'Ege', '2025 Yaz', '[\"\\/assets\\/images\\/products\\/dogal-sampuan.svg\"]', '[{\"name\":\"Organik Kozmetik Sertifikas\\u0131\",\"issuer\":\"COSMOS\",\"validity\":\"2026-03-31\",\"certificate_number\":\"COSMOS-2025-023\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %95 oran\\u0131nda organik i\\u00e7erik bar\\u0131nd\\u0131rd\\u0131\\u011f\\u0131n\\u0131 ve do\\u011fal i\\u00e7erikli oldu\\u011funu belgeler.\"},{\"name\":\"Vegan \\u00dcr\\u00fcn Sertifikas\\u0131\",\"issuer\":\"The Vegan Society\",\"validity\":\"2026-06-30\",\"certificate_number\":\"VEGAN-2025-056\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn hayvansal i\\u00e7erik bar\\u0131nd\\u0131rmad\\u0131\\u011f\\u0131n\\u0131 ve hayvan testi yap\\u0131lmad\\u0131\\u011f\\u0131n\\u0131 belgeler.\"},{\"name\":\"Cruelty Free Sertifikas\\u0131\",\"issuer\":\"Leaping Bunny\",\"validity\":\"2026-09-30\",\"certificate_number\":\"CRUELTY-2025-089\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde hayvan testi yap\\u0131lmad\\u0131\\u011f\\u0131n\\u0131 ve hayvan haklar\\u0131na sayg\\u0131 duyuldu\\u011funu belgeler.\"},{\"name\":\"Do\\u011fal \\u0130\\u00e7erik Sertifikas\\u0131\",\"issuer\":\"Natural Products Association\",\"validity\":\"2026-12-31\",\"certificate_number\":\"NPA-2025-112\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn do\\u011fal i\\u00e7erik bar\\u0131nd\\u0131rd\\u0131\\u011f\\u0131n\\u0131 ve sentetik kimyasallar i\\u00e7ermedi\\u011fini belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"9\",\"url\":\"\\/product?id=9\",\"generated_at\":\"2025-10-15T14:06:27+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:27', '2025-10-16 10:25:09'),
(10, '609bbf14-a9bf-11f0-ad49-dcfe0744453b', 4, 'Organik Krem', 'PRD-KOZ-002', '8691234567898', 'Kozmetik', 'Yüz Bakım Ürünleri', 'Doğal Bakım', 'Organik içerikli nemlendirici yüz kremi. Aloe vera ve jojoba yağı içerir. Hassas ciltler için uygundur.', 0.050, 0.050, '{\"length\":10,\"width\":6,\"height\":12}', 'Plastik Kavanoz', 730, 'Türkiye', 'Akdeniz', '2025 Sonbahar', '[\"\\/assets\\/images\\/products\\/organik-krem.svg\"]', '[{\"name\":\"Organik Kozmetik Sertifikas\\u0131\",\"issuer\":\"COSMOS\",\"validity\":\"2026-03-31\",\"certificate_number\":\"COSMOS-2025-023\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn %95 oran\\u0131nda organik i\\u00e7erik bar\\u0131nd\\u0131rd\\u0131\\u011f\\u0131n\\u0131 ve do\\u011fal i\\u00e7erikli oldu\\u011funu belgeler.\"},{\"name\":\"Vegan \\u00dcr\\u00fcn Sertifikas\\u0131\",\"issuer\":\"The Vegan Society\",\"validity\":\"2026-06-30\",\"certificate_number\":\"VEGAN-2025-056\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn hayvansal i\\u00e7erik bar\\u0131nd\\u0131rmad\\u0131\\u011f\\u0131n\\u0131 ve hayvan testi yap\\u0131lmad\\u0131\\u011f\\u0131n\\u0131 belgeler.\"},{\"name\":\"Cruelty Free Sertifikas\\u0131\",\"issuer\":\"Leaping Bunny\",\"validity\":\"2026-09-30\",\"certificate_number\":\"CRUELTY-2025-089\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn \\u00fcretim s\\u00fcrecinde hayvan testi yap\\u0131lmad\\u0131\\u011f\\u0131n\\u0131 ve hayvan haklar\\u0131na sayg\\u0131 duyuldu\\u011funu belgeler.\"},{\"name\":\"Do\\u011fal \\u0130\\u00e7erik Sertifikas\\u0131\",\"issuer\":\"Natural Products Association\",\"validity\":\"2026-12-31\",\"certificate_number\":\"NPA-2025-112\",\"description\":\"\\u00dcr\\u00fcn\\u00fcn do\\u011fal i\\u00e7erik bar\\u0131nd\\u0131rd\\u0131\\u011f\\u0131n\\u0131 ve sentetik kimyasallar i\\u00e7ermedi\\u011fini belgeler.\"}]', NULL, '{\"type\":\"product\",\"product_id\":\"10\",\"url\":\"\\/product?id=10\",\"generated_at\":\"2025-10-15T14:06:27+02:00\",\"error\":\"QR code generator not available\"}', 'active', '2025-10-15 12:06:27', '2025-10-16 10:25:09'),
(11, '643c79fd-b1d1-11f0-a816-dcfe0744453b', 6, 'Soğuk Sıkım Nar Eksisi', 'NE-KM-500ML', '8680987654321', 'food', 'Şerbetçi Otlu & Meyveli İçecekler', 'Köyümün Lezzetleri', '%100 doğal, katkısız, soğuk sıkım yöntemiyle elde edilmiş nar ekşisi. İçinde hiçbir koruyucu madde bulunmaz.', 0.650, 0.500, NULL, 'Cam Şişe', 540, 'Türkiye', 'Antakya', '2024 Sonbahar', NULL, NULL, '/qr-codes/product-11.png', '{\"type\":\"product\",\"product_id\":\"11\",\"product_uuid\":\"643c79fd-b1d1-11f0-a816-dcfe0744453b\",\"url\":\"http:\\/\\/localhost\\/K\\u00fcresel\\/product?uuid=643c79fd-b1d1-11f0-a816-dcfe0744453b\",\"generated_at\":\"2025-10-25T20:35:34+02:00\",\"version\":\"1.0\"}', 'active', '2025-10-25 18:35:33', '2025-10-26 20:55:57'),
(12, 'e4bd2398-b2ab-11f0-a92d-dcfe0744453b', 6, 'Ezine Beyaz Peynir - Tam Yağlı', 'PEY-EZN-500GR', '8681234567894', 'food', 'Peynir Çeşitleri', 'Ezine Geleneksel', 'Geleneksel yöntemlerle hazırlanmış, tam yağlı, tuzlu beyaz peynir. Kahvaltılık olarak ve börek, poğaça gibi hamur işlerinde kullanıma uygundur. +4°C\'de muhafaza ediniz.', 0.550, 0.600, NULL, 'Vakumlu Plastik Paket', 60, 'Türkiye', 'Çanakkale', 'Yıl Boyu', '[{\"filename\":\"product_1761511179_7058.png\",\"url\":\"\\/K\\u00fcresel\\/uploads\\/products\\/product_1761511179_7058.png\",\"original_name\":\"ChatGPT Image 26 Eki 2025 23_39_24.png\"}]', NULL, '/qr-codes/product-12.png', '{\"type\":\"product\",\"product_id\":\"12\",\"product_uuid\":\"e4bd2398-b2ab-11f0-a92d-dcfe0744453b\",\"url\":\"http:\\/\\/localhost\\/K\\u00fcresel\\/product?uuid=e4bd2398-b2ab-11f0-a92d-dcfe0744453b\",\"generated_at\":\"2025-10-26T21:39:39+01:00\",\"version\":\"1.0\"}', 'active', '2025-10-26 20:39:39', '2025-10-26 20:39:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_batches`
--

CREATE TABLE `product_batches` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `product_id` int(11) NOT NULL,
  `batch_number` varchar(100) NOT NULL,
  `production_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(20) DEFAULT 'pieces',
  `production_facility` varchar(255) DEFAULT NULL,
  `production_line` varchar(100) DEFAULT NULL,
  `quality_grade` char(1) DEFAULT NULL,
  `blockchain_hash` varchar(66) DEFAULT NULL,
  `block_number` bigint(20) DEFAULT NULL,
  `status` enum('in_production','in_transit','delivered','recalled') DEFAULT 'in_production',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_batches`
--

INSERT INTO `product_batches` (`id`, `uuid`, `product_id`, `batch_number`, `production_date`, `expiry_date`, `quantity`, `unit`, `production_facility`, `production_line`, `quality_grade`, `blockchain_hash`, `block_number`, `status`, `created_at`, `updated_at`) VALUES
(1, '10ed13db-a9c1-11f0-ad49-dcfe0744453b', 2, 'BATCH-2-202510', '2025-09-15', '2027-10-15', 1000, 'pieces', 'Ege', 'Line-2', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:32', '2025-10-15 12:18:32'),
(2, '10ffd179-a9c1-11f0-ad49-dcfe0744453b', 3, 'BATCH-3-202510', '2025-09-15', '2028-10-14', 1000, 'pieces', 'Karadeniz', 'Line-3', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:32', '2025-10-15 12:18:32'),
(3, '11267739-a9c1-11f0-ad49-dcfe0744453b', 4, 'BATCH-4-202510', '2025-09-15', '2029-10-14', 1000, 'pieces', 'Marmara', 'Line-4', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:32', '2025-10-15 12:18:32'),
(4, '113adc01-a9c1-11f0-ad49-dcfe0744453b', 5, 'BATCH-5-202510', '2025-09-15', '2035-10-13', 1000, 'pieces', 'Ege', 'Line-5', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:33', '2025-10-15 12:18:33'),
(5, '11536bef-a9c1-11f0-ad49-dcfe0744453b', 6, 'BATCH-6-202510', '2025-09-15', '2035-10-13', 1000, 'pieces', 'Marmara', 'Line-6', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:33', '2025-10-15 12:18:33'),
(6, '116330b2-a9c1-11f0-ad49-dcfe0744453b', 7, 'BATCH-7-202510', '2025-09-15', '2030-10-14', 1000, 'pieces', 'Shenzhen', 'Line-7', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:33', '2025-10-15 12:18:33'),
(7, '1172fa03-a9c1-11f0-ad49-dcfe0744453b', 8, 'BATCH-8-202510', '2025-09-15', '2030-10-14', 1000, 'pieces', 'Shanghai', 'Line-8', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:33', '2025-10-15 12:18:33'),
(8, '11a10008-a9c1-11f0-ad49-dcfe0744453b', 9, 'BATCH-9-202510', '2025-09-15', '2028-10-14', 1000, 'pieces', 'Ege', 'Line-9', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:33', '2025-10-15 12:18:33'),
(9, '11e9ce3e-a9c1-11f0-ad49-dcfe0744453b', 10, 'BATCH-10-202510', '2025-09-15', '2027-10-15', 1000, 'pieces', 'Akdeniz', 'Line-10', 'A', NULL, NULL, 'in_production', '2025-10-15 12:18:34', '2025-10-15 12:18:34');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `scheduled_notifications`
--

CREATE TABLE `scheduled_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` enum('daily_summary','biweekly_summary','weekly_update') NOT NULL,
  `last_sent` timestamp NULL DEFAULT NULL,
  `next_send` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `scheduled_notifications`
--

INSERT INTO `scheduled_notifications` (`id`, `user_id`, `notification_type`, `last_sent`, `next_send`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 19, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22'),
(2, 20, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22'),
(3, 21, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22'),
(4, 22, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22'),
(5, 26, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22'),
(6, 27, 'daily_summary', NULL, '2025-10-18 12:34:22', 1, '2025-10-17 12:34:22', '2025-10-17 12:34:22');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `supply_chain_steps`
--

CREATE TABLE `supply_chain_steps` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `product_batch_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `step_type` enum('raw_material','processing','manufacturing','packaging','logistics','retail') NOT NULL,
  `step_name` varchar(255) NOT NULL,
  `step_description` text DEFAULT NULL,
  `step_order` int(11) NOT NULL,
  `location_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`location_coordinates`)),
  `address` varchar(500) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `duration_hours` int(11) DEFAULT NULL,
  `carbon_emissions` decimal(12,6) DEFAULT NULL,
  `water_usage` decimal(12,3) DEFAULT NULL,
  `energy_consumption` decimal(12,6) DEFAULT NULL,
  `waste_generated` decimal(12,3) DEFAULT NULL,
  `renewable_energy_percentage` decimal(5,2) DEFAULT NULL,
  `worker_count` int(11) DEFAULT NULL,
  `average_wage` decimal(10,2) DEFAULT NULL,
  `working_hours_per_day` decimal(4,2) DEFAULT NULL,
  `safety_incidents` int(11) DEFAULT NULL,
  `worker_satisfaction_score` decimal(3,2) DEFAULT NULL,
  `transport_mode` varchar(50) DEFAULT NULL,
  `distance_km` decimal(10,2) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `fuel_consumption` decimal(10,4) DEFAULT NULL,
  `certificates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certificates`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `validation_status` enum('pending','validated','rejected','under_review') DEFAULT 'pending',
  `validation_score` decimal(5,2) DEFAULT NULL,
  `blockchain_hash` varchar(66) DEFAULT NULL,
  `block_number` bigint(20) DEFAULT NULL,
  `ipfs_hash` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `supply_chain_steps`
--

INSERT INTO `supply_chain_steps` (`id`, `uuid`, `product_batch_id`, `company_id`, `step_type`, `step_name`, `step_description`, `step_order`, `location_coordinates`, `address`, `start_date`, `end_date`, `duration_hours`, `carbon_emissions`, `water_usage`, `energy_consumption`, `waste_generated`, `renewable_energy_percentage`, `worker_count`, `average_wage`, `working_hours_per_day`, `safety_incidents`, `worker_satisfaction_score`, `transport_mode`, `distance_km`, `fuel_type`, `fuel_consumption`, `certificates`, `documents`, `validation_status`, `validation_score`, `blockchain_hash`, `block_number`, `ipfs_hash`, `created_at`, `updated_at`) VALUES
(1, '60d45b8a-a9c1-11f0-ad49-dcfe0744453b', 1, 4, 'raw_material', 'Ham Madde Temini', 'Organik tarım uygulamalarıyla üretilmiş ham maddelerin toplanması', 1, '{\"lat\":38.4237,\"lng\":27.1428}', 'Organik Tarım Alanı, Ege Bölgesi, Türkiye', '2025-08-31 11:20:46', '2025-09-05 11:20:46', 120, 5.200000, 150.000, 25.500000, 2.100, 35.00, 15, 25.50, 8.00, 0, 4.20, NULL, NULL, NULL, NULL, '[\"Organic Certification\",\"Fair Trade\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:46', '2025-10-15 12:20:46'),
(2, '60df7c75-a9c1-11f0-ad49-dcfe0744453b', 1, 4, 'processing', 'İşleme ve Üretim', 'Ham maddelerin işlenerek son ürüne dönüştürülmesi', 2, '{\"lat\":38.3529,\"lng\":27.2341}', 'Üretim Tesisi, Manisa, Türkiye', '2025-09-10 11:20:46', '2025-09-20 11:20:46', 240, 12.800000, 320.000, 85.200000, 8.500, 45.00, 25, 28.75, 8.00, 1, 4.00, NULL, NULL, NULL, NULL, '[\"ISO 22000\",\"HACCP\"]', NULL, 'validated', 4.60, NULL, NULL, NULL, '2025-10-15 12:20:46', '2025-10-15 12:20:46'),
(3, '60ffa190-a9c1-11f0-ad49-dcfe0744453b', 1, 4, 'packaging', 'Ambalajlama', 'Ürünlerin çevre dostu ambalaj malzemeleriyle paketlenmesi', 3, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-09-25 11:20:46', '2025-09-30 11:20:46', 120, 3.500000, 45.000, 32.100000, 5.200, 60.00, 12, 26.30, 8.00, 0, 4.50, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:46', '2025-10-15 12:20:46'),
(4, '611ffed3-a9c1-11f0-ad49-dcfe0744453b', 1, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 4, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-05 11:20:46', '2025-10-10 11:20:46', 120, 25.600000, 0.000, 45.800000, 1.200, 25.00, 8, 24.50, 8.00, 0, 4.10, 'truck', 450.00, 'diesel', 35.2000, '[\"Green Logistics\"]', NULL, 'validated', 4.30, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(5, '61279ff9-a9c1-11f0-ad49-dcfe0744453b', 2, 4, 'raw_material', 'Ham Madde Temini', 'Organik tarım uygulamalarıyla üretilmiş ham maddelerin toplanması', 1, '{\"lat\":38.4237,\"lng\":27.1428}', 'Organik Tarım Alanı, Ege Bölgesi, Türkiye', '2025-08-31 11:20:47', '2025-09-05 11:20:47', 120, 5.200000, 150.000, 25.500000, 2.100, 35.00, 15, 25.50, 8.00, 0, 4.20, NULL, NULL, NULL, NULL, '[\"Organic Certification\",\"Fair Trade\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(6, '612e11d9-a9c1-11f0-ad49-dcfe0744453b', 2, 4, 'processing', 'İşleme ve Üretim', 'Ham maddelerin işlenerek son ürüne dönüştürülmesi', 2, '{\"lat\":38.3529,\"lng\":27.2341}', 'Üretim Tesisi, Manisa, Türkiye', '2025-09-10 11:20:47', '2025-09-20 11:20:47', 240, 12.800000, 320.000, 85.200000, 8.500, 45.00, 25, 28.75, 8.00, 1, 4.00, NULL, NULL, NULL, NULL, '[\"ISO 22000\",\"HACCP\"]', NULL, 'validated', 4.60, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(7, '6135099a-a9c1-11f0-ad49-dcfe0744453b', 2, 4, 'packaging', 'Ambalajlama', 'Ürünlerin çevre dostu ambalaj malzemeleriyle paketlenmesi', 3, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-09-25 11:20:47', '2025-09-30 11:20:47', 120, 3.500000, 45.000, 32.100000, 5.200, 60.00, 12, 26.30, 8.00, 0, 4.50, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(8, '613f0dee-a9c1-11f0-ad49-dcfe0744453b', 2, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 4, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-05 11:20:47', '2025-10-10 11:20:47', 120, 25.600000, 0.000, 45.800000, 1.200, 25.00, 8, 24.50, 8.00, 0, 4.10, 'truck', 450.00, 'diesel', 35.2000, '[\"Green Logistics\"]', NULL, 'validated', 4.30, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(9, '61471308-a9c1-11f0-ad49-dcfe0744453b', 3, 4, 'raw_material', 'Ham Madde Temini', 'Organik tarım uygulamalarıyla üretilmiş ham maddelerin toplanması', 1, '{\"lat\":38.4237,\"lng\":27.1428}', 'Organik Tarım Alanı, Ege Bölgesi, Türkiye', '2025-08-31 11:20:47', '2025-09-05 11:20:47', 120, 5.200000, 150.000, 25.500000, 2.100, 35.00, 15, 25.50, 8.00, 0, 4.20, NULL, NULL, NULL, NULL, '[\"Organic Certification\",\"Fair Trade\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(10, '614df03a-a9c1-11f0-ad49-dcfe0744453b', 3, 4, 'processing', 'İşleme ve Üretim', 'Ham maddelerin işlenerek son ürüne dönüştürülmesi', 2, '{\"lat\":38.3529,\"lng\":27.2341}', 'Üretim Tesisi, Manisa, Türkiye', '2025-09-10 11:20:47', '2025-09-20 11:20:47', 240, 12.800000, 320.000, 85.200000, 8.500, 45.00, 25, 28.75, 8.00, 1, 4.00, NULL, NULL, NULL, NULL, '[\"ISO 22000\",\"HACCP\"]', NULL, 'validated', 4.60, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(11, '61538333-a9c1-11f0-ad49-dcfe0744453b', 3, 4, 'packaging', 'Ambalajlama', 'Ürünlerin çevre dostu ambalaj malzemeleriyle paketlenmesi', 3, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-09-25 11:20:47', '2025-09-30 11:20:47', 120, 3.500000, 45.000, 32.100000, 5.200, 60.00, 12, 26.30, 8.00, 0, 4.50, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(12, '615e34d4-a9c1-11f0-ad49-dcfe0744453b', 3, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 4, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-05 11:20:47', '2025-10-10 11:20:47', 120, 25.600000, 0.000, 45.800000, 1.200, 25.00, 8, 24.50, 8.00, 0, 4.10, 'truck', 450.00, 'diesel', 35.2000, '[\"Green Logistics\"]', NULL, 'validated', 4.30, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(13, '6165777f-a9c1-11f0-ad49-dcfe0744453b', 4, 4, 'raw_material', 'Pamuk Tarımı', 'Organik pamuk tarımı ve hasadı', 1, '{\"lat\":38.4237,\"lng\":27.1428}', 'Organik Pamuk Çiftliği, Ege Bölgesi, Türkiye', '2025-08-16 11:20:47', '2025-08-26 11:20:47', 240, 8.500000, 500.000, 35.200000, 3.200, 30.00, 20, 22.50, 8.00, 0, 4.00, NULL, NULL, NULL, NULL, '[\"Organic Cotton\",\"Fair Trade\"]', NULL, 'validated', 4.70, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(14, '616bf4cb-a9c1-11f0-ad49-dcfe0744453b', 4, 4, 'processing', 'İplik Üretimi', 'Pamuk liflerinin ipliğe dönüştürülmesi', 2, '{\"lat\":41.0082,\"lng\":28.9784}', 'İplik Fabrikası, Istanbul, Türkiye', '2025-08-31 11:20:47', '2025-09-10 11:20:47', 240, 15.200000, 280.000, 95.600000, 12.500, 40.00, 30, 26.75, 8.00, 1, 3.80, NULL, NULL, NULL, NULL, '[\"ISO 14001\",\"Oeko-Tex\"]', NULL, 'validated', 4.50, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(15, '6177279b-a9c1-11f0-ad49-dcfe0744453b', 4, 4, 'manufacturing', 'Dokuma ve Dikim', 'Kumaşın dokunması ve giysinin dikilmesi', 3, '{\"lat\":40.7128,\"lng\":29.9255}', 'Dikim Atölyesi, Kocaeli, Türkiye', '2025-09-15 11:20:47', '2025-09-25 11:20:47', 240, 12.800000, 150.000, 78.300000, 8.700, 50.00, 25, 24.30, 8.00, 0, 4.20, NULL, NULL, NULL, NULL, '[\"WRAP\",\"SA8000\"]', NULL, 'validated', 4.60, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(16, '617f72f9-a9c1-11f0-ad49-dcfe0744453b', 4, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 4, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-09-30 11:20:47', '2025-10-05 11:20:47', 120, 2.500000, 25.000, 18.700000, 3.100, 70.00, 10, 23.50, 8.00, 0, 4.40, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(17, '6187e7c5-a9c1-11f0-ad49-dcfe0744453b', 4, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 5, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-10 11:20:47', '2025-10-15 11:20:47', 120, 22.400000, 0.000, 38.500000, 1.500, 30.00, 8, 25.50, 8.00, 0, 4.10, 'truck', 420.00, 'diesel', 32.8000, '[\"Green Logistics\"]', NULL, 'validated', 4.40, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(18, '6193363d-a9c1-11f0-ad49-dcfe0744453b', 5, 4, 'raw_material', 'Pamuk Tarımı', 'Organik pamuk tarımı ve hasadı', 1, '{\"lat\":38.4237,\"lng\":27.1428}', 'Organik Pamuk Çiftliği, Ege Bölgesi, Türkiye', '2025-08-16 11:20:47', '2025-08-26 11:20:47', 240, 8.500000, 500.000, 35.200000, 3.200, 30.00, 20, 22.50, 8.00, 0, 4.00, NULL, NULL, NULL, NULL, '[\"Organic Cotton\",\"Fair Trade\"]', NULL, 'validated', 4.70, NULL, NULL, NULL, '2025-10-15 12:20:47', '2025-10-15 12:20:47'),
(19, '619f4d51-a9c1-11f0-ad49-dcfe0744453b', 5, 4, 'processing', 'İplik Üretimi', 'Pamuk liflerinin ipliğe dönüştürülmesi', 2, '{\"lat\":41.0082,\"lng\":28.9784}', 'İplik Fabrikası, Istanbul, Türkiye', '2025-08-31 11:20:47', '2025-09-10 11:20:47', 240, 15.200000, 280.000, 95.600000, 12.500, 40.00, 30, 26.75, 8.00, 1, 3.80, NULL, NULL, NULL, NULL, '[\"ISO 14001\",\"Oeko-Tex\"]', NULL, 'validated', 4.50, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(20, '61a9a5fb-a9c1-11f0-ad49-dcfe0744453b', 5, 4, 'manufacturing', 'Dokuma ve Dikim', 'Kumaşın dokunması ve giysinin dikilmesi', 3, '{\"lat\":40.7128,\"lng\":29.9255}', 'Dikim Atölyesi, Kocaeli, Türkiye', '2025-09-15 11:20:47', '2025-09-25 11:20:47', 240, 12.800000, 150.000, 78.300000, 8.700, 50.00, 25, 24.30, 8.00, 0, 4.20, NULL, NULL, NULL, NULL, '[\"WRAP\",\"SA8000\"]', NULL, 'validated', 4.60, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(21, '61b0cc6f-a9c1-11f0-ad49-dcfe0744453b', 5, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 4, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-09-30 11:20:47', '2025-10-05 11:20:47', 120, 2.500000, 25.000, 18.700000, 3.100, 70.00, 10, 23.50, 8.00, 0, 4.40, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(22, '61b9d719-a9c1-11f0-ad49-dcfe0744453b', 5, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 5, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-10 11:20:47', '2025-10-15 11:20:47', 120, 22.400000, 0.000, 38.500000, 1.500, 30.00, 8, 25.50, 8.00, 0, 4.10, 'truck', 420.00, 'diesel', 32.8000, '[\"Green Logistics\"]', NULL, 'validated', 4.40, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(23, '61c0b94a-a9c1-11f0-ad49-dcfe0744453b', 6, 4, 'raw_material', 'Ham Madde Temini', 'Çeşitli metaller ve plastiklerin temini', 1, '{\"lat\":31.2304,\"lng\":121.4737}', 'Ham Madde Tedarikçisi, Shanghai, Çin', '2025-08-26 11:20:48', '2025-08-31 11:20:48', 120, 18.500000, 320.000, 125.600000, 15.800, 25.00, 40, 18.50, 10.00, 2, 3.20, NULL, NULL, NULL, NULL, '[\"ISO 14001\",\"Conflict-Free Minerals\"]', NULL, 'validated', 4.20, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(24, '61cc6e10-a9c1-11f0-ad49-dcfe0744453b', 6, 4, 'manufacturing', 'Üretim', 'Elektronik bileşenlerin montajı ve test edilmesi', 2, '{\"lat\":22.3964,\"lng\":114.1095}', 'Üretim Tesisi, Hong Kong, Çin', '2025-09-05 11:20:48', '2025-09-20 11:20:48', 360, 45.200000, 580.000, 320.800000, 32.500, 35.00, 150, 22.75, 10.00, 3, 3.50, NULL, NULL, NULL, NULL, '[\"ISO 9001\",\"ISO 14001\",\"SA8000\"]', NULL, 'validated', 4.00, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(25, '61d7cfcd-a9c1-11f0-ad49-dcfe0744453b', 6, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 3, '{\"lat\":39.9042,\"lng\":116.4074}', 'Ambalaj Tesisi, Beijing, Çin', '2025-09-25 11:20:48', '2025-09-30 11:20:48', 120, 8.700000, 65.000, 58.300000, 12.200, 45.00, 25, 20.30, 8.00, 1, 3.80, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.30, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(26, '61dec7bc-a9c1-11f0-ad49-dcfe0744453b', 6, 4, 'logistics', 'Uluslararası Nakliye', 'Ürünlerin Türkiye\'ye deniz yoluyla sevkiyatı', 4, '{\"lat\":40.9937,\"lng\":29.0226}', 'İstanbul Limanı, Türkiye', '2025-10-05 11:20:48', '2025-10-15 11:20:48', 480, 185.600000, 0.000, 0.000000, 3.500, 0.00, 15, 28.50, 12.00, 0, 4.00, 'ship', 9500.00, 'heavy fuel oil', 250.8000, '[\"Green Shipping\"]', NULL, 'validated', 3.80, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(27, '61e849fd-a9c1-11f0-ad49-dcfe0744453b', 7, 4, 'raw_material', 'Ham Madde Temini', 'Çeşitli metaller ve plastiklerin temini', 1, '{\"lat\":31.2304,\"lng\":121.4737}', 'Ham Madde Tedarikçisi, Shanghai, Çin', '2025-08-26 11:20:48', '2025-08-31 11:20:48', 120, 18.500000, 320.000, 125.600000, 15.800, 25.00, 40, 18.50, 10.00, 2, 3.20, NULL, NULL, NULL, NULL, '[\"ISO 14001\",\"Conflict-Free Minerals\"]', NULL, 'validated', 4.20, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(28, '61f002a6-a9c1-11f0-ad49-dcfe0744453b', 7, 4, 'manufacturing', 'Üretim', 'Elektronik bileşenlerin montajı ve test edilmesi', 2, '{\"lat\":22.3964,\"lng\":114.1095}', 'Üretim Tesisi, Hong Kong, Çin', '2025-09-05 11:20:48', '2025-09-20 11:20:48', 360, 45.200000, 580.000, 320.800000, 32.500, 35.00, 150, 22.75, 10.00, 3, 3.50, NULL, NULL, NULL, NULL, '[\"ISO 9001\",\"ISO 14001\",\"SA8000\"]', NULL, 'validated', 4.00, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(29, '61f9b5e8-a9c1-11f0-ad49-dcfe0744453b', 7, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 3, '{\"lat\":39.9042,\"lng\":116.4074}', 'Ambalaj Tesisi, Beijing, Çin', '2025-09-25 11:20:48', '2025-09-30 11:20:48', 120, 8.700000, 65.000, 58.300000, 12.200, 45.00, 25, 20.30, 8.00, 1, 3.80, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.30, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(30, '61fe9a05-a9c1-11f0-ad49-dcfe0744453b', 7, 4, 'logistics', 'Uluslararası Nakliye', 'Ürünlerin Türkiye\'ye deniz yoluyla sevkiyatı', 4, '{\"lat\":40.9937,\"lng\":29.0226}', 'İstanbul Limanı, Türkiye', '2025-10-05 11:20:48', '2025-10-15 11:20:48', 480, 185.600000, 0.000, 0.000000, 3.500, 0.00, 15, 28.50, 12.00, 0, 4.00, 'ship', 9500.00, 'heavy fuel oil', 250.8000, '[\"Green Shipping\"]', NULL, 'validated', 3.80, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(31, '6206bde8-a9c1-11f0-ad49-dcfe0744453b', 8, 4, 'raw_material', 'Doğal Bileşen Temini', 'Organik bitki özlerinin ve doğal hammaddelerin temini', 1, '{\"lat\":36.8969,\"lng\":30.7133}', 'Bitki Yetiştirme Alanı, Antalya, Türkiye', '2025-09-05 11:20:48', '2025-09-10 11:20:48', 120, 3.200000, 280.000, 18.500000, 1.800, 65.00, 12, 24.50, 8.00, 0, 4.50, NULL, NULL, NULL, NULL, '[\"Organic Certification\",\"Fair Trade\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(32, '620ce494-a9c1-11f0-ad49-dcfe0744453b', 8, 4, 'processing', 'Ekstraksiyon ve İşleme', 'Doğal bileşenlerin ekstraksiyonu ve işleme', 2, '{\"lat\":41.0082,\"lng\":28.9784}', 'İşleme Tesisi, Istanbul, Türkiye', '2025-09-15 11:20:48', '2025-09-25 11:20:48', 240, 9.800000, 420.000, 65.200000, 6.500, 55.00, 18, 27.75, 8.00, 0, 4.30, NULL, NULL, NULL, NULL, '[\"ISO 22716\",\"GMP\"]', NULL, 'validated', 4.70, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(33, '621e3864-a9c1-11f0-ad49-dcfe0744453b', 8, 4, 'manufacturing', 'Formülasyon ve Üretim', 'Ürünlerin formülasyonu ve üretimi', 3, '{\"lat\":38.4237,\"lng\":27.1428}', 'Üretim Tesisi, Izmir, Türkiye', '2025-09-27 11:20:48', '2025-10-03 11:20:48', 144, 7.500000, 180.000, 42.800000, 4.200, 60.00, 15, 26.30, 8.00, 0, 4.40, NULL, NULL, NULL, NULL, '[\"ISO 9001\",\"Cruelty Free\",\"Vegan\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(34, '6223e82b-a9c1-11f0-ad49-dcfe0744453b', 8, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 4, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-10-05 11:20:48', '2025-10-10 11:20:48', 120, 2.800000, 35.000, 22.700000, 2.500, 75.00, 10, 25.50, 8.00, 0, 4.60, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(35, '622e16f1-a9c1-11f0-ad49-dcfe0744453b', 8, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 5, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-12 11:20:48', '2025-10-15 11:20:48', 96, 18.600000, 0.000, 32.500000, 1.200, 35.00, 8, 24.50, 8.00, 0, 4.20, 'truck', 450.00, 'diesel', 28.8000, '[\"Green Logistics\"]', NULL, 'validated', 4.50, NULL, NULL, NULL, '2025-10-15 12:20:48', '2025-10-15 12:20:48'),
(36, '6253dac6-a9c1-11f0-ad49-dcfe0744453b', 9, 4, 'raw_material', 'Doğal Bileşen Temini', 'Organik bitki özlerinin ve doğal hammaddelerin temini', 1, '{\"lat\":36.8969,\"lng\":30.7133}', 'Bitki Yetiştirme Alanı, Antalya, Türkiye', '2025-09-05 11:20:49', '2025-09-10 11:20:49', 120, 3.200000, 280.000, 18.500000, 1.800, 65.00, 12, 24.50, 8.00, 0, 4.50, NULL, NULL, NULL, NULL, '[\"Organic Certification\",\"Fair Trade\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:49', '2025-10-15 12:20:49'),
(37, '625d1870-a9c1-11f0-ad49-dcfe0744453b', 9, 4, 'processing', 'Ekstraksiyon ve İşleme', 'Doğal bileşenlerin ekstraksiyonu ve işleme', 2, '{\"lat\":41.0082,\"lng\":28.9784}', 'İşleme Tesisi, Istanbul, Türkiye', '2025-09-15 11:20:49', '2025-09-25 11:20:49', 240, 9.800000, 420.000, 65.200000, 6.500, 55.00, 18, 27.75, 8.00, 0, 4.30, NULL, NULL, NULL, NULL, '[\"ISO 22716\",\"GMP\"]', NULL, 'validated', 4.70, NULL, NULL, NULL, '2025-10-15 12:20:49', '2025-10-15 12:20:49'),
(38, '6262b4fd-a9c1-11f0-ad49-dcfe0744453b', 9, 4, 'manufacturing', 'Formülasyon ve Üretim', 'Ürünlerin formülasyonu ve üretimi', 3, '{\"lat\":38.4237,\"lng\":27.1428}', 'Üretim Tesisi, Izmir, Türkiye', '2025-09-27 11:20:49', '2025-10-03 11:20:49', 144, 7.500000, 180.000, 42.800000, 4.200, 60.00, 15, 26.30, 8.00, 0, 4.40, NULL, NULL, NULL, NULL, '[\"ISO 9001\",\"Cruelty Free\",\"Vegan\"]', NULL, 'validated', 4.80, NULL, NULL, NULL, '2025-10-15 12:20:49', '2025-10-15 12:20:49'),
(39, '626966d0-a9c1-11f0-ad49-dcfe0744453b', 9, 4, 'packaging', 'Ambalajlama', 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi', 4, '{\"lat\":41.0082,\"lng\":28.9784}', 'Ambalaj Tesisi, Istanbul, Türkiye', '2025-10-05 11:20:49', '2025-10-10 11:20:49', 120, 2.800000, 35.000, 22.700000, 2.500, 75.00, 10, 25.50, 8.00, 0, 4.60, NULL, NULL, NULL, NULL, '[\"FSC\",\"Recycled Packaging\"]', NULL, 'validated', 4.90, NULL, NULL, NULL, '2025-10-15 12:20:49', '2025-10-15 12:20:49'),
(40, '6273cf6f-a9c1-11f0-ad49-dcfe0744453b', 9, 4, 'logistics', 'Dağıtım', 'Ürünlerin perakende noktalarına dağıtımı', 5, '{\"lat\":39.9334,\"lng\":32.8597}', 'Dağıtım Merkezi, Ankara, Türkiye', '2025-10-12 11:20:49', '2025-10-15 11:20:49', 96, 18.600000, 0.000, 32.500000, 1.200, 35.00, 8, 24.50, 8.00, 0, 4.20, 'truck', 450.00, 'diesel', 28.8000, '[\"Green Logistics\"]', NULL, 'validated', 4.50, NULL, NULL, NULL, '2025-10-15 12:20:49', '2025-10-15 12:20:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_type` enum('admin','company','validator','consumer') NOT NULL,
  `status` enum('active','inactive','suspended','pending') DEFAULT 'pending',
  `email_verified` tinyint(1) DEFAULT 0,
  `profile_image` varchar(255) DEFAULT NULL,
  `language` char(2) DEFAULT 'tr',
  `timezone` varchar(50) DEFAULT 'Europe/Istanbul',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `uuid`, `email`, `password_hash`, `first_name`, `last_name`, `phone`, `user_type`, `status`, `email_verified`, `profile_image`, `language`, `timezone`, `created_at`, `updated_at`, `last_login`) VALUES
(19, 'c30c8938-a607-11f0-9b30-dcfe0744453b', 'test_consumer@example.com', '$2y$10$zqre24Iw7sa0eFCmSpQToOK3AMq9AUDQ6gGbGbgBEkdQyiABSdrQC', 'Test', 'Consumer', NULL, 'consumer', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-10 18:34:31', '2025-10-10 18:34:32', '2025-10-10 18:34:32'),
(20, '34f78c18-a8dd-11f0-8c23-dcfe0744453b', 'admin@kuresaletzinciri.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', NULL, 'admin', 'active', 1, NULL, 'tr', 'Europe/Istanbul', '2025-10-14 09:07:27', '2025-10-29 17:22:55', '2025-10-29 17:22:55'),
(21, '353c2a7b-a8dd-11f0-8c23-dcfe0744453b', 'test@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Company', NULL, 'company', 'active', 1, NULL, 'tr', 'Europe/Istanbul', '2025-10-14 09:07:27', '2025-10-29 17:36:56', '2025-10-29 17:36:56'),
(22, '354425c4-a8dd-11f0-8c23-dcfe0744453b', 'test@validator.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Validator', NULL, 'validator', 'active', 1, NULL, 'tr', 'Europe/Istanbul', '2025-10-14 09:07:28', '2025-10-25 16:57:54', '2025-10-25 16:57:54'),
(26, '3aae1200-a8fe-11f0-8c23-dcfe0744453b', 'test_1760447030@example.com', '$2y$10$tMVrXKr6tw8Xmmw.FZ1B6evpU5Ky0HGj2pqk924j2H/OZBLGiGUAC', 'Test', 'User', '+905551234567', 'consumer', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-14 13:03:50', '2025-10-14 13:03:50', NULL),
(27, '69d42eda-a8fe-11f0-8c23-dcfe0744453b', 'enes@gmail.com', '$2y$10$vd917gLlB7FfbkBFz.E19Otqug0mNbF0dqHdD/s23FCwByMs/fKIO', 'Enes', 'çekin', '+905314936342', 'consumer', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-14 13:05:09', '2025-10-29 17:46:24', '2025-10-29 17:37:19'),
(28, '97810370-b035-11f0-b45f-dcfe0744453b', 'jsmith@sample.com', '$2y$10$/qQn3O4XqKd2Kywpp8/vseQ7v2300VtXSuODC16v9AoOyB2R3C6ky', 'Enes', 'çekin', '058865558552', 'validator', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-23 17:27:47', '2025-10-28 13:31:52', '2025-10-28 13:31:52'),
(29, '47490e29-b1cb-11f0-a816-dcfe0744453b', 'kolayildiz@gmail.com', '$2y$10$mYoIIBpQYh2KJ58IVVRbgurf7wjnkGVNivWXLwBYaAbdnhhi3vpI.', 'Koray', 'Yıldız', '0548565555', 'company', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-25 17:51:48', '2025-10-29 16:17:10', '2025-10-29 16:17:10'),
(30, '51d51743-b4ed-11f0-869d-dcfe0744453b', 'ahmet@gmail.com', '$2y$10$3kNElYoRNDbrZllg5KrjnexA93i1SaLhM4BLHaWCFmJkC73Y8zxw6', 'Ahmet', 'yıldız ', '558522663', 'consumer', 'active', 0, NULL, 'tr', 'Europe/Istanbul', '2025-10-29 17:33:02', '2025-10-29 17:33:02', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_2fa`
--

CREATE TABLE `user_2fa` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `backup_codes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`backup_codes`)),
  `is_enabled` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_devices`
--

CREATE TABLE `user_devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `device_os` varchar(50) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `user_devices`
--

INSERT INTO `user_devices` (`id`, `user_id`, `device_name`, `device_type`, `device_os`, `browser`, `ip_address`, `session_id`, `is_current`, `last_activity`, `created_at`) VALUES
(1, 27, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'qcj7u9buils4g5vjggch9gjbcf', 0, '2025-10-14 13:48:14', '2025-10-14 13:48:13'),
(2, 27, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'ap5hvv853jeh1ap36r7itt3rs5', 0, '2025-10-14 15:55:57', '2025-10-14 15:55:56'),
(3, 21, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', '5o3p35dr5752dsskpvhqsdu6gi', 0, '2025-10-23 17:41:50', '2025-10-23 17:41:50'),
(4, 22, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'm0vpjg4minkppq6opin4oespd2', 0, '2025-10-23 17:42:13', '2025-10-23 17:42:13'),
(5, 22, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'e3dhhnu3mgrmg2si8611s362n2', 0, '2025-10-23 17:43:26', '2025-10-23 17:43:26'),
(6, 22, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'slbq92ujfg6oi600lgnbu9qknn', 0, '2025-10-23 18:00:18', '2025-10-23 18:00:18'),
(7, 28, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', '30kf6fdqqr6343o36c4rinfcrs', 0, '2025-10-23 18:00:36', '2025-10-23 18:00:36'),
(8, 22, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 't29f596hpg1tp5avfeb8s643pv', 0, '2025-10-23 18:08:49', '2025-10-23 18:08:49'),
(9, 28, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'f6ahdi5suj1rp1l8oib3lcctee', 0, '2025-10-23 18:09:02', '2025-10-23 18:09:02'),
(10, 22, 'Windows Desktop', 'Desktop', 'Windows', 'Chrome', '::1', 'urqdjk84d05sam4nhejdqgoaa4', 0, '2025-10-23 18:34:42', '2025-10-23 18:34:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preference_key` varchar(100) NOT NULL,
  `preference_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `preference_key`, `preference_value`, `created_at`, `updated_at`) VALUES
(37, 27, 'email_notifications', 'false', '2025-10-14 13:58:07', '2025-10-23 13:57:30'),
(38, 27, 'sms_notifications', 'false', '2025-10-14 13:58:07', '2025-10-23 13:57:30'),
(39, 27, 'marketing_emails', 'false', '2025-10-14 13:58:07', '2025-10-23 13:57:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `type` enum('remember_me','password_reset','email_verification') NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `validation_records`
--

CREATE TABLE `validation_records` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `supply_chain_step_id` int(11) NOT NULL,
  `validator_id` int(11) NOT NULL,
  `validation_type` enum('document','field_visit','third_party_audit','blockchain_verification') NOT NULL,
  `validation_method` varchar(100) DEFAULT NULL,
  `validation_criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`validation_criteria`)),
  `validation_result` enum('approved','rejected','needs_clarification') NOT NULL,
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `findings` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `evidence_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evidence_documents`)),
  `evidence_photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evidence_photos`)),
  `evidence_blockchain_refs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evidence_blockchain_refs`)),
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `response_time_hours` int(11) DEFAULT NULL,
  `validation_fee` decimal(18,8) DEFAULT NULL,
  `reward_amount` decimal(18,8) DEFAULT NULL,
  `stake_amount` decimal(18,8) DEFAULT NULL,
  `blockchain_hash` varchar(66) DEFAULT NULL,
  `status` enum('pending','in_progress','completed','disputed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `validation_records`
--

INSERT INTO `validation_records` (`id`, `uuid`, `supply_chain_step_id`, `validator_id`, `validation_type`, `validation_method`, `validation_criteria`, `validation_result`, `confidence_score`, `findings`, `recommendations`, `evidence_documents`, `evidence_photos`, `evidence_blockchain_refs`, `requested_at`, `started_at`, `completed_at`, `response_time_hours`, `validation_fee`, `reward_amount`, `stake_amount`, `blockchain_hash`, `status`, `created_at`, `updated_at`) VALUES
(1, '8731fb60-b03a-11f0-b45f-dcfe0744453b', 1, 2, 'third_party_audit', NULL, NULL, 'approved', NULL, 'Sample validation for Organik Zeytinyağı from Test Food Company', NULL, NULL, NULL, NULL, '2025-10-23 18:03:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:03:07', '2025-10-23 18:03:07'),
(2, '87389df2-b03a-11f0-b45f-dcfe0744453b', 2, 2, 'document', NULL, NULL, 'approved', NULL, 'Sample validation for Organik Zeytinyağı from Test Food Company', NULL, NULL, NULL, NULL, '2025-10-23 18:03:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:03:07', '2025-10-23 18:03:07'),
(3, '874ddeb5-b03a-11f0-b45f-dcfe0744453b', 3, 2, 'field_visit', NULL, NULL, 'rejected', NULL, 'Sample validation for Organik Zeytinyağı from Test Food Company', NULL, NULL, NULL, NULL, '2025-10-23 18:03:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:03:07', '2025-10-23 18:03:07'),
(4, '875660ae-b03a-11f0-b45f-dcfe0744453b', 4, 2, 'field_visit', NULL, NULL, 'rejected', NULL, 'Sample validation for Organik Zeytinyağı from Test Food Company', NULL, NULL, NULL, NULL, '2025-10-23 18:03:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:03:07', '2025-10-23 18:03:07'),
(5, '875d4c34-b03a-11f0-b45f-dcfe0744453b', 5, 2, 'document', NULL, NULL, 'rejected', NULL, 'Sample validation for Bal from Test Food Company', NULL, NULL, NULL, NULL, '2025-10-23 18:03:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:03:07', '2025-10-23 18:03:07'),
(6, '2e62c695-b040-11f0-b45f-dcfe0744453b', 36, 2, 'third_party_audit', NULL, NULL, 'rejected', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:43:35', '2025-10-23 18:43:35'),
(7, '2e6c3bc1-b040-11f0-b45f-dcfe0744453b', 31, 2, 'field_visit', NULL, NULL, 'approved', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:43:35', '2025-10-23 18:43:35'),
(8, '2e89972b-b040-11f0-b45f-dcfe0744453b', 23, 2, 'third_party_audit', NULL, NULL, 'approved', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:43:35', '2025-10-23 18:43:35'),
(9, '2e92a004-b040-11f0-b45f-dcfe0744453b', 18, 2, 'field_visit', NULL, NULL, 'approved', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:43:35', '2025-10-23 18:43:35'),
(10, '2e9a9d99-b040-11f0-b45f-dcfe0744453b', 27, 2, 'document', NULL, NULL, 'approved', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:43:35', '2025-10-23 18:43:35'),
(11, '75fb83ce-b040-11f0-b45f-dcfe0744453b', 9, 2, 'field_visit', NULL, NULL, '', NULL, 'Pending validation for review', NULL, NULL, NULL, NULL, '2025-10-23 18:45:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-23 18:45:35', '2025-10-23 18:45:35'),
(12, '8ff22c64-b040-11f0-b45f-dcfe0744453b', 13, 2, 'third_party_audit', NULL, NULL, 'rejected', NULL, 'Sample validation record for testing purposes', NULL, NULL, NULL, NULL, '2025-10-23 18:46:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', '2025-10-23 18:46:18', '2025-10-23 18:46:18'),
(13, '9da46388-b1b6-11f0-a816-dcfe0744453b', 1, 2, 'document', NULL, NULL, 'approved', NULL, 'All documents verified and compliant with standards.', NULL, NULL, NULL, NULL, '2025-10-20 14:23:53', NULL, '2025-10-21 14:23:53', 24, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:23:53', '2025-10-25 15:23:53'),
(14, '9dbdcadb-b1b6-11f0-a816-dcfe0744453b', 5, 2, 'field_visit', NULL, NULL, 'approved', NULL, 'Facility inspection completed. All safety protocols followed.', NULL, NULL, NULL, NULL, '2025-10-22 14:23:53', NULL, '2025-10-23 14:23:53', 18, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:23:53', '2025-10-25 15:23:53'),
(15, '9dcae781-b1b6-11f0-a816-dcfe0744453b', 9, 2, 'third_party_audit', NULL, NULL, 'rejected', NULL, 'Non-compliance with labor standards found. Immediate corrective actions required.', NULL, NULL, NULL, NULL, '2025-10-23 14:23:53', NULL, '2025-10-24 14:23:53', 36, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:23:53', '2025-10-25 15:23:53'),
(16, '9ddb61ec-b1b6-11f0-a816-dcfe0744453b', 13, 2, 'document', NULL, NULL, 'approved', NULL, 'Documentation review complete. All certificates valid.', NULL, NULL, NULL, NULL, '2025-10-24 14:23:53', NULL, '2025-10-25 14:23:53', 12, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:23:53', '2025-10-25 15:23:53'),
(17, '9df0af47-b1b6-11f0-a816-dcfe0744453b', 18, 2, 'field_visit', NULL, NULL, 'needs_clarification', NULL, 'Additional information required regarding waste management procedures.', NULL, NULL, NULL, NULL, '2025-10-25 08:23:53', NULL, '2025-10-25 14:23:53', 6, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:23:54', '2025-10-25 15:23:54'),
(18, '9e12cd0b-b1b6-11f0-a816-dcfe0744453b', 1, 2, 'document', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 14:23:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:23:54', '2025-10-25 15:23:54'),
(19, '9e4c87a0-b1b6-11f0-a816-dcfe0744453b', 5, 2, 'field_visit', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 13:23:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:23:54', '2025-10-25 15:23:54'),
(20, '9e5bd952-b1b6-11f0-a816-dcfe0744453b', 9, 2, 'third_party_audit', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 13:53:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:23:54', '2025-10-25 15:23:54'),
(21, 'f90eb952-b1b6-11f0-a816-dcfe0744453b', 1, 2, 'document', NULL, NULL, 'approved', NULL, 'All documents verified and compliant with standards.', NULL, NULL, NULL, NULL, '2025-10-20 14:26:26', NULL, '2025-10-21 14:26:26', 24, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:26:26', '2025-10-25 15:26:26'),
(22, 'f92d3670-b1b6-11f0-a816-dcfe0744453b', 5, 2, 'field_visit', NULL, NULL, 'approved', NULL, 'Facility inspection completed. All safety protocols followed.', NULL, NULL, NULL, NULL, '2025-10-22 14:26:26', NULL, '2025-10-23 14:26:26', 18, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:26:27', '2025-10-25 15:26:27'),
(23, 'f93e214f-b1b6-11f0-a816-dcfe0744453b', 9, 2, 'third_party_audit', NULL, NULL, 'rejected', NULL, 'Non-compliance with labor standards found. Immediate corrective actions required.', NULL, NULL, NULL, NULL, '2025-10-23 14:26:26', NULL, '2025-10-24 14:26:26', 36, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:26:27', '2025-10-25 15:26:27'),
(24, 'f957a184-b1b6-11f0-a816-dcfe0744453b', 13, 2, 'document', NULL, NULL, 'approved', NULL, 'Documentation review complete. All certificates valid.', NULL, NULL, NULL, NULL, '2025-10-24 14:26:26', NULL, '2025-10-25 14:26:26', 12, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:26:27', '2025-10-25 15:26:27'),
(25, 'f9a89e7c-b1b6-11f0-a816-dcfe0744453b', 18, 2, 'field_visit', NULL, NULL, 'needs_clarification', NULL, 'Additional information required regarding waste management procedures.', NULL, NULL, NULL, NULL, '2025-10-25 08:26:26', NULL, '2025-10-25 14:26:26', 6, NULL, NULL, NULL, NULL, 'completed', '2025-10-25 15:26:28', '2025-10-25 15:26:28'),
(26, 'f9d6802d-b1b6-11f0-a816-dcfe0744453b', 1, 2, 'document', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 14:26:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:26:28', '2025-10-25 15:26:28'),
(27, 'f9e7b3c3-b1b6-11f0-a816-dcfe0744453b', 5, 2, 'field_visit', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 13:26:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:26:28', '2025-10-25 15:26:28'),
(28, 'f9f6fcfb-b1b6-11f0-a816-dcfe0744453b', 9, 2, 'third_party_audit', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 13:56:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 15:26:28', '2025-10-25 15:26:28'),
(29, 'validation_68fd0308ab7992.21705455', 1, 1, 'document', 'Document Review', '{\"organic_cert_required\":true,\"pesticide_test_required\":true}', '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 16:04:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 17:04:08', '2025-10-25 17:04:08'),
(30, 'validation_68fd0308bf2129.85400671', 2, 1, 'field_visit', 'On-site Inspection', '{\"processing_standards\":\"ISO 22000\",\"worker_safety_protocols\":true}', '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-22 16:04:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 17:04:08', '2025-10-25 17:04:08'),
(31, 'validation_68fd0308d509f2.17269996', 3, 1, 'document', 'Quality Testing', '{\"material_composition\":\"100% organic cotton\",\"color_fastness\":\"Grade A\"}', '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-22 16:04:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-10-25 17:04:08', '2025-10-25 17:04:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `validators`
--

CREATE TABLE `validators` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `user_id` int(11) NOT NULL,
  `validator_name` varchar(255) NOT NULL,
  `organization_type` enum('ngo','certification_body','audit_firm','government','independent') NOT NULL,
  `specialization` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specialization`)),
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`credentials`)),
  `service_regions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`service_regions`)),
  `reputation_score` decimal(5,2) DEFAULT 0.00,
  `total_validations` int(11) DEFAULT 0,
  `successful_validations` int(11) DEFAULT 0,
  `experience_years` int(11) DEFAULT 0,
  `average_response_time` int(11) DEFAULT 0,
  `token_balance` decimal(18,8) DEFAULT 0.00000000,
  `stake_amount` decimal(18,8) DEFAULT 0.00000000,
  `blockchain_address` varchar(42) DEFAULT NULL,
  `status` enum('active','inactive','suspended','under_review') DEFAULT 'under_review',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `validators`
--

INSERT INTO `validators` (`id`, `uuid`, `user_id`, `validator_name`, `organization_type`, `specialization`, `credentials`, `service_regions`, `reputation_score`, `total_validations`, `successful_validations`, `experience_years`, `average_response_time`, `token_balance`, `stake_amount`, `blockchain_address`, `status`, `created_at`, `updated_at`) VALUES
(1, '97930acc-b035-11f0-b45f-dcfe0744453b', 28, 'Örnek Denetim Şirketi', 'audit_firm', NULL, NULL, NULL, 0.00, 0, 0, 0, 0, 0.00000000, 0.00000000, NULL, 'under_review', '2025-10-23 17:27:47', '2025-10-23 17:27:47'),
(2, '1005fc44-b037-11f0-b45f-dcfe0744453b', 22, 'Demo Validation Organization', 'certification_body', NULL, NULL, NULL, 0.00, 28, 12, 0, 19, 0.00000000, 0.00000000, NULL, 'active', '2025-10-23 17:38:18', '2025-10-25 15:26:28');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Tablo için indeksler `blockchain_records`
--
ALTER TABLE `blockchain_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `transaction_hash` (`transaction_hash`),
  ADD KEY `idx_transaction_hash` (`transaction_hash`),
  ADD KEY `idx_record_type` (`record_type`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_confirmation_status` (`confirmation_status`);

--
-- Tablo için indeksler `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_company_type` (`company_type`),
  ADD KEY `idx_industry_sector` (`industry_sector`),
  ADD KEY `idx_transparency_score` (`transparency_score`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_company_transparency` (`transparency_score`,`status`);

--
-- Tablo için indeksler `impact_scores`
--
ALTER TABLE `impact_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `product_batch_id` (`product_batch_id`),
  ADD KEY `idx_overall_grade` (`overall_grade`),
  ADD KEY `idx_overall_score` (`overall_score`),
  ADD KEY `idx_environmental_score` (`environmental_score`),
  ADD KEY `idx_social_score` (`social_score`),
  ADD KEY `idx_transparency_score` (`transparency_score`),
  ADD KEY `idx_impact_score_grade_date` (`overall_grade`,`calculation_date`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `idx_product_code` (`product_code`),
  ADD KEY `idx_barcode` (`barcode`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_company_id` (`company_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_product_company_category` (`company_id`,`category`,`status`);

--
-- Tablo için indeksler `product_batches`
--
ALTER TABLE `product_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `unique_batch_product` (`product_id`,`batch_number`),
  ADD KEY `idx_batch_number` (`batch_number`),
  ADD KEY `idx_production_date` (`production_date`),
  ADD KEY `idx_status` (`status`);

--
-- Tablo için indeksler `scheduled_notifications`
--
ALTER TABLE `scheduled_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_notification_type` (`notification_type`),
  ADD KEY `idx_next_send` (`next_send`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Tablo için indeksler `supply_chain_steps`
--
ALTER TABLE `supply_chain_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `idx_step_type` (`step_type`),
  ADD KEY `idx_validation_status` (`validation_status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_supply_chain_batch_order` (`product_batch_id`,`step_order`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_status` (`status`);

--
-- Tablo için indeksler `user_2fa`
--
ALTER TABLE `user_2fa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_2fa` (`user_id`);

--
-- Tablo için indeksler `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_session` (`user_id`,`session_id`),
  ADD KEY `idx_last_activity` (`last_activity`);

--
-- Tablo için indeksler `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_preference` (`user_id`,`preference_key`),
  ADD KEY `idx_user_key` (`user_id`,`preference_key`);

--
-- Tablo için indeksler `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_type` (`user_id`,`type`),
  ADD KEY `idx_user_type` (`user_id`,`type`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Tablo için indeksler `validation_records`
--
ALTER TABLE `validation_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `supply_chain_step_id` (`supply_chain_step_id`),
  ADD KEY `idx_validation_type` (`validation_type`),
  ADD KEY `idx_validation_result` (`validation_result`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_requested_at` (`requested_at`),
  ADD KEY `idx_validation_validator_status` (`validator_id`,`status`,`requested_at`);

--
-- Tablo için indeksler `validators`
--
ALTER TABLE `validators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_organization_type` (`organization_type`),
  ADD KEY `idx_reputation_score` (`reputation_score`),
  ADD KEY `idx_status` (`status`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- Tablo için AUTO_INCREMENT değeri `blockchain_records`
--
ALTER TABLE `blockchain_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `impact_scores`
--
ALTER TABLE `impact_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `product_batches`
--
ALTER TABLE `product_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `scheduled_notifications`
--
ALTER TABLE `scheduled_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `supply_chain_steps`
--
ALTER TABLE `supply_chain_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `user_2fa`
--
ALTER TABLE `user_2fa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Tablo için AUTO_INCREMENT değeri `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Tablo için AUTO_INCREMENT değeri `validation_records`
--
ALTER TABLE `validation_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `validators`
--
ALTER TABLE `validators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `impact_scores`
--
ALTER TABLE `impact_scores`
  ADD CONSTRAINT `impact_scores_ibfk_1` FOREIGN KEY (`product_batch_id`) REFERENCES `product_batches` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_batches`
--
ALTER TABLE `product_batches`
  ADD CONSTRAINT `product_batches_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `scheduled_notifications`
--
ALTER TABLE `scheduled_notifications`
  ADD CONSTRAINT `scheduled_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `supply_chain_steps`
--
ALTER TABLE `supply_chain_steps`
  ADD CONSTRAINT `supply_chain_steps_ibfk_1` FOREIGN KEY (`product_batch_id`) REFERENCES `product_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supply_chain_steps_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_2fa`
--
ALTER TABLE `user_2fa`
  ADD CONSTRAINT `user_2fa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `validation_records`
--
ALTER TABLE `validation_records`
  ADD CONSTRAINT `validation_records_ibfk_1` FOREIGN KEY (`supply_chain_step_id`) REFERENCES `supply_chain_steps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `validation_records_ibfk_2` FOREIGN KEY (`validator_id`) REFERENCES `validators` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `validators`
--
ALTER TABLE `validators`
  ADD CONSTRAINT `validators_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
