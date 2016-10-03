-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 03, 2016 at 08:03 AM
-- Server version: 5.7.15-0ubuntu0.16.04.1
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asterisk`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `channel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conference` int(10) DEFAULT NULL,
  `mutte` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'yes',
  `callerid` int(10) NOT NULL,
  `video` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `channel`, `conference`, `mutte`, `callerid`, `video`) VALUES
(5, 'admin', 'SIP/SOE_193-0000008a', 0, 'no', 193, 'yes'),
(6, '490', NULL, NULL, 'yes', 897490, 'yes'),
(7, '894220', 'SIP/894220-00000026', 0, 'no', 894220, 'yes'),
(8, '894016', 'SIP/894016-00000017', 0, 'no', 894016, 'yes'),
(9, '894351', 'SIP/894351-00000310', 0, 'no', 894351, 'yes'),
(10, '894353', 'SIP/894353-00000013', 0, 'no', 894353, 'yes'),
(11, '894490', 'SIP/894490-00000082', 0, 'no', 894490, 'yes'),
(12, 'Белополье РЭС ', NULL, NULL, 'yes', 813350, 'yes'),
(13, 'Белополье РОЭ', NULL, NULL, 'yes', 813450, 'yes'),
(14, 'Бурынь РЭС', NULL, NULL, 'yes', 814360, 'yes'),
(15, 'Бурынь РОЭ', NULL, NULL, 'yes', 814460, 'yes'),
(16, 'Глухов РЭС', NULL, NULL, 'yes', 815350, 'yes'),
(17, 'Конотоп РЭС', NULL, NULL, 'yes', 827870, 'yes'),
(18, 'Конотоп РОЭ', NULL, NULL, 'yes', 827466, 'yes'),
(19, 'Краснополье РЭС', NULL, NULL, 'yes', 817360, 'yes'),
(20, 'Кровец РЭС', NULL, NULL, 'yes', 818361, 'yes'),
(21, 'Лебедин РЭС', NULL, NULL, 'yes', 829360, 'yes'),
(22, 'Лебедин РОЭ', NULL, NULL, 'yes', 829460, 'yes'),
(23, 'Недрыгайлов РЭС', NULL, NULL, 'yes', 819350, 'yes'),
(24, 'Ахтырка РЭС', NULL, NULL, 'yes', 832350, 'yes'),
(25, 'Ахтырка РОЭ', NULL, NULL, 'yes', 832440, 'yes'),
(26, 'В. Писаревка РЭС', NULL, NULL, 'yes', 831250, 'yes'),
(27, 'Путивль РЭС', NULL, NULL, 'yes', 820158, 'yes'),
(28, 'Ромны РЭС', NULL, NULL, 'yes', 821353, 'yes'),
(29, 'Л. Долина РЭС', NULL, NULL, 'yes', 828250, 'yes'),
(30, 'Сумы РТЕС', NULL, NULL, 'yes', 82215350, 'yes'),
(31, 'Сумы РЭС', NULL, NULL, 'yes', 82315350, 'yes'),
(32, 'Сумы МРОЭ', NULL, NULL, 'yes', 15426, 'yes'),
(33, 'Тростянец РЭС', NULL, NULL, 'yes', 824360, 'yes'),
(34, 'Шостка РЭС', NULL, NULL, 'yes', 825341, 'yes'),
(35, 'С. Буда РЭС', NULL, NULL, 'yes', 836361, 'yes'),
(36, 'Ямполь РЭС', NULL, NULL, 'yes', 835361, 'yes'),
(37, 'test', NULL, NULL, 'yes', 312, 'yes'),
(38, '894018', 'SIP/894018-00000021', 0, 'no', 894018, 'yes'),
(39, '894010', 'SIP/894010-00000083', 0, 'no', 894010, 'yes'),
(40, '323', 'SIP/SOE_193-000003a5', 0, 'no', 323, 'yes'),
(41, '888312', 'SIP/SOE_193-000003a6', 0, 'no', 888312, 'yes'),
(42, '213', 'SIP/SOE_193-0000008d', 0, 'yes', 213, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1465806856),
('m130524_201442_init', 1465806873);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'QJtORypnY2RPPk3dKRHO1u8FQhWupuOL', '$2y$13$JL/sQXIJ3rvkGQ8vMRULFOJfaUxJ5M5KKnWRQN5gqpEqb.honRmt2', NULL, 'mail@admin.com', 10, 1465807564, 1465807564);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
