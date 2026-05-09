-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 09, 2026 at 06:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phonebook_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `name`, `phone`, `email`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'doraemon', '012345667', 'mama@gmail.com', 'default.png', '2026-05-09 12:27:18', '2026-05-09 13:20:04'),
(2, 1, 'xiaomaomi', '0123456789', 'abcd@gmail.com', 'default.png', '2026-05-09 12:27:48', '2026-05-09 13:12:04'),
(4, 2, 'Ressie Lindgren', '425-802-5015', 'moshe25@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(5, 2, 'Nella Gusikowski', '385-373-6827', 'haley69@example.org', '1778341001_e1e5fd16d094b5aaf0e3.jpg', '2026-05-09 13:50:15', '2026-05-09 15:36:41'),
(6, 2, 'Mrs. Zola Windler III', '+1-540-856-4406', 'junius.leuschke@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(7, 2, 'Lesly Strosin', '405.652.2873', 'conn.flossie@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(8, 2, 'Arvel Cole DVM', '+1-678-478-3271', 'rosamond64@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(9, 2, 'Lila Schowalter', '1-406-335-8266', 'heidi.hintz@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(10, 2, 'Doris Powlowski', '1-754-573-9409', 'muriel.white@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(11, 2, 'Camylle Mosciski', '+1-551-524-1563', 'allie.gislason@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(12, 2, 'Thurman Gulgowski', '+1-218-468-3865', 'eokeefe@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(13, 2, 'Mr. Gayle Adams', '920.737.0950', 'tillman.meggie@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(14, 2, 'Clifford Stoltenberg Jr.', '(828) 989-5815', 'nkeeling@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(15, 2, 'Dock Lind', '872-700-3119', 'maria.hyatt@example.org', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(16, 2, 'Miss Sarah Nienow', '629-731-7239', 'kaleigh73@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(17, 2, 'Ryleigh Flatley', '+15397814092', 'schaden.darren@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(18, 2, 'Dashawn Wilderman', '+1-385-919-5565', 'jwisozk@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(19, 2, 'Fanny Nienow PhD', '(256) 232-1246', 'scottie43@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(20, 2, 'Prof. Isobel Reichel', '(848) 590-7155', 'vivianne.bartell@example.com', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(21, 2, 'Ms. Gisselle Gutkowski DDS', '+1-571-661-8175', 'xschmeler@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(22, 2, 'Colten Bartell DDS', '689-372-0994', 'bflatley@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(23, 2, 'Prof. Makenna Marquardt', '+1-808-333-9189', 'sigrid.hermann@example.net', 'default.png', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(24, 1, 'abcd', '0123456f', 'abcd@gmail.com', 'default.png', '2026-05-09 13:50:52', '2026-05-09 13:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-05-09-111822', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1778326490, 1),
(2, '2026-05-09-113415', 'App\\Database\\Migrations\\CreateContactsTable', 'default', 'App', 1778326490, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'chongzc1234', '$2y$12$.yBH5XOk0LtedoiFkTU2Ke1E4DbDCz1pLvFD/XDZEawNyBuydBBcW', '2026-05-09 12:17:52', '2026-05-09 12:17:52'),
(2, 'testadmin', '$2y$12$xkk61l.ZTs1om5vnMvn9o.iLVUM4wygyedQ8PHlmhqO4z1vqJC6Wi', '2026-05-09 13:50:15', '2026-05-09 13:50:15'),
(3, 'testadmin1', '$2y$12$0cRutLhwSxh6Zh1f1FbsHeTJnJg9Su.n9t4jtDekBdfmGX18QC2Em', '2026-05-09 13:53:26', '2026-05-09 13:53:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
