-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2025 at 05:45 PM
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
-- Database: `agencia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Nf4rt3tsNsHdRwsjMrPii.Dh5DtcPxqS/lBLnOs64HvBTpte0YTCO'),
(2, 'nick_rangel', ''),
(3, 'admin3', '$2y$10$K0pJDfpNFRMKgbD.eAmBdO63//DkybjeIZKS42jzqZLP.LmYG5PK6');

-- --------------------------------------------------------

--
-- Table structure for table `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  `img_name` varchar(128) NOT NULL,
  `year` int(11) NOT NULL,
  `used` tinyint(1) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carros`
--

INSERT INTO `carros` (`id`, `name`, `price`, `type`, `featured`, `description`, `img_name`, `year`, `used`, `deleted`) VALUES
(1, 'Corvette C8', 56000, 'sport', 1, 'The 2025 Chevrolet Corvette C8 continues the mid-engine platform, with updates including new colors, interior schemes, and a redesigned Z51 spoiler.', 'corvette_c8', 2025, 0, 0),
(2, 'Chevrolet Malibu', 32000, 'sedan', 1, 'The 2025 Malibu remains a symbol of refinement in the midsize sedan category. For comfort and influential style at an affordable price — Malibu delivers.', 'chevy_malibu', 2025, 0, 0),
(3, 'Honda Civic LX', 5000, 'coupe', 0, 'The 2010 Honda Civic LX Coupe is a compact, fuel-efficient, and reliable two-door coupe known for its good handling and a comfortable ride. ', 'civic_lx_coupe_2010', 2010, 1, 0),
(4, 'Chevrolet Aveo', 1, 'hatchback', 0, 'Used by the #1 volleyball player in the Universidad Cuauhtemoc', 'chevy_aveo', 2017, 1, 0),
(5, 'Nissan Tsuru', 7000, 'sedan', 0, 'Nissan Tsuru, el icónico vehículo que durante más de tres décadas ha brindado movilidad accesible, económica y confiable a millones de mexicanos', 'nissan_tsuru', 2016, 1, 0),
(6, 'Toyota Sera', 34000, 'sport', 1, 'El Toyota Sera es un deportivo compacto que combina un diseño futurista con una experiencia de conducción emocionante. Con sus icónicas puertas de ala de gaviota y un motor ágil, ofrece una conducción dinámica y cómoda.', 'toyota_sera', 1993, 0, 0),
(7, 'Mazda Autozam AZ-1', 20000, 'sport', 1, 'The Autozam AZ-1, known by the framecode PG6SA, is a mid-engined kei-class sports car, designed and manufactured by Mazda under its Autozam brand. Suzuki provided the engine as well as the inspiration for the design.', 'mazda_autozam', 1992, 0, 0),
(8, 'Pegassi Infernus', 230000, 'sport', 1, 'The performance of the Infernus remains similar in all of its appearances by being one of, if not the fastest car in each game. Unlike subsequent depictions that have an all-wheel-drive system, the Infernus in GTA III has a rear-wheel-drive layout.\r\n\r\nDespite the change in drivetrain layout, its handling remains similar and its all-wheel-drive system means the car has good front grip.', 'pegassi_infernus', 2023, 0, 0),
(9, 'Volkswagen Golf GTI 3', 62000, 'coupe', 1, 'Its cool i think idk tho ', 'gti', 2025, 0, 0),
(10, 'Chevrolet Express Cargo', 10, 'van', 0, 'coolo\r\n', 'chevy_van', 2025, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citas`
--

INSERT INTO `citas` (`id`, `datetime`, `client_name`, `phone`, `id_car`, `id_employee`, `deleted`) VALUES
(1, '2025-04-04 11:00:00', 'Johnny', 0, 6, 1, 0),
(2, '2025-04-25 10:00:00', 'brah idk', 0, 6, 1, 0),
(3, '2025-04-30 14:00:00', 'Miguel Angel', 4491234567, 6, 8, 0),
(4, '2025-05-23 12:00:00', 'Kadir Koprulu Hernandez', 4497510546, 8, 1, 0),
(5, '2025-05-30 13:00:00', 'Ricardo ', 4495714543, 7, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` int(11) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `level`, `rfc`, `phone`, `password`, `deleted`) VALUES
(1, 'Bob Johnny', 0, 'abcdefghijklm', 4495810546, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(2, 'Alice Johnson', 1, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(3, 'Bob Smith', 2, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(4, 'Charlie Brown', 3, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(5, 'Diana Evans', 1, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(6, 'Ethan Foster', 2, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(7, 'Fiona Green', 3, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(8, 'George Harris', 1, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(9, 'Hannah White', 2, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(10, 'Ian King', 3, '', 0, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0),
(11, 'hey', 4, '4', 4491234567, '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `rating` text NOT NULL,
  `name` varchar(32) NOT NULL,
  `score` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `client` varchar(32) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `down` float NOT NULL,
  `monthly` int(11) NOT NULL DEFAULT 0,
  `months` int(11) NOT NULL DEFAULT 0,
  `card_number` bigint(20) NOT NULL,
  `expiration_month` int(11) NOT NULL,
  `expiration_year` int(11) NOT NULL,
  `pin` int(11) NOT NULL,
  `datetimePurchase` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_car`, `client`, `employee_id`, `client_id`, `price`, `down`, `monthly`, `months`, `card_number`, `expiration_month`, `expiration_year`, `pin`, `datetimePurchase`, `deleted`) VALUES
(1, 7, 'John Doe', 3, 0, 20000, 2000, 1, 36, 4532756279624064, 5, 2026, 1234, '0000-00-00 00:00:00', 0),
(2, 1, 'Jane Smith', 7, 0, 56000, 0, 0, 0, 4716108999716532, 8, 2027, 4321, '0000-00-00 00:00:00', 0),
(3, 5, 'Carlos Rivera', 2, 0, 7000, 700, 1, 12, 6011000990139424, 3, 2028, 5678, '0000-00-00 00:00:00', 0),
(4, 6, 'Emily Johnson', 5, 0, 34000, 3400, 1, 18, 6011111111111117, 11, 2029, 8765, '0000-00-00 00:00:00', 0),
(5, 2, 'Liam Brown', 1, 0, 32000, 0, 0, 0, 5105105105105100, 6, 2030, 3456, '0000-00-00 00:00:00', 0),
(6, 6, 'Sophia Wilson', 9, 0, 34000, 3400, 1, 24, 4111111111111111, 12, 2026, 9876, '0000-00-00 00:00:00', 0),
(7, 1, 'Olivia Taylor', 8, 0, 56000, 0, 0, 0, 4222222222222, 7, 2028, 2468, '0000-00-00 00:00:00', 0),
(8, 1, 'William Martinez', 4, 0, 56000, 5600, 1, 24, 378282246310005, 2, 2027, 1357, '0000-00-00 00:00:00', 0),
(9, 7, 'Mia Anderson', 6, 0, 20000, 2000, 1, 12, 6011111111111117, 10, 2030, 2468, '0000-00-00 00:00:00', 0),
(10, 5, 'James Thomas', 1, 0, 7000, 0, 0, 0, 5425233430109903, 9, 2029, 1234, '0000-00-00 00:00:00', 0),
(11, 3, 'Charlotte Garcia', 3, 0, 5000, 500, 1, 36, 4916012544445423, 5, 2026, 9876, '0000-00-00 00:00:00', 0),
(12, 6, 'Benjamin Lee', 10, 0, 34000, 3400, 1, 24, 4539314185927235, 11, 2027, 7654, '0000-00-00 00:00:00', 0),
(13, 7, 'Evelyn Harris', 2, 0, 20000, 2000, 1, 36, 6011000995504100, 8, 2028, 8765, '0000-00-00 00:00:00', 0),
(14, 3, 'Lucas White', 7, 0, 5000, 0, 0, 0, 4485739310396723, 3, 2030, 2345, '0000-00-00 00:00:00', 0),
(15, 2, 'Amelia Walker', 5, 0, 32000, 3200, 1, 12, 4024007139845788, 4, 2027, 9876, '0000-00-00 00:00:00', 0),
(16, 7, 'Alexander Hall', 9, 0, 20000, 2000, 1, 18, 6011123678931256, 10, 2026, 8642, '0000-00-00 00:00:00', 0),
(17, 7, 'Ella Allen', 1, 0, 20000, 2000, 1, 36, 4263982640269299, 6, 2031, 6543, '0000-00-00 00:00:00', 0),
(18, 5, 'Daniel Young', 4, 0, 7000, 0, 0, 0, 5425233430109903, 2, 2029, 7832, '0000-00-00 00:00:00', 0),
(19, 3, 'Scarlett Hernandez', 8, 0, 5000, 500, 1, 36, 4532756279624064, 7, 2032, 4321, '0000-00-00 00:00:00', 0),
(20, 2, 'Henry King', 3, 0, 32000, 0, 0, 0, 6011000990139424, 9, 2028, 2468, '0000-00-00 00:00:00', 0),
(21, 2, 'Grace Scott', 5, 0, 32000, 3200, 1, 12, 6011123678931256, 4, 2027, 1234, '0000-00-00 00:00:00', 0),
(22, 7, 'Samuel Adams', 6, 0, 20000, 2000, 1, 18, 4539314185927235, 7, 2028, 5678, '0000-00-00 00:00:00', 0),
(23, 2, 'Victoria Carter', 8, 0, 32000, 0, 0, 0, 4916012544445423, 11, 2030, 8765, '0000-00-00 00:00:00', 0),
(24, 2, 'Sebastian Ramirez', 2, 0, 32000, 0, 0, 0, 4111111111111111, 2, 2026, 6543, '0000-00-00 00:00:00', 0),
(25, 2, 'Harper Nelson', 4, 0, 32000, 3200, 1, 36, 5425233430109903, 9, 2031, 4321, '0000-00-00 00:00:00', 0),
(26, 7, 'Jack Mitchell', 7, 0, 20000, 0, 0, 0, 6011000990139424, 8, 2028, 2468, '0000-00-00 00:00:00', 0),
(27, 5, 'Isla Murphy', 1, 0, 7000, 700, 1, 12, 6011111111111117, 6, 2029, 1357, '0000-00-00 00:00:00', 0),
(28, 4, 'Daniel Hughes', 9, 0, 1, 0.1, 1, 18, 378282246310005, 5, 2027, 7890, '0000-00-00 00:00:00', 0),
(29, 3, 'Luna Torres', 3, 0, 5000, 0, 0, 0, 4532756279624064, 10, 2030, 9864, '0000-00-00 00:00:00', 0),
(30, 4, 'Logan Flores', 10, 0, 1, 0.1, 1, 24, 4222222222222, 1, 2027, 5648, '0000-00-00 00:00:00', 0),
(31, 1, 'Chloe Price', 6, 0, 56000, 5600, 1, 36, 4916012544445423, 9, 2032, 7654, '0000-00-00 00:00:00', 0),
(32, 3, 'Owen Jenkins', 1, 0, 5000, 500, 1, 24, 4539314185927235, 3, 2028, 4321, '0000-00-00 00:00:00', 0),
(33, 3, 'Layla Cooper', 8, 0, 5000, 0, 0, 0, 6011000995504100, 2, 2031, 3456, '0000-00-00 00:00:00', 0),
(34, 4, 'Nathan Gray', 5, 0, 1, 0.1, 1, 18, 4263982640269299, 8, 2026, 2345, '0000-00-00 00:00:00', 0),
(35, 5, 'Aria Brooks', 3, 0, 7000, 700, 1, 36, 6011123678931256, 4, 2029, 1357, '0000-00-00 00:00:00', 0),
(36, 4, 'Mason Powell', 9, 0, 1, 0, 0, 0, 5105105105105100, 12, 2027, 6543, '0000-00-00 00:00:00', 0),
(37, 7, 'Ellie Bell', 2, 0, 20000, 2000, 1, 36, 4532756279624064, 6, 2030, 8765, '0000-00-00 00:00:00', 0),
(38, 2, 'Leo Morgan', 7, 0, 32000, 0, 0, 0, 6011000990139424, 11, 2031, 2468, '0000-00-00 00:00:00', 0),
(39, 6, 'Zoe Griffin', 4, 0, 34000, 3400, 1, 12, 378282246310005, 10, 2027, 7890, '0000-00-00 00:00:00', 0),
(40, 6, 'Carter Reed', 10, 0, 34000, 3400, 1, 18, 6011111111111117, 1, 2029, 9876, '0000-00-00 00:00:00', 0),
(41, 2, 'Hannah Fox', 6, 0, 32000, 3200, 1, 36, 4222222222222, 5, 2026, 8642, '0000-00-00 00:00:00', 0),
(42, 7, 'Elijah Ward', 3, 0, 20000, 0, 0, 0, 4916012544445423, 7, 2030, 7564, '0000-00-00 00:00:00', 0),
(43, 7, 'Addison Bailey', 8, 0, 20000, 2000, 1, 36, 4539314185927235, 3, 2032, 3245, '0000-00-00 00:00:00', 0),
(44, 6, 'Gabriel Bryant', 1, 0, 34000, 0, 0, 0, 6011000995504100, 2, 2028, 9871, '0000-00-00 00:00:00', 0),
(45, 2, 'Penelope Hayes', 5, 0, 32000, 3200, 1, 12, 4263982640269299, 8, 2027, 6124, '0000-00-00 00:00:00', 0),
(46, 7, 'Hunter Perry', 7, 0, 20000, 2000, 1, 18, 6011123678931256, 4, 2026, 8574, '0000-00-00 00:00:00', 0),
(47, 6, 'Avery Cruz', 9, 0, 34000, 0, 0, 0, 5105105105105100, 9, 2031, 5432, '0000-00-00 00:00:00', 0),
(48, 2, 'Stella Jenkins', 4, 0, 32000, 3200, 1, 24, 4532756279624064, 11, 2029, 1286, '0000-00-00 00:00:00', 0),
(49, 4, 'David Sanders', 2, 0, 1, 0.1, 1, 36, 6011000990139424, 6, 2027, 6724, '0000-00-00 00:00:00', 0),
(50, 7, 'Leah Barnes', 3, 0, 20000, 0, 0, 0, 378282246310005, 10, 2030, 4187, '0000-00-00 00:00:00', 0),
(51, 2, 'Nick Rangel', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 8, 2027, 1234, '0000-00-00 00:00:00', 0),
(52, 2, 'Fofo Marquez', 1, 0, 32000, 9600, 0, 0, 1234567890987654, 8, 2027, 2345, '0000-00-00 00:00:00', 0),
(53, 2, 'Nick Rangel', 1, 0, 32000, 9600, 0, 0, 2345672345678987, 8, 2027, 2345, '0000-00-00 00:00:00', 0),
(54, 2, 'Nickla Rangel', 1, 0, 32000, 9600, 0, 0, 3456789098765434, 8, 2027, 3456, '0000-00-00 00:00:00', 0),
(55, 2, 'Daniel O\'tarris', 1, 0, 32000, 9600, 0, 0, 5896123655478965, 8, 4568, 3456, '0000-00-00 00:00:00', 0),
(105, 2, 'Kadir', 1, 0, 32000, 9600, 0, 0, 1234567890000000, 8, 2027, 3949, '0000-00-00 00:00:00', 0),
(106, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 8, 7899, 7879, '0000-00-00 00:00:00', 0),
(107, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 9600, 0, 0, 1234567890000000, 8, 2029, 4567, '0000-00-00 00:00:00', 0),
(108, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 909, '0000-00-00 00:00:00', 0),
(109, 2, 'Ka', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 9, 9090, 9090, '0000-00-00 00:00:00', 0),
(110, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 3200, 0, 0, 1234567890000000, 9, 9090, 909, '0000-00-00 00:00:00', 0),
(111, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 9090, '0000-00-00 00:00:00', 0),
(112, 2, 'Kadir Koprulu Hernandez', 1, 0, 32000, 6400, 0, 0, 1234567890000000, 9, 9090, 9090, '0000-00-00 00:00:00', 0),
(113, 4, 'Bob Johnny', 1, 0, 1, 0.1, 0, 0, 1234567890000000, 9, 9090, 9090, '0000-00-00 00:00:00', 0),
(114, 6, 'K', 1, 0, 34000, 3400, 0, 0, 1234567890000000, 9, 9090, 9090, '0000-00-00 00:00:00', 0),
(115, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 11200, 0, 0, 1234567890000000, 9, 9090, 9876, '0000-00-00 00:00:00', 0),
(116, 7, 'Kadir', 1, 0, 20000, 6000, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-14 20:18:34', 0),
(117, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:11:16', 0),
(118, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:13:21', 0),
(119, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:14:34', 0),
(120, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:18:54', 0),
(121, 1, 'Kadir Koprulu Hernandez', 1, 0, 56000, 5600, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:25:20', 0),
(122, 1, 'Kadir Koprulu Hernandez', 1, 3, 56000, 5600, 0, 12, 2526935717563634, 8, 2026, 4518, '2025-05-22 09:33:27', 0),
(123, 7, 'Kadir Koprulu Hernandez', 2, 3, 20000, 2000, 0, 0, 1234567890000000, 9, 9090, 9090, '2025-05-22 09:42:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fullname`, `profile_picture`) VALUES
(1, 'Fy644', '$2y$10$9UrXEwJhcjp/lOJ8ycqNhuiOONPMxIfQ3/povF95d8FPYV8aKsAlK', '', '', NULL),
(2, 'Kadir', '$2y$10$QNufLCcjYvhuE2ICmWSgIeeKxE4vpX7fb.y.Zg33IIj11TIK4.AZy', '', '', NULL),
(3, 'fy64', '$2y$10$6qYFDz5LcKUSNO7ceyRrreg619.bgFHWU7ZIE.JGRwor15JxNxfnm', 'fy6644@gmail.com', 'Kadir Koprulu Hernandez', 'userpfp/682576ccb04cc.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
